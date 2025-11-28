<?php 

include("yhteys.php");

// Collect basic fields
$yritys  = $_POST['yritysNimi'];
$etu     = $_POST['etuNimi'];
$suku    = $_POST['sukuNimi'];
$puhelin = $_POST['puhelinNumero'];

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
    ':yritys'  => $yritys,
    ':etu'     => $etu,
    ':suku'    => $suku,
    ':puhelin' => $puhelin,
    ':työajat' => $choicesJSON,
    ':muut'    => $muuJSON
]);

echo "Data saved!";


?>