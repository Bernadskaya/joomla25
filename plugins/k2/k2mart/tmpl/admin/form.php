<?php
/**
 * @version		$Id: form.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

defined('_JEXEC') or die('Restricted access');
?>
<div id="k2martForm" class="vmForm">
	<ul class="k2martTabsNavigation">
		<li><a href="#k2martTab1"><?php echo JText::_('K2MART_PRODUCT_INFORMATION'); ?></a></li>
		<li><a href="#k2martTab2"><?php echo JText::_('K2MART_PRODUCT_PRICE'); ?></a></li>
		<li><a href="#k2martTab3"><?php echo JText::_('K2MART_PRODUCT_STATUS'); ?></a></li>
		<li><a href="#k2martTab4"><?php echo JText::_('K2MART_CUSTOM_FIELDS'); ?></a></li>
		<li><a href="#k2martTab5"><?php echo JText::_('K2MART_PRODUCT_DIMENSIONS_AND_WEIGHT'); ?></a></li>
		<?php if($params->get('advancedTab') && $mainframe->isAdmin()):?>
		<li><a href="#k2martTab6"><?php echo JText::_('K2MART_ADVANCED'); ?></a></li>
		<?php endif; ?>
	</ul>
	<div id="k2martTab1" class="k2martTabsContent">
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_SKU')?></label>
			<input type="text" maxlength="64" size="32" value="<?php echo $product->product_sku; ?>" name="product_sku" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_URL')?></label>
			<input type="text" maxlength="255" size="32" value="<?php echo $product->product_url; ?>" name="product_url" />
		</div>
		<?php if(isset($lists['vendors'])): ?>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_VENDOR')?></label>
			<?php echo $lists['vendors']; ?>
		</div>
		<?php endif; ?>
		<?php if(isset($lists['manufacturers'])): ?>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_MANUFACTURER')?></label>
			<?php echo $lists['manufacturers']; ?>
			<?php if($mainframe->isAdmin()):?>
			<a title="<?php echo JText::_('K2MART_MANAGE_MANUFACTURERS')?>" class="modal vmButton" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" href="index.php?option=com_virtuemart&amp;view=manufacturer"><img src="<?php echo $editIcon; ?>" alt="<?php echo JText::_('K2MART_MANAGE_MANUFACTURERS'); ?>" /></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if($params->get('categoriesList')):?>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_CATEGORY_S')?></label>
			<select id="categories" name="categories[]" multiple="multiple" size="10">
				<option value=""><?php echo JText::_('COM_VIRTUEMART_UNCATEGORIZED')  ?></option>
				<?php echo $lists['categories']; ?>
			</select>
			<?php if($mainframe->isAdmin()):?>
			<a title="<?php echo JText::_('K2MART_MANAGE_CATEGORIES')?>" class="modal vmButton" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" href="index.php?option=com_virtuemart&amp;view=category"><img src="<?php echo $editIcon; ?>" alt="<?php echo JText::_('K2MART_MANAGE_CATEGORIES'); ?>" /></a>
			<?php endif; ?>
		</div>
		<?php else :?>
		<?php foreach($product->categories as $category): ?>
		<input type="hidden" name="categories[]" value="<?php echo $category; ?>" />
		<?php endforeach; ?>
		<?php endif; ?>
		<label><?php echo JText::_('K2MART_PARENT')?></label>
		<?php echo $lists['product_parent_id']; ?>
		<?php if(isset($lists['shopperGroups'])): ?>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_SHOPPER_FORM_GROUP'); ?></label>
			<?php echo $lists['shopperGroups']; ?>
			<?php if($mainframe->isAdmin()):?>
			<a title="<?php echo JText::_('K2MART_MANAGE_SHOPPER_GROUPS'); ?>" class="modal vmButton" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" href="index.php?option=com_virtuemart&amp;view=shoppergroup"><img src="<?php echo $editIcon;?>" alt="<?php echo JText::_('K2MART_MANAGE_SHOPPER_GROUPS'); ?>" /></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<div id="k2martTab2" class="k2martTabsContent">
		<div class="k2martFormRow">
			<label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST_TIP'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_COST'); ?></label>
			<input type="text" value="<?php echo $product->prices['costPrice']; ?>" name="product_price" size="12" />
			<?php echo $lists['currencies']; ?>
		</div>
		<div class="k2martFormRow">
			<label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE_TIP'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASE'); ?></label>
			<input type="text" readonly="readonly" name="basePrice" size="12" value="<?php echo $product->prices['basePrice']; ?>" /> <span class="currencySymbol"><?php echo $vendorCurrency; ?></span>
		</div>
		<div class="k2martFormRow">
			<label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL_TIP'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_FINAL'); ?></label>
			<input type="text" name="salesPrice" size="12" value="<?php echo $product->prices['salesPriceTemp']; ?>" /> <span class="currencySymbol"><?php echo $vendorCurrency; ?></span>
			<div class="k2martClr"></div>
			<input type="checkbox" id="use_desired_price" name="use_desired_price" value="1" />
			<label class="k2martInlineLabel" for="use_desired_price" class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL_TIP'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_CALCULATE_PRICE_FINAL'); ?></label>
		</div>
        <div class="k2martFormRow">
            <label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE_TIP'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_OVERRIDE'); ?></label>
            <input type="text" size="12" name="product_override_price" value="<?php echo $product->product_override_price ?>"/> <span class="currencySymbol"><?php echo $vendorCurrency; ?></span>
            <div class="k2martClr"></div>
            <div class="priceOverrideOptions"><?php echo $lists['price_override_options']; ?></div>
        </div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_RATE_FORM_VAT_ID'); ?></label>
			<?php echo $lists['taxrates']; ?>
			<?php if($mainframe->isAdmin()):?>
			<a title="<?php echo JText::_('K2MART_MANAGE_RULES'); ?>" class="modal vmButton" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" href="index.php?option=com_virtuemart&amp;view=calc"><img src="<?php echo $editIcon;?>" alt="<?php echo JText::_('K2MART_MANAGE_RULES'); ?>" /></a>
			<?php endif; ?>
			<br />
        	<?php echo $taxRules; ?>
        </div>
        <div class="k2martFormRow">
	        <label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_DISCOUNT_TYPE'); ?></label>
			<?php echo $lists['discounts']; ?>
			<?php if($mainframe->isAdmin()):?>
			<a title="<?php echo JText::_('K2MART_MANAGE_RULES'); ?>" class="modal vmButton" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" href="index.php?option=com_virtuemart&amp;view=calc"><img src="<?php echo $editIcon;?>" alt="<?php echo JText::_('K2MART_MANAGE_RULES'); ?>" /></a>
			<?php endif; ?>
			<br />
	        <?php if(!empty($dbTaxRules)){ 
	        	echo JText::_('COM_VIRTUEMART_RULES_EFFECTING').$dbTaxRules.'<br />';
			}
			if(!empty($daTaxRules)){
				echo JText::_('COM_VIRTUEMART_RULES_EFFECTING').$daTaxRules;
			}
			?>
        </div>
	</div>
	<div id="k2martTab3" class="k2martTabsContent">
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_IN_STOCK')?></label>
			<input type="text" name="product_in_stock" value="<?php echo $product->product_in_stock; ?>" size="10" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_ORDERED_STOCK')?></label>
			<input type="text" name="product_ordered" value="<?php echo $product->product_ordered; ?>" size="10" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_LOW_STOCK_NOTIFICATION')?></label>
			<input type="text" name="low_stock_notification" value="<?php echo $product->low_stock_notification; ?>" size="3" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_MIN_ORDER')?></label>
			<input type="text" name="min_order_level" value="<?php echo $product->min_order_level; ?>" size="10" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_MAX_ORDER')?></label>
			<input type="text" name="max_order_level" value="<?php echo $product->max_order_level; ?>" size="10" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABLE_DATE')?></label>
			<?php echo JHTML::_('calendar', $product->product_available_date, 'product_available_date', 'product_available_date', '%Y-%m-%d'); ?>
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_AVAILABILITY')?></label>
			<input type="text" id="product_availability" name="product_availability" value="<?php echo $product->product_availability; ?>" />
			<span class="vmicon vmicon-16-info hasTip" title="<?php echo JText::_('COM_VIRTUEMART_AVAILABILITY'); ?>" rel="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP1'); ?>"></span>
			<?php echo JHTML::_('list.images', 'image', $product->product_availability, " ", $imagePath); ?>
			<span class="vmicon vmicon-16-info hasTip" title="<?php echo JText::_('COM_VIRTUEMART_AVAILABILITY'); ?>" rel="<?php echo JText::sprintf('COM_VIRTUEMART_PRODUCT_FORM_AVAILABILITY_TOOLTIP2',  $imagePath ); ?>"></span>
			<div id="k2martAvailabilityImage">
				<?php if($product->product_availability): ?>
				<img alt="<?php echo JText::_('COM_VIRTUEMART_PREVIEW'); ?>" src="<?php echo JURI::root(true).$imagePath.$product->product_availability;?>"/>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="k2martTab4" class="k2martTabsContent">
		<fieldset>
			<legend><?php echo JText::_('COM_VIRTUEMART_CUSTOM_FIELD_TYPE' );?></legend>
			<div class="inline"><?php echo $customsList; ?></div>
			<table id="custom_fields" class="adminlist" cellspacing="0" cellpadding="0">
				<thead>
				<tr class="row1">
					<th><?php echo JText::_('COM_VIRTUEMART_TITLE');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_CUSTOM_TIP');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_VALUE');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_CART_PRICE');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_TYPE');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_CUSTOM_IS_CART_ATTRIBUTE');?></th>
					<th><?php echo JText::_('COM_VIRTUEMART_DELETE'); ?></th>
				</tr>
				</thead>
				<tbody id="custom_field">
				<?php $i = 0; foreach ($product->customfields as $customRow): ?>
				<?php if(!in_array($customRow->field_type, array('Z', 'R', 'G', 'E'))):?>
				<tr class="removable">
					<td><?php echo JText::_($customRow->custom_title); ?></td>
					<td><?php echo $customRow->custom_tip; ?></td>
					<td><?php echo $customRow->display; ?></td>
					<td><?php echo JText::_($fieldTypes[$customRow->field_type]).VirtueMartModelCustomfields::setEditCustomHidden($customRow, $i);?></td>
					<td>
					<span class="vmicon vmicon-16-<?php echo ($customRow->is_cart_attribute)? 'default':'default-off'; ?>"></span>
					</td>
					<td><span class="vmicon vmicon-16-remove"></span><input class="ordering" type="hidden" value="<?php echo $customRow->ordering; ?>" name="field[<?php echo $i; ?>][ordering]" /></td>
				 </tr>
				 <?php endif; ?>
				<?php $i++; endforeach; ?>
				</tbody>
			</table>
			<input type="hidden" name="save_customfields" value="1" />
		</fieldset>
		<fieldset>
			<legend><?php echo JText::_('COM_VIRTUEMART_CUSTOM_EXTENSION'); ?></legend>
			<div id="custom_customPlugins">
			<?php $i = 0; foreach ($product->customfields as $customRow): ?>
			<?php if($customRow->field_type == 'E'):?>
				<fieldset class="removable">
					<legend><?php echo JText::_($customRow->custom_title);?></legend>
					<span><?php echo $customRow->display.$customRow->custom_tip; ?></span>
					<?php echo VirtueMartModelCustomfields::setEditCustomHidden($customRow, $i); ?>
					<span class="vmicon icon-nofloat vmicon-16-<?php echo ($customRow->is_cart_attribute)? 'default':'default-off'; ?>"></span>
					<span class="vmicon vmicon-16-remove"></span>
				</fieldset>
			<?php endif; ?>
			<?php $i++; endforeach; ?>
			</div>
		</fieldset>
	</div>
	<div id="k2martTab5" class="k2martTabsContent">
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_LENGTH')?></label>
			<input type="text" name="product_length" value="<?php echo $product->product_length; ?>" size="15" maxlength="15" />
			<?php echo $lists['product_lwh_uom']; ?>
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_WIDTH')?></label>
			<input type="text" name="product_width" value="<?php echo $product->product_width; ?>" size="15" maxlength="15" />
		</div>
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_HEIGHT')?></label>
			<input type="text" name="product_height" value="<?php echo $product->product_height; ?>" size="15" maxlength="15" />
		</div>
		<hr class="vmSeperator" />
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_WEIGHT')?></label>
			<input type="text" name="product_weight" size="15" maxlength="15" value="<?php echo $product->product_weight; ?>" />
			<?php echo $lists['product_weight_uom'];?>
		</div>
		<hr class="vmSeperator" />
		<div class="k2martFormRow">
			<label><?php echo JText::_('COM_VIRTUEMART_PRODUCT_UNIT')?></label>
			<input type="text" name="product_unit" size="15" maxlength="15" value="<?php echo $product->product_unit; ?>" />
		</div>
		<div class="k2martFormRow">
			<label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING_DESCRIPTION'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_PACKAGING')?></label>
			<input type="text" name="product_packaging" value="<?php echo $product->product_packaging & 0xFFFF; ?>" size="8" maxlength="32" />
		</div>
		<div class="k2martFormRow">
			<label class="hasTip" title="<?php echo JText::_('COM_VIRTUEMART_PRODUCT_BOX_DESCRIPTION'); ?>"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_BOX')?></label>
			<input type="text" name="product_box" value="<?php echo ($product->product_packaging>>16)&0xFFFF; ?>" size="8" maxlength="32" />
		</div>
	</div>
	<?php if($params->get('advancedTab') && $mainframe->isAdmin()):?>
	<div id="k2martTab6" class="k2martTabsContent">
		<div class="k2martFormRow">
			<label class="k2martAutoWidth"><?php echo JText::_('K2MART_ASSIGN_UNASSIGN_VIRTUEMART_PRODUCT');?></label>
			<input type="text" disabled="disabled" value="<?php echo $product->product_name; ?>" id="k2martAssignField"/>
			<div class="button2-left">
				<div class="blank">
					<a href="<?php echo JRoute::_('index.php?option=com_k2mart&view=virtuemart');?>" class="modal" rel="{handler: 'iframe', size: {x: 900, y: 500}}" ><?php echo JText::_('K2MART_ASSIGN');?></a>
				</div>
			</div>
			<div class="button2-left">
    			<div class="blank">
    				<a href="#" id="k2martUnassignButton"><?php echo JText::_('K2MART_UNASSIGN');?></a>
    			</div>
			</div>
			<div class="k2martClr"></div>
			<input value="" id="k2martAssignFlag" name="k2martAssignFlag" type="hidden"/>
			<input value="" id="k2martUnassignFlag" name="k2martUnassignFlag" type="hidden"/>
			<span class="k2Note"><?php echo JText::_('K2MART_ASSIGN_UNASSIGN_DESCRIPTION');?></span>
		</div>
		<?php if($productID):?>
		<div class="k2martFormRow">
			<label class="k2martAutoWidth"><?php echo JText::_('K2MART_EDIT_PRODUCT_IN_VIRTUEMART');?></label>
			<div class="button2-left">
    			<div class="blank">
    				<a href="index.php?option=com_virtuemart&amp;view=product&amp;task=edit&amp;virtuemart_product_id=<?php echo $productID; ?>&amp;product_parent_id=<?php echo $product->product_parent_id; ?>" rel="{handler: 'iframe', size: {x: 900, y: 500}, onClose: function() {window.parent.location.reload()}}" class="modal"><?php echo JText::_('K2MART_EDIT_PRODUCT_IN_VIRTUEMART');?></a>
    			</div>
			</div>
			<div class="k2martClr"></div>
			<span class="k2Note"><?php echo JText::_('K2MART_USE_THIS_TO_EDIT_THE_PRODUCT_DIRECTLY_IN_VIRTUEMART');?></span>
		</div>
		<?php endif;?>
		<div class="k2martFormRow">
			<label for="k2martDeleteFlag"><?php echo JText::_('K2MART_REMOVE_PRODUCT');?></label> 
			<input value="1" id="k2martDeleteFlag" name="k2martDeleteFlag" type="checkbox" />
			<span class="k2Note"><?php echo JText::_('K2MART_REMOVE_PRODUCT_DESCRIPTION');?></span>
		</div>
	</div>
	<?php endif; ?>
	<input value="<?php echo $productID; ?>" name="virtuemart_product_id" id="k2martProductID" type="hidden"/>
	<?php if($product->image->virtuemart_media_id): ?>
	<input type="hidden" name="file_is_product_image" value="1" />
	<input type="hidden" name="media_published" value="1" />
	<input type="hidden" name="media_rolesfile_is_displayable" value="file_is_displayable" />
	<input type="hidden" name="media_action" value="0" />
	<input type="hidden" name="active_media_id" value="<?php echo $product->image->virtuemart_media_id; ?>" />
	<input type="hidden" name="virtuemart_media_id[]" value="<?php echo $product->image->virtuemart_media_id; ?>">
	<input class="ordering" type="hidden" value="0" name="mediaordering[<?php echo $product->image->virtuemart_media_id; ?>]">
	<?php endif; ?>
	
</div>