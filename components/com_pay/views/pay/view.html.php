<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class PayViewPay extends JView
{

function display ($tpl = null)
	{
	$model = $this->getModel();
	$rows = $model->getPay();
	$this->assignRef('rows',$rows);
	//print_r($_POST); exit;
	if(isset($_POST['bill']))
		{
			//unset($_POST['bill']);
			$profile = $model->getProfile($_POST['profileid']);
			$item = $model->getTitle($_POST['adid']);
			$bill = $model->getBill($item,$profile);
			$this->assignRef('bill',$bill);
			$this->assignRef('profile',$profile);
			$this->assignRef('item',$item);
		}
parent::display($tp1);
	}


}

?>