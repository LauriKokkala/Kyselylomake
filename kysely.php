<?php 

include("yhteys.php");

// Kerätään kaikki helpot
$yritysNimi  = $_POST['yritysNimi'];
$etuNimi     = $_POST['etuNimi'];
$sukuNimi    = $_POST['sukuNimi'];
$puhelinNumero = $_POST['puhelinNumero'];
$palkkaAloitus = $_POST['aloitusPaiva'];
$palkkaLopetus = $_POST['lopetusPaiva'];

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



// Databaseen heitto
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
    header("Location: kysely.html?status=ok");
    exit;
} 
else {
    echo "Database error";
};

echo "Data saved!";

?>
