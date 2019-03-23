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

class Facture2Pdf
{
  protected $vars = array();

  protected $lignes = null;

  protected $model = null;

  protected $model_dir = '../models';

  protected $html2pdf = null;

  protected $name = null;

  protected $css_file = null;

  protected $footer_file = null;

  protected $header_file = null;

  protected $thead_file = null;

  protected $repeat_thead = true;

  public $error = null;

  public function __construct(
      $orientation = 'P',
      $format = 'A4',
      $lang = 'fr',
      $unicode = true,
      $encoding = 'UTF-8',
      $margins = array(10, 10, 10, 8)
  )
  {
    $this->html2pdf = new myHtml2Pdf($orientation,
                                    $format,
                                    $lang,
                                    $unicode,
                                    $encoding,
                                    $margins,
                                    false
                                  );
    $this->html2pdf->pdf->SetMyFooter(true);
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
      $this->html2pdf->writeHtml($html);
    } catch (Html2PdfException $e) {
      $this->html2pdf->clean();

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
    $maxH = $this->html2pdf->pdf->getH() - $hfooter - 
        $this->html2pdf->getDefaultMargins()['bottom'] - 0.01;
    
    $row_file = dirname(__FILE__).'/'.$this->model_dir.$name.'/'.$row_file;
    $count = count($lignes);
    $i = 1;
    $this->html2pdf->pdf->startTransaction();
    foreach($lignes as $ligne) {
      // On est où sur la page
      $y = $this->html2pdf->pdf->getY();
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
      $yt = $this->html2pdf->getTagHeight($row);
      // Si nouvelle row > $maxH, efface la précédente
      if ($y + $yt > $maxH) {
        $this->html2pdf->pdf->rollbackTransaction(true);
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


        $this->html2pdf->addNewPage();
        if ($thead && $this->repeat_thead) {
          $this->html2pdf->writeHtml($thead);
        } 
        $this->html2pdf->pdf->commitTransaction();
        $this->html2pdf->pdf->startTransaction();
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
          $startTable = false;
        }
        // On l'ecrit dans le pdf
        $this->writeHtml($row);
      }
      else {
        $this->html2pdf->pdf->commitTransaction();
        $this->html2pdf->pdf->startTransaction();
        $this->writeHtml($row);
      }
      // sauvegarde des précédentes positions
      $ys = $y; $yts = $yt; $i++;
      $ligne_s = $ligne;
    }

    // Doit-on rajouter du padding-bottom?
    if ($ys + $yt < $maxH) {
      // Alors on efface et on rajoute du padding-bottom
      $this->html2pdf->pdf->rollbackTransaction(true);
      $closeTable = true;
      $paddingB = true;
      $paddingU = $maxH - $ys - $yt - 0.01;
      ob_start();
      include $row_file;
      $row = ob_get_clean();
      $this->writeHtml($row);
    }
    $this->html2pdf->pdf->commitTransaction();
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
      $hfooter = $this->html2pdf->getTagHeight($footer);
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
  
	  /**
	 * Send the document to a given destination: string, local file or browser.
	 * Dest can be :
	 *  I : send the file inline to the browser (default). The plug-in is used if available. 
	 *  The name given by name is used when one selects the "Save as" option on the link generating the PDF.
	 *  D : send to the browser and force a file download with the name given by name.
	 *  F : save to a local server file with the name given by name.
	 *  S : return the document as a string (name is ignored).
	 *  FI: equivalent to F + I option
	 *  FD: equivalent to F + D option
	 *  E : return the document as base64 mime multi-part email attachment (RFC 2045)
	 *
	 * @param string $name The name of the file when saved.
	 * @param string $dest Destination where to send the document.
	 *
	 * @throws Html2PdfException
	 * @return string content of the PDF, if $dest=S
	 * @see    TCPDF::close
	 */
  public function output($name = 'facture.pdf', $dest = 'I')
  {
    //Close and output PDF document
    $this->html2pdf->output($name); 
  }

}
