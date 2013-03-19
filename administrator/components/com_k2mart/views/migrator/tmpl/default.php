<?php
/**
 * @version		$Id: default.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
	//<![CDATA[
	var $k2mart = jQuery.noConflict();
	if (typeof(Joomla) === 'undefined') {
		var Joomla = {};
		Joomla.submitbutton = function(pressbutton){
			submitform(pressbutton);
		}
		function submitbutton(pressbutton) {
			Joomla.submitbutton(pressbutton);
		}
	}
	Joomla.submitbutton = function(pressbutton){
		if($k2mart('#categoryName').val()=="" && $k2mart('#catid').val()=='0'){
			alert('<?php echo JText::_('K2MART_PLEASE_TYPE_A_CATEGORY_NAME', true);?>');
			return false;
		}
		var answer = confirm('<?php echo JText::_('K2MART_IMPORT_WARNING', true); ?>');
		if (answer){
			window.open('', 'k2martMigrator', 'width=640,height=480,0,0');
			submitform( pressbutton );
		}
	}
	$k2mart(document).ready(function() {
		$k2mart('#catid').change(function(event){
			if($k2mart(this).val()=='0'){
				$k2mart('#categoryName').fadeIn('normal');
			}
			else {
				$k2mart('#categoryName').fadeOut('normal');
			}
		});
	});
	//]]>
</script>
<div class="k2martMigrator">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="k2martImportForm" target="k2martMigrator">
		<fieldset>
			<legend><?php echo JText::_('K2MART_IMPORT_SETTINGS');?></legend>
			<div class="k2martMigratorField">
				<label for="catid"><?php echo JText::_('K2MART_CATALOG_ROOT_CATEGORY');?></label>
				<?php echo $this->lists['category']; ?> <input type="text" name="categoryName" id="categoryName" size="50" />
				<span class="k2martNote"><?php echo JText::_('K2MART_CATALOG_ROOT_CATEGORY_DESCRIPTION')?></span>
			</div>
			<?php if(isset($this->lists['language'])): ?>
			<div class="k2martMigratorField">
				<label for="vmlang"><?php echo JText::_('K2MART_LANGUAGE');?></label>
				<?php echo $this->lists['language']; ?>
				<span class="k2martNote"><?php echo JText::_('K2MART_LANGUAGE_DESCRIPTION');?></span>
			</div>
			<?php endif; ?>
			<div class="k2martMigratorField">
				<label for="ignoreUnpublished"><?php echo JText::_('K2MART_IGNORE_UNPUBLISHED_VIRTUEMART_PRODUCTS_AND_CATEGORIES');?></label>
				<input type="checkbox" value="1" name="ignoreUnpublished" id="ignoreUnpublished" />
				<span class="k2martNote"><?php echo JText::_('K2MART_IGNORE_UNPUBLISHED_VIRTUEMART_PRODUCTS_AND_CATEGORIES_DESCRIPTION');?></span>
			</div>
			<div class="k2martMigratorField">
				<label for="proccessImages"><?php echo JText::_('K2MART_PROCESS_PRODUCT_IMAGES');?></label>
				<input type="checkbox" value="1" checked="checked" name="proccessImages" id="proccessImages" />
				<span class="k2martNote"><?php echo JText::_('K2MART_PROCESS_PRODUCT_IMAGES_DESCRIPTION');?></span>
			</div>
			<input type="hidden" name="option" value="com_k2mart" /> 
			<input type="hidden" name="view" value="migrator" /> 
			<input type="hidden" name="task" value="popup" />
			<input type="hidden" name="tmpl" value="component" />
			<?php echo JHTML::_('form.token'); ?>
		</fieldset>
	</form>
</div>
<div id="k2martAdminFooter">
	<?php echo JText::_('K2MART_COPYRIGHTS'); ?>
</div>
