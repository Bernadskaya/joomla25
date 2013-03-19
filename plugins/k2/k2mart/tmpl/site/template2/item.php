<?php
/**
 * @version		$Id: item.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

defined('_JEXEC') or die('Restricted access');
?>
<!-- K2mart STARTS HERE -->
<div class="k2mart k2martItemLayout productdetails-view">
	<!-- Add to cart block -->
	<?php if($this->params->get('itemProductAddToCart') && !VmConfig::get('use_as_catalog',0)): ?>
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
			
			<!-- Product custom fields with cart attributes -->
			<?php if($this->params->get('itemCartProductFields') && !empty($product->customFieldsCart)): ?>
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
					<?php if($this->params->get('itemProductQuantity')): ?>
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
					<?php if($this->params->get('itemProductAvailability') && $product->availability): ?>
					<td class="k2martAvailability">
						<span class="k2martFieldLabel k2martAvailabilityLabel"><?php echo JText::_('K2MART_AVAILABILITY');?>:</span>
						<span class="k2martFieldValue k2martAvailabilityValue"><?php echo $product->availability;?></span>
					</td>
					<?php endif;?>
					<?php if($this->params->get('itemProductShipping') && $product->shipping): ?>
					<td class="k2martShipping">
						<span class="k2martFieldLabel k2martShippingLabel"><?php echo JText::_('K2MART_USUALLY_SHIPS_IN');?>:</span>
						<span class="k2martFieldValue k2martShippingValue"><?php echo $product->shipping;?></span>
					</td>
					<?php endif;?>
				</tr>
			</table>
			<div class="k2martAddToCartBar k2martClearFix">
				<?php if($this->params->get('itemProductPrice') && $product->prices && VmConfig::get('show_prices',1)): ?>
				<div class="k2martPriceContainer">
					<div class="product-price" id="productPrice<?php echo $product->id ?>">
						<div class="k2martPrice"><?php echo $product->mainPrice; ?></div>
						<a class="k2martExtendedPriceToggler" title="<?php echo JText::_('K2MART_VIEW_EXTENDED_PRICE_INFO'); ?>"></a>
						<div class="k2martExtendedPrice k2martHidden"><?php echo $product->extendedPrice; ?></div>
					</div>
				</div>
				<?php endif;?>
				<span class="k2martAddToCartButton">
					<?php if($product->notifyLink): ?>
					<a class="k2martButton notify-button" href="<?php echo $product->notifyLink; ?>"><?php echo JText::_('K2MART_NOTIFY_ME'); ?></a>
					<?php else: ?>
					<input type="submit" name="<?php echo $product->addToCartButtonName; ?>" class="k2martButton <?php echo $product->addToCartButtonClass; ?>" value="<?php echo $product->addToCartButtonLabel; ?>" title="<?php echo $product->addToCartButtonLabel; ?>" />
					<?php endif; ?>
				</span>
			</div>
			<input type="hidden" class="pname" value="<?php echo $product->name;?>" />
			<input type="hidden" name="option" value="com_virtuemart" />
			<input type="hidden" name="view" value="cart" />
			<noscript><input type="hidden" name="task" value="add" /></noscript>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->id;?>" />
			<input type="hidden" name="virtuemart_manufacturer_id" value="<?php echo $product->virtuemart_manufacturer_id;?>" />
			<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->catid;?>" />
			<?php if(!$this->params->get('itemProductQuantity')): ?>
			<input type="hidden" name="quantity[]" value="1" />
			<?php endif; ?>
		</div>
	</form>
	
	<?php endif;?>

	
	<div id="k2martTabs" class="k2martClearFix">
		<?php if($this->params->get('itemAskQuestionButton')): ?>
		<a class="k2martAskQuestionButton k2martModal" href="<?php echo $product->askQuestionLink; ?>" ><?php echo JText::_('K2MART_ASK_A_QUESTION_ABOUT_THIS_PRODUCT');?></a>
		<?php endif;?>
		<ul class="k2martTabsNavigation k2martClearFix">
			<li><a><?php echo JText::_('K2MART_PRODUCT_DETAILS'); ?></a></li>
			<?php if($this->params->get('itemProductReviews')): ?>
			<li><a><?php echo JText::_('K2MART_PRODUCT_REVIEWS'); ?></a></li>
			<?php endif; ?>
		</ul>
		<div class="k2martTabContent">
			<table class="k2martFields">
				<?php if($this->params->get('itemProductName') && !empty($product->name)): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_NAME');?></td>
					<td class="k2martFieldValue"><?php echo $product->name;?></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductSku') && !empty($product->sku)): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_SKU');?></td>
					<td class="k2martFieldValue"><?php echo $product->sku;?></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductUrl') && !empty($product->url)): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_URL');?></td>
					<td class="k2martFieldValue"><a href="<?php echo $product->url;?>" target="_blank" rel="nofollow"><?php echo $product->url;?></a></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductVendor')): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_VENDOR');?></td>
					<td class="k2martFieldValue"><?php echo $product->vendorName;?></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductManufacturer') && !empty($product->manufacturerLink)): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_MANUFACTURER');?></td>
					<td class="k2martFieldValue"><a class="k2martModal" href="<?php echo $product->manufacturerLink;?>"><?php echo $product->manufacturerName;?></a></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductRating') && $product->showRating): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_RATING');?></td>
					<td class="k2martFieldValue"><?php echo $product->rating;?></td>
				</tr>
				<?php endif;?>
		
				<?php if($this->params->get('itemProductDimensions')):?>
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
				<?php if($this->params->get('itemProductWeight') && $product->weight > 0): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::_('K2MART_WEIGHT');?></td>
					<td class="k2martFieldValue"><?php echo $product->weight;?> <?php echo $product->weightUnit;?></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductPackaging') && $product->packaging): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::sprintf('K2MART_NUMBER_IN_PACKAGING', ($product->unit) ? $product->unit : JText::_('K2MART_PRICE_PER_UNIT'));?></td>
					<td class="k2martFieldValue"><?php echo $product->packaging;?></td>
				</tr>
				<?php endif;?>
				<?php if($this->params->get('itemProductBox') && $product->box): ?>
				<tr>
					<td class="k2martFieldLabel"><?php echo JText::sprintf('K2MART_NUMBER_IN_BOX', ($product->unit) ? $product->unit : JText::_('K2MART_PRICE_PER_UNIT'));?></td>
					<td class="k2martFieldValue"><?php echo $product->box;?></td>
				</tr>
				<?php endif;?>
			</table>
			<!-- Product custom fields -->
			<?php if ($this->params->get('itemProductFields') && !empty($product->customFields)): ?>
			<h4 class="k2martHeading"><?php echo JText::_('K2MART_MORE_DETAILS'); ?></h4>
			<table class="k2martFields product-fields">
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
		<?php if($this->params->get('itemProductReviews')): ?>
		<div class="k2martTabContent">
			<!-- Product reviews -->
			<div id="k2martReviews">
				<?php if($product->allowRating || $product->showReview): ?>
					<form method="post" action="<?php echo $product->reviewFormAction; ?>" name="reviewForm" id="reviewform">
				<?php endif;?>
				<?php if($product->showReview): ?>
						<div id="k2martReviewsList">
						<?php if($product->reviews): ?>
							<?php foreach($product->reviews as $review): ?>
							<div class="k2martReview <?php echo $review->class;?>">
								<div class="k2martReviewInfo k2martClearFix">
									<span class="k2martReviewUser"><?php echo $review->customer; ?></span>
									<span class="k2martReviewRating">
										<?php echo JText::_('K2MART_RATING'); ?> <span class="k2martRatingValue"><?php echo $review->review_rates; ?></span>
										<span class="k2martRatingStars">
											<span class="k2martTotalRating"></span>
											<span class="k2martCurrentRating" style="width:<?php echo ($review->review_rates > 0)? 100*($review->review_rates/5):0; ?>%;"></span>
										</span>
									</span>
									<span class="k2martReviewDate"><?php echo JHTML::date($review->created_on, JText::_('DATE_FORMAT_LC'));?></span>
								</div>
								<blockquote><?php echo $review->comment;?></blockquote>
							</div>
							<?php endforeach;?>
						<?php else:?>
							<span class="k2martNote"><?php echo JText::_('K2MART_NO_REVIEWS'); ?></span>
						<?php endif;?>
						</div>
						
						<?php if($product->allowReview): ?>
						<div class="k2martReviewFormContainer">
							<?php if($product->showRating && $product->allowRating && $reviewEditable): ?>
							<h4 class="k2martHeading"><?php echo JText::_('K2MART_SUBMIT_REVIEW'); ?></h4>
							<div class="k2martReviewFormHeading">
								<h5 class="k2martReviewFormHeadingTitle"><?php echo JText::_('K2MART_RATE_THE_PRODUCT'); ?></h5>
								<span class="k2martReviewFormHeadingHint"><?php echo JText::_('K2MART_PLEASE_SELECT_A_RATING'); ?></span>
							</div>
							<table class="k2martReviewFormRating">
								<tr>
								<?php for ($num=0 ; $num<=$maxRating;  $num++ ): ?>
								<td>
									<input<?php echo ($num == $maxRating)?' checked="checked"':''; ?> id="k2martVote<?php echo $num ?>" type="radio" value="<?php echo $num ?>" name="vote">
									<label for="k2martVote<?php echo $num ?>">
										<span class="k2martRatingStars">
											<span class="k2martTotalRating"></span>
											<span class="k2martCurrentRating" style="width:<?php echo 100*($num/5); ?>%;"></span>
										</span>
										<?php echo $stars[$num];?>
									</label>
								</td>
								<?php endfor;?>
								</tr>
							</table>
							<?php endif;?>
							<?php if($reviewEditable): ?>
							<div class="k2martReviewFormHeading k2martClearFix">
								<span class="k2martReviewFormCounter"><?php echo JText::_('COM_VIRTUEMART_REVIEW_COUNT'); ?><input class="k2martInput" type="text" value="0" size="4" name="counter" maxlength="4" readonly="readonly" /></span>
								<h5 class="k2martReviewFormHeadingTitle"><?php echo JText::_('K2MART_PLEASE_WRITE_A_SHORT_REVIEW'); ?></h5>
								<span class="k2martReviewFormHeadingHint"><?php echo JText::sprintf('COM_VIRTUEMART_REVIEW_COMMENT', VmConfig::get('reviews_minimum_comment_length', 100), VmConfig::get('reviews_maximum_comment_length', 2000));?></span>
							</div>
							<div class="k2martReviewComment k2martClearFix">
								<textarea id="comment" class="k2martInput" onblur="refresh_counter();" onfocus="refresh_counter();" onkeyup="refresh_counter();" name="comment" rows="5" cols="60"><?php if (!empty($product->userReview->comment)) {echo $product->userReview->comment;} ?></textarea>
								<input id="k2martReviewSubmitButton" class="k2martButton" type="submit" onclick="return( check_reviewform());" name="submit_review" title="<?php echo JText::_('COM_VIRTUEMART_REVIEW_SUBMIT'); ?>" value="<?php echo JText::_('COM_VIRTUEMART_REVIEW_SUBMIT')  ?>" />
							</div>
    						<?php else:?>
    						<span class="k2martNote"><strong><?php echo JText::_('COM_VIRTUEMART_DEAR').$user->name;?>,</strong> <?php echo JText::_('COM_VIRTUEMART_REVIEW_ALREADYDONE');?></span>
    						<?php endif;?>
    				    </div>
						<?php endif;?>
						<?php endif;?>
		
				<?php if($product->allowRating || $product->showReview): ?>
						<input type="hidden" name="virtuemart_product_id" value="<?php echo $product->virtuemart_product_id;?>" />
						<input type="hidden" name="option" value="com_virtuemart" />
						<input type="hidden" name="virtuemart_category_id" value="<?php echo $product->virtuemart_category_id;?>" />
						<input type="hidden" name="virtuemart_rating_review_id" value="0" />
						<input type="hidden" name="task" value="review" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid');?>" />
					</form>
				<?php endif;?>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
<!-- K2mart ENDS HERE -->