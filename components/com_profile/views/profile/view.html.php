<?php
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.application.component.view');
class ProfileViewProfile extends JView
{
function display ($tpl = null)
	{
	$model = $this->getModel();
	$rows = $model->getProfile();
	$this->assignRef('rows',$rows);	
parent::display($tp1);
	}
}
?>