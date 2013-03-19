
<?php defined('_JEXEC') or die('Restricted access'); 
if(version_compare( JVERSION, '1.6.0', 'ge' )) {
	JHTML::_('behavior.framework'); /* to load mootools */
}
?>
<form action="index.php" method="post" name="adminForm" class="k2import">




<?php
foreach ($this->k2fields as $k2field)
{

	$additional_num='';
	echo '<div class="k2importfield" id="k2importfield_'.$k2field['extra']. $k2field['id'].'">
		<div class="k2importtitle">'. $k2field['title'].'</div>';
		
		

	 if ($k2field['id']=='attachment' || 
	 	 $k2field['id']=='attachment_title' || 
	 	 $k2field['id']=='attachment_title_attribute')
	 	 {
	 		echo '<div class="k2attachments"><div class="k2importselect"><div class="k2optionscount">[0]</div>';
	 		$additional_num=0;
	 	 }
	 else
	 	echo '<div class="k2importselect">';

	echo '<select name="k2import'.$k2field['extra'].'fields['. $k2field['id'].$additional_num.']" class="k2importfield" size="0">';
	echo '<option  value="" label="empty">take standard value or leave empty</option>';

	$already_selected=false;
	for ($csv_header_num=0;$csv_header_num<count($this->csv_headers);$csv_header_num++)
	{
		echo '<option  value="'.$csv_header_num.'" label="'.$this->csv_headers[$csv_header_num] . '"';
		
		$header_to_lower=strtolower($this->csv_headers[$csv_header_num]);
		if ((strtolower($k2field['title'])==$header_to_lower ||
			($k2field['id']=='attachment' && preg_match("/attachment\s?\d+$/", $header_to_lower)) ||
			($k2field['id']=='attachment_title' && preg_match("/attachment\s?title\s?\d+$/", $header_to_lower)) ||
			($k2field['id']=='attachment_title_attribute' && preg_match("/attachment\s?title\s?attribute\s?\d+$/", $header_to_lower))
			)
			&& $already_selected==false
			)
		{
			echo " selected ";
			$already_selected=true;
		}
		echo '>'.$this->csv_headers[$csv_header_num].'</option>';
	}
	
	echo '</select>';

	if (isset($k2field['tooltip'])) 
	echo JHtml::tooltip($k2field['tooltip']);
	
	echo '</div>
	</div>
	';
	
	 if ($k2field['id']=='attachment' || 
	 	 $k2field['id']=='attachment_title' || 
	 	 $k2field['id']=='attachment_title_attribute')
	 	 {
	 		echo '</div>';
	 	 }	
}
?>
<script type="text/javascript">
var count_attachments=1;

</script>

<input type="hidden" name="option" value="com_k2import" />
<input type="hidden" name="task" value="import_ajax_wrapper" />
<input type="hidden" name="file" value="<?php echo $this->file;?>" />
<input type="hidden" name="modus" value="<?php echo $this->modus;?>" />
<input type="hidden" name="csv_count_rows_to_do" value="<?php echo $this->csv_count_rows_to_do;?>" />
<input type="hidden" name="max_execution_time" value="<?php echo $this->max_execution_time;?>" />
<input type="hidden" name="start_row_num" value="<?php echo $this->start_row_num;?>" />

<input type="hidden" name="controller" value="k2import" />

<input type="hidden" name="k2category" value="<?php echo $this->k2category; ?>" />
<input type="hidden" name="in_charset" value="<?php echo $this->in_charset; ?>" />
<input type="hidden" name="out_charset" value="<?php echo $this->out_charset; ?>" />
<input type="hidden" name="overwrite" value="<?php echo $this->overwrite; ?>" />
<input type="hidden" name="ignore_level" value="<?php echo $this->ignore_level; ?>" />
<input type="hidden" name="k2extrafieldgroup" value="<?php echo $this->k2extrafieldgroup; ?>" />
<input type="hidden" name="should_we_import_the_id" value="<?php echo $this->should_we_import_the_id; ?>" />


<button type="submit"><?php echo  JText::_( 'continue' ); ?></button>

</form>

<?php
/*
		echo "<pre>";
print_r($this->k2fields);
echo "</pre>";	
*/
?>