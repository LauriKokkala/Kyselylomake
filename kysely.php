<?php 

include("yhteys.php");

// Collect basic fields
$yritysNimi  = $_POST['yritysNimi'];
$etuNimi     = $_POST['etuNimi'];
$sukuNimi    = $_POST['sukuNimi'];
$puhelinNumero = $_POST['puhelinNumero'];

// Collect selected checkboxes
$choices = [];
foreach ($_POST as $key => $value) {
    if (str_starts_with($key, 'työAikaKirjaus')) {
        $choices[] = $value;
    }
}

$choicesJSON = json_encode($choices);  // easier to store

// Collect extra work texts
$muuText = array_filter($_POST['muuText'], function($v) {
    return trim($v) !== "";
});

$muuJSON = json_encode(array_values($muuText));

// Insert into DB
$stmt = $yhteys->prepare("
    INSERT INTO kysely (
        yritysNimi, etuNimi, sukuNimi, puhelinNumero,
        työAjat, muuTyöt
    ) VALUES (
        :yritys, :etu, :suku, :puhelin,
        :työajat, :muut
    )
");

$stmt->execute([
    ':yritys'  => $yritysNimi,
    ':etu'     => $etuNimi,
    ':suku'    => $sukuNimi,
    ':puhelin' => $puhelinNumero,
    ':työajat' => $choicesJSON,
    ':muut'    => $muuJSON
]);

echo "Data saved!";


?>