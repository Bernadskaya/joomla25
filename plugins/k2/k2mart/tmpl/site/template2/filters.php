<?php
/**
 * @version		$Id: filters.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

defined('_JEXEC') or die('Restricted access'); ?>
<?php if($filters['k2martCategories'] || $filters['k2martManufacturers'] || $filters['k2martOrdering']): ?>
<div id="k2mart" class="k2martClearFix">
	<a id="k2martFiltersToggler">
		<span class="k2martFiltersTogglerText"><?php echo JText::_('K2MART_FILTER_ITEMS'); ?></span>
		<span class="k2martFiltersTogglerIcon"></span>
	</a>
	<div id="k2martFilters">
		<div class="k2martFiltersDescription"><?php echo JText::_('K2MART_FILTERS_DESCRIPTION'); ?></div>
		<form action="<?php echo JRoute::_('index.php'); ?>">
			<table id="k2martFiltersTable">
				<?php if($filters['k2martCategories']): ?>
				<tr>
					<td class="k2martFilterLabel"><label for="k2martCategories"><?php echo JText::_('K2MART_CATEGORIES')?></label></td>
					<td><?php echo $filters['k2martCategories']; ?></td>
				</tr>
				<?php endif; ?>
				<?php if($filters['k2martManufacturers']): ?>
				<tr>
					<td class="k2martFilterLabel"><label for="k2martManufacturers"><?php echo JText::_('K2MART_MANUFACTURERS')?></label></td>
					<td><?php echo $filters['k2martManufacturers']; ?></td>
				</tr>
				<?php endif; ?>
				<?php if($filters['k2martOrdering']): ?>
				<tr>
					<td class="k2martFilterLabel"><label><?php echo JText::_('K2MART_ORDERING')?></label></td>
					<td>
					<?php echo $filters['k2martOrdering']; ?>
					<?php echo $filters['k2martOrderingDir']; ?>
					</td>
				</tr>
				<?php endif; ?>
			</table>
		</form>
	</div>
</div>
<?php endif; ?>