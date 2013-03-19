<?php
/**
 * @version		$Id: mod_k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$language = JFactory::getLanguage();
$language->load('com_k2mart');

$document = JFactory::getDocument();
if ($params->get('modCSSStyling', 1))
{
	$document->addStyleSheet(JURI::root(true).'/administrator/modules/mod_k2mart/tmpl/css/style.css');
}
$document->addStyleSheet(JURI::base(true).'/components/com_k2mart/css/style.css');
$document->addScript(JURI::base(true).'/components/com_k2mart/js/simpletabs_1.3.packed.js');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js');
$document->addScript(JURI::base(true).'/components/com_k2mart/js/highcharts.js');
$document->addScript(JURI::base(true).'/components/com_k2mart/js/script.jquery.js');

$document->addScriptDeclaration("
					Highcharts.setOptions({
						lang: {
							months: [
									'".JText::_('JANUARY', true)."',
									'".JText::_('FEBRUARY', true)."',
									'".JText::_('MARCH', true)."',
									'".JText::_('APRIL', true)."',
									'".JText::_('MAY', true)."',
									'".JText::_('JUNE', true)."',
									'".JText::_('JULY', true)."',
									'".JText::_('AUGUST', true)."',
									'".JText::_('SEPTEMBER', true)."',
									'".JText::_('OCTOBER', true)."',
									'".JText::_('NOVEMBER', true)."',
									'".JText::_('DECEMBER', true)."'
									],
							weekdays: [
									'".JText::_('SUNDAY', true)."',
									'".JText::_('MONDAY', true)."',
									'".JText::_('TUESDAY', true)."',
									'".JText::_('WEDNESDAY', true)."',
									'".JText::_('THURSDAY', true)."',
									'".JText::_('FRIDAY', true)."',
									'".JText::_('SATURDAY', true)."'
									],
							resetZoom: '".JText::_('K2MART_RESET_ZOOM', true)."'
						}
					});
        ");

require (JModuleHelper::getLayoutPath('mod_k2mart', 'default'));
