<?php
require_once('./pages/template.php'); // Script qui gère les templates
require_once('./lib/anecdote/anecdote.php');
require_once('./lib/content/message.php');

use lib\anecdote\Anecdote;
use lib\content\Message;

$contentPageTemplate = new Template('./pages/commiterndm.html'); // Construction du template

if (isset($_POST['username']) &&
	isset($_POST['anecdote']) &&
	isset($_POST['password'])) {

	if (!empty($_POST['username']))
		$contentPageTemplate->replace('username', $_POST['username']);
	if (!empty($_POST['anecdote']))
		$contentPageTemplate->replace('anecdote', $_POST['anecdote']);
	
	if (empty($_POST['username']) || empty($_POST['anecdote'])) {
		$contentPageTemplate->replace('message', new Message('Tous les champs ne sont pas renseignés.', Message::ERROR));
	}
	elseif ($_POST['password'] != 'azerty') {
		$contentPageTemplate->replace('message', new Message('Mot de passe invalide.', Message::ERROR));
	}
	else {
		$anecdote = new Anecdote();
		$anecdote->setUsername($_POST['username']);
		$anecdote->setAnecdote($_POST['anecdote']);

		Anecdote::saveAnecdote($anecdote);

		header('Location: ./index.php?page=home');
		exit();
	}
}

?>