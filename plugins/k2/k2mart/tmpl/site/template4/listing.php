<?php
/**
 * @version		$Id: listing.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

defined('_JEXEC') or die('Restricted access');
?>
<!-- K2mart STARTS HERE -->
<div class="k2mart k2martListingLayout">
	<!-- Add to cart block -->
	<?php if($this->params->get('listingProductAddToCart') && !VmConfig::get('use_as_catalog',0)): ?>
	<form method="post" class="product js-recalculate" action="<?php echo JRoute::_('index.php')?>" id="addtocartproduct<?php echo $product->id ?>">
		<div class="k2martAddToCart">
			<!-- @TODO Check if this block is needed -->
			<!-- Product custom fields children -->
			<?php if (!empty($product->customsChilds)): ?>
			<div class="product-fields">
				<?php foreach ($product->customsChilds as $field): ?>
				<div class="product-field product-field-type-<?php echo $field->field->field_type ?>">
					<span class="product-fields-title" ><b><?php echo JText::_($field->field->custom_title); ?></b></span>
					<span class="product-field-desc"><?php echo JText::_($field->field->custom_value); ?></span>
					<span class="product-field-display"><?php echo $field->display; ?></span>
				</div>
				<?php endforeach;?>
			</div>
			<?php endif;?>
			
			<div class="k2martListProductHeaderContainer">
				<table class="k2martListProductHeader">
					<tr>
						<td>
							<?php if($this->params->get('listingProductSku') && !empty($product->sku)): ?>
							<span class="k2martProductSKU">
								<?php echo JText::_('K2MART_SKU');?> <?php echo $product->sku;?>
							</span>
							<?php endif;?>
							<a class="k2martProductDetailsToggler"><?php echo JText::_('K2MART_PRODUCT_DETAILS'); ?></a>
						</td>
						<?php if($this->params->get('listingProductRating') && $product->showRating): ?>
						<td class="k2martProductRatingCell">
							<span class="k2martProductRating">
								<?php echo JText::_('K2MART_RATING');?> <?php echo $product->rating;?>
							</span>
						</td>
						<?php endif;?>
					</tr>
				</table>
				<div class="k2martProductDetails">
					<table class="k2martFields">
						<?php if($this->params->get('listingProductName') && !empty($product->name)): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_NAME');?></td>
							<td class="k2martFieldValue"><?php echo $product->name;?></td>
						</tr>
						<?php endif;?>
		
						<?php if($this->params->get('listingProductUrl') && !empty($product->url)): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_URL');?></td>
							<td class="k2martFieldValue"><a href="<?php echo $product->url;?>" target="_blank" rel="nofollow"><?php echo $product->url;?></a></td>
						</tr>
						<?php endif;?>
						<?php if($this->params->get('listingProductVendor')): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_VENDOR');?></td>
							<td class="k2martFieldValue"><?php echo $product->vendorName;?></td>
						</tr>
						<?php endif;?>
						<?php if($this->params->get('listingProductManufacturer') && !empty($product->manufacturerLink)): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_MANUFACTURER');?></td>
							<td class="k2martFieldValue"><a class="k2martModal" href="<?php echo $product->manufacturerLink;?>"><?php echo $product->manufacturerName;?></a></td>
						</tr>
						<?php endif;?>
				
						<?php if($this->params->get('listingProductDimensions')):?>
						<?php if($product->length > 0): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_LENGTH');?></td>
							<td class="k2martFieldValue"><?php echo $product->length;?> <?php echo $product->dimensionUnit;?></td>
						</tr>
						<?php endif;?>
						<?php if($product->width > 0): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_WIDTH');?></td>
							<td class="k2martFieldValue"><?php echo $product->width;?> <?php echo $product->dimensionUnit;?></td>
						</tr>
						<?php endif;?>
						<?php if($product->height > 0): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_HEIGHT');?></td>
							<td class="k2martFieldValue"><?php echo $product->height;?> <?php echo $product->dimensionUnit;?></td>
						</tr>
						<?php endif;?>
						<?php endif;?>
						<?php if($this->params->get('listingProductWeight') && $product->weight > 0): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::_('K2MART_WEIGHT');?></td>
							<td class="k2martFieldValue"><?php echo $product->weight;?> <?php echo $product->weightUnit;?></td>
						</tr>
						<?php endif;?>
						<?php if($this->params->get('listingProductPackaging') && $product->packaging): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::sprintf('K2MART_NUMBER_IN_PACKAGING', ($product->unit) ? $product->unit : JText::_('K2MART_PRICE_PER_UNIT'));?></td>
							<td class="k2martFieldValue"><?php echo $product->packaging;?></td>
						</tr>
						<?php endif;?>
						<?php if($this->params->get('listingProductBox') && $product->box): ?>
						<tr>
							<td class="k2martFieldLabel"><?php echo JText::sprintf('K2MART_NUMBER_IN_BOX', ($product->unit) ? $product->unit : JText::_('K2MART_PRICE_PER_UNIT'));?></td>
							<td class="k2martFieldValue"><?php echo $product->box;?></td>
						</tr>
						<?php endif;?>
					</table>
					<!-- Product custom fields -->
					<?php if ($this->params->get('listingProductFields') && !empty($product->customFields)): ?>
					<h4 class="k2martHeading"><?php echo JText::_('K2MART_MORE_DETAILS'); ?></h4>
					<table class="k2martCustomFields product-fields">
						<?php foreach ($product->customFields as $field): ?>
						<tr class="k2martField k2martFieldType<?php echo $field->field_type ?>">
							<td class="k2martFieldLabel">
								<?php if ($field->custom_title): ?>
								<?php echo JText::_($field->custom_title);?>
								<?php if($field->custom_tip): ?>
								<span class="k2martFieldLabelTip"><?php echo JText::_($field->custom_tip);?></span>
								<?php endif;?>
								<?php endif;?>
							</td>
							<td class="k2martFieldValue">
								<?php echo $field->display; ?>
								<span class="k2martFieldValueDescription"><?php echo JText::_($field->custom_field_desc); ?></span>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
					<?php endif;?>
				</div>
			</div>
		

			<!-- Product custom fields with cart attributes -->
			<?php if($this->params->get('listingCartProductFields') && !empty($product->customFieldsCart)): ?>
			<table class="k2martCustomCartFields product-fields">
				<tr>
				<?php foreach ($product->customFieldsCart as $field): ?>
				<td class="k2martField k2martFieldType<?php echo $field->field_type ?>">
					<span class="k2martFieldLabel">
						<?php echo  JText::_($field->custom_title); ?>
						<?php if ($field->custom_tip): ?>
						<span class="k2martFieldLabelTip"><?php echo JText::_($field->custom_tip);?></span>
						<?php endif; ?>
					</span>
					<span class="k2martFieldValue">
						<?php echo $field->display; ?>
						<span class="k2martFieldValueDescription"><?php echo $field->custom_field_desc; ?></span>
					</span>
				</td>
				<?php endforeach;?>
				</tr>
			</table>
			<?php endif;?>
			
			<table class="k2martAddToCartFields">
				<tr>
					<?php if($this->params->get('listingProductAvailability') && $product->availability): ?>
					<td class="k2martAvailability">
						<span class="k2martFieldLabel k2martAvailabilityLabel"><?php echo JText::_('K2MART_AVAILABILITY');?>:</span>
						<span class="k2martFieldValue k2martAvailabilityValue"><?php echo $product->availability;?></span>
					</td>
					<?php endif;?>
					
					<?php if($this->params->get('listingProductQuantity')): ?>
					<td class="k2martQuantity">
						<label><?php echo JText::_('K2MART_QUANTITY'); ?></label>
						<span class="k2martQuantityBox">
							<input type="text" size="5" class="quantity-input k2martInput" name="quantity[]" value="<?php echo $product->minimumOrder; ?>" />
						</span>
						<span class="k2martQuantityButtons">
							<input type="button" class="quantity-controls quantity-plus" />
							<input type="button" class="quantity-controls quantity-minus" />
						</span>
					</td>
					<?php endif;?>
				</tr>
				<tr>
					<?php if($this->params->get('listingProductShipping') && $product->shipping): ?>
					<td class="k2martShipping">
						<span class="k2martFieldLabel k2martShippingLabel"><?php echo JText::_('K2MART_USUALLY_SHIPS_IN');?>:</span>
						<span class="k2martFieldValue k2martShippingValue"><?php echo $product->shipping;?></span>
					</td>
					<?php endif;?>
					<?php if($this->params->get('listingProductPrice') && $product->prices && VmConfig::get('show_prices',1)): ?>
					<td class="k2martPriceContainer">
						<div class="product-price" id="productPrice<?php echo $product->id ?>">
							<div class="k2martPrice"><?php echo $product->mainPrice; ?></div>
							<a class="k2martExtendedPriceToggler" title="<?php echo JText::_('K2MART_VIEW_EXTENDED_PRICE_INFO'); ?>"></a>
							<div class="k2martExtendedPrice k2martHidden"><?php echo $product->extendedPrice; ?></div>
						</div>
						<?php if($this->params->get('listingAskQuestionButton')): ?>
						<a class="k2martAskQuestionButton k2martModal" href="<?php echo $product->askQuestionLink; ?>" ><?php echo JText::_('K2MART_ASK_A_QUESTION_ABOUT_THIS_PRODUCT');?></a>
						<?php endif;?>
					</td>
					<?php endif;?>
				</tr>
			</table>

			<input type="hidden" class="pname" value="<?php echo $product->name;?>" />
			<input type="hidden" name="option" value="com_virtuemart" />
			<input type="hidden" name="view" value="cart" />
			<noscript><input type="hidden" name="task" value="add" /></noscript>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->id;?>" />
			<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id;?>" />
			<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->catid;?>" />
			<?php if(!$this->params->get('listingProductQuantity')): ?>
			<input type="hidden" name="quantity[]" value="1" />
			<?php endif; ?>
		</div>
		<div class="k2martAddToCartBar k2martClearFix">
			<span class="k2martAddToCartButton">
				<?php if($product->notifyLink): ?>
				<a class="k2martButton notify-button" href="<?php echo $product->notifyLink; ?>"><?php echo JText::_('K2MART_NOTIFY_ME'); ?></a>
				<?php else: ?>
				<input type="submit" name="<?php echo $product->addToCartButtonName; ?>" class="k2martButton <?php echo $product->addToCartButtonClass; ?>" value="<?php echo $product->addToCartButtonLabel; ?>" title="<?php echo $product->addToCartButtonLabel; ?>" />
				<?php endif; ?>
			</span>
		</div>
	</form>
	<?php endif;?>
</div>
<!-- K2mart ENDS HERE -->