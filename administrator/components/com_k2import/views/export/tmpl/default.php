<?php defined('_JEXEC') or die('Restricted access');


$csv='"ID","Title","Alias","Introtext","Fulltext","Tags","Published","Publish Up","Publish Down","Access","Trash","Created","User ID","Video",'.
	 '"Ordering","Featured","Featured ordering","Image caption","Image credits",'.
	 '"Video caption","Video credits","Gallery Name","Images for the Gallery","Meta Description","Meta Data","Meta Keywords",'.
	 '"Item Plugins",'.
	 '"Category Name",'.
	 '"Category Description",'.
	 '"Category Access",'.
	 '"Category Plugins",'.
	 '"Comments"';
for ($i=1;$i<=$this->max_attachments;$i++)
{

	$csv.= ',"Attachment'.$i.'","Attachment Title '.$i.'","Attachment Title Attribute '.$i.'"';
}

foreach ($this->all_extrafields as $extra_field)
{

	$csv.= ',"'.$extra_field->name.'"';

}

//print_r($this->items);

foreach ($this->items as $item)
{

	if (!isset($item->introtext))
	$item->introtext='';

	if (!isset($item->fulltext))
	$item->fulltext='';

	if (!isset($item->alias))
	$item->alias='';

	if (!isset($item->ordering))
	$item->ordering='';

	if (!isset($item->featured))
	$item->featured='';

	if (!isset($item->featured_ordering))
	$item->featured_ordering='';

	if (!isset($item->gallery_images))
	$item->gallery_images='';

	if (!isset($item->image_caption))
	$item->image_caption='';

	if (!isset($item->image_credits))
	$item->image_credits='';

	if (!isset($item->video))
	$item->video='';

	if (!isset($item->video_credits))
	$item->video_credits='';

	if (!isset($item->video_caption))
	$item->video_caption='';

	if (!isset($item->gallery))
	$item->gallery='';

	if (!isset($item->gallery_images))
	$item->gallery_images='';

	if (!isset($item->metadesc))
	$item->metadesc='';

	if (!isset($item->metadata))
	$item->metadata='';

	if (!isset($item->metakey))
	$item->metakey='';

	if (!isset($item->comment))
	$item->comment='';

	if (!isset($item->published))
	$item->published='';


	if (!isset($item->publish_up))
	$item->publish_up='';

	if (!isset($item->publish_down))
	$item->publish_down='';

	if (!isset($item->access))
	$item->access='';

	if (!isset($item->trash))
	$item->trash='';	
	
	if (!isset($item->created))
	$item->created='';

	if (!isset($item->created_by))
	$item->created_by='';

	$csv.= "\r\n\"".$item->id .'","' .$item->title .'","' .$item->alias.'","' .$item->introtext .'","'.$item->fulltext .'","';

	$first_tag=true;
	if (isset($item->tags) && count($item->tags)>0)
	{
		foreach ($item->tags as $tag) {
			if (!$first_tag)
			$csv.= ",";

			$csv.= $tag->name;
			$first_tag=false;
		}
	}




	$csv.= '","'.$item->published.'","'.$item->publish_up.'","'.$item->publish_down.'","'.$item->access . '","'.$item->trash . '","' .$item->created .'","' .$item->created_by .'","' .$item->video.'","' .
	$item->ordering.'","' .$item->featured .'","' .$item->featured_ordering.'","' .
	$item->image_caption .'","' .$item->image_credits .'","' .$item->video_caption.'","' .
	$item->video_credits .'","' . $item->gallery .'","' . $item->gallery_images .'","' . $item->metadesc .'","' .
	$item->metadata .'","' . $item->metakey . '","' . $item->item_plugins . '","' . $item->category_name . '","' .
	$item->category_description .'","' .$item->category_access.'","' .$item->category_plugins.'","' .$item->comment.'"';


	$count_attachment=0;
	if (isset($item->attachments) && count($item->attachments)>0)
	{
			


		foreach ($item->attachments as $attachment)
		{
			$csv.= ',"'. $attachment->filename .'","'. $attachment->title .'","'. $attachment->titleAttribute .'"';
			$count_attachment++;
		}
	}

	for ($count_attachment=$count_attachment;$count_attachment<$this->max_attachments;$count_attachment++)
	{

		$csv.= ',,,';
	}


	//print_r($item);

	foreach ($this->all_extrafields as $extra_field)
	{
		$found_extra_field=false;
		if (isset($item->extra_fields) && count($item->extra_fields)>0)
		{
			for ($i=0;$i<count($item->extra_fields);$i++)
			{

				//if there is no set type, then there are extra-fields in the item, but the category
				// is not associated with the extra-field groub, so we don't show them
				if (isset($item->extra_fields[$i]->type) && $extra_field->id==$item->extra_fields[$i]->id && $item->extra_fields[$i]->type!='link')
				{
					$csv.= ',"';

					if (isset($item->extra_fields[$i]->entry) && is_array($item->extra_fields[$i]->entry))
					{
						$first_entry=true;
						foreach ($item->extra_fields[$i]->entry as $entry)
						{
							if (!$first_entry)
							$csv.=",";

							$csv.=$entry;
							$first_entry=false;

						}
					}
					elseif (isset($item->extra_fields[$i]->entry))
					{
						$csv.=$item->extra_fields[$i]->entry;
					}
					elseif (isset($item->extra_fields[$i]->value))
					{
						$csv.=$item->extra_fields[$i]->value;
					}

					$csv.='"';
					$found_extra_field=true;
				}
				elseif (isset($item->extra_fields[$i]->type) &&  $extra_field->id==$item->extra_fields[$i]->id && $item->extra_fields[$i]->type=='link')
				{
					$csv.=',"'.$item->extra_fields[$i]->value[0] . ";" . $item->extra_fields[$i]->value[1]. ";" . $item->extra_fields[$i]->value[2] . '"';
					$found_extra_field=true;
				}



			}
		}

		if (!$found_extra_field)
		{
			$csv.=',';
		}


	}


}

echo $csv;


?>

