<?php
/**
 * @version		$Id: item.php 1766 2012-11-22 14:10:24Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>
<?php if($this->item->params->get('itemImage') && !empty($this->item->image)): ?>
	  <!-- Item Image -->
      <div class="itemImageBlock">
        <span class="itemImage">
		    <a class="modal" rel="{handler: 'image'}" href="<?php echo $this->item->imageXLarge; ?>" title="<?php echo JText::_('K2_CLICK_TO_PREVIEW_IMAGE'); ?>">
	    		<img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
		  	</a>
    	  </span>

		  <?php if($this->item->params->get('itemImageMainCaption') && !empty($this->item->image_caption)): ?>
		  <!-- Image caption -->
		  <span class="itemImageCaption"><?php echo $this->item->image_caption; ?></span>
		  <?php endif; ?>

		  <?php if($this->item->params->get('itemImageMainCredits') && !empty($this->item->image_credits)): ?>
		  <!-- Image credits -->
		  <span class="itemImageCredits"><?php echo $this->item->image_credits; ?></span>
		  <?php endif; ?>

		  <div class="clr"></div>
	  </div>
	  <?php endif; ?>
          

				<h1><a href="index.html">Galleriffic</a></h1>
				<h2>Thumbnail rollover effects and slideshow crossfades</h2>

				<!-- Start Advanced Gallery Html Containers -->
				
					<div id="controls"></div>
					<div id="loading" class="loader"></div>
					<div id="slideshow" class="slideshow"></div>
					<div id="caption" class="caption-container"></div>
				
				<div id="thumbs" class="navigation">
					<ul class="thumbs noscript">
						<li>
							<a class="thumb" name="leaf" href="http://localhost:8888/joomla25/media/k2/items/cache/91b1b90c684fd8e5c2ec1b7418ca380f_m.jpg" title="Title #0">
								<img src="http://localhost:8888/joomla25/media/k2/items/cache/91b1b90c684fd8e5c2ec1b7418ca380f_xs.jpg" alt="Title #0" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #0</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" name="drop" href="http://farm3.static.flickr.com/2404/2538171134_2f77bc00d9.jpg" title="Title #1">
								<img src="http://farm3.static.flickr.com/2404/2538171134_2f77bc00d9_s.jpg" alt="Title #1" />
							</a>
							<div class="caption">
								Any html can be placed here ...
							</div>
						</li>

						<li>
							<a class="thumb" name="bigleaf" href="http://farm3.static.flickr.com/2093/2538168854_f75e408156.jpg" title="Title #2">
								<img src="http://farm3.static.flickr.com/2093/2538168854_f75e408156_s.jpg" alt="Title #2" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2093/2538168854_f75e408156_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #2</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" name="lizard" href="http://farm4.static.flickr.com/3153/2538167690_c812461b7b.jpg" title="Title #3">
								<img src="http://farm4.static.flickr.com/3153/2538167690_c812461b7b_s.jpg" alt="Title #3" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3153/2538167690_c812461b7b_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #3</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18.jpg" title="Title #4">
								<img src="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18_s.jpg" alt="Title #4" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3150/2538167224_0a6075dd18_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #4</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3204/2537348699_bfd38bd9fd.jpg" title="Title #5">
								<img src="http://farm4.static.flickr.com/3204/2537348699_bfd38bd9fd_s.jpg" alt="Title #5" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3204/2537348699_bfd38bd9fd_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #5</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3124/2538164582_b9d18f9d1b.jpg" title="Title #6">
								<img src="http://farm4.static.flickr.com/3124/2538164582_b9d18f9d1b_s.jpg" alt="Title #6" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3124/2538164582_b9d18f9d1b_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #6</div>
								<div class="image-desc">Description</div>
							</div>
						</li>
					</ul>
				</div>
				<div style="clear: both;"></div>
			
		<div id="footer">&copy; 2009 Trent Foley</div>
	