<?php
require_once('./pages/template.php'); // Script qui gère les templates

if (!isset($_GET['n']) ||
	empty($_GET['n']) ||
	($_GET['n']!='401') ||
	($_GET['n']!='403') ||
	($_GET['n']!='403') ||
	($_GET['n']!='410') ||
	($_GET['n']!='500')) {

	$_GET['n']='404';
}

$contentPageTemplate = new Template('./errors/'.$_GET['n'].'.html'); // Construction du template

?>