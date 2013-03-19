<?php
/**
 * @version		$Id: mod_k2_stats.php 1779 2012-11-22 17:21:38Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

$user = JFactory::getUser();

if (K2_JVERSION != '15')
{
	if (!$user->authorise('core.manage', 'com_k2'))
	{
		return;
	}
}

if (K2_JVERSION != '15')
{
	$language = JFactory::getLanguage();
	$language->load('mod_k2.j16', JPATH_ADMINISTRATOR);
}

require_once (dirname(__FILE__).DS.'helper.php');

if ($params->get('latestItems', 1))
{
	$latestItems = modK2StatsHelper::getLatestItems();
}
if ($params->get('popularItems', 1))
{
	$popularItems = modK2StatsHelper::getPopularItems();
}
if ($params->get('mostCommentedItems', 1))
{
	$mostCommentedItems = modK2StatsHelper::getMostCommentedItems();
}
if ($params->get('latestComments', 1))
{
	$latestComments = modK2StatsHelper::getLatestComments();
}
if ($params->get('statistics', 1))
{
	$statistics = modK2StatsHelper::getStatistics();
}

// Quick and dirty fix for Joomla! 3.0 missing CSS tabs when creating tabs using the API. It will be removed when Joomla! fixes that
if (K2_JVERSION == '30')
{
	$document = JFactory::getDocument();
	$document->addStyleDeclaration('
	dl.tabs {float: left; margin: 10px 0 -1px 0; z-index: 50;}
	dl.tabs dt {float: left;padding: 4px 10px;border: 1px solid #ccc;margin-left: 3px;background: #e9e9e9;color: #666;}
	dl.tabs dt.open {background: #F9F9F9;border-bottom: 1px solid #f9f9f9;	z-index: 100;	color: #000;}
	div.current {clear: both;border: 1px solid #ccc;padding: 10px 10px;}
	dl.tabs h3 {font-size:12px; line-height:12px; margin: 4px; 0;}
');
}

require (JModuleHelper::getLayoutPath('mod_k2_stats'));
