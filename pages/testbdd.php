<?php
require_once('./lib/anecdote/anecdote.php');
require_once('./pages/template.php'); // Script qui gère les templates

use lib\anecdote\Anecdote;

$datas = Anecdote::getBDDAnecdotes();
$array = array();
foreach ($datas as $data)
	$array[] = $data->toArray();

$contentPageTemplate = new Template('./pages/testbdd.html'); // Construction du template
$contentPageTemplate->iterateReplace('anecdotes', $array);

?>