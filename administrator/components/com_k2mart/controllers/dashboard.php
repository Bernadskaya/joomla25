<?php
/**
 * @version		$Id: dashboard.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2martControllerDashboard extends JController
{

	public function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('view', 'dashboard');
		parent::display();
	}

	public function loadChartData()
	{
		$mainframe = JFactory::getApplication();
		$model = $this->getModel('dashboard');
		$model->loadChartData();
		$mainframe->close();
	}

}
