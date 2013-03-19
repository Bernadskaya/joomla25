<?php
/**
 * @version		$Id: admin.k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// core.manage check
$user = JFactory::getUser();
if (!$user->authorise('core.manage', 'com_k2mart'))
{
	JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
	$mainframe = JFactory::getApplication();
	$mainframe->redirect('index.php');
}

// Import Joomla! libraries
jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.application.component.view');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.html.pagination');

// Load Virtuemart configuration
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
VmConfig::loadConfig();

// Add tables and models paths
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'tables');
JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models');
JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models');

// Add the helpers to auto-load
JLoader::register('K2martProductHelper', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'product.php');
JLoader::register('K2martCategoryHelper', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'category.php');

// Add styles and scripts
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base(true).'/templates/system/css/system.css');
$document->addStyleSheet(JURI::base(true).'/components/com_k2mart/css/style.css');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js');
$document->addScriptDeclaration('var $K2mart = jQuery.noConflict();');

// Execute
$view = JRequest::getCmd('view', 'dashboard');
if (JFile::exists(JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php'))
{
	JLoader::register('K2martController'.$view, JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php');
	$class = 'K2martController'.$view;
	$controller = new $class();
	$controller->execute(JRequest::getWord('task'));
	$controller->redirect();
}
