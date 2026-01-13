<?php 

include("yhteys.php");

// Kerätään kaikki helpot
$yritysNimi  = $_POST['yritysNimi'];
$etuNimi     = $_POST['etuNimi'];
$sukuNimi    = $_POST['sukuNimi'];
$palkkaAloitus = $_POST['aloitusPaiva'];
$palkkaLopetus = $_POST['lopetusPaiva'];


if (is_numeric($_POST['puhelinNumero'])){
    $puhelinNumero = $_POST['puhelinNumero'];
}
else {
    throw new Exception("Käytä vain numeroita");
}

// Kerätään valitut työaikakirjaukset
$tyot = array_filter($_POST['työAikaKirjaus'], function($v) {
    return trim($v) !== "";
});

$tyotJSON = json_encode($tyot);

// Kerätään kaikki rasittavat extratyöt
$muuText = array_filter($_POST['muuText'], function($v) {
    return trim($v) !== "";
});

$muuJSON = json_encode(array_values($muuText));


// Tarkistetaan, onko tauko tai ajo valittu
// Jos ei, laitetaan tyhjäksi, että ei tule erroria
if (isset($_POST['taukoButton'])) {
    $tauko = $_POST['taukoButton'];
}
else {
    $tauko = "";
}

if (isset($_POST['ajoButton'])) {
    $kmKirjaus = $_POST['ajoButton'];
}
else {
    $kmKirjaus = "";
}

// Databaseen laitto
$stmt = $yhteys->prepare("
    INSERT INTO asiakkaat (
        yritysNimi, etuNimi, sukuNimi, puhelinNumero,
        tyoAjat, muutTyot, tauko, kilometriKirjaus, 
        palkkaAloitus, palkkaLopetus
    ) VALUES (
        :yritys, :etu, :suku, :puhelin,
        :tyoajat, :muut, :tauko, :kmkirjaus,
        :aloitus, :lopetus
        
    )
");

if ($stmt->execute([
    ':yritys'   => $yritysNimi,
    ':etu'      => $etuNimi,
    ':suku'     => $sukuNimi,
    ':puhelin'  => $puhelinNumero,
    ':tyoajat'  => $tyotJSON,
    ':muut'     => $muuJSON,
    ':tauko'    => $tauko,
    ':kmkirjaus'=> $kmKirjaus,
    ':aloitus'  => $palkkaAloitus,
    ':lopetus'  => $palkkaLopetus
])) {
    $msg = "Yrityksen nimi: " . $yritysNimi . "\n Pääkäyttäjän Etunimi: " . $etuNimi . "\n Pääkäyttäjän Sukunimi: " . $sukuNimi . "\n Pääkäyttäjän Puhelinnumero: " . $puhelinNumero . "\n Työaikakirjauksen valinnat: " . $tyotJSON . "\n Muut Työaikakirjauksen Tiedot: " . $muuJSON . "\n Onko tauko palkallinen: " . $tauko . "\n Kirjataanko kilometrit: " . $kmKirjaus . "\n Seuraavan Palkkakauden Alku: " . $palkkaAloitus . "\n Seuraavan Palkkakauden Loppu: " . $palkkaLopetus;
    mail("laita tähän sähköpostisi", "Uusi Taskari Ilmoitus", $msg);
    header("Location: kysely.html");
    exit;
} 
else {
    echo "Database error";
};

echo "Data saved!";

?>
