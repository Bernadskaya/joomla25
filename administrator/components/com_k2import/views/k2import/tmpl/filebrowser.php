<?php
/**
 * @version		$Id: filebrowser.php 478 2010-06-16 16:11:42Z joomlaworks $
 * @package		K2Import
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * 				modified by Artur Neumann http://www.individual-it.net for the use with K2Import
 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<form method="post" name="fileBrowserForm" class="fileBrowserForm" action="index.php">

	  <h1><?php echo $this->title;?></h1>

		<div id="fileBrowserFolderPath">
			<a id="fileBrowserFolderPathUp" href="<?php echo JRoute::_('index.php?option=com_k2import&view=item&task=filebrowser&folder='.$this->parent.'&type='.$this->type.'&tmpl=component');?>">
				<span><?php echo JText::_('Up'); ?></span>
			</a>
			<input type="text" value="<?php echo $this->path;?>" name="path" disabled="disabled" id="addressPath" maxlength="255" />
			<div class="clr"></div>
		</div>

	  <div id="fileBrowserFolders">

			<?php if(count($this->folders)): ?>
				<?php foreach ($this->folders as $folder) :	?>
					<div class="item">
						<a href="<?php 
							if (!empty($this->path))
							{
								$dest_folder=$this->path.'/'.$folder;
							}
							else
							{
								$dest_folder=$folder;
							}
							echo JRoute::_('index.php?option=com_k2import&view=item&task=filebrowser&folder='.$dest_folder.'&type='.$this->type.'&tmpl=component');
							
							?>">
							<img alt="<?php echo $folder;?>" src="<?php echo JURI::root()?>administrator/components/com_k2import/icons/folder.png">
							<span><?php echo $folder;?></span>
						</a>
					</div>
				<?php endforeach;?>
			<?php endif;?>

			<?php if(count($this->files)): ?>
			<?php foreach($this->files as $file): 
			?>
				<div class="item">
				<?php 
				if (!empty($this->path))
				{
					$dest_path="/".$this->path.'/'.$file;
				}
				else
				{
					$dest_path='/'.$file;
				}
				
				if (JFile::getExt($file)=='zip')
				{
					$icon_file="application-zip";
				}
				else 
				{
					$icon_file="text-csv";
				}
				?>
					<a href="<?php echo $dest_path;?>" class="importFile">
						<img width="64" alt="<?php echo $file;?>" src="<?php echo JURI::root()?>administrator/components/com_k2import/icons/<?php echo $icon_file;?>.png">
						<span><?php echo $file;?></span>
					</a>
				</div>
			<?php endforeach;?>
			<?php endif;?>

			<div class="clr"></div>
		</div>

</form>
