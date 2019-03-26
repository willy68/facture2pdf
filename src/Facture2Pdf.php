<?php
/**
 * Facture2Pdf Library - main class
 *
 * HTML => PDF converter
 * distributed under the LGPL-3.0 License
 *
 * @package   facture2pdf
 * @author    William Lety <william.lety@gmail.com>
 * @copyright 2019 William Lety
 */

namespace Facture2Pdf;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Parsing\HtmlLexer;
use Spipu\Html2Pdf\Parsing\Node;
use Spipu\Html2Pdf\Parsing\TagParser;
use Spipu\Html2Pdf\Parsing\TextParser;

class Facture2Pdf extends Html2Pdf
{
  protected $vars = array();

  protected $lignes = null;

  protected $model = null;

  protected $model_dir = '../models';

  protected $name = null;

  protected $css_file = null;

  protected $footer_file = null;

  protected $header_file = null;

  protected $thead_file = null;

  protected $repeat_thead = true;

  protected $myLexer = null;

  public $error = null;

  public function __construct(
      $orientation = 'P',
      $format = 'A4',
      $lang = 'fr',
      $unicode = true,
      $encoding = 'UTF-8',
      $margins = array(10, 10, 10, 8),
      $pdfa = false
  ) {
      parent::__construct($orientation, 
      $format, 
      $lang, 
      $unicode, 
      $encoding, 
      $margins, 
      $pdfa);
      $this->myLexer = new HtmlLexer();
      $this->pdf->SetMyFooter(true);
    }

  /**
   * 
   */
  public function setModelDir($dir)
  {
    if (!is_string($name) || empty($name)) {
        throw new \InvalidArgumentException('Le repertoire doit être une chaine de caractères valide');
    }

    if (is_dir($dir)) {
        $this->model_dir = $dir;
        return true;
    }
    return false;
  }

  /**
   * 
   */
  public function loadModel($name)
  {
    if (!is_string($name) || empty($name)) {
        throw new \InvalidArgumentException('Le nom du model doit être une chaine de caractères valide');
    }

    $file = dirname(__FILE__).'/'.$this->model_dir.'/'.$name.'/model.php';
    if (file_exists($file)) {
        // ob_start();
        include $file;
        // $data = ob_get_clean();
        $this->model = $model;
        if ($this->model['name'] === $name) {
          $this->name = $name;
          unset($this->model['name']);
          if (isset($this->model['css'])) {
            $this->css_file = $this->model['css'];
            unset($this->model['css']);
          }
          if (isset($this->model['footer'])) {
            $this->footer_file = $this->model['footer'];
            unset($this->model['footer']);
          }
          if (isset($this->model['header'])) {
            $this->header_file = $this->model['header'];
            unset($this->model['header']);
          }
          if (isset($this->model['thead'])) {
            $this->thead_file = $this->model['thead'];
            unset($this->model['thead']);
          }
          // $this->model = array_values($this->model);
          return true;
        }
    }
    return false;
  }

  /**
   * @param bool $repeat entete des colonnes, 
   * se repete en haut des tables a chaques pages
   */
  public function repeatThead($repeat = true)
  {
    $this->repeat_thead = $repeated;
  }

  /**
   * @param string $var
   * @param mixed $value
   */
  public function addVar($var, $value)
  {
    if (!is_string($var) || is_numeric($var) || empty($var)) {
        throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractère non nulle');
    }
    $this->vars[$var] = $value;
  }

  /**
   * 
   */
  public function getVar($var)
  {
    if (!isset($this->vars[$var])) {
        return false;
    }
    return $this->vars[$var];
  }

  /**
   * @param array $lignes array de array :
   * array(array('libelle' => '...', 'prix_ht' => 245.00), array(etc...))
   * lignes (articles) de facture dans une table (voir row.php)
   */
  public function addLinesVar($lignes)
  {
    if (!is_array($lignes) || empty($lignes)) {
      throw new \InvalidArgumentException('Le nom de la variable doit être un tableau non vide');
    }
    $this->lignes = $lignes;
  }

  /**
   * 
   */
  public function writeHtml($html) {
    if (!is_string($html) || empty($html)) {
      throw new \InvalidArgumentException('La chaine HTML doit être une chaine de caractère non nulle');
    }
    try {
      parent::writeHtml($html);
    } catch (Html2PdfException $e) {
      $this->clean();

      $formatter = new ExceptionFormatter($e);
      $this->error = $formatter->getHtmlMessage();
    }
  }

  /**
   * 
   */
  public function writeRepeated($row_file, $lignes, $hfooter = 0, $thead = null) {
    $name = '/'.$this->name;
    $row = null;
    $y = 0; $ys = 0; $yts = 0;
    $ligne_s = null;
    $ligne_w = null;
    $row_w = null;
    $closeTable = false;
    $startTable = false;
    $paddingB = false;
    $paddingU = 0;

    // marge de 8 mm normal, 
    $maxH = $this->pdf->getH() - $hfooter - 
        $this->getDefaultMargins()['bottom'] - 0.01;
    
    $row_file = dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$row_file;
    $count = count($lignes);
    $i = 1;
    $this->pdf->startTransaction();
    foreach($lignes as $ligne) {
      // On est où sur la page
      $y = $this->pdf->getY();
      // Si dernière row, on ferme la table (rollback pour padding-bottom?)
      if ($i === $count) {
        $closeTable = true;
      }
      ob_start();
      include $row_file;
      $row = ob_get_clean();
      $closeTable = false;
      $startTable = false;
      // hauteur de la row
      $yt = $this->getTagHeight($row);
      // Si nouvelle row > $maxH, efface la précédente
      if ($y + $yt > $maxH) {
        $this->pdf->rollbackTransaction(true);
        // On ferme la table
        $closeTable = true;
        // On revient en arrière

        // Doit-on rajouter du padding-bottom?
        if ($ys + $yts  < $maxH) {
          $paddingB = true;
          $paddingU = $maxH - $ys - $yts - 0.01;
        }
        // Sauve la row et la ligne en cours en cours
        $row_w = $row;
        $ligne_w = $ligne;
        // On reprend la précédente ligne
        $ligne = $ligne_s;
        // On remet à jour la row
        ob_start();
        include $row_file;
        $row = ob_get_clean();
        $this->writeHtml($row);


        $this->addNewPage();
        if ($thead && $this->repeat_thead) {
          $this->writeHtml($thead);
        } 
        // On est où sur la page
        $y = $this->pdf->getY();
        $this->pdf->commitTransaction();
        $this->pdf->startTransaction();
        $closeTable = false;
        $paddingB = false;
        // On rétablit la row et la ligne en cours
        $row = $row_w;
        $ligne = $ligne_w;
        // juste pour une bordure!
        if (!$this->repeat_thead) {
          $startTable = true;
          include $row_file;
          $row = ob_get_clean();
          // $startTable = false;
        }
        // On l'ecrit dans le pdf
        $this->writeHtml($row);
      }
      else {
        $this->pdf->commitTransaction();
        $this->pdf->startTransaction();
        $this->writeHtml($row);
      }
      // sauvegarde des précédentes positions
      $ys = $y; $yts = $yt; $i++;
      $ligne_s = $ligne;
    }

    // Doit-on rajouter du padding-bottom?
    if ($ys + $yt < $maxH) {
      // Alors on efface et on rajoute du padding-bottom
      $this->pdf->rollbackTransaction(true);
      $closeTable = true;
      $paddingB = true;
      $paddingU = $maxH - $ys - $yt - 0.01;
      ob_start();
      include $row_file;
      $row = ob_get_clean();
      $this->writeHtml($row);
    }
    $this->pdf->commitTransaction();
  }

  /**
   * 
   */
  public function writeModel($pdf_file = 'facture.pdf') {
    $name = '/'.$this->name;
    $css = null;
    $footer = null;
    $thead = null;
    $row_file = null;
    $hfooter = 0;
    
    if ($this->model['repeated']) {
      $row_file = $this->model['repeated'];
      unset($this->model['repeated']);
    }

    extract($this->vars);

    if ($this->css_file) {
      ob_start();
      include dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$this->css_file;
      $css = ob_get_clean();
    }

    if ($this->footer_file) {
      ob_start();
      include dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$this->footer_file;
      $footer = ob_get_clean();
      $css .= $footer;
    }
    if ($css) {
      $this->writeHtml($css);
    }

    if ($footer) {
      $hfooter = $this->getTagHeight($footer);
    }

    if ($this->header_file) {
      ob_start();
      include dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$this->header_file;
      $header = ob_get_clean();
      $this->writeHtml($header);
    }

    // là ça pèche carrement!
    if (count($this->model)) {
      foreach( $this->model as $key => $file) {
        ob_start();
        include dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$file;
        $html = ob_get_clean();
        $this->writeHtml($html);
      }
    }

    if ( $this->thead_file) {
      ob_start();
      include dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$this->thead_file;
      $thead = ob_get_clean();
      $this->writeHtml($thead);
    }

    $this->writeRepeated($row_file, $this->lignes, $hfooter, $thead);
  }

  public function setPageMargins($margins = null) {

  }

  public function getDefaultMargins() {
      return array(
          'left' => $this->_defaultLeft,
          'top' => $this->_defaultTop,
          'right' => $this->_defaultRight,
          'bottom' => $this->_defaultBottom
      );
  }

  public function getTagHeight($html) {
      $res = null;

      $html = $this->parsingHtml->prepareHtml($html);
      $html = $this->parsingCss->extractStyle($html);
      $this->parsingHtml->parse($this->myLexer->tokenize($html));
      $sub = $this->createSubHTML();
      if (in_array($this->parsingHtml->code[0]->getName(), array('page_footer'))) {
          $sub->parsingHtml->code = $this->parsingHtml->getLevel(0);
      }else {
          $sub->parsingHtml->code = $this->parsingHtml->code;
      }
      $sub->_makeHTMLcode();
      $res = $sub->_maxY;
      $this->_destroySubHTML($sub);

      return $res;
  }

  public function addNewPage($format = null, 
      $orientation = '', 
      $background = null, 
      $curr = null, 
      $resetPageNumber = false) {
      $this->_setNewPage($format, 
          $orientation, 
          $background, 
          $curr, 
          $resetPageNumber);
      return $this;
  }

  /**
   * display an image
   *
   * @access protected
   * @param  string $src
   * @param  boolean $subLi if true=image of a list
   * @return boolean depending on "isForOneLine"
   */
  protected function _drawImage($src, $subLi = false)
  {
      // get the size of the image
      // WARNING : if URL, "allow_url_fopen" must turned to "on" in php.ini
      $infos=@getimagesize($src);

      // if the image does not exist, or can not be loaded
      if (!is_array($infos) || count($infos)<2) {
          if ($this->_testIsImage) {
              $e = new ImageException('Unable to get the size of the image ['.$src.']');
              $e->setImage($src);
              throw $e;
          }

          // display a gray rectangle
          $src = null;
          $infos = array(16, 16);

          // if we have a fallback Image, we use it
          if ($this->_fallbackImage) {
              $src = $this->_fallbackImage;
              $infos = @getimagesize($src);

              if (count($infos)<2) {
                  $e = new ImageException('Unable to get the size of the fallback image ['.$src.']');
                  $e->setImage($src);
                  throw $e;
              }
          }
      }

      // if image is a string
      if (!is_file($src) && strpos($src, 'data:image') !== false) {
          list($stream, $data) = explode(",", $src);
          if (strpos($stream, 'base64')) {
              $src = '@' . base64_decode($data);
          }
          else {
              $src = '@' . $data;
          }
      }

      // convert the size of the image in the unit of the PDF
      $imageWidth = $infos[0]/$this->pdf->getK();
      $imageHeight = $infos[1]/$this->pdf->getK();

      $ratio = $imageWidth / $imageHeight;

      // calculate the size from the css style
      if ($this->parsingCss->value['width'] && $this->parsingCss->value['height']) {
          $w = $this->parsingCss->value['width'];
          $h = $this->parsingCss->value['height'];
      } elseif ($this->parsingCss->value['width']) {
          $w = $this->parsingCss->value['width'];
          $h = $w / $ratio;
      } elseif ($this->parsingCss->value['height']) {
          $h = $this->parsingCss->value['height'];
          $w = $h * $ratio;
      } else {
          // convert px to pt
          $w = 72./96.*$imageWidth;
          $h = 72./96.*$imageHeight;
      }

      if (isset($this->parsingCss->value['max-width']) && $this->parsingCss->value['max-width'] < $w) {
          $w = $this->parsingCss->value['max-width'];
          if (!$this->parsingCss->value['height']) {
              // reprocess the height if not constrained
              $h = $w / $ratio;
          }
      }
      if (isset($this->parsingCss->value['max-height']) && $this->parsingCss->value['max-height'] < $h) {
          $h = $this->parsingCss->value['max-height'];
          if (!$this->parsingCss->value['width']) {
              // reprocess the width if not constrained
              $w = $h * $ratio;
          }
      }

      // are we in a float
      $float = $this->parsingCss->getFloat();

      // if we are in a float, but if something else if on the line
      // => make the break line (false if we are in "_isForOneLine" mode)
      if ($float && $this->_maxH && !$this->_tag_open_BR(array())) {
          return false;
      }

      // position of the image
      $x = $this->pdf->GetX();
      $y = $this->pdf->GetY();

      // if the image can not be put on the current line => new line
      if (!$float && ($x + $w>$this->pdf->getW() - $this->pdf->getrMargin()) && $this->_maxH) {
          if ($this->_isForOneLine) {
              return false;
          }

          // set the new line
          $hnl = max($this->_maxH, $this->parsingCss->getLineHeight());
          $this->_setNewLine($hnl);

          // get the new position
          $x = $this->pdf->GetX();
          $y = $this->pdf->GetY();
      }

      // if the image can not be put on the current page
      if (($y + $h>$this->pdf->getH() - $this->pdf->getbMargin()) && !$this->_isInOverflow) {
          // new page
          $this->_setNewPage();

          // get the new position
          $x = $this->pdf->GetX();
          $y = $this->pdf->GetY();
      }

      // correction for display the image of a list
      $hT = 0.80*$this->parsingCss->value['font-size'];
      if ($subLi && $h<$hT) {
          $y+=($hT-$h);
      }

      // add the margin top
      $yc = $y-$this->parsingCss->value['margin']['t'];

      // get the width and the position of the parent
      $old = $this->parsingCss->getOldValues();
      if ($old['width']) {
          $parentWidth = $old['width'];
          $parentX = $x;
      } else {
          $parentWidth = $this->pdf->getW() - $this->pdf->getlMargin() - $this->pdf->getrMargin();
          $parentX = $this->pdf->getlMargin();
      }

      // if we are in a gloat => adapt the parent position and width
      if ($float) {
          list($lx, $rx) = $this->_getMargins($yc);
          $parentX = $lx;
          $parentWidth = $rx-$lx;
      }

      // calculate the position of the image, if align to the right
      if ($parentWidth>$w && $float !== 'left') {
          if ($float === 'right' || $this->parsingCss->value['text-align'] === 'li_right') {
              $x = $parentX + $parentWidth - $w-$this->parsingCss->value['margin']['r']-$this->parsingCss->value['margin']['l'];
          }
      }

      // display the image
      if (!$this->_subPart && !$this->_isSubPart) {
          if ($src) {
              $this->pdf->Image($src, $x, $y, $w, $h, '', $this->_isInLink);
          } else {
              // rectangle if the image can not be loaded
              $this->pdf->SetFillColorArray(array(240, 220, 220));
              $this->pdf->Rect($x, $y, $w, $h, 'F');
          }
      }

      // apply the margins
      $x-= $this->parsingCss->value['margin']['l'];
      $y-= $this->parsingCss->value['margin']['t'];
      $w+= $this->parsingCss->value['margin']['l'] + $this->parsingCss->value['margin']['r'];
      $h+= $this->parsingCss->value['margin']['t'] + $this->parsingCss->value['margin']['b'];

      if ($float === 'left') {
          // save the current max
          $this->_maxX = max($this->_maxX, $x+$w);
          $this->_maxY = max($this->_maxY, $y+$h);

          // add the image to the margins
          $this->_addMargins($float, $x, $y, $x+$w, $y+$h);

          // get the new position
          list($lx, $rx) = $this->_getMargins($yc);
          $this->pdf->SetXY($lx, $yc);
      } elseif ($float === 'right') {
          // save the current max. We don't save the X because it is not the real max of the line
          $this->_maxY = max($this->_maxY, $y+$h);

          // add the image to the margins
          $this->_addMargins($float, $x, $y, $x+$w, $y+$h);

          // get the new position
          list($lx, $rx) = $this->_getMargins($yc);
          $this->pdf->SetXY($lx, $yc);
      } else {
          // set the new position at the end of the image
          $this->pdf->SetX($x+$w);

          // save the current max
          $this->_maxX = max($this->_maxX, $x+$w);
          $this->_maxY = max($this->_maxY, $y+$h);
          $this->_maxH = max($this->_maxH, $h);
      }

      return true;
  }
}
