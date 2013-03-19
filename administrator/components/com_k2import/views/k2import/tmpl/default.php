<?php
/**
 * @version		$Id: default.php 1190 2011-10-17 14:31:26Z lefteris.kavadas $
 * @package		K2 import - original: K2
 * @author		JoomlaWorks http://www.joomlaworks.gr - chaged by Artur Neumann www.individual-it.net
 * 				for the needs of the K2 Import / Export component
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$document = & JFactory::getDocument();

//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
//deleted some javascript

//Artur Neumann www.individual-it.net info@individual-it.net  29.02.2012
//added some explanations and changed all K2_* textes
?>
<strong>
<?php 
echo JText::_('COM_K2IMPORT_CATEGORY_LIST_DESC');
?>
</strong>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table class="k2AdminTableFilters">
		<tr>
			<td class="k2AdminTableFiltersSearch">
				<?php echo JText::_('COM_K2IMPORT_FILTER'); ?>
				<input type="text" name="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('COM_K2IMPORT_FILTER_BY_TITLE'); ?>"/>
			</td>
			<td class="k2AdminTableFiltersSelects">
				<?php echo $this->lists['trash']; ?> <?php echo $this->lists['state']; ?>
				<?php if(isset($this->lists['language'])): ?>
				<?php echo $this->lists['language']; ?>
				<?php endif; ?>
				<button id="k2SubmitButton"><?php 
				echo JText::_('COM_K2IMPORT_FILTER_CATEGORIES'); ?></button>
				<button id="k2ResetButton"><?php echo JText::_('COM_K2IMPORT_RESET'); ?></button>
			</td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th>
					#
				</th>
				<th>
				<?php 
				//Artur Neumann www.individual-it.net info@individual-it.net  24.02.2012
				//changed to joomla own checkAll function http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_6_-_Adding_Backend_Actions#Checkboxes_and_Links
				?>
					<input type="checkbox" onclick="Joomla.checkAll(this)" title="" value="" name="checkall-toggle">
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_ITEM_TITLE', 'c.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_ORDER', 'c.ordering', @$this->lists['order_Dir'], @$this->lists['order'] );
//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
//deleted save button
					?> 
				</th>
				<th>
					<?php echo JText::_('COM_K2IMPORT_PARAMETER_INHERITANCE'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_ASSOCIATED_EXTRA_FIELD_GROUPS', 'extra_fields_group', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_ACCESS_LEVEL', 'c.access', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_PUBLISHED', 'c.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JText::_('COM_K2IMPORT_IMAGE'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_K2IMPORT_ID', 'c.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->page->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->rows as $key => $row) :	?>
			<tr class="row<?php echo ($key%2); ?>">
				<td>
					<?php echo $key+1; ?>
				</td>
				<td class="k2Center">
					<?php if(!$this->filter_trash || $row->trash) { $row->checked_out = 0; echo JHTML::_('grid.checkedout', $row, $key );}?>
				</td>
				<td>
					<?php if ($this->filter_trash): ?>
					<?php if ($row->trash): ?>
					<strong><?php echo $row->treename; ?></strong>
					<?php else: ?>
					<?php echo $row->treename; ?>
					<?php endif; ?>
					<?php else: ?>
					<a href="<?php echo JRoute::_('index.php?option=com_k2&view=category&cid='.$row->id); ?>"><?php echo $row->treename; ?>
					<?php if($this->params->get('showItemsCounterAdmin')): ?>
					(<?php echo $row->numOfItems; ?>)
					<?php endif; ?>
					</a>
					<?php endif; ?>
				</td>
				<td class="order k2Order">
				<?php 
				//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
				//deleted order buttons and changed the input box to simple text
				?>
					<?php echo $row->ordering; ?>
				</td>
				<td class="k2Center">
					<?php echo $row->inheritFrom; ?>
				</td>
				<td class="k2Center">
					<?php echo $row->extra_fields_group; ?>
				</td>
				<td class="k2Center">
						

					<?php 
					//Artur Neumann www.individual-it.net info@individual-it.net  29.02.2012
					//changed from 	K2_JVERSION to the joomla standard function
					
					echo ($this->filter_trash || version_compare( JVERSION, '1.6.0', 'ge' ))?strip_tags(JHTML::_('grid.access', $row, $key )):JHTML::_('grid.access', $row, $key ); ?>
				</td>
				<td class="k2Center">
				<?php
				//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
				//changed from picture to text
				 if ($row->published) {
				 	echo JText::_('COM_K2IMPORT_YES'); 
				 } else {
				 	echo JText::_('COM_K2IMPORT_NO'); 
				 }
				?>
				</td>
				<td class="k2Center">
					<?php if($row->image): ?>
					<a href="<?php echo JURI::root().'media/k2/categories/'.$row->image; ?>" class="modal">
						<img src="templates/<?php echo $this->template; ?>/images/menu/icon-16-media.png" alt="<?php echo JText::_('COM_K2IMPORT_PREVIEW_IMAGE'); ?>" />
					</a>
					<?php endif; ?>
				</td>
				<td class="k2Center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php 
	//Artur Neumann www.individual-it.net info@individual-it.net  20.02.2012
	//changes to com_k2import
	?>
	<input type="hidden" name="option" value="com_k2import" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>



