/**
 * @version 	$Id: admin.k2mart.js 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package 	K2mart
 * @author 		JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license 	http://www.joomlaworks.net/license
 */

var $K2mart = jQuery.noConflict();

function k2martAssignProduct(id, title) {
	$K2('#k2martAssignField').val(title);
	$K2('#k2martProductID').val(id);
	$K2('#k2martAssignFlag').val('1');
	$K2('#k2martUnassignFlag').val('0');
	if ( typeof (window.parent.SqueezeBox.close == 'function')) {
		window.SqueezeBox.close();
	} else {
		$K2('#sbox-window').close();
	}
}

function k2martUnassignProduct() {
	$K2('#k2martAssignField').val('');
	$K2('#k2martProductID').val('');
	$K2('#k2martAssignFlag').val('0');
	$K2('#k2martUnassignFlag').val('1');
}


$K2(document).ready(function() {
	$K2('#k2martForm').tabs();
	$K2('#k2martUnassignButton').click(function(event) {
		event.preventDefault();
		k2martUnassignProduct();
	});
	$K2mart('.vmForm select').chosen();
	$K2('#image').change(function() {
		var imageValue = $K2(this).val();
		if (imageValue) {
			if ($K2('#k2martAvailabilityImage img').length == 0) {
				$K2('#k2martAvailabilityImage').append('<img src="' + k2martVmImagePath + imageValue + '" alt="" />');
			} else {
				$K2('#k2martAvailabilityImage img').attr('src', k2martVmImagePath + imageValue);
			}
		} else {
			$K2('#k2martAvailabilityImage img').remove();
		}
		$K2('#product_availability').val(imageValue);
	});
	nextCustom = k2martVmCustomFieldsNum;
	$K2('#custom_field').sortable({
		update : function(event, ui) {
			$K2(this).find('.ordering').each(function(index, element) {
				$K2(element).val(index);
			});
		}
	});
	$K2('#customlist_chzn li').click(function() {
		selected = $K2('select#customlist').find('option:selected').val();
		$K2.getJSON('index.php?option=com_virtuemart&view=product&task=getData&format=json&type=fields&id=' + selected + '&row=' + nextCustom + '&virtuemart_product_id=' + $K2('#k2martProductID').val(), function(data) {
			$K2.each(data.value, function(index, value) {
				$K2('#custom_' + data.table).append(value);
			});
		});
		nextCustom++;
		$K2('select#customlist').val('');
	});
	$K2('.vmForm').delegate('span.vmicon-16-remove', 'click', function() {
		$K2(this).closest('.removable').fadeOut('500', function() {
			$K2(this).remove()
		});
	});
});
