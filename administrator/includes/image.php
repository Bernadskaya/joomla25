<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image
 * class to help upload files in different sizes and create titles.
 * @author nataliabernadskaya
 */
class image extends K2ModelItem {
    public $imageSize = array(imageXSmall,imageSmall,imageMedium,imageLarge,imageXLarge);
    protected $sizes = array('XL', 'L', 'M', 'S', 'XS');
    protected $imageNumber = array (1,2,3,4,5);
    function __construct ($imageTitle) {
        $this->imageTitle = $imageTitle;
    }
    function createImageTitle(){
        $src = md5("Image".$item['ref_id']);
        $target = md5("Image".$row->id);
        //$sizes = array('XL', 'L', 'M', 'S', 'XS');
        $savepath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache';
        foreach($sizes as $size)
        {
            if(JFile::exists($savepath.DS.$src.'_'.$size.'.jpg') && !JFile::exists($savepath.DS.$target.'_'.$size.'.jpg'))
            {
                JFile::copy($savepath.DS.$src.'_'.$size.'.jpg', $savepath.DS.$target.'_'.$size.'.jpg');
            }
        }
        }
    
    
                $item->imageXSmall = '';
		$item->imageSmall = '';
		$item->imageMedium = '';
		$item->imageLarge = '';
		$item->imageXLarge = '';

		$date = JFactory::getDate($item->modified);
		$timestamp = '?t='.$date->toUnix();

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XS.jpg'))
		{
			$item->imageXSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XS.jpg';
			if ($params->get('imageTimestamp'))
			{
				$item->imageXSmall .= $timestamp;
			}
		}

		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg'))
		{
			$item->imageSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
			if ($params->get('imageTimestamp'))
			{
				$item->imageSmall .= $timestamp;
			}
		}
    
    
    function imageUpload(){}
    function createThumb(){}
    function displayImagesInItem(){}
}

?>