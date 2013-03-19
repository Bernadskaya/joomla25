<?php defined('_JEXEC') or die('Restricted access'); 
if(version_compare( JVERSION, '1.6.0', 'ge' )) {
	JHTML::_('behavior.framework'); /* to load mootools */
}
?>

<form action="index.php" method="post" name="adminForm">

<table>
<?php

if (is_array($this->file) && $this->modus=='archive')
{
	?>
	<tr>
		<td><?php echo  JText::_( 'File with the Data to import' ); ?></td>
		<td><select name="file" size="0">
			
			<?php
			foreach ($this->file as $file)
			{


				echo '<option  value="'.$file.'" >'.$file.'</option>';
				//echo $k2category->id.' '.$k2category->name;


			}
			?>
		
		</select>
			<input type="hidden" name="modus" value="archive" />
		
		</td>
	</tr>
	<?php
}
else
{
	?>
	<input type="hidden" name="file" value="<?php echo $this->file; ?>" />
	<?php 
}
?>
<tr>
<td><?php echo  JText::_( 'Main Category' ); ?></td>
<td>
<select name="k2category" id="k2category" size="0" onchange="hide_view_extra_field_group_selection();">
<option  value="take_from_csv" >Take from CSV</option>
<?php
foreach ($this->k2categories as $k2category)
{


		echo '<option  value="'.$k2category->id.'" >'.$k2category->name.'</option>';
		//echo $k2category->id.' '.$k2category->name;
	

}
?>

</select>

</td>
</tr>
<tr id="k2ignore_level_tr">
<td>
<?php echo  JText::_( 'recognize that the category already<br>exists regardless of the level' ); ?>
</td>
<td>
<input type="checkbox" name="ignore_level" value="YES"/>
</td>
</tr>

<tr id="k2extrafieldgroup_tr">
<td><?php echo  JText::_( 'Extra field group' ); ?></td>
<td>
<select name="k2extrafieldgroup" id="k2extrafieldgroup" size="0">
<option  value="" >----</option>
<?php
foreach ($this->k2extrafieldgroups as $k2extrafieldgroup)
{


		echo '<option  value="'.$k2extrafieldgroup->id.'" >'.$k2extrafieldgroup->name.'</option>';
		//echo $k2category->id.' '.$k2category->name;
	

}
?>

</select>


</td>
</tr>
<tr>
<td>
<?php echo  JText::_( 'Overwrite existing Items' ); ?>
</td>
<td>
<select name="overwrite">
<option value="NO"><?php echo  JText::_( 'No' ); ?></option>
<option value="ID"><?php echo  JText::_( 'based on ID' ); ?></option>
<option value="TITLE"><?php echo  JText::_( 'based on Title' ); ?></option>
</select>
</td>
</tr>

<tr id="k2import_id_tr">
<td>
<?php echo  JText::_( 'Import the ID' ); ?>
</td>
<td>
<input type="checkbox" name="should_we_import_the_id" value="YES"/>
<?php echo  JText::_( 'Try to import the ID from the CSV file. If the ID already exist a new will be creted' ); ?>

</td>
</tr>

<tr>
<td>
<?php echo  JText::_( 'Import rows per loop' ); ?>
</td>
<td>
<input type="text" name="csv_count_rows_to_do" value="10" size=3 maxlength=3/>
<?php echo  JText::_('If you have a short PHP max_execution_time you will have to take a small value here. The script run in a loop and import x items per loop');?>
</td>
</tr>


<?php 
$max_execution_time1=ini_get('max_execution_time');
ini_set('max_execution_time', $max_execution_time1+23);
$max_execution_time2=ini_get('max_execution_time');

//we are able to set the max_execution_time
if ($max_execution_time1<$max_execution_time2)
{


?>
<tr>
<td>
<?php echo  JText::_( 'Max execution time' ); ?>
</td>
<td>
<input type="text" name="max_execution_time" value="<?php echo $max_execution_time1+1000; ?>" size=4 maxlength=4/>
<?php echo  JText::_('Try to increase the max_execution_time. No guaranty that that would work');?>
</td>
</tr>

<?php 
}
?>

<tr>
<td>
<?php echo  JText::_( 'Start num Row' ); ?>
</td>
<td>
<input type="text" name="start_row_num" value="1" size=5 maxlength=5/>
<?php echo  JText::_('What Row should we start. Row No. 0 is the heading');?>
</td>
</tr>
<tr>
<td>
<?php echo  JText::_( 'File charset' ); ?>
</td>
<td>
<input type="text" name="in_charset" value="UTF-8" size=8 maxlength=20/>
<?php echo  JText::_( '<a href="http://www.php.net/manual/en/function.iconv.php">iconv()</a> will be uses to convert');?>
</td>
</tr>
<tr>
<td>
<?php echo  JText::_( 'Database charset' ); ?>
</td>
<td>
<input type="text" name="out_charset" value="UTF-8" size=8 maxlength=20/>

<?php echo  JText::_( '<a href="http://www.php.net/manual/en/function.iconv.php">iconv()</a> will be uses to convert');?>
</td>
</tr>
</table>

<br>

<input type="hidden" name="option" value="com_k2import" />
<input type="hidden" name="task" value="associate" />

<input type="hidden" name="controller" value="k2import" />
<button type="submit"><?php echo  JText::_( 'continue' ); ?></button>
</form>

<?php


?>