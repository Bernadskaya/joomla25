<?php

/**
 * K2import default controller
 *
 * @package    K2import
 * @link http://www.individual-it.net
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');


/**
 * K2import Component Controller
 *
 * @package		K2import
 */
class K2importController extends JController
{
	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
				
		
		parent::display();
	}

	function import_ajax_wrapper()
	{

		$view = & $this->getView( 'importajaxwrapper', 'html' );
		//$view->setModel( $this->getModel( 'k2import' ), true );
	
// 		echo "import_ajax_wrappe";
// 		echo "<pre>";
// 		print_r($_POST);
// 		echo "</pre>";
		$_POST['task']='import';
		$csv_count_rows_to_do	= JRequest::getVar( 'csv_count_rows_to_do', '10', 'post', 'int' );
		$start_row_num	= JRequest::getVar( 'start_row_num', '1', 'post', 'int' );
		$view->display();


		$document = & JFactory::getDocument();
		$url=JURI::base().'index.php?option=com_k2import&task=import';
			

		$js="
		var xmlHttpObject;
		var response;
		var start_row_num =".$start_row_num.";
			window.addEvent('domready', function(){
			
			";
		
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "xmlHttpObject = new Request({
				    url: '".$url."',
				    method: 'post',
				    onRequest: showWorkingIndicator,
				    onSuccess: handleContent,
				    onFailure: handleError
				});
				
				xmlHttpObject.send('".http_build_query($_POST, '', '&')."&start_row_num='+start_row_num);	
									
				";
		}
		else 
		{
			$js .= "xmlHttpObject = new XHR({ method: 'post', onFailure: handleError, onSuccess: handleContent, onRequest: showWorkingIndicator}).send('".$url."','".http_build_query($_POST, '', '&')."&start_row_num='+start_row_num);";
		}


				
		$js .= "	
			});
			

			
			function showWorkingIndicator()
			{
					var message_div=new Element('div', {
					    'class': 'k2import_message'
					   
					});

					
				  	message_div.set";
		
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "('text',";
		}
		else 
		{
			$js .= "Text(";
		}


				
		$js .= "'". JText::_( 'importing items, please stand by')	."');					
				message_div.injectInside($('k2import_messages'));					
					
					
			}
			
			function successWrapper()
			{
			alert (xmlHttpObject.response.text);
				//window.setTimeout('handleContent', 10000);
			}
			
			function handleError()
			{
				alert ('error');
			}
			
			function handleContent()
				{
				
					var error_messages='';
					start_row_num=start_row_num+".$csv_count_rows_to_do.";
					//alert (xmlHttpObject.response.text);
					
					response = xmlHttpObject.response.text.match(/{\"finish\":.*}/g);
					
					";

		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "response = JSON.decode(response[0],true);";
		}
		else
		{
			$js .= "response = Json.evaluate(response[0],true);";
		}



		$js .= "				

					var message_div=new Element('div', {
					    'class': 'k2import_message'
					   
					});
					
					
					if (response == null)
					{
										
					
						response = new Object();
						response.finish=true;
						
						error_messages=error_messages+'<li>Unespected ERROR. We cancel the import. If there are just Warnings and Notices try to adjust your PHP settings. <a href=\"http://de.php.net/manual/en/errorfunc.configuration.php#ini.error-reporting\">Error Reporting </a><br><br>' + xmlHttpObject.response.text +'</li>';
						
					
					}
					else
					{
						
						//alert(response.error_message[0]);
						response.error_message.each(function(error_message)
						{
							//error_message=stripHTML(error_message);
						
							if (error_message!='')
							{
								error_messages=error_messages+'<li>' + error_message +'</li>';
							}						
						});
						
					}

					if (error_messages=='')
					{
						message='". JText::_( 'items were successful imported')	."';
					}
					else
					{
						message='". JText::_( 'items were imported, but with some problems')	.":' + ' <ol>' + error_messages + '</ol>';
					}

					message_div.set";

		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "('html',";
		}
		else
		{
			$js .= "HTML(";
		}



		$js .= "message + ' <div class=\"k2import_rows_left\">Row: ' + response.absolute_row_num + ' / memory peak: ' + response.memory + '<div>');
					
					
					message_div.injectInside($('k2import_messages'));
					
					if (response.finish==false)
					{
					";
		
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "xmlHttpObject = new Request({
				    url: '".$url."',
				    method: 'post',
				    onRequest: showWorkingIndicator,
				    onSuccess: handleContent,
				    onFailure: handleError
				});
				
				xmlHttpObject.send('".http_build_query($_POST)."&start_row_num='+start_row_num);	
									
				";
		}
		else 
		{
			$js .= "xmlHttpObject = new XHR({method: 'post', onFailure: handleError,  onSuccess: handleContent, onRequest: showWorkingIndicator}).send('".$url."','".http_build_query($_POST)."&start_row_num='+start_row_num);";
		}


				
		$js .= "
	
					}
					else
					{

						finish_message_div=message_div.clone();
						finish_message_div.set";
		
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$js .= "('text',";
		}
		else 
		{
			$js .= "Text(";
		}


				
		$js .= "'". JText::_( 'Import finished')	."');
						finish_message_div.injectInside($('k2import_messages'));
					}	
					
					
				    //alert (response.rows_left  + ' ' + typeof(response));
				}
			
			
			";

		$document->addStyleSheet('components/com_k2import/css/k2import.css');
		$document->addScript('components/com_k2import/js/k2import.js');
		
		
		$document->addScriptDeclaration($js);
		if(version_compare(JVERSION,'1.6.0','ge')) {
			JHtml::_('behavior.framework', true);
		}		
		
		
	}

	function import()
	{

    	require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'JSON.php');
    	

		$db =& JFactory::getDBO();
		$mainframe 				= JFactory::getApplication();


		$file        			= JRequest::getVar( 'file', '', 'post', 'string' );
		$k2category        		= JRequest::getVar( 'k2category', '', 'post', 'string' );
		$k2importfields       	= JRequest::getVar( 'k2importfields', '', 'post' );
		$k2importextrafields   	= JRequest::getVar( 'k2importextrafields', '', 'post' );
		$in_charset        		= substr(JRequest::getVar( 'in_charset', '', 'post' ),0,20);
		$out_charset        	= substr(JRequest::getVar( 'out_charset', '', 'post' ),0,20);
		$overwrite        		= JRequest::getVar( 'overwrite', 'NO', 'post','string' );
		$ignore_level       	= JRequest::getVar( 'ignore_level', 'NO', 'post', 'string' );
		$should_we_import_the_id= JRequest::getVar( 'should_we_import_the_id', 'NO', 'post', 'string' );
		
		$modus        			= JRequest::getVar( 'modus', '', 'post', 'string' );
		$start_row_num 			= JRequest::getVar( 'start_row_num', '1', '', 'int' );
		$max_execution_time		= JRequest::getVar( 'max_execution_time', '', 'post', 'int' );
		$csv_count_rows_to_do	= JRequest::getVar( 'csv_count_rows_to_do', '10', 'post', 'int' );

		if ((int)$max_execution_time>0)
		{
			ini_set('max_execution_time', (int)$max_execution_time);
		}
		$start_row_num=(int)$start_row_num;

		$file=JFile::makeSafe($file);

		if ($overwrite!='ID' && $overwrite!='TITLE')
		$overwrite='NO';
		
		if ($ignore_level!='YES')
		$ignore_level='NO';
		
		if ($should_we_import_the_id!='YES')
		$should_we_import_the_id='NO';		



		if ($k2category=='take_from_csv')
		$k2extrafieldgroup        = JRequest::getVar( 'k2extrafieldgroup', '', 'post', 'int' );
		else
		$k2extrafieldgroup='';
			

		$json=new Services_JSON;


		$sql_error_message='';
		$error_message=array();
		//array_push($error_message, $start_row_num);
		//[]='' . $start_row_num;

		$model=$this->getModel( 'k2import' );
		if ($modus=='archive')
		{
			$file='k2_import'.DS.$file;
		}


		$csv_data=$model->getData($file,$in_charset,$out_charset,$start_row_num,$csv_count_rows_to_do);

		if (empty($csv_data['error']))
		{
			$csv_data=$csv_data['data'];
		}
		else 
		{
			$error_message[]=$csv_data['error'];
			$csv_data=array();
		}
		
		$k2fields=$model->getK2fields($k2category,$k2extrafieldgroup);


		//create a list of all k2fields we like to import
		$k2fieldlist='';
		$count_csv_rows=count($csv_data);


		$return=new stdClass();

		//we are comming to the end
		if ($csv_count_rows_to_do>$count_csv_rows)
		{
			$csv_count_rows_to_do=$count_csv_rows;

			$return->finish=true;
		}
		else
		{
			$return->finish=false;
		}



		for($csv_row_num=0; $csv_row_num<$count_csv_rows;$csv_row_num++)
		{

			$absolute_row_num=$start_row_num+$csv_row_num;
			$return->absolute_row_num=& $absolute_row_num;

			$attachments_names=array();
			$attachments_titles=array();
			$attachments_title_attributes=array();
			//add the imported values
			$field_array=array();
			$k2_sub_category = array();
			$gallery='';
			$gallery_images='';
			$tags=array();
			$id_from_csv='';

			foreach ($k2importfields as $name=>$column_num)
			{
				if (is_numeric($column_num) && $csv_data[$csv_row_num][$column_num]!='')
				{
					if ($name=='tags' )
					{

						$tags=explode(',',$csv_data[$csv_row_num][$column_num]);
						array_walk($tags , create_function('&$temp', '$temp = trim($temp);'));
						$tags=$model->getK2tags($tags);
					}
					elseif ($name=='id' )
					{
						//we don't import the id, we just need it for overwriting
						$id_from_csv=(int)$csv_data[$csv_row_num][$column_num];
					}


					elseif ($name =='image')
					{
						$image_name=$csv_data[$csv_row_num][$column_num];
					}
					elseif (substr($name,0,26) =='attachment_title_attribute')
					{
						array_push($attachments_title_attributes, $csv_data[$csv_row_num][$column_num]);
					}
					elseif (substr($name,0,16) =='attachment_title')
					{
						array_push($attachments_titles, $csv_data[$csv_row_num][$column_num]);
					}
					elseif (substr($name,0,10) =='attachment')
					{
						array_push($attachments_names, $csv_data[$csv_row_num][$column_num]);
					}

					elseif ($name =='created_by')
					{
						//if (strlen($field_array)>0)
						//	$field_array .= ',';
						$user =& JFactory::getUser();

						if ($csv_data[$csv_row_num][$column_num]!='')
						{
							//check if there is such a user in the DB
							$db =& JFactory::getDBO();

							$db->setQuery("SELECT count(id) from `#__users` WHERE id='".(int)$csv_data[$csv_row_num][$column_num]."'");
							$user_count = $db->loadResult();

							if ($user_count==1)
							$field_array[$name]= (int)$csv_data[$csv_row_num][$column_num];
							else
							{
								$field_array[$name]= (int)$user->id;
								array_push($error_message, 'row: '. $absolute_row_num  .' there is no user with the ID: ' . (int)$csv_data[$csv_row_num][$column_num]. " I'm using the ID:".(int)$user->id );
									
								//							$error_message[]=. "\n";
							}
						}
						else
						$field_array[$name]= (int)$user->id;

					}
					elseif ($name =='k2category_name')
					{
						if (is_numeric($column_num))
						{

							//check if there is a category with this name, if yes get the id
							if ($ignore_level=='NO')
							{
									
								if ($k2category!='take_from_csv')
								$parent_cat_sql=' AND parent="'.(int)$k2category.'" ';
								else
								$parent_cat_sql=' AND parent="0" ';
							}
							else
							$parent_cat_sql=' ';




							$query="SELECT id from `#__k2_categories` WHERE name='".$db->getEscaped($csv_data[$csv_row_num][$column_num])."'" . $parent_cat_sql . " AND trash='0' LIMIT 1";

							$db->setQuery("SELECT id from `#__k2_categories` WHERE name='".$db->getEscaped($csv_data[$csv_row_num][$column_num])."'" . $parent_cat_sql . " AND trash='0' LIMIT 1");

							//echo "SELECT id from `#__k2_categories` WHERE name='".$db->getEscaped($csv_data[$csv_row_num][$column_num])."'" . $parent_cat_sql . " LIMIT 1";
							//die();
							$k2_sub_category['id'] = $db->loadResult();
							if ($k2_sub_category['id'] == null)
							{
								//we have create a new category, so collect the data for it
								$k2_sub_category['name'] = $csv_data[$csv_row_num][$column_num];
								if ($k2category!='take_from_csv')
								$k2_sub_category['parent_id'] = (int)$k2category;
								else
								$k2_sub_category['parent_id'] =0;
								//							$k2_sub_category =$model->createK2category($csv_data[$csv_row_num][$column_num],(int)$k2category);
							}

						}
						else
						{
							if ($k2category!='take_from_csv')
							{

								$k2_sub_category['id'] = (int)$k2category;
							}

							else
							{
								$k2_sub_category['parent_id'] =0;
								$k2_sub_category['name'] = "imported data ". date('Y-m-d H:i:s');
							}

						}
					}
					elseif ($name =='k2category_description' )
					{
						//collect the data for the category
						if (is_numeric($column_num))
						$k2_sub_category['description'] = $csv_data[$csv_row_num][$column_num];
					}
					elseif ($name =='k2category_access')
					{
						//collect the data for the category
						if (is_numeric($column_num) &&
						$csv_data[$csv_row_num][$column_num]!=0 &&
						$csv_data[$csv_row_num][$column_num]!=1 &&
						$csv_data[$csv_row_num][$column_num]!=2 )
						{
							$error_message[]='row: '. $absolute_row_num  ." the category access is not valid. Possible values are 0 for Public, 1 for Registred and 2 for Special\n";
						}


						$k2_sub_category['access'] = $csv_data[$csv_row_num][$column_num];
					}
					elseif ($name =='k2category_plugins')
					{			
						$k2_sub_category['plugins'] = $csv_data[$csv_row_num][$column_num];
					}					
					elseif ($name=='gallery')
					{
							
						if (strlen(trim($csv_data[$csv_row_num][$column_num]))>0)
						{
							$gallery=JFolder::makeSafe(trim($csv_data[$csv_row_num][$column_num]));
							$field_array['gallery']='{gallery}'.  $gallery . '{/gallery}';

						}
							
					}
					elseif ($name=='gallery_images')
					{
						if (strlen(trim($csv_data[$csv_row_num][$column_num]))>0)
						{
							$gallery_images=explode(',',$csv_data[$csv_row_num][$column_num]);
						}
						//$value=JFolder::makeSafe($value);
						//create gallery folder if it not exist
						//JFolder::create(JPATH_SITE.DS.'media'.DS.'k2'.DS.'galleries' . DS . $value);
					}
					elseif ($name=='comments')
					{
						if (strlen(trim($csv_data[$csv_row_num][$column_num]))>0)
						{
							$comments=$json->decode($csv_data[$csv_row_num][$column_num]);



						}
						else
						{
							$comments='';
						}


					}
					elseif ($name=='publish_up')
					{
						if (strlen(trim($csv_data[$csv_row_num][$column_num]))<=0)
						{
							$field_array[$name]=date('Y-m-d H:i:s');
						}
						else
						{
							$field_array[$name]= $csv_data[$csv_row_num][$column_num];
						}
					}
					elseif ($name == 'item_plugins')
					{
						$field_array['plugins']= $csv_data[$csv_row_num][$column_num];
						
					}
					
					else
					{
						$field_array[$name]= $csv_data[$csv_row_num][$column_num];

					}
				}
			}

			//we need a new category when there is no given $k2_sub_category['id']
			//and no main-category is defined or the main-category should be the parent category
			//and the category from the CSV should be a sub-category but it does not exist
			if ((!isset($k2_sub_category['id']) || $k2_sub_category['id'] == null) &&
			(!is_numeric($k2category) || $k2_sub_category['parent_id']==$k2category))
			{
				//create a new category
				if (!isset($k2_sub_category['access']) ||
				(
				$k2_sub_category['access']!=0 &&
				$k2_sub_category['access']!=1 &&
				$k2_sub_category['access']!=2))
				{
					$k2_sub_category['access']=0;
				}
					
				if (!isset($k2_sub_category['parent_id']) || !is_int($k2_sub_category['parent_id']))
				{
					$k2_sub_category['parent_id'] =0;
				}
				if (!isset($k2_sub_category['name']))
				{
					$k2_sub_category['name'] = "imported data ". date('Y-m-d H:i:s');
				}
					
				if (!isset($k2_sub_category['description']))
				{
					$k2_sub_category['description'] = "";
				}

				if (!isset($k2extrafieldgroup) || !is_int($k2extrafieldgroup))
				$k2extrafieldgroup='';

				$k2_sub_category['id'] =$model->createK2category($k2_sub_category['name'],$k2_sub_category['parent_id'],$k2_sub_category['access'],$k2_sub_category['description'],$k2extrafieldgroup,$k2_sub_category['plugins']);
			}

			$extrafield_values=array();
			$extrafield_search_values='';
			foreach ($k2fields as $k2field)
			{

				if ($k2field['extra']=='extra')
				{
					$k2field['value']=$json->decode($k2field['value']);

					$column_num=$k2importextrafields[$k2field['id']];
					if (is_numeric($column_num))
					{
						switch($k2field['type'])
						{
							case 'link':
								$csv_data[$csv_row_num][$column_num]=explode(';', $csv_data[$csv_row_num][$column_num]);
								for ($link_i=0;$link_i<count($csv_data[$csv_row_num][$column_num]); $link_i++)
								{
									$csv_data[$csv_row_num][$column_num][$link_i]=trim($csv_data[$csv_row_num][$column_num][$link_i]);
								}
								if ($csv_data[$csv_row_num][$column_num][0]=='')
								{
									$csv_data[$csv_row_num][$column_num][0]=$k2field['value'][0]->name;
									$error_message[]='row: '. $absolute_row_num  . 'no Link-Text found for ' .  $k2field['title']  . ". I will take ".$k2field['value'][0]->name." as Link-Text";
								}
								if ($csv_data[$csv_row_num][$column_num][1]=='')
								{
									$csv_data[$csv_row_num][$column_num][1]='http://www.individual-it.net';
									$error_message[]='row: '. $absolute_row_num  .' no Link-URL found for ' .  $k2field['title']  . ". I will link to the best URL in the net: http://www.individual-it.net :-)";
								}
								if ($csv_data[$csv_row_num][$column_num][2]=='' ||
								($csv_data[$csv_row_num][$column_num][2]!='same' &&
								$csv_data[$csv_row_num][$column_num][2]!='new' &&
								$csv_data[$csv_row_num][$column_num][2]!='popup' &&
								$csv_data[$csv_row_num][$column_num][2]!='lightbox'))
								{
									$csv_data[$csv_row_num][$column_num][2]=$k2field['value'][0]->target;
									$error_message[]='row: '. $absolute_row_num  .' no or not valid Link-Target found for ' .  $k2field['title']  . ". I will take ".$k2field['value'][0]->target." as Link-Target. Possible values are: same,new,popup and lightbox \n";
								}
									
								array_push($extrafield_values, array('id'=>$k2field['id'], 'value'=>array($csv_data[$csv_row_num][$column_num][0],$csv_data[$csv_row_num][$column_num][1], $csv_data[$csv_row_num][$column_num][2])));
									
								break;
							case 'select':
							case 'radio':
								$select_error=true;
								//$select_values=explode("},{",$k2field['value']);
								$select_error=true;
								foreach ( $k2field['value'] as $select_k2field)
								{
									if ($select_k2field->name == trim($csv_data[$csv_row_num][$column_num]))
									{
										array_push($extrafield_values,array('id'=>$k2field['id'],'value'=>(string)$select_k2field->value) );
										$select_error=false;
									}

								}
									
									
								if ($select_error)
								$error_message[]='row: '. $absolute_row_num  . ' no association found for ' .  $k2field['title']  ;
								break;
									
							case 'multipleSelect':
								$select_error=true;
								$multipleSelect_csv_data=explode (",",$csv_data[$csv_row_num][$column_num]);
								$multipleSelect_values=array();

								//trim all values inside the array
								array_walk($multipleSelect_csv_data , create_function('&$temp', '$temp = trim($temp);'));

								foreach ( $k2field['value'] as $select_k2field)
								{

									if (in_array($select_k2field->name,$multipleSelect_csv_data))
									{
										array_push($multipleSelect_values,(string)$select_k2field->value );
										$select_error=false;
									}

								}
								if (sizeof($multipleSelect_values)>0)
								{
									array_push($extrafield_values,array('id'=>$k2field['id'],'value'=>$multipleSelect_values) );
								}
								if ($select_error)
								{
									$error_message[]='row: '. $absolute_row_num  . 'no association found for ' .  $k2field['title'] ;
								}
								break;
							default:


								if (!isset($csv_data[$csv_row_num][$column_num]) || strlen($csv_data[$csv_row_num][$column_num])<=0)
								{
									if (isset($k2field['value']['value']))
									{
										$csv_data[$csv_row_num][$column_num]=$k2field['value']['value'];
									}
									else
									{
										$csv_data[$csv_row_num][$column_num]='';
									}
								}


									
								array_push($extrafield_values,array('id'=>$k2field['id'],'value'=>$csv_data[$csv_row_num][$column_num] ));

								//print_r($extrafield_values);
								break;

						}
						$extrafield_search_values.= " " . $csv_data[$csv_row_num][$column_num];
							
					}


				}

			}

			//so what category should we use for importing?
			if (is_numeric($k2category) && (!isset($k2_sub_category['id']) || !is_numeric($k2_sub_category['id'])))
			{
				$k2_sub_category['id']=$k2category;
			}




			$return_save=$model->save($overwrite,$field_array,$k2_sub_category['id'],$json->encode($extrafield_values),$extrafield_search_values,$tags,$gallery,$gallery_images,$comments,$id_from_csv,$should_we_import_the_id);


			if ($return_save!='no error')
			{

				$error_message[]='row: '. $absolute_row_num  . ' - save item - ' . $return_save;
			}



			if (isset($image_name) && trim ($image_name)!='')
			{
				$return_image=$model->saveImage($image_name);


				if ($return_image!='no error')
				{
					$error_message[]='row: '. $absolute_row_num  . ' - save image - ' .$return_image;
				}
			}




			if (isset($attachments_names[0]) && $attachments_names[0]!='')
			{
				$return_attachments=$model->saveAttachments($attachments_names,$attachments_titles,$attachments_title_attributes);
				if ($return_attachments!='no error')
				{
					//if ($error_message!='')
					//$error_message[]="\n";

					$error_message[]='row: '. $absolute_row_num  . ' - save attachments - ' .$return_attachments;
				}
			}





		}


		//$returnURL = JURI::base().'index.php?option=com_k2import';

		//we can clean up

		if ($return->finish)
		{
			$import_tmp_dir=$mainframe->getCfg('tmp_path').DS.'k2_import';
			if( JFolder::exists($import_tmp_dir) )
			{
				JFolder::delete($import_tmp_dir);
			}


			$fDestName= $mainframe->getCfg('tmp_path') . DS . $file;

			if( JFile::exists($fDestName) )
			{
				JFile::delete($fDestName);
			}
		}

		$return->error_message=$error_message;




		$return->memory=(memory_get_peak_usage(true) / 1024 / 1024) . " MB";

		$return= $json->encode($return);

		$document = &JFactory::getDocument();
		$doc = &JDocument::getInstance('json');
		$doc->setMimeEncoding("application/json");


		$document = $doc;
		$view = & $this->getView( 'import', 'json' );

		$view->assignRef('return',$return);

		$view->display();


	}

	function associate()
	{

		$view = & $this->getView( 'associate', 'html' );
		$view->setModel( $this->getModel( 'k2import' ), true );
		$view->display();


	}

	function selectcategory()
	{
		$view = & $this->getView( 'selectcategory', 'html' );
		$view->setModel( $this->getModel( 'k2import' ), true );
			

			
		$view->display();


	}

	/**
	 * 
	 * Uploads a file if the upload is used
	 * copy the file to the tmp directory if a local file is used
	 * unpack a packed file is used
	 */
	function upload()
	{

		$mainframe = JFactory::getApplication();
		jimport('joomla.filesystem.file');


		//Retrieve file details from uploaded file, sent from upload form:
		$uploaded_file = JRequest::getVar('uploaded_file', null, 'files', 'array');

		//do we use an existing file on server or an uploades file?
		if (empty($uploaded_file['name']))
		{
			$existing_file = JRequest::getVar('existing_file', null, 'post', 'string');
			
			$existing_file_name = JFile::makeSafe(Jfile::getname(Jfile::stripExt($existing_file)));
			$existing_file_extension = JFile::makeSafe(JFile::getExt($existing_file));
			$existing_file_folder_name=JFolder::makeSafe(strstr($existing_file,$existing_file_name,true));
			
			
			// $mainframe->getCfg('tmp_path') . DS . JFile::makeSafe(JFile::getName($existing_file));
			$tempfile=tempnam($mainframe->getCfg('tmp_path'), $existing_file_name);
			$fDestName=$tempfile . "." . $existing_file_extension;
			
			//we don't need the file itseld, we just need the name
			unlink ($tempfile);
			
			JFile::copy(JPATH_ROOT . $existing_file_folder_name . $existing_file_name . "." .$existing_file_extension,$fDestName);
			

		}
		else
		{
			$fDestName= $mainframe->getCfg('tmp_path') . DS . JFile::makeSafe($uploaded_file['name']);
			$fNameTmp = $uploaded_file['tmp_name'];

			if ( !JFile::upload($fNameTmp, $fDestName))
			{
				//Display the back button:
				JToolBarHelper::back();

				$this->setError( 'The file could not be uploaded.');
			}
		}

		if (count($this->getErrors()==0))
		{

			$returnURL = JURI::base().'index.php?option=com_k2import&task=selectcategory&file='.JFile::getName($fDestName);




			if (strtolower(JFile::getExt($fDestName))!='csv')
			{
				//we will try to use the file as archive
				jimport('joomla.filesystem.archive');

				$import_tmp_dir=$mainframe->getCfg('tmp_path').DS.'k2_import';

				if( JArchive::extract($fDestName, $import_tmp_dir) )
				{
					$csv_files = JFolder::files($import_tmp_dir, '.csv');

					foreach ($csv_files as $csv_file)
					{
						if( ! JFile::move($csv_file, JFile::makeSafe($csv_file), $import_tmp_dir) )
						{
							$this->setError( 'The file '. $csv_file.' could not be renamed.');

							return false;
						}
					}

					$this->setRedirect($returnURL.'&modus=archive','The file was successful uploaded and extracted');
				}
				else
				{
					JToolBarHelper::back();

					$this->setError( 'The file could not be extracted.');
				}
			}
			else
			{
				$this->setRedirect($returnURL,'The file was successful uploaded');
			}

		}
			
		
	}





	function export()
	{
		$document = &JFactory::getDocument();
		if(version_compare(JVERSION,'1.6.0','ge')) {
			$document->setMimeEncoding( 'text/csv' );
		}
		else 
		{
			$doc = &JDocument::getInstance('raw');
			$doc->setMimeEncoding( 'text/csv' );
			$document = $doc;
		}
		
		$categories_to_export = JRequest::getVar( 'cid', '', 'post', 'array' );
		
//		print_r($categories_to_export);
		
//		$document->setMimeEncoding( 'text/plain' );
		
		
		$view = & $this->getView( 'export', 'raw' );
		$view->setModel( $this->getModel( 'k2import' ), true );
		JResponse::setHeader( 'Content-Disposition', 'attachment; filename="'.$view->getName().'.csv"' );


		$model =& $this->getModel();
		
		$view->assignRef( 'max_attachments', $model->get_max_attachments($categories_to_export) );
		$view->assignRef( 'all_extrafields', $model->get_all_extrafields($categories_to_export) );
		$view->assignRef( 'items', $model->export($categories_to_export) );
		

		$view->display();


	}

	function filebrowser(){
		$view = & $this->getView('k2import', 'html');
		$view->setLayout('filebrowser');
		$view->filebrowser();

	}

}
?>
