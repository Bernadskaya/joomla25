<?php
/**
 * @version		$Id: default.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="k2martModule">
	<?php if($params->get('modLogo')): ?>
	<div id="k2martTitle">
		<a href="<?php echo JRoute::_('index.php?option=com_k2mart'); ?>" title="<?php echo JText::_('K2MART_K2MART_DASHBOARD'); ?>">
			<span>K2mart</span>
		</a>
	</div>
	<?php endif; ?>
	<div id="k2martChartsNavigation">
		<div class="simpleTabs">
			<ul class="simpleTabsNavigation">
				<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=30&format=raw'); ?>"><?php echo JText::_('K2MART_TOTAL_SALES'); ?></a></li>
				<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=products&limit=10&format=raw'); ?>"><?php echo JText::_('K2MART_TOP_SELLING_PRODUCTS'); ?></a></li>
			</ul>
			<div class="simpleTabsContent">
				<span><?php echo JText::_('K2MART_PLEASE_SELECT_A_DATE_RANGE_TO_APPLY_BELOW'); ?>:</span>
				<ul>
					<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=7&format=raw'); ?>"> 1<?php echo JText::_('K2MART_WEEK'); ?></a></li>
		  			<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=14&format=raw'); ?>">2 <?php echo JText::_('K2MART_WEEKS'); ?></a></li>
		  			<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=30&format=raw'); ?>">4 <?php echo JText::_('K2MART_WEEKS'); ?></a></li>
		  			<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=90&format=raw'); ?>">3 <?php echo JText::_('K2MART_MONTHS'); ?></a></li>
					<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=180&format=raw'); ?>">6 <?php echo JText::_('K2MART_MONTHS'); ?></a></li>
		  			<li class="lastItem"><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=sales&interval=365&format=raw'); ?>">12 <?php echo JText::_('K2MART_MONTHS'); ?></a></li>
		  		</ul>
	    	</div>
	    	<div class="simpleTabsContent">
	    		<span><?php echo JText::_('K2MART_PLEASE_SELECT_COUNT_FOR_TOP_SELLING_PRODUCTS_TO_APPLY_BELOW'); ?>:</span>
				<ul>
					<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=products&limit=10&format=raw'); ?>"><?php echo JText::_('K2MART_TOP'); ?> 10</a></li>
					<li><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=products&limit=20&format=raw'); ?>"><?php echo JText::_('K2MART_TOP'); ?> 20</a></li>
					<li class="lastItem"><a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=dashboard&task=loadChartData&type=products&limit=30&format=raw'); ?>"><?php echo JText::_('K2MART_TOP'); ?> 30</a></li>
				</ul>
	  		</div>
		</div>
		<div id="k2martChart"></div>
	</div>
</div>