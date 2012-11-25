<?php
ini_set('display_errors', true);

require('lib/anecdote/anecdote.php');

use lib\anecdote\Anecdote;

$anecdote = Anecdote::getAnecdote('1');
echo $anecdote->getId();        
echo $anecdote->getUsername();   
echo $anecdote->getTheAnecdote();   
?>