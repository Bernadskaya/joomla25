<?php

/**
 * K2import Model for K2import Component
 *
 * @package    K2import
 * parts tooked from components/com_k2/helpers/route.php JoomlaWorks http://www.joomlaworks.net
 * and: http://thuejk.blogspot.com/2010/01/phps-implementation-of-fgetcsv-and.html
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */


// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model' );
jimport('joomla.application.component.helper');


class K2importModelK2import extends JModel
{

	var $_data = array();
	var $_header;
	var $_categories;
	var $_extra_fields;
	var $_k2_item_id;
	var $_extrafieldgroups;
	var $_count_csv_rows=0;


	function save($overwrite,$field_array,$k2category,$extrafield_values,$extrafield_search_values,$tags,$gallery,$gallery_images,$comments,$id_from_csv,$should_we_import_the_id)
	{

		jimport( 'joomla.filter.output' );
		

		$jAp=& JFactory::getApplication();

		
		date_default_timezone_set('UTC');

		$errors='';

		$id_from_csv=(int)$id_from_csv;

		if (trim($field_array['created'])=='')
			$field_array['created']=date('Y-m-d H:i:s');

		//automatic title creation if there is no title given
		if (trim($field_array['title'])=='')
			$field_array['title']="Imported Item - " . date('Y-m-d H:i:s');

		//automatic alias creation if there is no title given
		if(trim($field_array['alias'])=='') {
			$field_array['alias'] = $field_array['title'];
		}

		//alias correction. Tooked from components/com_k2/helpers/route.php
		/**
		 * @version		$Id: route.php 1492 2012-02-22 17:40:09Z joomlaworks@gmail.com $
		 * @package		K2
		 * @author		JoomlaWorks http://www.joomlaworks.net
		 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
		 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
		 */
		
		if(JFactory::getConfig()->get('unicodeslugs') == 1 || JPluginHelper::isEnabled('system', 'unicodeslug') || JPluginHelper::isEnabled('system', 'jw_unicodeSlugsExtended')){			
			$field_array['alias'] = $field_array['alias'];
		} else {
			mb_internal_encoding("UTF-8");
			mb_regex_encoding("UTF-8");
			$field_array['alias'] = trim(mb_strtolower($field_array['alias']));
			//$field_array['alias'] = str_replace('-', ' ', $field_array['alias']);
			$field_array['alias'] = mb_ereg_replace('[[:space:]]+', ' ', $field_array['alias']);
			$field_array['alias'] = trim(str_replace(' ', '', $field_array['alias']));
			$field_array['alias'] = str_replace('.', '', $field_array['alias']);
		
			$stripthese = ',|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|â€ž|â€¹|â€™|â€˜|â€œ|â€�|â€¢|â€º|Â«|Â´|Â»|Â°|«|»|…';
			$strips = explode('|', $stripthese);
			foreach ($strips as $strip) {
				$field_array['alias'] = str_replace($strip, '', $field_array['alias']);
			}
			$params = &JComponentHelper::getParams('com_k2');
			$SEFReplacements = array();
			$items = explode(',', $params->get('SEFReplacements', NULL));
			foreach ($items as $item) {
				if (! empty($item)) {
					@list($src, $dst) = explode('|', trim($item));
					$SEFReplacements[trim($src)] = trim($dst);
				}
			}
			foreach ($SEFReplacements as $key=>$value) {
				$field_array['alias'] = str_replace($key, $value, $field_array['alias']);
			}
			$field_array['alias'] = trim($field_array['alias'], '-.');
			if (trim(str_replace('-', '', $field_array['alias'])) == '') {
				$datenow = &JFactory::getDate();
				$field_array['alias'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
			}
		}		
		

		if ($overwrite!='NO')
		{

			if ($overwrite=='TITLE')
			{
				$overwrite_where= " title = '".$this->_db->getEscaped($field_array['title'])."'
						AND `catid` ='".(int)$k2category."'";
			}
			elseif ($overwrite=='ID')
			{
				$overwrite_where= " id = '". (int)$id_from_csv ."' ";
			}

			$query="SELECT id
					FROM `#__k2_items`
					WHERE ".$overwrite_where."
							LIMIT 1";

			$this->_db->setQuery($query);

			$old_item=$this->_db->loadAssocList();



			if (isset($old_item) && isset($old_item[0]))
			{
					
				$old_item=$old_item[0];

				$fieldlist ='';
				foreach ($field_array as $name=>$value)
				{
					if (strlen($fieldlist)>0)
						$fieldlist .= ',';

					if ($name=='created_by')
						$name='modified_by';



					$fieldlist.="`".$this->_db->getEscaped($name) . "`='".$this->_db->getEscaped($value) . "'";
				}
					
				$old_item['id']=(int)$old_item['id'];
				$query = "UPDATE `#__k2_items` SET " . $fieldlist . ",`modified`='".date('Y-m-d H:i:s')."',
						`extra_fields`='".$this->_db->getEscaped($extrafield_values)."',
								`extra_fields_search`=".$this->_db->Quote($this->_db->getEscaped($extrafield_search_values))."

										WHERE id='".$old_item['id']."'";

				$this->_k2_item_id=$old_item['id'];

				$this->_db->setQuery($query);
				$this->_db->query();

				if ($this->_db->getErrorNum())
				{
					return $this->_db->getErrorMsg();
				}


				//delete all ref tags for this item
				$this->_db->setQuery("DELETE FROM `#__k2_tags_xref` WHERE itemID = '".(int)$this->_k2_item_id."'");
				$this->_db->query();
					
				if ($this->_db->getErrorNum())	{
					return $this->_db->getErrorMsg();
				}

				//delete all COMMENTS for this item
				$this->_db->setQuery("DELETE FROM `#__k2_comments` WHERE itemID = '".(int)$this->_k2_item_id."'");
				$this->_db->query();
					
				if ($this->_db->getErrorNum())	{
					return $this->_db->getErrorMsg();
				}

			}
			else
			{
				$errors.= '<li>could not find item to overwrite I will create a new one. WHERE clause:'. $overwrite_where.'</li>';

				$overwrite='NO';
			}



		}


		if ($overwrite=='NO')
		{
			$fieldlist ='';
			$field_values='';
			foreach ($field_array as $name=>$value)
			{



				if (strlen($fieldlist)>0)
					$fieldlist .= ',';
					
				$fieldlist.="`".$this->_db->getEscaped($name) . "`";
					
				if (strlen($field_values)>0)
					$field_values .= ',';
					
				$field_values.="'".$this->_db->getEscaped($value) . "'";
			}


			//if we have to import the ID check if it exists
			//if not put it into the fieldlist
			//if it exists we do nothing and so the auto-increment function is used
			//but we show a error
			if ($should_we_import_the_id=="YES")
			{
				$this->_db->setQuery("SELECT id from `#__k2_items` WHERE id =	".$id_from_csv." ");
				$this->_db->query();
				if ($this->_db->getNumRows()>0)
				{
					$errors.= '<li>could not import ID '. $id_from_csv. ', this ID already exist in the database, I will use a autoincrement-ID to import the item</li>';
				}
				else
				{
					$fieldlist.=',`id`';
					$field_values .= ','.$id_from_csv;
				}
					
			}

			$query="INSERT INTO `#__k2_items` ( ".$fieldlist.",
					`catid`,
					`extra_fields`,
					`extra_fields_search`)
					VALUES (" .$field_values . ",".
					$this->_db->getEscaped((int)$k2category) .
					",'".
					$this->_db->getEscaped($extrafield_values). "',".
					$this->_db->Quote($this->_db->getEscaped($extrafield_search_values)). ")";



			$this->_db->setQuery($query);
			$this->_db->query();

			if ($this->_db->getErrorNum())
			{
				return $this->_db->getErrorMsg();
			}


			$this->_k2_item_id=$this->_db->insertid();

			if ($this->_k2_item_id<=0)
			{
					
				//$jAp->enqueueMessage(nl2br($this->_db->getErrorMsg()),'error');
				return $this->_db->getErrorMsg();

			}
		}




		foreach ($tags as $tag)
		{
			if (!isset($tag->id))
			{
				$this->_db->setQuery("INSERT INTO `#__k2_tags` (`name`,`published`)
						VALUES ('".$this->_db->getEscaped($tag->name)."',1)");
				$this->_db->query();
				if ($this->_db->getErrorNum())
				{
					return $this->_db->getErrorMsg();
				}

				$tag->id=$this->_db->insertid();
			}

			$this->_db->setQuery("INSERT INTO `#__k2_tags_xref` (`tagID` ,`itemID`)
					VALUES (".(int)$tag->id.",".(int)$this->_k2_item_id.")");
			$this->_db->query();

			if ($this->_db->getErrorNum())
			{
				return $this->_db->getErrorMsg();
			}

		}
			
		//create the gallery

		//create the directory for the gallery if it does not exist
		if (isset($gallery_images) && is_array($gallery_images) && count($gallery_images)>0)
		{
			if (strlen(trim($gallery))<=0)
			{
				//there ar images for the gallery but no gallery name, so we create one just with the id of the item as name
				$gallery=(int)$this->_k2_item_id;
				$query = "UPDATE `#__k2_items` SET gallery='{gallery}".$this->_db->getEscaped($gallery)."{/gallery}' WHERE id='".(int)$this->_k2_item_id."'";

				$this->_db->setQuery($query);
				$this->_db->query();
			}

			JFolder::create(JPATH_SITE.DS.'media'.DS.'k2'.DS.'galleries' . DS . $gallery);

			foreach ($gallery_images as $image)
			{
				$image=trim($image);
				if (strlen($image)>0)
				{
					$image_safe=JFile::makeSafe($image);



					if( !JFile::copy(JPATH_SITE.DS.$image , JPATH_SITE.DS.'media'.DS.'k2'.DS.'galleries' . DS . $gallery . DS . $image_safe) )
					{
						$errors.= '<li>could not copy the file '. $image. '</li>';
					}
				}
			}
		}

		//insert the comments
		if (is_array($comments))
		{

			foreach ($comments as $comment)
			{
				if (!isset($comment->userName))
				{
					$comment->userName='';
				}
				if (!isset($comment->commentEmail))
				{
					$comment->commentEmail='';
				}
				if (!isset($comment->commentURL))
				{
					$comment->commentURL='';
				}
				if (!isset($comment->commentDate))
				{
					$comment->commentDate=date('Y-m-d H:i:s');
				}
				if (!isset($comment->commentText))
				{
					$comment->commentText='';
				}

				$this->_db->setQuery("INSERT INTO `#__k2_comments` (`itemID`,`userName`, `commentDate`,
						`commentText`,`commentEmail`,`commentURL`,`published` )
						VALUES (".(int)$this->_k2_item_id.",'".$this->_db->getEscaped($comment->userName)."','".
						$this->_db->getEscaped($comment->commentDate)."','".
						$this->_db->getEscaped($comment->commentText)."','".
						$this->_db->getEscaped($comment->commentEmail)."','".
						$this->_db->getEscaped($comment->commentURL)."',1)");

				$this->_db->query();
			}

		}



		if ($errors=='')
		{
			$errors='no error';
		}
		else
		{
			$errors='<ul>'.$errors.'</ul>';
		}

		return $errors;


	}


	/**
	 * Retrieves the header-line from the CSV-file
	 * @return array with the header fields
	 */
	function getHeader($file,$in_charset,$out_charset)
	{
		$mainframe = JFactory::getApplication();
		// Lets load the data if it doesn't already exist
		if (empty( $this->_header ))
		{
			$fDestName= $mainframe->getCfg('tmp_path') . DS . $file;
			if (($handle = fopen($fDestName, "r")) !== FALSE)
			{

				$this->_header = $this->parse(fgets($handle), ",",0,1,$in_charset,$out_charset);
				$this->_header = $this->_header[0];
			}
			else
				return false;
		}

// 		echo "<br>----------------------------<pre>";
// 		print_r($this->_header);
// 		echo "</pre><br>----------------------------<br>";

		return $this->_header;
	}


	/**
	 * Retrieves the data from the CSV-file
	 * @return array with the data
	 */
	function getData($file,$in_charset,$out_charset,$start_row_num,$csv_count_rows_to_do)
	{
		$start_row_num=(int)$start_row_num;
		$csv_count_rows_to_do=(int)$csv_count_rows_to_do;
		$error='';

		$mainframe = & JFactory::getApplication();

		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$fDestName= $mainframe->getCfg('tmp_path') . DS . $file;
			if (($handle = fopen($fDestName, "r")) !== FALSE)
			{
				$this->_data =$this->parse(fread($handle,filesize($fDestName)), ",",$start_row_num,$csv_count_rows_to_do,$in_charset,$out_charset);
			}
			else {
				$error= "cannot open file: " . $fDestName;
			}
		}
			

		//print_r($this->_data);
		return array('data'=>$this->_data,'error'=>$error);
	}

	/*
	 * @return array with the all needed K2fields including all extra_fields
	*/
	function getK2fields($category,$k2extrafieldgroup)
	{

		$k2extrafieldgroup=(int)$k2extrafieldgroup;

		if (empty($this->_extra_fields))
		{
			if(version_compare(JVERSION,'1.6.0','ge')) {
					
				$access_tooltip = 'see Users->Accesslevel e.g. (1=>Public, 2=>Registred, 3=>Special)';


			}
			else {
				$access_tooltip = '0=>Public, 1=>Registred, 2=>Special';
			}


			$this->_extra_fields = array(
					array('extra'=>'','id'=>'id','title'=>'ID','tooltip'=>'If you selected "import ID" we will try to import the ID! If not we will not import the ID, we just need it if you selected "Overwrite existing Items - based on ID"'),
					array('extra'=>'','id'=>'title','title'=>'Title'),
					array('extra'=>'','id'=>'alias','title'=>'Alias'),
					array('extra'=>'','id'=>'introtext','title'=>'Introtext'),
					array('extra'=>'','id'=>'fulltext','title'=>'Fulltext'),
					array('extra'=>'','id'=>'tags','title'=>'Tags','tooltip'=>'comma seperated'),
					array('extra'=>'','id'=>'published','title'=>'Published','tooltip'=>'1 or 0'),
					array('extra'=>'','id'=>'publish_up','title'=>'Publish Up','tooltip'=>'format: YYYY-MM-DD hh:mm:s'),
					array('extra'=>'','id'=>'publish_down','title'=>'Publish Down','tooltip'=>'format: YYYY-MM-DD hh:mm:s'),
					array('extra'=>'','id'=>'access','title'=>'Access','tooltip'=>$access_tooltip),
					array('extra'=>'','id'=>'trash','title'=>'Trash','tooltip'=>'1 or 0'),

					array('extra'=>'','id'=>'created_by','title'=>'User ID'),
					array('extra'=>'','id'=>'created','title'=>'Created','tooltip'=>'format: YYYY-MM-DD hh:mm:ss'),
					array('extra'=>'','id'=>'image','title'=>'Image','tooltip'=>'path to an existing image on your server. If you use the ZIP upload and have images packed in the ZIP file use: <joomla_tmp_dir>/k2_import/<file_name> '),
					array('extra'=>'','id'=>'image_caption','title'=>'Image caption'),
					array('extra'=>'','id'=>'image_credits','title'=>'Image credits'),
					array('extra'=>'','id'=>'video','title'=>'Video','tooltip'=>'embeded code'),
					array('extra'=>'','id'=>'video_caption','title'=>'Video caption'),
					array('extra'=>'','id'=>'video_credits','title'=>'Video credits'),
					array('extra'=>'','id'=>'gallery','title'=>'Gallery Name','tooltip'=>'if there is no directory with this name it will be created'),
					array('extra'=>'','id'=>'gallery_images','title'=>'Images for the Gallery','tooltip'=>'path to existing images on your server, separated by comma. If you use the ZIP upload and have images packed in the ZIP file use: <joomla_tmp_dir>/k2_import/<file_name> '),
					array('extra'=>'','id'=>'ordering','title'=>'Ordering','tooltip'=>'must be integer'),
					array('extra'=>'','id'=>'featured','title'=>'Featured','tooltip'=>'1 or 0'),
					array('extra'=>'','id'=>'featured_ordering','title'=>'Featured ordering','tooltip'=>'must be integer'),



					array('extra'=>'','id'=>'attachment','title'=>'Attachment <a href="javascript:add_more_attachments()">add more</a>','tooltip'=>'path to an existing file on your server. If you use the ZIP upload and have files for the attachment packed in the ZIP file use: <joomla_tmp_dir>/k2_import/<file_name>'),
					array('extra'=>'','id'=>'attachment_title','title'=>'Attachment title'),
					array('extra'=>'','id'=>'attachment_title_attribute','title'=>'Attachment title attribute'),

					array('extra'=>'','id'=>'metadesc','title'=>'Meta Description'),
					array('extra'=>'','id'=>'metadata','title'=>'Meta Data','tooltip'=>'(Robots / Author) the content of this field should be:robots=xxxx [linebreak] author=xxxx'),
					array('extra'=>'','id'=>'metakey','title'=>'Meta Keywords'),
					array('extra'=>'','id'=>'item_plugins','title'=>'Item Plugins'),
						

					array('extra'=>'','id'=>'k2category_name','title'=>'Category name','tooltip'=>'if the name doesn\'t exist a new category will be created, if you selected a category before this will be a sub-category'),
					array('extra'=>'','id'=>'k2category_description','title'=>'Category description'),
					array('extra'=>'','id'=>'k2category_access','title'=>'Category access','tooltip'=>$access_tooltip),
					array('extra'=>'','id'=>'k2category_plugins','title'=>'Category Plugins'),
						
					array('extra'=>'','id'=>'comments','title'=>'Comments','tooltip'=>'JSON encoded string: [{"userName":"My NAME","commentEmail":"email@email.cim","commentURL":"http://individual-it.net","commentDate":"2010-06-16 16:48:22","commentText":"some text."},{"userName":"My NAME","commentEmail":"email@email.cim","commentURL":"http://individual-it.net","commentDate":"2010-06-16 16:48:22","commentText":"some text."}]'),

			);



			if ($category!='take_from_csv' && $k2extrafieldgroup <=0)
			{
				$query = "SELECT f.id, f.name as title, f.value, f.type, 'extra'
						FROM `#__k2_categories` AS cats,
						#__k2_extra_fields_groups AS groups,
						#__k2_extra_fields AS f
						WHERE cats.extraFieldsGroup = groups.id
						AND groups.id = f.group
						AND f.published =1
						AND cats.id =".(int)$category."
								ORDER BY f.ordering";
					
					
				$this->_db->setQuery($query);

				$jAp=& JFactory::getApplication();


				if (is_null($extra_fields = $this->_db->loadAssocList())) {
					$jAp->enqueueMessage(nl2br($this->_db->getErrorMsg()),'error'); return;
				}

				$this->_extra_fields = array_merge($this->_extra_fields, $extra_fields);
			}

			elseif ($category=='take_from_csv' && $k2extrafieldgroup >0)
			{
				$query = "SELECT f.id, f.name as title, f.value, f.type, 'extra'
				FROM 	#__k2_extra_fields_groups AS groups,
				#__k2_extra_fields AS f
				WHERE groups.id = f.group
				AND f.published =1
				AND f.group =$k2extrafieldgroup
				ORDER BY f.ordering";
					
					
				$this->_db->setQuery($query);

				$jAp=& JFactory::getApplication();


				if (is_null($extra_fields = $this->_db->loadAssocList())) {
					$jAp->enqueueMessage(nl2br($this->_db->getErrorMsg()),'error'); return;
				}

				$this->_extra_fields = array_merge($this->_extra_fields, $extra_fields);
			}
		}

		// 		echo "<pre>";
		// 		print_r($this->_extra_fields);
		// 		echo "</pre>";
		return $this->_extra_fields;
	}


	/**
	 * @return object array with all K2 categories
	*/
	function getK2categories()
	{

		if (empty( $this->_categories ))
		{
			$query = "SELECT id,name FROM `#__k2_categories`";
			$this->_categories = $this->_getList( $query );

		}


		return $this->_categories;
	}


	/**
	 * @return object array with all K2 extra field groups
	*/
	function getK2extrafieldgroups()
	{

		if (empty( $this->_extrafieldgroups ))
		{
			$query = "SELECT id,name FROM `#__k2_extra_fields_groups`";
			$this->_extrafieldgroups= $this->_getList( $query );

		}


		return $this->_extrafieldgroups;
	}

	/**
	 * @creates a new category with the given name and the parent id and returns the new id
	*/
	function createK2category($category_name,$parent_id=0,$access=0,$description='',$extraFieldsGroup=0,$plugins)
	{

		$extraFieldsGroup=(int)$extraFieldsGroup;
		//if there is a parent category we will take the extraFieldsGroup and use it for the
		//new category
		if ($parent_id>0 && $extraFieldsGroup<=0)
		{
			$query = "SELECT extraFieldsGroup FROM `#__k2_categories` WHERE id='".(int)$parent_id."'";
			$extraFieldsGroup = $this->_getList( $query );
			$extraFieldsGroup=(int)$extraFieldsGroup[0]->extraFieldsGroup;

		}



		$this->_db->setQuery("INSERT INTO `#__k2_categories` (`name`,
				`description`,
				`published`,
				`parent`,
				`extraFieldsGroup`,
				`access`,
				`plugins`,
				`trash`,
				`params`)
				VALUES ('".$this->_db->getEscaped($category_name)."',
				'".$description."',
				1,
				'".(int)$parent_id."',
				'".(int)$extraFieldsGroup."',
				'".(int) $access."',
				'".$plugins."',
				'0',
				'inheritFrom=0
				theme=
				num_leading_items=2
				num_leading_columns=1
				leadingImgSize=Large
				num_primary_items=4
				num_primary_columns=2
				primaryImgSize=Medium
				num_secondary_items=4
				num_secondary_columns=1
				secondaryImgSize=Small
				num_links=4
				num_links_columns=1
				linksImgSize=XSmall
				catCatalogMode=0
				catFeaturedItems=1
				catOrdering=
				catPagination=2
				catPaginationResults=1
				catTitle=1
				catTitleItemCounter=1
				catDescription=1
				catImage=1
				catFeedLink=1
				feedLink=1
				subCategories=1
				subCatColumns=2
				subCatOrdering=
				subCatTitle=1
				subCatTitleItemCounter=1
				subCatDescription=1
				subCatImage=1
				itemImageXS=
				itemImageS=
				itemImageM=
				itemImageL=
				itemImageXL=
				catItemTitle=1
				catItemTitleLinked=1
				catItemFeaturedNotice=0
				catItemAuthor=1
				catItemDateCreated=1
				catItemRating=0
				catItemImage=1
				catItemIntroText=1
				catItemIntroTextWordLimit=
				catItemExtraFields=0
				catItemHits=0
				catItemCategory=1
				catItemTags=1
				catItemAttachments=0
				catItemAttachmentsCounter=0
				catItemVideo=0
				catItemVideoWidth=
				catItemVideoHeight=
				catItemVideoAutoPlay=0
				catItemImageGallery=0
				catItemDateModified=0
				catItemReadMore=1
				catItemCommentsAnchor=1
				catItemK2Plugins=1
				itemDateCreated=1
				itemTitle=1
				itemFeaturedNotice=1
				itemAuthor=1
				itemFontResizer=1
				itemPrintButton=1
				itemEmailButton=1
				itemSocialButton=1
				itemVideoAnchor=1
				itemImageGalleryAnchor=1
				itemCommentsAnchor=1
				itemRating=1
				itemImage=1
				itemImgSize=Large
				itemImageMainCaption=1
				itemImageMainCredits=1
				itemIntroText=1
				itemFullText=1
				itemExtraFields=1
				itemDateModified=1
				itemHits=1
				itemTwitterLink=1
				itemCategory=1
				itemTags=1
				itemShareLinks=1
				itemAttachments=1
				itemAttachmentsCounter=1
				itemRelated=1
				itemRelatedLimit=5
				itemVideo=1
				itemVideoWidth=
				itemVideoHeight=
				itemVideoAutoPlay=0
				itemVideoCaption=1
				itemVideoCredits=1
				itemImageGallery=1
				itemNavigation=1
				itemComments=1
				itemAuthorBlock=1
				itemAuthorImage=1
				itemAuthorDescription=1
				itemAuthorURL=1
				itemAuthorEmail=0
				itemAuthorLatest=1
				itemAuthorLatestLimit=5
				itemK2Plugins=1')");

		$this->_db->query();
		return $this->_db->insertid();

	}

	/**
	 * @return object array with all K2 tags
	* if the tag has an id, then its already in the database, if not we have to create a new tag
	*/
	function getK2tags($csvtags)
	{

		$search_tags='';

		foreach ($csvtags as $csvtag)
		{
			$csvtag=trim($csvtag);
			if ($csvtag!='')
			{
				if (strlen($search_tags)>0)
					$search_tags .= ',';
					
				$search_tags.= "'". $this->_db->getEscaped($csvtag) ."'";
			}
		}
		if (strlen($search_tags)>0)
		{
			$query = "SELECT id,name FROM `#__k2_tags` WHERE name IN(".$search_tags.")";
			$k2tags = $this->_getList( $query );

		}

		if (!isset($k2tags))
			$k2tags = array();

		foreach ($csvtags as $csvtag)
		{
			$found_tag=false;
			foreach ($k2tags as $k2tag)
			{
				if ($k2tag->name==$csvtag)
				{
					$found_tag=true;
					continue;
				}
			}

			if (!$found_tag)
			{
				$csvtag_obj=new stdClass();
				$csvtag_obj->name=$csvtag;
				array_push($k2tags, $csvtag_obj);
			}


		}

		return $k2tags;
	}


	/**
	 * @saves the K2 Attachments
	*/
	function saveAttachments($attachments_names,$attachments_titles,$attachments_title_attributes)
	{
		require_once (JPATH_ADMINISTRATOR .DS."components".DS."com_k2".DS.'lib'.DS.'class.upload.php');

		if (count($attachments_names) && isset($this->_k2_item_id) && is_int($this->_k2_item_id) && $this->_k2_item_id>0)
		{
			$image = JPATH_SITE.DS.JPath::clean($imagepath);
			$errors='';

			$params = &JComponentHelper::getParams('com_k2');

			$path = $params->get('attachmentsFolder', NULL);
			if (is_null($path)) {
				$savepath = JPATH_ROOT.DS.'media'.DS.'k2'.DS.'attachments';
			} else {
				$savepath = $path;
			}

			$counter = 0;




			foreach ($attachments_names as $file) {

				$file=trim($file);
				if (!empty($file))
				{
					$file=JPATH_SITE.DS.JPath::clean($file);

					$handle = new Upload($file);


					if ($handle->uploaded) {
						$handle->file_auto_rename = true;
						$handle->allowed[] = 'application/x-zip';
						$handle->Process($savepath);
						$filename = $handle->file_dst_name;

						// $handle->Clean();
						$attachment = &JTable::getInstance('K2Attachment', 'Table');
						$attachment->itemID = $this->_k2_item_id;
						$attachment->filename = $filename;
						$attachment->title = ( empty($attachments_titles[$counter])) ? $filename : $attachments_titles[$counter];
						$attachment->titleAttribute = ( empty($attachments_title_attributes[$counter])) ? $filename : $attachments_title_attributes[$counter];
						$attachment->store();

					} else {
						$errors.= '<li>' . $handle->error  . " " . $file . '</li>';
					}



					$counter++;
				}
			}

		}

		else
		{
			$errors= 'It seems like the item were not creted (no item_id was given) so the image was not imported.';
		}

		if ($errors=='')
			$errors='no error';
		else
			$errors='<ul>'.$errors.'</ul>';
			
		return $errors;


	}



	/*
	 * save the images it gets in the way K2 saves his images
	*
	*/

	//TODOa import image from a URL

	function saveImage($imagepath)
	{
		$imagepath=trim($imagepath);

		if (isset($this->_k2_item_id) && is_int($this->_k2_item_id) && $this->_k2_item_id>0)
		{

			//echo JPATH_ADMINISTRATOR .DS."components".DS."com_k2".DS.'lib'.DS.'class.upload.php';
			//die();
			require_once (JPATH_ADMINISTRATOR .DS."components".DS."com_k2".DS.'lib'.DS.'class.upload.php');

			$image = JPATH_SITE.DS.JPath::clean($imagepath);


			$handle = new upload($image);


			$handle->allowed = array('image/*');

			$params = &JComponentHelper::getParams('com_k2');

			if ($handle->uploaded)
			{


					
				//Original image
				$savepath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'src';
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = 100;
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;

				$handle->file_new_name_body = md5("Image".$this->_k2_item_id);

				$handle->Process($savepath);

				$filename = $handle->file_dst_name_body;
				$savepath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache';

				//XLarge image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_XL';
				if (JRequest::getInt('itemImageXL')) {
					$imageWidth = JRequest::getInt('itemImageXL');
				} else {
					$imageWidth = $params->get('itemImageXL', '800');
				}
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);

				//Large image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_L';
				if (JRequest::getInt('itemImageL')) {
					$imageWidth = JRequest::getInt('itemImageL');
				} else {
					$imageWidth = $params->get('itemImageL', '600');
				}
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);

				//Medium image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_M';
				if (JRequest::getInt('itemImageM')) {
					$imageWidth = JRequest::getInt('itemImageM');
				} else {
					$imageWidth = $params->get('itemImageM', '400');
				}
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);

				//Small image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_S';
				if (JRequest::getInt('itemImageS')) {
					$imageWidth = JRequest::getInt('itemImageS');
				} else {
					$imageWidth = $params->get('itemImageS', '200');
				}
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);

				//XSmall image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_XS';
				if (JRequest::getInt('itemImageXS')) {
					$imageWidth = JRequest::getInt('itemImageXS');
				} else {
					$imageWidth = $params->get('itemImageXS', '100');
				}
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);

				//Generic image
				$handle->image_resize = true;
				$handle->image_ratio_y = true;
				$handle->image_convert = 'jpg';
				$handle->jpeg_quality = $params->get('imagesQuality');
				$handle->file_auto_rename = false;
				$handle->file_overwrite = true;
				$handle->file_new_name_body = $filename.'_Generic';
				$imageWidth = $params->get('itemImageGeneric', '300');
				$handle->image_x = $imageWidth;
				$handle->Process($savepath);


			}

			//   $handle->clean();
			if($handle->error =='')
				return 'no error';
			else
				return $handle->error . " " .$imagepath;

		}
		else
			return 'It seems like the item were not creted (no item_id was given) so the image was not imported.';




	}

	/**
	 * @return array with all extrafields
	*/
	function get_all_extrafields($categories_to_export)
	{

		$limit_to_several_categories_query = $this->create_limit_to_several_categories_query($categories_to_export);


		$query="SELECT `extra_fields`.id as id, `extra_fields`.name as name
				FROM `#__k2_extra_fields` as extra_fields,
				#__k2_categories as categories
					
				WHERE extra_fields.group = categories.extraFieldsGroup ".
				$limit_to_several_categories_query .
				" GROUP BY `extra_fields`.id";

		$extrafields =  $this->_getList( $query );
		$extra_char_search   = array('"');
		$extra_char_replace = array('""');
		
		foreach ($extrafields as $extrafield) {
			$extrafield->name = str_replace($extra_char_search, $extra_char_replace, $extrafield->name);
		}

		//		print_r($extrafields);
		return $extrafields;

	}

	/**
	 * @return int the max count of attachments
	*
	*/


	function get_max_attachments($categories_to_export)
	{
		$limit_to_several_categories_query = $this->create_limit_to_several_categories_query($categories_to_export);

		$query="SELECT count(*) as count FROM `#__k2_attachments` group by `itemID` order by count desc limit 1";

		$query="SELECT count(*) as count  FROM `#__k2_attachments` as attachments,
				#__k2_items as items,
				`#__k2_categories` as categories
				WHERE
				attachments.itemID = items.id
				AND
				items.catid = categories.id ".
				$limit_to_several_categories_query .
				" GROUP by `itemID` order by count desc limit 1";

			

			
		$data=$this->_getList( $query );


		if (is_array($data) && isset($data[0]) && is_numeric($data[0]->count))
		{

			return (int)$data[0]->count;
		}
		else {
			return 0;
		}


	}

	/**
	 * returns an array with all the data for the export
	*/
	function export($categories_to_export)
	{

		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'JSON.php');

		$json=new Services_JSON;


		$limit_to_several_categories_query = $this->create_limit_to_several_categories_query($categories_to_export);

		$query = "SELECT
				`items`.`id`,
				`title`,
				`items`.`alias`,
				`items`.`published`,
				`items`.`trash`,
				`introtext`,
				`fulltext`,
				`video`,
				`publish_up`,
				`publish_down`,
				`items`.`ordering`,
				`items`.`access`,
				`extra_fields`,
				`created_by`,
				`featured`,
				`featured_ordering`,
				`image_caption`,
				`image_credits`,
				`video_caption`,
				`video_credits`,
				REPLACE(REPLACE(gallery,'{gallery}',''),'{/gallery}','') as gallery,
				`created`,
				`modified`,
				`metadesc`,
				`metadata`,
				`metakey`,
				`items`.`plugins` as 'item_plugins',
				`categories`.`id`  as 'category_id' ,
				`categories`.`name` as 'category_name' ,
				`categories`.`description` as 'category_description' ,
				`categories`.`access` as 'category_access',
				`categories`.`plugins` as 'category_plugins'
				FROM `#__k2_items` as items, #__k2_categories as categories
				WHERE  items.catid=categories.id " . $limit_to_several_categories_query;

		$items = $this->_getList( $query );
			
		//			$extra_char_search   = array("\r\n", "\n", "\r",'"');
		//			$extra_char_replace = array('\\r\\n','\\n','\\r','""');
		$extra_char_search   = array('"');
		$extra_char_replace = array('""');

		for ($item_count=0;$item_count<count($items);$item_count++)
		{



			$items[$item_count]->title = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->title);
			$items[$item_count]->introtext = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->introtext);
			$items[$item_count]->fulltext = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->fulltext);
			$items[$item_count]->video = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->video);
			$items[$item_count]->image_credits = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->image_credits);
			$items[$item_count]->image_caption = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->image_caption);
			$items[$item_count]->video_caption = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->video_caption);
			$items[$item_count]->video_credits = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->video_credits);
			$items[$item_count]->category_name = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->category_name);
			$items[$item_count]->category_description = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->category_description);
			$items[$item_count]->metadesc = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->metadesc);
			$items[$item_count]->metadata = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->metadata);
			$items[$item_count]->metakey = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->metakey);
			$items[$item_count]->item_plugins = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->item_plugins);
			$items[$item_count]->category_plugins = str_replace($extra_char_search, $extra_char_replace, $items[$item_count]->category_plugins);
				

			//get the tags
			$query = "SELECT name
					FROM #__k2_tags, `#__k2_tags_xref`
					WHERE #__k2_tags_xref.tagID = #__k2_tags.id
					AND #__k2_tags_xref.itemID =".(int)$items[$item_count]->id;

			//echo $query . "\n\n";

			$items[$item_count]->tags=$this->_getList( $query );

			//attachments
			$query = "SELECT `filename` , `title` , `titleAttribute`
					FROM `#__k2_attachments`
					WHERE `itemID` =".(int)$items[$item_count]->id;

			$items[$item_count]->attachments=$this->_getList( $query );




			//comments
			$query = "SELECT userName, commentDate,	commentText ,commentEmail, commentURL
					FROM `#__k2_comments`
					WHERE `itemID` =".(int)$items[$item_count]->id;


			$items[$item_count]->comment=str_replace($extra_char_search, $extra_char_replace,$json->encode($this->_getList( $query )));
			//	$items[$item_count]->comment=$this->_getList( $query );
			//echo $items[$item_count]->comment ."<br>";

			//extra fields
			$items[$item_count]->extra_fields=json_decode($items[$item_count]->extra_fields);


			for ($extra_fields_count=0;$extra_fields_count<count($items[$item_count]->extra_fields);$extra_fields_count++)
			{
				$query="SELECT  f.id,f.name as title, f.value, f.type
						FROM `#__k2_categories` AS cats,
						#__k2_extra_fields_groups AS groups,
						#__k2_extra_fields AS f
						WHERE cats.extraFieldsGroup = groups.id
						AND groups.id = f.group
						AND f.published =1
						AND f.id=".(int)$items[$item_count]->extra_fields[$extra_fields_count]->id ."
								AND cats.id =".(int)$items[$item_count]->category_id;

					
				if($this->_getListCount($query)>0)
				{
					$extra_fields_entries=$this->_getList( $query );

					$extra_fields_entries=$extra_fields_entries[0];
					$extra_fields_entries->value=json_decode($extra_fields_entries->value);
					$items[$item_count]->extra_fields[$extra_fields_count]->title=$extra_fields_entries->title;
					$items[$item_count]->extra_fields[$extra_fields_count]->type=$extra_fields_entries->type;


					//we don't need to check the values for the links
					if ($items[$item_count]->extra_fields[$extra_fields_count]->type!='link')
					{
						foreach ($extra_fields_entries->value as $value)
						{
							//in multipleSelect fields the value is an array


							if (is_array($items[$item_count]->extra_fields[$extra_fields_count]->value))
							{
								$key=array_search($value->value, $items[$item_count]->extra_fields[$extra_fields_count]->value);

								if ($key!==FALSE)
								{
									$items[$item_count]->extra_fields[$extra_fields_count]->entry[$key]=$value->name;
								}

							}
							else
							{
								if ($value->value == $items[$item_count]->extra_fields[$extra_fields_count]->value )
								{
									$items[$item_count]->extra_fields[$extra_fields_count]->entry=$value->name;
								}
							}
						}

					}


				}
				
				if (isset($items[$item_count]->extra_fields[$extra_fields_count]->entry)) {
					$items[$item_count]->extra_fields[$extra_fields_count]->entry=str_replace($extra_char_search, $extra_char_replace,$items[$item_count]->extra_fields[$extra_fields_count]->entry);
				} elseif (isset($items[$item_count]->extra_fields[$extra_fields_count]->value)) {
					$items[$item_count]->extra_fields[$extra_fields_count]->value=str_replace($extra_char_search, $extra_char_replace,$items[$item_count]->extra_fields[$extra_fields_count]->value);						
				}
			}

			//gallery images
			$items[$item_count]->gallery=trim(JFolder::makeSafe($items[$item_count]->gallery));
			if ($items[$item_count]->gallery!='')
			{

				$gallery_images = JFolder::files( JPATH_SITE.DS.'media'.DS.'k2'.DS.'galleries' . DS . $items[$item_count]->gallery);


				$items[$item_count]->gallery_images='';

				if ($gallery_images)
				{
					$first_image=true;
					foreach ($gallery_images as $image)
					{
						if (!$first_image)
						{
							$items[$item_count]->gallery_images.=',';
						}
						else
						{$first_image=false;
						}

						$items[$item_count]->gallery_images.=$image;



					}
				}
			}



		}
			
		//find empty categories
		$query = "SELECT id,name,description,access from #__k2_categories as categories WHERE id NOT IN (SELECT catid
				FROM `#__k2_items` AS items, #__k2_categories AS categories
				WHERE items.catid = categories.id) " .  $limit_to_several_categories_query;

		$empty_categories = $this->_getList( $query );


		foreach ($empty_categories as $empty_category)
		{
			$item_count++;
			$items[$item_count]->id='0';
			$items[$item_count]->title='empty category';
			$items[$item_count]->category_id=(int) $empty_category->id;
			$items[$item_count]->category_name=str_replace($extra_char_search, $extra_char_replace, $empty_category->name);
			$items[$item_count]->category_description=str_replace($extra_char_search, $extra_char_replace, $empty_category->description);
			$items[$item_count]->category_access=$empty_category->access;

		}


		return $items;


	}

	function get_count_csv_rows()
	{
		return $this->_count_csv_rows;
	}


	private function create_limit_to_several_categories_query($categories_to_export)
	{
		$limit_to_several_categories_query="";
		foreach ($categories_to_export as $category_to_export)
		{
			//ignore non numeric stuff
			if (is_numeric($category_to_export))
			{
				$limit_to_several_categories_query .= $category_to_export . ",";
			}

		}

		//delete the last comma
		$limit_to_several_categories_query = substr($limit_to_several_categories_query,0,-1);

		if (trim($limit_to_several_categories_query) != "")
		{
			$limit_to_several_categories_query = " AND categories.id IN(" . $limit_to_several_categories_query . ")";
		}
		else
		{
			$limit_to_several_categories_query = "";
		}

		return $limit_to_several_categories_query;

	}




	/**
	 * error reporting method for the parse method. Raises a JError
	 *
	 * @param  $error
	 * @param $str
	 * @param  $m
	 * @param  $offset
	 * @param  $moving_offset
	 * @param  $csv_line
	 * @since  0.9_beta3
	 */
	private static function csv_parse_error($error, $str, $m, $offset, $moving_offset, $csv_line) {
		if ($error === "unexpected quote in unquoted field") {
			$raw = _("Found an unexpected quote character in field %d of csv line %d (text line %d).".
					" The first 50 chars from the start of the field are '%s'.");
		} else if ($error === "unexpected quote in quoted field") {
			$raw = _("Found an unescaped quote in quoted field %d of csv line %d (text line %d).".
					" The first 50 chars from the start of the field are '%s'.");
		} else if ($error === "unexpected text after end quote in quoted field") {
			$raw = _("Unexpected test after end quote in quoted field %d of csv line %d (text line %d).".
					" The first 50 chars from the start of the field are '%s'.");
		} else {
			die("impossible");
		}

		$t = sprintf($raw,
				sizeof($m[$csv_line])+1,
				$csv_line,
				sizeof(explode("\n", substr($str, 0, $offset))),
				addcslashes(substr($str, $offset, 50), "\t\r\n'\\")
		);
		JError::raiseError(422,$t);
	}

	/** This function comed from http://thuejk.blogspot.com/2010/01/phps-implementation-of-fgetcsv-and.html
	 *
	 *
	 * Faily speed-important, therefore:
	 * -With few function calls.
	 * -Never copy the entire (potentially MB-long) string
	 * -Never operate on the whole string (so therefore we use no pregs)
	 *
	 * This implementation parses a 1MB csv file in under a second,
	 * which is obviously slow, but should be fast enough. My first try
	 * was using regexp_max with regexp's offset parameter, which failed
	 * horribly speed-wise.
	 *
	 * The basic idea is to eat one char at a time, and append fields
	 * and lines to the matrix as we encounter separators and newlines.
	 *
	 * Not using PHP's fgetcsv due to http://bugs.php.net/bug.php?id=50686
	 *

	 * @see http://thuejk.blogspot.com/2010/01/phps-implementation-of-fgetcsv-and.html
	 *
	 * @param string $str the string to parse
	 * @param char  $sep Separator
	 * @param int $start_row_num row to start with (first row = 0)
	 * @param int $csv_count_rows_to_do rows to read
	 * @param string $in_charset charset of the CSV file, iconv() will be uses to convert
	 * @param string $out_charset wished output charset, iconv() will be uses to convert
	 * @return array(start_line1 => Array(field1, field2,...),
	 *       start_line2 =>
	 *       ...);
	 * @since  0.9_beta3
	 * @throws Exception
	 */

	public static function parse($str, $sep=',',$start_row_num,$csv_count_rows_to_do,$in_charset,$out_charset) {
		if ($str === "") {
			return Array();
		}

		//we want to end the string with a \r\n
		if (substr($str,-1) != "\n")
		{
			$str .= "\r\n";
		}
		
		$offset = 0;
		$item = "";
		$len = strlen($str);
		$csv_line = 0;
		$readed_csv_line = 0;
		$m = Array($csv_line => Array());
		$force_empty_field = false;


		$moving_offset = 0;

		while ($moving_offset < $len && $readed_csv_line < $csv_count_rows_to_do) {
			$c = $str[$moving_offset];

			//check if the first_line_to_read is already reached
			if ($csv_line < $start_row_num)
			{
				if ($c === "\n") {
					//newline
					$moving_offset++;
					$csv_line++;
					//end of string
					if ($moving_offset === $len) {
						return $m;
					}

				} else if ($c === '"') {
					//quoted item

					//eat quote
					$moving_offset++;

					//read until end quote
					while (true) {
						$c = $str[$moving_offset];

						if ($moving_offset >= $len) {
							//throws exception
							self::csv_parse_error("unexpected quote in quoted field",
									$str, $m, $offset, $moving_offset, $csv_line);
						}
						if ($c === '"') {
							if ($str[$moving_offset+1] === '"') {
								//escaped quote
								$moving_offset += 2; //eat doubled quotes
							} else {
								//end of item
								$moving_offset++; //eat end quote
								break;
							}
						} else {
							$moving_offset++;
						}
					}

				} else {
					$moving_offset++;
				}

			}

			//we skiped all the lines we don't need, now the real reading starts
			else
			{
				if ($c === $sep) {

					//separator
					$m[$readed_csv_line][] = $item;
					$item = "";
					$force_empty_field = true;
					$offset = ++$moving_offset;
				} else if ($c === "\n") {
					
					
					//newline
					if ($str[$moving_offset -1] === "\r") {
						//The \r belonged to the newline
						$item = substr($item, 0, -1);
					}
					if ($item !== "" || $force_empty_field) {

						//try to change the encoding.
						//don't trust this to much, its mutch better to use UTF8 everywhere
						if ($in_charset!=$out_charset)
						{
							$item =iconv($in_charset,$out_charset.'//TRANSLIT',$item);
						}
							
						//add field and reset for next field
						$m[$readed_csv_line][] = $item;
						$item = "";
					}
					$offset = ++$moving_offset;

					//end of string
					if ($offset === $len) {
						return $m;
					}

					$readed_csv_line++;

					if ($readed_csv_line == $csv_count_rows_to_do)
					{
						return $m;
					}

					$m[$readed_csv_line] = Array();
					$csv_line++;
					$force_empty_field = false;
				} else if ($c === '"') {
					//quoted item

					if ($item !== "") {
						//throws exception
						self::csv_parse_error("unexpected quote in unquoted field",
								$str, $m, $offset, $moving_offset, $csv_line);
					}

					//eat quote
					$moving_offset++;

					//read until end quote
					while (true) {
						$c = $str[$moving_offset];

						if ($moving_offset >= $len) {
							//throws exception
							self::csv_parse_error("unexpected quote in quoted field",
									$str, $m, $offset, $moving_offset, $csv_line);
						}
						if ($c === '"') {
							if ($str[$moving_offset+1] === '"') {
								//escaped quote
								$item .= '"';
								$moving_offset += 2; //eat doubled quotes
							} else {
								//end of item
								$moving_offset++; //eat end quote
								break;
							}
						} else {
							$item .= $c;
							$moving_offset++;
						}
					}

					//eat separator
					if ($str[$moving_offset] === $sep) {
						$force_empty_field = true;
						$moving_offset++;
					} else if ( ($str[$moving_offset] === "\r" && $str[$moving_offset+1] === "\n")
							|| $str[$moving_offset] === "\n") {
						$force_empty_field = false;
					} else {
						self::csv_parse_error("unexpected text after end quote in quoted field",
								$str, $m, $offset, $moving_offset, $csv_line);
					}


					//try to change the encoding.
					//don't trust this to much, its mutch better to use UTF8 everywhere
					if ($in_charset!=$out_charset)
					{
						$item =iconv($in_charset,$out_charset.'//TRANSLIT',$item);
					}

					//add field and reset for next field
					$m[$readed_csv_line][] = $item;
					$item = "";
					$offset = $moving_offset;
				} else {
					$moving_offset++;
					$item .= $c;
				}
			}
		}


		die("impossible, since the last char is a \n, and the newline handling should catch that");
	}


}
