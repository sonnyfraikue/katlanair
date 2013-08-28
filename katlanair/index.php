<?php 
/*
 * USERS BROWSE TEMPLATES TO CUSTOMISE
 */
require_once 'classes/dbconnect.class.php';
require_once 'classes/template.class.php';

$myTemplateInstance = new myTemplateClass ();
$mydb	=	new db();


//displaying loaded markup
$content		=	($_GET['dev'])?'MARKUP':null;
$contentclass	=	($_GET['dev'])?'dev':null;

$header			=	file_get_contents('html/global/header.htm');
$content		=	file_get_contents('html/global/content.htm');
$footer			=	file_get_contents('html/global/footer.htm');
$loadpage 		= 	($_GET['dev'])?'html/index.htm':'html/dev.htm';
$myTemplateInstance->htmlFile = $loadpage;
$myTemplateInstance->htmlPath = '';
$tags = array ('HEADER'=>$header,'FOOTER'=>$footer,'CONTENT' => $content,'SITETITLE'=>'Katlanair','DEVCLASS'=>$contentclass);
echo $myTemplateInstance->generateHtml ( $tags );
?>