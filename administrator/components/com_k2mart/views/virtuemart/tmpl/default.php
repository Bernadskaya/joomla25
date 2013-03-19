<?php
/**
 * @version		$Id: default.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
var $k2mart = jQuery.noConflict();
$k2mart(document).ready(function(){
	$k2mart('#applyFilters').click(function(){
		submitform();
	});
	$k2mart('#resetFilters').click(function(){
		$k2mart('#search').val('');
		$k2mart('#published').val('-1');
		$k2mart('#catid').val(0);
		$k2mart('#vmlang option').removeAttr('selected');
		submitform();
	});
});
</script>
<form action="index.php" method="post"	name="adminForm">
  <table>
    <tr>
      <td>
      	<label for="search"><?php echo JText::_('K2MART_SEARCH'); ?>:</label>
        <input type="text" name="search" id="search" value="<?php echo $this->filters['search'];?>" title="<?php echo JText::_('K2MART_SEARCH_BY_NAME_OR_SKU');?>"/>
        <button id="applyFilters"><?php echo JText::_('K2MART_GO'); ?></button>
        <button id="resetFilters"><?php echo JText::_('K2MART_RESET'); ?></button>
	  </td>
	  <td>
	  	<label for="published"><?php echo JText::_('K2MART_STATE');?>:</label>
	  	<?php echo $this->filters['published'];?>
	  </td>
	  <td>
	  	<label for="catid"><?php echo JText::_('K2MART_CATEGORIES');?>:</label>
	  	<?php echo $this->filters['category'];?>
	  </td>
	  <?php if(isset($this->filters['language'])):?>
	  <td>
	  	<label for="vmlang"><?php echo JText::_('K2MART_LANGUAGE');?>:</label>
	  	<?php echo $this->filters['language'];?>
	  </td>
	  <?php endif; ?>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th>#</th>
		<th><?php echo JHTML::_('grid.sort', 'K2MART_NAME', 'product_name', @$this->filters['orderingDir'], @$this->filters['ordering'] ); ?></th>
		<th><?php echo JHTML::_('grid.sort', 'K2MART_SKU', 'product_sku', @$this->filters['orderingDir'], @$this->filters['ordering'] ); ?></th>
		<th><?php echo JHTML::_('grid.sort', 'K2MART_CATEGORIES', 'category_name', @$this->filters['orderingDir'], @$this->filters['ordering']); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'K2MART_PUBLISHED', 'product_publish', @$this->filters['orderingDir'], @$this->filters['ordering'] ); ?></th>
      	<th><?php echo JHTML::_('grid.sort', 'K2MART_ID', 'virtuemart_product_id', @$this->filters['orderingDir'], @$this->filters['ordering'] ); ?></th>
      </tr>
    </thead>
    <tbody>
	<?php foreach($this->rows as $key=>$row): ?>
	      <tr class="row<?php echo (($key+1)%2); ?>">
	        <td><?php echo $key+1; ?></td>
			<td><a href="#" onclick="window.parent.k2martAssignProduct('<?php echo $row->virtuemart_product_id;?>', '<?php echo $this->escape($row->product_name);?>');"><?php echo $this->escape($row->product_name);?></a> </td>
			<td><?php echo $row->product_sku; ?></td>
			<td><?php echo $row->category_name; ?></td>
			<td>
			<?php if ($row->product_publish): ?>
				<img src="<?php echo JURI::base(true).'/templates/'.$this->template; ?>/images/admin/tick.png" alt="<?php echo JText::_('K2MART_PRODUCT_IS_PUBLISHED')?>"/>
			<?php else:?>
				<img src="<?php echo JURI::base(true).'/templates/'.$this->template; ?>/images/admin/publish_x.png" alt="<?php echo JText::_('K2MART_PRODUCT_IS_UNPUBLISHED')?>"/>
			<?php endif;?>
			</td>
			<td><?php echo $row->virtuemart_product_id; ?></td>
	      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>
  <input type="hidden" name="option" value="com_k2mart" />
  <input type="hidden" name="view" value="virtuemart" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->filters['ordering']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['orderingDir']; ?>" />
  <?php echo JHTML::_('form.token'); ?>
</form>

<div id="k2martAdminFooter">
	<?php echo JText::_('K2MART_COPYRIGHTS'); ?>
</div>
