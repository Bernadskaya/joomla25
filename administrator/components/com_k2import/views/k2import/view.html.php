<?php
/**
 * K2import Model for K2import Component
 *
 * @package    K2import
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**

*/
class K2importViewK2import extends JView
{
	/**
	 *  view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		JToolBarHelper::title (JText::_( 'COM_K2IMPORT_TITLE' ), 'generic.png' );
		k2importToolbarHelper::upload();
		k2importToolbarHelper::export();
 
		k2importToolbarHelper::instructions();

		$document = & JFactory::getDocument();
		$document->addStyleSheet('components/com_k2import/css/k2import.css');
		$document->addScript('../media/system/js/modal.js');
		$document->addStyleSheet('../media/system/css/modal.css');

		if(version_compare(JVERSION,'1.6.0','ge')) {
			
			JHtml::_('behavior.framework', true);
			JHTML::_('behavior.modal');
				
			jimport( 'joomla.html.html.behavior' );
				
				
		}


		
		//TODO shouldn't this go to the .js file?
		$js="
		window.addEvent('domready', function(){
				$('browseSrv').addEvent('click', function(e){
			e = new Event(e).stop();
			SqueezeBox.initialize();
			SqueezeBox.fromElement(this, {
				handler: 'iframe',
				url: '". JURI::base()."index.php?option=com_k2import&view=item&task=filebrowser&type=text&tmpl=component',
				size: {x: 590, y: 400}
			});
		})
		
		
		$('k2ResetButton').addEvent('click', function(e){
			e.preventDefault();
			$('adminForm').search.value = '';
			$('adminForm').filter_trash.options[0].selected = true;
			$('adminForm').filter_state.options[0].selected = true;
			$('adminForm').language.options[0].selected = true;		
			$('adminForm').action = 'index.php';
			$('adminForm').task.value = ''; 
			this.form.submit();
			});
	
		$('k2SubmitButton').addEvent('click', function(e){	
			$('adminForm').action = 'index.php';
			$('adminForm').task.value = ''; 
			this.form.submit();
			});
		
		
		});
		";
		$document->addScriptDeclaration($js);
		
		//---------------------------------------
		//the pieces starting from here is taken from K2:
		//administrator/components/com_k2/views/categories/view.html.php
/**
 * @version		$Id: view.html.php 1112 2011-10-11 14:34:53Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
		
		//Artur Neumann www.individual-it.net info@individual-it.net  29.02.2012
		//changed all K2_* textes
		
		$mainframe = &JFactory::getApplication();
		$user = & JFactory::getUser();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
	
		//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
		//importing the model directly
		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		
		//Artur Neumann www.individual-it.net info@individual-it.net  21.02.2012
		//$model = & $this->getModel(); doesn't work for us so we take it directly
		$model = new K2ModelCategories();
		
		$categories = $model->getData();

		require_once(JPATH_COMPONENT.DS.'models'.DS.'category.php');
		$categoryModel= new K2ModelCategory;

		$params = & JComponentHelper::getParams('com_k2');
		$this->assignRef('params', $params);
		if ($params->get('showItemsCounterAdmin')){
			for ($i=0; $i<sizeof($categories); $i++){
				$categories[$i]->numOfItems=$categoryModel->countCategoryItems($categories[$i]->id);
			}
		}

		$this->assignRef('rows', $categories);
		$total = $model->getTotal();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		$this->assignRef('page', $pageNav);

		$lists = array ();
		$lists['search'] = $search;
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$filter_trash_options[] = JHTML::_('select.option', 0, JText::_('COM_K2IMPORT_CURRENT'));
		$filter_trash_options[] = JHTML::_('select.option', 1, JText::_('COM_K2IMPORT_TRASHED'));
		$lists['trash'] = JHTML::_('select.genericlist', $filter_trash_options, 'filter_trash', '', 'value', 'text', $filter_trash);

		$filter_state_options[] = JHTML::_('select.option', -1, JText::_('COM_K2IMPORT_SELECT_STATE'));
		$filter_state_options[] = JHTML::_('select.option', 1, JText::_('COM_K2IMPORT_PUBLISHED'));
		$filter_state_options[] = JHTML::_('select.option', 0, JText::_('COM_K2IMPORT_UNPUBLISHED'));
		$lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', '', 'value', 'text', $filter_state);

		if(version_compare( JVERSION, '1.6.0', 'ge' )) {
			$languages = JHTML::_('contentlanguage.existing', true, true);
			array_unshift($languages, JHTML::_('select.option', '', JText::_('COM_K2IMPORT_SELECT_LANGUAGE')));
			$lists['language'] = JHTML::_('select.genericlist', $languages, 'language', '', 'value', 'text', $language);
		}
		$this->assignRef('lists', $lists);

		//Artur Neumann www.individual-it.net info@individual-it.net 20.02.2012
		//deleted K2 toolbar because not needed in my extension
		
		$this->assignRef('filter_trash', $filter_trash);
		$template = $mainframe->getTemplate();
		$this->assignRef('template', $template);
		$ordering = ( ($this->lists['order'] == 'c.ordering' || $this->lists['order'] == 'c.parent, c.ordering') && (!$this->filter_trash) );
		$this->assignRef('ordering', $ordering);
		
		//
		
		
		
		
		parent::display($tpl);

	}

	
	/**
	 *  method to display the filebrowser
	 *  taken from K2 (administrator/components/com_k2/views/item/view.html.php)
	 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z lefteris.kavadas $
	 * @package		K2
	 * @author		JoomlaWorks http://www.joomlaworks.gr
	 * @copyright	Copyright (c) 2006 - 2010 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
	 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html

	 *  modified by Artur Neumann http://www.individual-it.net for the use with K2Import
	 **/
	function filebrowser($tpl = null){

		if(version_compare(JVERSION,'1.6.0','ge')) {
					
			JHtml::_('behavior.framework', true);
		}
				
		
		$document = & JFactory::getDocument();
		$document->addScript('components/com_k2import/js/k2import.mootools.js');

		if(version_compare(JVERSION,'1.6.0','ge')) {
			$document->addStyleSheet('../media/media/css/popup-imagelist.css');		
			
		}
		else {
			$document->addStyleSheet(JURI::base().'components/com_media/assets/popup-imagelist.css');
			
		}

		$params = &JComponentHelper::getParams('com_media');

		$root = '/';
		$folder = JRequest::getVar( 'folder', $root, 'default', 'path');

		if(JString::trim($folder)=="")
		$folder = $root;
		$path=JPATH_SITE.DS.JPath::clean($folder);
		JPath::check($path);

		$title = JText::_('Browse Import-files (*.csv, *.gz, *.zip, *.bz2)');
		$filter = '.csv|gz|zip|bz2';


		if (JFolder::exists($path)){
			$folderList=JFolder::folders($path);
			$filesList=JFolder::files($path, $filter);
		}

		if (!empty($folder) && $folder!=$root){
			$parent=substr($folder, 0,strrpos($folder,'/'));
		}
		else {
			$parent = $root;
		}

		if ($folder=='/')
		{
			$folder='';
		}

		$this->assignRef('folders',$folderList);
		$this->assignRef('files',$filesList);
		$this->assignRef('parent',$parent);
		$this->assignRef('path',$folder);
		$this->assignRef('type',$type);
		$this->assignRef('title',$title);

		parent::display($tpl);

	}

}



