<?php

require_once('./lib/anecdote/anecdote.php');


use lib\anecdote\Anecdote;

$anecdote = new Anecdote();
$anecdote->setUsername($_POST['username']);
$anecdote->setAnecdote($_POST['anecdote']);

Anecdote::saveAnecdote($anecdote);


header('Location: ./index.php?page=home');
    exit();

?>