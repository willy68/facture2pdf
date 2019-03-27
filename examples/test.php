<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once dirname(__FILE__).'/data.php';

use Facture2Pdf\Facture2Pdf;

$fact_pdf = new Facture2Pdf();

if (!$fact_pdf->loadModel('facture_std')) {
    exit('Impossible de charger le model');
};

// Les données viennent en fait d'un fichier php et pas d'un sgbd
// Les cles 'ste' 'client' etc... sont les noms des tableaux PHP dans
// les templates HTML (echo $ste['nom'])
$fact_pdf->addVar('ste', $ste);
$fact_pdf->addVar('client', $client);
$fact_pdf->addVar('adresse_client', $adresse_client);
$fact_pdf->addVar('facture', $facture);
$fact_pdf->addVar('total_tva', $total_tva);

/**
 * @param array $lignes array de array :
 * array(array('libelle' => '...', 'prix_ht' => 245.00), array(etc...))
 * lignes (articles) de facture dans une table (voir row.php)
 */
$fact_pdf->addLinesVar($ligne_factures);
$fact_pdf->repeatThead(false);

$fact_pdf->writeModel();

$fact_pdf->output('facture.pdf');
