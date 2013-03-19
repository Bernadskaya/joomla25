<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class NotifyViewNotify extends JView
{

function display ($tpl = null)
	{
	$model = $this->getModel();
	if(isset($_GET['action']))
	{
	switch($_GET['action'])
	{
		case 'notify': $notify = $model->getNotify(); break;
		case 'fail': ; break;
		case 'success': $item = $model->getItem($_POST['shp_adid']); break;
		case 'result': $result = $model->getResult($_POST); break;
	}
	
	if($_GET['action']=='success'&&$item)
		{
			$user = & JFactory::getUser($notyfed->userid);
			$this->assignRef('user',$user);
			$this->assignRef('item',$item);
			$this->assignRef('action',$_GET['action']);
			parent::display($tp1);
			
		}
	else if($_GET['action']=='fail')
		{
		exit;
		//$this->assignRef('action',$_GET['action']);
		//parent::display($tp1);
		}
	else if($_GET['action']=='result')
		{
		if($result) echo 'OK'.$result."\n";
			else echo "bad sign\n";
		exit;
		}
		else exit;
	

	}
	else exit;
	}
}
?>