/**
 * @version		$Id: site.k2mart.js 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

var $K2 = jQuery.noConflict();

// K2mart XHR function
function k2martXHR(url) {
	$K2('#k2Container').addClass('k2martLoading').animate({
		opacity : 0.3
	});
	$K2.get(url, function(data) {
		$K2('#k2Container').html(data).removeClass('k2martLoading').animate({
			opacity : 1
		});
		$K2('#k2Container').trigger('k2martReady');
	});
}

// Pagination fix for frontpage
function fixPagination() {
	if (K2martFrontPage) {
		var filtersData = $K2('#k2martFilters form').serialize();
		$K2('.k2Pagination a').each(function(index, element) {
			var currentURL = $K2(element).attr('href');
			var newURL = currentURL + '&' + $K2('#k2martFilters form').serialize();
			$K2(element).attr('href', newURL);
		});
	}
}


$K2(document).ready(function() {

	// Fix pagination when page is loaded
	fixPagination();

	// Fix add to cart modal
	if ( typeof (jQuery.facebox == 'undefined')) {
		jQuery.facebox = $K2.facebox;
	}

	// Ask a question button
	$K2('#k2Container a.k2martModal').live('click', function(event) {
		event.preventDefault();
		$K2.facebox({
			iframe : $K2(this).attr('href'),
			rev : 'iframe|550|550'
		});
	});

	// K2mart XHR callback event.
	$K2('#k2Container').bind('k2martReady', function() {

		// Fix pagination after each K2mart AJAX request
		fixPagination();

		// Virtuemart functions callback
		Virtuemart.product($K2('#k2Container .product'));
		$K2('#k2Container form.js-recalculate').each(function() {
			if ($K2(this).find('.product-fields').length) {
				var id = $K2(this).find('input[name="virtuemart_product_id[]"]').val();
				Virtuemart.setproducttype($K2(this), id);
			}
		});

		// K2 functions callback
		$K2('#k2Container .itemRatingForm a').click(function(event) {
			event.preventDefault();
			var itemID = $K2(this).attr('rel');
			var log = $K2('#itemRatingLog' + itemID).empty().addClass('formLogLoading');
			var rating = $K2(this).html();
			$K2.ajax({
				url : K2SitePath + "index.php?option=com_k2&view=item&task=vote&format=raw&user_rating=" + rating + "&itemID=" + itemID,
				type : 'get',
				success : function(response) {
					log.removeClass('formLogLoading');
					log.html(response);
					$K2.ajax({
						url : K2SitePath + "index.php?option=com_k2&view=item&task=getVotesPercentage&format=raw&itemID=" + itemID,
						type : 'get',
						success : function(percentage) {
							$K2('#itemCurrentRating' + itemID).css('width', percentage + "%");
							setTimeout(function() {
								$K2.ajax({
									url : K2SitePath + "index.php?option=com_k2&view=item&task=getVotesNum&format=raw&itemID=" + itemID,
									type : 'get',
									success : function(response) {
										log.html(response);
									}
								});
							}, 2000);
						}
					});
				}
			});
		});
	});

	// K2mart filters
	$K2('.k2martFilter').chosen({
		no_results_text : K2martNoResultsText,
		width : '100%'
	}).change(function() {
		var url = K2martURL + $K2('#k2martFilters form').serialize();
		if (K2martAJAX) {
			var url = url.replace('.html', '.raw');
			k2martXHR(url + '&format=raw');
		} else {
			window.location.href = url;
		}
	});
	$K2('#k2martCategories_chzn, #k2martManufacturers_chzn').css('width', '100%');
	$K2('#k2martFilters').slideToggle(0);
	$K2('#k2martFiltersToggler').click(function(event) {
		event.preventDefault
		$K2('#k2martFilters').slideToggle('fast', function() {
			$K2('#k2martFiltersToggler').toggleClass('k2martFiltersTogglerIconExpanded');
		});
	});

	// K2mart pagination
	if (K2martAJAX) {
		$K2('#k2Container .k2Pagination a').live('click', function(event) {
			event.preventDefault();
			var url = $K2(this).attr('href') + '&format=raw';
			k2martXHR(url);
		});
	}

	// K2mart tabs (item layout only)
	$K2('#k2martTabs ul li a:first').addClass('active');
	$K2('#k2martTabs .k2martTabContent').css('display', 'none');
	$K2('#k2martTabs .k2martTabContent:first').css('display', 'block');
	$K2('#k2martTabs ul li a').click(function(event) {
		event.preventDefault();
		$K2('#k2martTabs ul li a').removeClass('active');
		$K2(this).addClass('active');
		$K2('#k2martTabs .k2martTabContent').css('display', 'none');
		var index = $K2('#k2martTabs ul li a').index($K2(this));
		var tab = $K2('#k2martTabs .k2martTabContent').get(index);
		$K2(tab).css('display', 'block');
	});

	// K2mart extended price info toggler
	$K2('.k2martExtendedPriceToggler').live('click', function(event) {
		event.preventDefault();
		var position = $K2(this).position();
		var togglerHeight = $K2(this).outerHeight(true);
		var tip = $K2(this).next('.k2martExtendedPrice');
		var tipWidth = tip.outerWidth(true);
		$K2(this).next('.k2martExtendedPrice').css('top', position.top + togglerHeight).css('left', position.left).toggleClass('k2martHidden');
	});

	// K2mart product details toggler (listing views)
	$K2('a.k2martProductDetailsToggler').live('click', function(event) {
		event.preventDefault();
		var element = $K2(this);
		$K2(this).parents('.k2martListProductHeaderContainer').find('.k2martProductDetails').slideToggle('normal', function() {
			$K2(element).toggleClass('open');
		});
	});

	// K2mart product children select
	/*$K2('.k2martChildren').unbind('change');
	 $K2('.k2martChildren').live('change', function(){
	 var currentID = $K2(this).closest('input[name="virtuemart_product_id[]"]').val();
	 var newID = $K2(this).val();
	 $K2('input[name="virtuemart_product_id[]"]').val(newID);
	 $K2('#productPrice'+currentID).attr('id', newID);
	 $K2.setproducttype($K2(this).closest('form.product'), newID);
	 });*/
});
