<?php
ini_set('display_errors', true);

require_once('./lib/content/menu.php');
require_once('./pages/template.php');

use lib\content\Menu;

//Template maitre, les pages supplémentaires sont à mettre dans le dossier pages

/*
if (empty($_GET['page'])) {
    header('Location: /index.php?page=home');
    exit();
}
*/

// Menu principal
$_GET['page'] = empty($_GET['page'])?'home':$_GET['page'];
$masterMenuLinks = array('home' => 'Accueil',
						 'commiterndm' => 'Commiter une NDM');
$masterMenu = new Menu();
foreach($masterMenuLinks as $page => $title){
    $masterMenu->addlink($title, $page, ($_GET['page'] == $page));
}

// Contenu de la page
str_replace("\0", '', $_GET['page']); //Protection bytenull
str_replace(DIRECTORY_SEPARATOR, '', $_GET['page']); //Protection navigation
$contentPage = 'pages/'.$_GET['page'].'.php';
$contentPage = file_exists($contentPage)?$contentPage:'./pages/404.php';
include($contentPage); // Créer un contentPageTemplate

//Affichage
$template = new Template('./pages/index.html'); // Construction du template
$template->replace('masterMenu', $masterMenu);
$template->replace('contentPage', $contentPageTemplate->toString());
//$template->replace('dateMaj', include('maj.php'));

echo $template->toString();

?>