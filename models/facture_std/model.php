<?php

/**
 * name: le nom de model et du repertoire doivent être identique
 * css: la mise en forme html de la facture
 * footer: pas obligatoire, en bas de toutes les pages
 * repeated: cellules des lignes de facture qui se repetent
 * header: en haut de la première page uniquement (adresse, ref facture, client etc...)
 * thead: entete des colonnes, se repete en haut des tables a chaques pages
 * Ce fichier ne doit contenir aucun <echo> ou autre fonction d'affichage!
 */
$model = array(
    'name' => 'facture_std',
    'css' => 'css.php',
    'footer' => 'footer.php',
    'repeated' => 'row.php',
    'header' => 'header.php',
    'thead' => 'thead.php'
);