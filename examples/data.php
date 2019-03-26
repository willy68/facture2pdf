<?php
include "logo.php";

$ste = array( 'nom' => 'Ma petite entreprise',
                'logo' => $logo,
                'adresse' => '24 rue du bout du monde',
                'suite_adresse' => '',
                'cp' => '78015',
                'ville' => 'LOIN EN CAMPAGNE',
                'portable' => '06 07 08 09 10',
                'email' => 'admin.user@gmail.com',
                'siret' => '45687634500023',
                'ape' => '025B',
                'tva_intracom' => 'FR 49 456 876 345');

$client = array( 'code_client' => 'CL0009',
                    'civilite' => '',
                    'nom' => 'ASSOCIATION DES SANS CLAVIER',
                    'prenom' => '');

$adresse_client = array( 'adresse_1' => '4, boulevard des capucines',
                            'adresse_2' => '',
                            'adresse_3' => '',
                            'cp' => '78009',
                            'ville' => 'FLEURI AUX BOIS',
                            'pays' => '');

$facture = array( 'code_facture' => 'FC3221',
                    'date_edition' => '31 janvier 2019',
                    'objet' => '',
                    'total_ht' => 3320.00,
                    'total_tva' => 595.85,
                    'acompte' => 300.00,
                    'total_ttc' => 3915.95,
                    'total_net' => 3615.95,
                    'created_at' => 'lundi 30 janvier 2019');

$total_tva = array(
                array( 'libelle' => '20.00 %',
                        'taux' => 20.00,
                        'total_tva' => 570.00),
                array( 'libelle' => '5.50 %',
                        'taux' => 5.50,
                        'total_tva' => 25.85)
);

$ligne_factures = array(
                    array( 'num_ligne' => '1',
                    	'type' => 'tranche_debut',
                        'libelle' => 'Entretien des espaces verts ',
                        'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => '',
                        'ttc' => ''),

                        array( 'num_ligne' => '1.1',
                    	'type' => 'standard',
                        'libelle' => 'Entretien des espaces verts pour le mois de
                        Novembre 2018 
                        ( Contrat N° C010.2002 )',
                         'quantite' => 1.000,
                        'unite' => '',
                        'ht' => 565.00,
                        'tva' => 113.00,
                        'total_ht' => 565.00,
                        'ttc' => 678.00),

                        array( 'num_ligne' => '1.2',
                    	'type' => 'standard',
                        'libelle' => 'Entretien des espaces verts pour le mois de 
                        Décembre 2018
                        ( Contrat N° C010.2002 )',
                         'quantite' => 1.000,
                        'unite' => '',
                        'ht' => 565.00,
                        'tva' => 113.00,
                        'total_ht' => 565.00,
                        'ttc' => 678.00),

                        array( 'num_ligne' => '',
                    	'type' => 'tranche_fin',
                        'libelle' => 'TOTAL Entretien des espaces verts',
                        'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => 1130.00,
                        'ttc' => 1356.00),

                        array( 'num_ligne' => '2',
                    	'type' => 'tranche_debut',
                        'libelle' => 'Rabattage et arrachage d\'arbustes',
                         'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => '',
                        'ttc' => ''),

                        array( 'num_ligne' => '2.1',
                    	'type' => 'standard',
                        'libelle' => 'Rabattage de grands arbustes',
                         'quantite' => '3.000',
                        'unite' => '',
                        'ht' => 100.00,
                        'tva' => 60.00,
                        'total_ht' => 300.00,
                        'ttc' => 360.00),

                        array( 'num_ligne' => '2.2',
                    	'type' => 'standard',
                        'libelle' => 'Arrachage d\'arbustes morts',
                         'quantite' => 2.000,
                        'unite' => '',
                        'ht' => 50.00,
                        'tva' => 20.00,
                        'total_ht' => 100.00,
                        'ttc' => 120.00),

                        array( 'num_ligne' => '',
                    	'type' => 'tranche_fin',
                        'libelle' => 'TOTAL Rabattage et arrachage d\'arbustes',
                         'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => 400.00,
                        'ttc' => 480.00),

                        array( 'num_ligne' => '3',
                    	'type' => 'tranche_debut',
                        'libelle' => 'Plantation d\'une haie de persistents',
                         'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => '',
                        'ttc' => ''),

                        array( 'num_ligne' => '3.1',
                    	'type' => 'standard',
                        'libelle' => 'Plantation des arbustes fournis avec apport de terreau
                        de plantion',
                         'quantite' => '12.000',
                        'unite' => '',
                        'ht' => '65.00',
                        'tva' => '156.00',
                        'total_ht' => '780.00',
                        'ttc' => '936.00'),
/*
                        array( 'num_ligne' => '3.2',
                    	'type' => 'standard',
                        'libelle' => 'Fourniture de Lauriers du Portugal 80/100
                        (Prunus Lusitanica)',
                         'quantite' => '12.000',
                        'unite' => '',
                        'ht' => '35.00',
                        'tva' => '23.10',
                        'total_ht' => '420.00',
                        'ttc' => '443.10'),

                        array( 'num_ligne' => '3.3',
                    	'type' => 'standard',
                        'libelle' => 'Fourniture de terreau de plantation
                        en vrac',
                         'quantite' => '0.500',
                        'unite' => 'm3',
                        'ht' => '100.00',
                        'tva' => '2.75',
                        'total_ht' => '50.00',
                        'ttc' => '52.75'),
*/
                        array( 'num_ligne' => '',
                    	'type' => 'tranche_fin',
                        'libelle' => 'TOTAL Plantation d\'une haie de persistents',
                         'quantite' => '',
                        'unite' => '',
                        'ht' => '',
                        'tva' => '',
                        'total_ht' => '1250.00',
                        'ttc' => '1431.85'),

                        array( 'num_ligne' => '4',
                    	'type' => 'standard',
                        'libelle' => 'Elagage de tilleuls,
                        évacuation des dechets de coupe',
                         'quantite' => '3.000',
                        'unite' => '',
                        'ht' => '180.00',
                        'tva' => '108.00',
                        'total_ht' => '540.00',
                        'ttc' => '648.00')

);
