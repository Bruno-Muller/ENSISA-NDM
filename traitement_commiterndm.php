<?php

require_once('./lib/anecdote/anecdote.php');
require_once('./lib/content/message.php');
require_once('./lib/content/form.php');

use lib\anecdote\Anecdote;
use lib\content\Message;
use lib\content\Form;

if (!isset($_POST['username']) ||
	!isset($_POST['anecdote'])) {
	echo new Message('Cheater !', Message::ERROR);
	exit();
}

if (empty($_POST['username']) || empty($_POST['anecdote'])) {
	echo new Message('Tous les champs ne sont pas renseignés.', Message::ERROR);
	exit();
}

$anecdote = new Anecdote();
$anecdote->setUsername($_POST['username']);
$anecdote->setAnecdote($_POST['anecdote']);

Anecdote::saveAnecdote($anecdote);

//echo new Message('NDM ajoutée !', Message::SUCCES); // sert à rien mais bon, on verra

header('Location: ./index.php?page=home');
exit();
?>