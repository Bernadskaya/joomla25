<?php
/**
 * @version		$Id: import.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
	//<![CDATA[
	var $k2mart = jQuery.noConflict();
	function k2martImport(){
		$k2mart.ajax({
			url : $k2mart('#k2martImportForm').attr('action'),
			type: 'POST',
			data: $k2mart('#k2martImportForm').serialize(),
			dataType: 'JSON',
			success : function(response) {
				$k2mart('#k2martStatus').html(response.message);
				if(response.status){
					window.close();
				} else {
					$k2mart('input[name="id"]').val(response.id);
					$k2mart('input[name="type"]').val(response.task);
					k2martImport();
				}
			}
		});
	}
	$k2mart(document).ready(function() {
		k2martImport();
	});
	//]]>
</script>
<div id="k2martMigrator">
	<h3><?php echo JText::_('K2MART_IMPORT'); ?></h3>
	<div id="k2martStatus"></div>
	<span class="k2martNote"><?php echo JText::_('K2MART_THIS_MAY_TAKE_SEVERAL_MINUTES_PLEASE_DO_NOT_CLOSE_THIS_WINDOW_IT_WILL_CLOSE_AUTOMATICALLY_WHEN_THE_JOB_IS_DONE')?></span>
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="k2martImportForm">
		<input type="hidden" name="option" value="com_k2mart" /> 
		<input type="hidden" name="view" value="migrator" /> 
		<input type="hidden" name="task" value="import" />
		<input type="hidden" name="catid" value="<?php echo JRequest::getInt('catid'); ?>" />
		<input type="hidden" name="categoryName" value="<?php echo JRequest::getString('categoryName'); ?>" />
		<input type="hidden" name="vmlang" value="<?php echo JRequest::getVar('vmlang', '*'); ?>" />
		<input type="hidden" name="ignoreUnpublished" value="<?php echo JRequest::getBool('ignoreUnpublished'); ?>" />
		<input type="hidden" name="proccessImages" value="<?php echo JRequest::getBool('proccessImages'); ?>" />
		<input type="hidden" name="type" value="importCategories" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="format" value="json" />
		<input type="hidden" name="tmpl" value="component" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
<div id="k2martAdminFooter">
	<?php echo JText::_('K2MART_COPYRIGHTS'); ?>
</div>