<?php

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

class BillViewBill extends JView
{

function display ($tpl = null)
	{
	$titles=array();
	$profiles=array();
	$model = $this->getModel();
	$rows = $model->getBill();
	foreach($rows as $row)
		{
			$titles[$row->id]=$model->getTitle($row->adid);
			$profiles[$row->id]=$model->getProfile($row->profileid);
		}
	$this->assignRef('profiles',$profiles);
	$this->assignRef('titles',$titles);
	$this->assignRef('rows',$rows);
	
parent::display($tp1);
	}


}

?>