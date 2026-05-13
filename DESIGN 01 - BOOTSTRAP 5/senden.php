<?php

$empfaenger1 = 'info@bueroservice-dringenberg.de';
#info@bueroservice-dringenberg.de

$name = $_POST['name'];

$mail = $_POST['email'];
$telefon = $_POST['telefon'];

$text = $_POST['message'];
$rechnung = $_POST['rechnung'];
$abfrage = $_POST['abfrage'];

if ($name != "" && $mail != "" && $text != "") {
#print "$betreff";

$betreff = 'Eine neue Kontaktanfrage auf bueroservice-dringenberg.de';
$mailtext = 'Name:'.$name.'     Mailadresse:'.$mail.'     Telefon:'.$telefon.'      Nachricht:'.$text;

mail($empfaenger1, $betreff, $mailtext, "From: Kontaktformular BÜROservice Dringenberg "); 


/* Bitte geben Sie hier den genauen Pfad zur Datei success.html an */
header("Location: success.html");
}

else {
	/* Bitte geben Sie hier den genauen Pfad zur Datei error.html an */
	header("Location: error.html");
}
?>
