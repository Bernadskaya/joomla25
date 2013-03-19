<?php 


defined('_JEXEC') or die ('Restricted access');
$user = & JFactory::getUser();
if ($user->get('guest') == '1') 
	$mainframe->redirect('/component/user/register', 'Требуется авторизация','error');

require_once ( JPATH_COMPONENT.DS.'controller.php');


if($controller = JRequest::getVar('controller')) {
require_once ( JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}


$classname = 'BillController'.$controller;
$controller = new $classname ();


$controller->execute(JRequest::getVar ('task'));


$controller->redirect();

?>