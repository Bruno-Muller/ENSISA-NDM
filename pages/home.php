<?php
require_once('./lib/anecdote/anecdote.php');
require_once('./pages/template.php'); // Script qui gère les templates

use lib\anecdote\Anecdote;

if (isset($_GET['a']) &&
	!empty($_GET['a']) &&
	isset($_GET['id']) &&
	!empty($_GET['id'])) {

	$anecdote = Anecdote::getBDDAnecdote($_GET['id']);
	switch($_GET['a']) {
		case 'validendm':
			$anecdote->incValideNDM();
			break;
		case 'bienmerite':
			$anecdote->incBienMerite();
			break;
	} 
	Anecdote::saveAnecdote($anecdote);
	header('Location: ./index.php?page=home');
    exit();
}


$datas = Anecdote::getBDDAnecdotes();
$array = array();
foreach ($datas as $data)
	$array[] = $data->toArray();

$contentPageTemplate = new Template('./pages/home.html'); // Construction du template
$contentPageTemplate->iterateReplace('anecdotes', $array);

?>