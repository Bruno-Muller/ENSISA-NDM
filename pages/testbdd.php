<?php
require_once('./pages/template.php'); // Script qui gère les templates

$instance = new PDO('mysql:host=82.242.35.40;dbname=nuitinfo', 'nuitinfo', 'azerty');
$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$req = $instance->prepare('SELECT username, anecdote, DATE_FORMAT(timestamp, \'%c/%e/%x\') AS date, DATE_FORMAT(timestamp, \'%Hh%i\') AS time FROM anecdotes');
$req->execute();

$array = array();
while($datas = $req->fetch())
       $array[] = $datas;
$req->closeCursor();

$contentPageTemplate = new Template('./pages/testbdd.html'); // Construction du template

$contentPageTemplate->iterateReplace('anecdotes', $array);

?>