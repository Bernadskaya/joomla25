<?php
/**
 * @version		$Id: view.html.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author      JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2martViewDashboard extends JView
{

	function display($tpl = null)
	{
		jimport('joomla.html.pane');
		$pane = JPane::getInstance('Tabs');
		$this->assignRef('pane', $pane);
		$model = $this->getModel();
		$numOfK2Items = $model->countK2Items();
		$this->assignRef('numOfK2Items', $numOfK2Items);
		$numOfVmProducts = $model->countVmProducts();
		$this->assignRef('numOfVmProducts', $numOfVmProducts);
		$numOfK2martProducts = $model->countK2martProducts();
		$this->assignRef('numOfK2martProducts', $numOfK2martProducts);
		$module = JModuleHelper::getModule('mod_k2mart');
		$params = new JRegistry;
		$params->loadString($module->params);
		$params->set('modLogo', "0");
		$params->set('modCSSStyling', "1");
		$module->params = $params->toString();
		$charts = JModuleHelper::renderModule($module);
		$this->assignRef('charts', $charts);
		$document = JFactory::getDocument();
		$document->addCustomTag('<!--[if lte IE 7]><link href="'.JURI::base().'components/com_k2mart/css/style_ie7.css" rel="stylesheet" type="text/css" /><![endif]-->');
		$this->loadHelper('html');
		K2martHTMLHelper::title('K2MART_DASHBOARD');
		K2martHTMLHelper::toolbar();
		K2martHTMLHelper::subMenu();
		parent::display($tpl);
	}

}
