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

<?php if(JRequest::getInt('print')==1): ?>
<!-- Print button at the top of the print page only -->
<a class="itemPrintThisPage" rel="nofollow" href="#" onclick="window.print();return false;">
	<span><?php echo JText::_('K2_PRINT_THIS_PAGE'); ?></span>
</a>
<?php endif; ?>

<!-- Start K2 Item Layout -->
<span id="startOfPageId<?php echo JRequest::getInt('id'); ?>"></span>

<div id="k2Container" class="itemView<?php echo ($this->item->featured) ? ' itemIsFeatured' : ''; ?><?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">

	<!-- Plugins: BeforeDisplay -->
	<?php echo $this->item->event->BeforeDisplay; ?>

	<!-- K2 Plugins: K2BeforeDisplay -->
	<?php echo $this->item->event->K2BeforeDisplay; ?>
<div id="fineuploader">
    <noscript>
        <p>Please enable JavaScript to use Fine Uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>
</div>
        <script type="text/javascript">
            $(document).ready(function () {
        $('#fineuploader').fineUploader({
          request: {
            endpoint: '/images'
          }
        });
      });
      </script>
	<div class="itemHeader">

		<?php if($this->item->params->get('itemDateCreated')): ?>
		<!-- Date created -->
		<span class="itemDateCreated">
			<?php echo JHTML::_('date', $this->item->created , JText::_('K2_DATE_FORMAT_LC2')); ?>
		</span>
		<?php endif; ?>

	  <?php if($this->item->params->get('itemTitle')): ?>
	  <!-- Item title -->
	  <h2 class="itemTitle">
			<?php if(isset($this->item->editLink)): ?>
			<!-- Item edit link -->
			<span class="itemEditLink">
				<a class="modal" rel="{handler:'iframe',size:{x:990,y:550}}" href="<?php echo $this->item->editLink; ?>">
					<?php echo JText::_('K2_EDIT_ITEM'); ?>
				</a>
			</span>
			<?php endif; ?>

	  	<?php echo $this->item->title; ?>

	  	<?php if($this->item->params->get('itemFeaturedNotice') && $this->item->featured): ?>
	  	<!-- Featured flag -->
	  	<span>
		  	<sup>
		  		<?php echo JText::_('K2_FEATURED'); ?>
		  	</sup>
	  	</span>
	  	<?php endif; ?>

	  </h2>
	  <?php endif; ?>

		<?php if($this->item->params->get('itemAuthor')): ?>
		<!-- Item Author -->
		<span class="itemAuthor">
			<?php echo K2HelperUtilities::writtenBy($this->item->author->profile->gender); ?>&nbsp;
			<?php if(empty($this->item->created_by_alias)): ?>
			<a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
			<?php else: ?>
			<?php echo $this->item->author->name; ?>
			<?php endif; ?>
		</span>
		<?php endif; ?>

  </div>

  <!-- Plugins: AfterDisplayTitle -->
  <?php echo $this->item->event->AfterDisplayTitle; ?>

  <!-- K2 Plugins: K2AfterDisplayTitle -->
  <?php echo $this->item->event->K2AfterDisplayTitle; ?>

	<?php if(
		$this->item->params->get('itemFontResizer') ||
		$this->item->params->get('itemPrintButton') ||
		$this->item->params->get('itemEmailButton') ||
		$this->item->params->get('itemSocialButton') ||
		$this->item->params->get('itemVideoAnchor') ||
		$this->item->params->get('itemImageGalleryAnchor') ||
		$this->item->params->get('itemCommentsAnchor')
	): ?>
  <div class="itemToolbar">
		<ul>
			<?php if($this->item->params->get('itemFontResizer')): ?>
			<!-- Font Resizer -->
			<li>
				<span class="itemTextResizerTitle"><?php echo JText::_('K2_FONT_SIZE'); ?></span>
				<a href="#" id="fontDecrease">
					<span><?php echo JText::_('K2_DECREASE_FONT_SIZE'); ?></span>
					<img src="<?php echo JURI::root(true); ?>/components/com_k2/images/system/blank.gif" alt="<?php echo JText::_('K2_DECREASE_FONT_SIZE'); ?>" />
				</a>
				<a href="#" id="fontIncrease">
					<span><?php echo JText::_('K2_INCREASE_FONT_SIZE'); ?></span>
					<img src="<?php echo JURI::root(true); ?>/components/com_k2/images/system/blank.gif" alt="<?php echo JText::_('K2_INCREASE_FONT_SIZE'); ?>" />
				</a>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemPrintButton') && !JRequest::getInt('print')): ?>
			<!-- Print Button -->
			<li>
				<a class="itemPrintLink" rel="nofollow" href="<?php echo $this->item->printLink; ?>" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;">
					<span><?php echo JText::_('K2_PRINT'); ?></span>
				</a>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemEmailButton') && !JRequest::getInt('print')): ?>
			<!-- Email Button -->
			<li>
				<a class="itemEmailLink" rel="nofollow" href="<?php echo $this->item->emailLink; ?>" onclick="window.open(this.href,'emailWindow','width=400,height=350,location=no,menubar=no,resizable=no,scrollbars=no'); return false;">
					<span><?php echo JText::_('K2_EMAIL'); ?></span>
				</a>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemSocialButton') && !is_null($this->item->params->get('socialButtonCode', NULL))): ?>
			<!-- Item Social Button -->
			<li>
				<?php echo $this->item->params->get('socialButtonCode'); ?>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemVideoAnchor') && !empty($this->item->video)): ?>
			<!-- Anchor link to item video below - if it exists -->
			<li>
				<a class="itemVideoLink k2Anchor" href="<?php echo $this->item->link; ?>#itemVideoAnchor"><?php echo JText::_('K2_MEDIA'); ?></a>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemImageGalleryAnchor') && !empty($this->item->gallery)): ?>
			<!-- Anchor link to item image gallery below - if it exists -->
			<li>
				<a class="itemImageGalleryLink k2Anchor" href="<?php echo $this->item->link; ?>#itemImageGalleryAnchor"><?php echo JText::_('K2_IMAGE_GALLERY'); ?></a>
			</li>
			<?php endif; ?>

			<?php if($this->item->params->get('itemCommentsAnchor') && $this->item->params->get('itemComments') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
			<!-- Anchor link to comments below - if enabled -->
			<li>
				<?php if(!empty($this->item->event->K2CommentsCounter)): ?>
					<!-- K2 Plugins: K2CommentsCounter -->
					<?php echo $this->item->event->K2CommentsCounter; ?>
				<?php else: ?>
					<?php if($this->item->numOfComments > 0): ?>
					<a class="itemCommentsLink k2Anchor" href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
						<span><?php echo $this->item->numOfComments; ?></span> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
					</a>
					<?php else: ?>
					<a class="itemCommentsLink k2Anchor" href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
						<?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?>
					</a>
					<?php endif; ?>
				<?php endif; ?>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clr"></div>
  </div>
	<?php endif; ?>

	<?php if($this->item->params->get('itemRating')): ?>
	<!-- Item Rating -->
	<div class="itemRatingBlock">
		<span><?php echo JText::_('K2_RATE_THIS_ITEM'); ?></span>
		<div class="itemRatingForm">
			<ul class="itemRatingList">
				<li class="itemCurrentRating" id="itemCurrentRating<?php echo $this->item->id; ?>" style="width:<?php echo $this->item->votingPercentage; ?>%;"></li>
				<li><a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a></li>
				<li><a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a></li>
				<li><a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a></li>
				<li><a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a></li>
				<li><a href="#" rel="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a></li>
			</ul>
			<div id="itemRatingLog<?php echo $this->item->id; ?>" class="itemRatingLog"><?php echo $this->item->numOfvotes; ?></div>
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

  <div class="itemBody">

	  <!-- Plugins: BeforeDisplayContent -->
	  <?php echo $this->item->event->BeforeDisplayContent; ?>

	  <!-- K2 Plugins: K2BeforeDisplayContent -->
	  <?php echo $this->item->event->K2BeforeDisplayContent; ?>

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

	  <?php if(!empty($this->item->fulltext)): ?>
	  <?php if($this->item->params->get('itemIntroText')): ?>
	  <!-- Item introtext -->
	  <div class="itemIntroText">
	  	<?php echo $this->item->introtext; ?>
	  </div>
	  <?php endif; ?>
	  <?php if($this->item->params->get('itemFullText')): ?>
	  <!-- Item fulltext -->
	  <div class="itemFullText">
	  	<?php echo $this->item->fulltext; ?>
	  </div>
	  <?php endif; ?>
	  <?php else: ?>
	  <!-- Item text -->
	  <div class="itemFullText">
	  	<?php echo $this->item->introtext; ?>
	  </div>
	  <?php endif; ?>

		<div class="clr"></div>

	  <?php if($this->item->params->get('itemExtraFields') && count($this->item->extra_fields)): ?>
	  <!-- Item extra fields -->
	  <div class="itemExtraFields">
	  	<h3><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h3>
	  	<ul>
			<?php foreach ($this->item->extra_fields as $key=>$extraField): ?>
			<?php if($extraField->value != ''): ?>
			<li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
				<?php if($extraField->type == 'header'): ?>
				<h4 class="itemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
				<?php else: ?>
				<span class="itemExtraFieldsLabel"><?php echo $extraField->name; ?>:</span>
				<span class="itemExtraFieldsValue"><?php echo $extraField->value; ?></span>
				<?php endif; ?>
			</li>
			<?php endif; ?>
			<?php endforeach; ?>
			</ul>
	    <div class="clr"></div>
	  </div>
	  <?php endif; ?>

		<?php if($this->item->params->get('itemHits') || ($this->item->params->get('itemDateModified') && intval($this->item->modified)!=0)): ?>
		<div class="itemContentFooter">

			<?php if($this->item->params->get('itemHits')): ?>
			<!-- Item Hits -->
			<span class="itemHits">
				<?php echo JText::_('K2_READ'); ?> <b><?php echo $this->item->hits; ?></b> <?php echo JText::_('K2_TIMES'); ?>
			</span>
			<?php endif; ?>

			<?php if($this->item->params->get('itemDateModified') && intval($this->item->modified)!=0): ?>
			<!-- Item date modified -->
			<span class="itemDateModified">
				<?php echo JText::_('K2_LAST_MODIFIED_ON'); ?> <?php echo JHTML::_('date', $this->item->modified, JText::_('K2_DATE_FORMAT_LC2')); ?>
			</span>
			<?php endif; ?>

			<div class="clr"></div>
		</div>
		<?php endif; ?>

	  <!-- Plugins: AfterDisplayContent -->
	  <?php echo $this->item->event->AfterDisplayContent; ?>

	  <!-- K2 Plugins: K2AfterDisplayContent -->
	  <?php echo $this->item->event->K2AfterDisplayContent; ?>

	  <div class="clr"></div>
  </div>

	<?php if($this->item->params->get('itemTwitterButton',1) || $this->item->params->get('itemFacebookButton',1) || $this->item->params->get('itemGooglePlusOneButton',1)): ?>
	<!-- Social sharing -->
	<div class="itemSocialSharing">

		<?php if($this->item->params->get('itemTwitterButton',1)): ?>
		<!-- Twitter Button -->
		<div class="itemTwitterButton">
			<a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal"<?php if($this->item->params->get('twitterUsername')): ?> data-via="<?php echo $this->item->params->get('twitterUsername'); ?>"<?php endif; ?>>
				<?php echo JText::_('K2_TWEET'); ?>
			</a>
			<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
		</div>
		<?php endif; ?>

		<?php if($this->item->params->get('itemFacebookButton',1)): ?>
		<!-- Facebook Button -->
		<div class="itemFacebookButton">
			<div id="fb-root"></div>
			<script type="text/javascript">
				(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
			<div class="fb-like" data-send="false" data-width="200" data-show-faces="true"></div>
		</div>
		<?php endif; ?>

		<?php if($this->item->params->get('itemGooglePlusOneButton',1)): ?>
		<!-- Google +1 Button -->
		<div class="itemGooglePlusOneButton">
			<g:plusone annotation="inline" width="120"></g:plusone>
			<script type="text/javascript">
			  (function() {
			  	window.___gcfg = {lang: 'en'}; // Define button default language here
			    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			    po.src = 'https://apis.google.com/js/plusone.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>
		<?php endif; ?>

		<div class="clr"></div>
	</div>
	<?php endif; ?>

  <?php if($this->item->params->get('itemCategory') || $this->item->params->get('itemTags') || $this->item->params->get('itemAttachments')): ?>
  <div class="itemLinks">

		<?php if($this->item->params->get('itemCategory')): ?>
		<!-- Item category -->
		<div class="itemCategory">
			<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
			<a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
		</div>
		<?php endif; ?>

	  <?php if($this->item->params->get('itemTags') && count($this->item->tags)): ?>
	  <!-- Item tags -->
	  <div class="itemTagsBlock">
		  <span><?php echo JText::_('K2_TAGGED_UNDER'); ?></span>
		  <ul class="itemTags">
		    <?php foreach ($this->item->tags as $tag): ?>
		    <li><a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a></li>
		    <?php endforeach; ?>
		  </ul>
		  <div class="clr"></div>
	  </div>
	  <?php endif; ?>

	  <?php if($this->item->params->get('itemAttachments') && count($this->item->attachments)): ?>
	  <!-- Item attachments -->
	  <div class="itemAttachmentsBlock">
		  <span><?php echo JText::_('K2_DOWNLOAD_ATTACHMENTS'); ?></span>
		  <ul class="itemAttachments">
		    <?php foreach ($this->item->attachments as $attachment): ?>
		    <li>
			    <a title="<?php echo K2HelperUtilities::cleanHtml($attachment->titleAttribute); ?>" href="<?php echo $attachment->link; ?>"><?php echo $attachment->title; ?></a>
			    <?php if($this->item->params->get('itemAttachmentsCounter')): ?>
			    <span>(<?php echo $attachment->hits; ?> <?php echo ($attachment->hits==1) ? JText::_('K2_DOWNLOAD') : JText::_('K2_DOWNLOADS'); ?>)</span>
			    <?php endif; ?>
		    </li>
		    <?php endforeach; ?>
		  </ul>
	  </div>
	  <?php endif; ?>

		<div class="clr"></div>
  </div>
  <?php endif; ?>

  <?php if($this->item->params->get('itemAuthorBlock') && empty($this->item->created_by_alias)): ?>
  <!-- Author Block -->
  <div class="itemAuthorBlock">

  	<?php if($this->item->params->get('itemAuthorImage') && !empty($this->item->author->avatar)): ?>
  	<img class="itemAuthorAvatar" src="<?php echo $this->item->author->avatar; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($this->item->author->name); ?>" />
  	<?php endif; ?>

    <div class="itemAuthorDetails">
      <h3 class="itemAuthorName">
      	<a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
      </h3>

      <?php if($this->item->params->get('itemAuthorDescription') && !empty($this->item->author->profile->description)): ?>
      <p><?php echo $this->item->author->profile->description; ?></p>
      <?php endif; ?>

      <?php if($this->item->params->get('itemAuthorURL') && !empty($this->item->author->profile->url)): ?>
      <span class="itemAuthorUrl"><?php echo JText::_('K2_WEBSITE'); ?> <a rel="me" href="<?php echo $this->item->author->profile->url; ?>" target="_blank"><?php echo str_replace('http://','',$this->item->author->profile->url); ?></a></span>
      <?php endif; ?>

      <?php if($this->item->params->get('itemAuthorEmail')): ?>
      <span class="itemAuthorEmail"><?php echo JText::_('K2_EMAIL'); ?> <?php echo JHTML::_('Email.cloak', $this->item->author->email); ?></span>
      <?php endif; ?>

			<div class="clr"></div>

			<!-- K2 Plugins: K2UserDisplay -->
			<?php echo $this->item->event->K2UserDisplay; ?>

    </div>
    <div class="clr"></div>
  </div>
  <?php endif; ?>

  <?php if($this->item->params->get('itemAuthorLatest') && empty($this->item->created_by_alias) && isset($this->authorLatestItems)): ?>
  <!-- Latest items from author -->
	<div class="itemAuthorLatest">
		<h3><?php echo JText::_('K2_LATEST_FROM'); ?> <?php echo $this->item->author->name; ?></h3>
		<ul>
			<?php foreach($this->authorLatestItems as $key=>$item): ?>
			<li class="<?php echo ($key%2) ? "odd" : "even"; ?>">
				<a href="<?php echo $item->link ?>"><?php echo $item->title; ?></a>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	<?php
	/*
	Note regarding 'Related Items'!
	If you add:
	- the CSS rule 'overflow-x:scroll;' in the element div.itemRelated {…} in the k2.css
	- the class 'k2Scroller' to the ul element below
	- the classes 'k2ScrollerElement' and 'k2EqualHeights' to the li element inside the foreach loop below
	- the style attribute 'style="width:<?php echo $item->imageWidth; ?>px;"' to the li element inside the foreach loop below
	...then your Related Items will be transformed into a vertical-scrolling block, inside which, all items have the same height (equal column heights). This can be very useful if you want to show your related articles or products with title/author/category/image etc., which would take a significant amount of space in the classic list-style display.
	*/
	?>

  <?php if($this->item->params->get('itemRelated') && isset($this->relatedItems)): ?>
  <!-- Related items by tag -->
	<div class="itemRelated">
		<h3><?php echo JText::_("K2_RELATED_ITEMS_BY_TAG"); ?></h3>
		<ul>
			<?php foreach($this->relatedItems as $key=>$item): ?>
			<li class="<?php echo ($key%2) ? "odd" : "even"; ?>">

				<?php if($this->item->params->get('itemRelatedTitle', 1)): ?>
				<a class="itemRelTitle" href="<?php echo $item->link ?>"><?php echo $item->title; ?></a>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedCategory')): ?>
				<div class="itemRelCat"><?php echo JText::_("K2_IN"); ?> <a href="<?php echo $item->category->link ?>"><?php echo $item->category->name; ?></a></div>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedAuthor')): ?>
				<div class="itemRelAuthor"><?php echo JText::_("K2_BY"); ?> <a rel="author" href="<?php echo $item->author->link; ?>"><?php echo $item->author->name; ?></a></div>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedImageSize')): ?>
				<img style="width:<?php echo $item->imageWidth; ?>px;height:auto;" class="itemRelImg" src="<?php echo $item->image; ?>" alt="<?php K2HelperUtilities::cleanHtml($item->title); ?>" />
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedIntrotext')): ?>
				<div class="itemRelIntrotext"><?php echo $item->introtext; ?></div>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedFulltext')): ?>
				<div class="itemRelFulltext"><?php echo $item->fulltext; ?></div>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedMedia')): ?>
				<?php if($item->videoType=='embedded'): ?>
				<div class="itemRelMediaEmbedded"><?php echo $item->video; ?></div>
				<?php else: ?>
				<div class="itemRelMedia"><?php echo $item->video; ?></div>
				<?php endif; ?>
				<?php endif; ?>

				<?php if($this->item->params->get('itemRelatedImageGallery')): ?>
				<div class="itemRelImageGallery"><?php echo $item->gallery; ?></div>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
			<li class="clr"></li>
		</ul>
		<div class="clr"></div>
	</div>
	<?php endif; ?>

	<div class="clr"></div>

  <?php if($this->item->params->get('itemVideo') && !empty($this->item->video)): ?>
  <!-- Item video -->
  <a name="itemVideoAnchor" id="itemVideoAnchor"></a>

  <div class="itemVideoBlock">
  	<h3><?php echo JText::_('K2_MEDIA'); ?></h3>

		<?php if($this->item->videoType=='embedded'): ?>
		<div class="itemVideoEmbedded">
			<?php echo $this->item->video; ?>
		</div>
		<?php else: ?>
		<span class="itemVideo"><?php echo $this->item->video; ?></span>
		<?php endif; ?>

	  <?php if($this->item->params->get('itemVideoCaption') && !empty($this->item->video_caption)): ?>
	  <span class="itemVideoCaption"><?php echo $this->item->video_caption; ?></span>
	  <?php endif; ?>

	  <?php if($this->item->params->get('itemVideoCredits') && !empty($this->item->video_credits)): ?>
	  <span class="itemVideoCredits"><?php echo $this->item->video_credits; ?></span>
	  <?php endif; ?>

	  <div class="clr"></div>
  </div>
  <?php endif; ?>

  <?php if($this->item->params->get('itemImageGallery') && !empty($this->item->gallery)): ?>
  <!-- Item image gallery -->
  <a name="itemImageGalleryAnchor" id="itemImageGalleryAnchor"></a>
  <div class="itemImageGallery">
	  <h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
	  <?php echo $this->item->gallery; ?>
  </div>
  <?php endif; ?>

  <?php if($this->item->params->get('itemNavigation') && !JRequest::getCmd('print') && (isset($this->item->nextLink) || isset($this->item->previousLink))): ?>
  <!-- Item navigation -->
  <div class="itemNavigation">
  	<span class="itemNavigationTitle"><?php echo JText::_('K2_MORE_IN_THIS_CATEGORY'); ?></span>

		<?php if(isset($this->item->previousLink)): ?>
		<a class="itemPrevious" href="<?php echo $this->item->previousLink; ?>">
			&laquo; <?php echo $this->item->previousTitle; ?>
		</a>
		<?php endif; ?>

		<?php if(isset($this->item->nextLink)): ?>
		<a class="itemNext" href="<?php echo $this->item->nextLink; ?>">
			<?php echo $this->item->nextTitle; ?> &raquo;
		</a>
		<?php endif; ?>

  </div>
  <?php endif; ?>

  <!-- Plugins: AfterDisplay -->
  <?php echo $this->item->event->AfterDisplay; ?>

  <!-- K2 Plugins: K2AfterDisplay -->
  <?php echo $this->item->event->K2AfterDisplay; ?>

  <?php if($this->item->params->get('itemComments') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1'))): ?>
  <!-- K2 Plugins: K2CommentsBlock -->
  <?php echo $this->item->event->K2CommentsBlock; ?>
  <?php endif; ?>

 <?php if($this->item->params->get('itemComments') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2')) && empty($this->item->event->K2CommentsBlock)): ?>
  <!-- Item comments -->
  <a name="itemCommentsAnchor" id="itemCommentsAnchor"></a>

  <div class="itemComments">

	  <?php if($this->item->params->get('commentsFormPosition')=='above' && $this->item->params->get('itemComments') && !JRequest::getInt('print') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
	  <!-- Item comments form -->
	  <div class="itemCommentsForm">
	  	<?php echo $this->loadTemplate('comments_form'); ?>
	  </div>
	  <?php endif; ?>

	  <?php if($this->item->numOfComments>0 && $this->item->params->get('itemComments') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2'))): ?>
	  <!-- Item user comments -->
	  <h3 class="itemCommentsCounter">
	  	<span><?php echo $this->item->numOfComments; ?></span> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
	  </h3>

	  <ul class="itemCommentsList">
	    <?php foreach ($this->item->comments as $key=>$comment): ?>
	    <li class="<?php echo ($key%2) ? "odd" : "even"; echo (!$this->item->created_by_alias && $comment->userID==$this->item->created_by) ? " authorResponse" : ""; echo($comment->published) ? '':' unpublishedComment'; ?>">

	    	<span class="commentLink">
		    	<a href="<?php echo $this->item->link; ?>#comment<?php echo $comment->id; ?>" name="comment<?php echo $comment->id; ?>" id="comment<?php echo $comment->id; ?>">
		    		<?php echo JText::_('K2_COMMENT_LINK'); ?>
		    	</a>
		    </span>

				<?php if($comment->userImage): ?>
				<img src="<?php echo $comment->userImage; ?>" alt="<?php echo JFilterOutput::cleanText($comment->userName); ?>" width="<?php echo $this->item->params->get('commenterImgWidth'); ?>" />
				<?php endif; ?>

				<span class="commentDate">
		    	<?php echo JHTML::_('date', $comment->commentDate, JText::_('K2_DATE_FORMAT_LC2')); ?>
		    </span>

		    <span class="commentAuthorName">
			    <?php echo JText::_('K2_POSTED_BY'); ?>
			    <?php if(!empty($comment->userLink)): ?>
			    <a href="<?php echo JFilterOutput::cleanText($comment->userLink); ?>" title="<?php echo JFilterOutput::cleanText($comment->userName); ?>" target="_blank" rel="nofollow">
			    	<?php echo $comment->userName; ?>
			    </a>
			    <?php else: ?>
			    <?php echo $comment->userName; ?>
			    <?php endif; ?>
		    </span>

		    <p><?php echo $comment->commentText; ?></p>

				<?php if($this->inlineCommentsModeration || ($comment->published && ($this->params->get('commentsReporting')=='1' || ($this->params->get('commentsReporting')=='2' && !$this->user->guest)))): ?>
				<span class="commentToolbar">
					<?php if($this->inlineCommentsModeration): ?>
					<?php if(!$comment->published): ?>
					<a class="commentApproveLink" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=publish&commentID='.$comment->id.'&format=raw')?>"><?php echo JText::_('K2_APPROVE')?></a>
					<?php endif; ?>

					<a class="commentRemoveLink" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=remove&commentID='.$comment->id.'&format=raw')?>"><?php echo JText::_('K2_REMOVE')?></a>
					<?php endif; ?>

					<?php if($comment->published && ($this->params->get('commentsReporting')=='1' || ($this->params->get('commentsReporting')=='2' && !$this->user->guest))): ?>
					<a class="modal" rel="{handler:'iframe',size:{x:560,y:480}}" href="<?php echo JRoute::_('index.php?option=com_k2&view=comments&task=report&commentID='.$comment->id)?>"><?php echo JText::_('K2_REPORT')?></a>
					<?php endif; ?>

					<?php if($comment->reportUserLink): ?>
					<a class="k2ReportUserButton" href="<?php echo $comment->reportUserLink; ?>"><?php echo JText::_('K2_FLAG_AS_SPAMMER'); ?></a>
					<?php endif; ?>

				</span>
				<?php endif; ?>

				<div class="clr"></div>
	    </li>
	    <?php endforeach; ?>
	  </ul>

	  <div class="itemCommentsPagination">
	  	<?php echo $this->pagination->getPagesLinks(); ?>
	  	<div class="clr"></div>
	  </div>
		<?php endif; ?>

		<?php if($this->item->params->get('commentsFormPosition')=='below' && $this->item->params->get('itemComments') && !JRequest::getInt('print') && ($this->item->params->get('comments') == '1' || ($this->item->params->get('comments') == '2' && K2HelperPermissions::canAddComment($this->item->catid)))): ?>
	  <!-- Item comments form -->
	  <div class="itemCommentsForm">
	  	<?php echo $this->loadTemplate('comments_form'); ?>
	  </div>
	  <?php endif; ?>

	  <?php $user = JFactory::getUser(); if ($this->item->params->get('comments') == '2' && $user->guest): ?>
	  		<div><?php echo JText::_('K2_LOGIN_TO_POST_COMMENTS'); ?></div>
	  <?php endif; ?>

  </div>
  <?php endif; ?>

	<?php if(!JRequest::getCmd('print')): ?>
	<div class="itemBackToTop">
		<a class="k2Anchor" href="<?php echo $this->item->link; ?>#startOfPageId<?php echo JRequest::getInt('id'); ?>">
			<?php echo JText::_('K2_BACK_TO_TOP'); ?>
		</a>
	</div>
	<?php endif; ?>
		<div class="clr"></div>
</div>

	<div id="page">
			<div id="container">
				<h1><a href="index.html">Galleriffic</a></h1>
				<h2>Insert and remove images after initialization</h2>

				<!-- Start Advanced Gallery Html Containers -->
				<div id="gallery" class="content">
					<div id="controls" class="controls"></div>
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
						<div id="caption" class="caption-container"></div>
					</div>
					<div id="captionToggle">
						<a href="#toggleCaption" class="off" title="Show Caption">Show Caption</a>
					</div>
				</div>
				<div id="thumbs" class="navigation">
					<ul class="thumbs noscript">
						<li>
							<a class="thumb" name="leaf" href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015.jpg" title="Title #0">
								<img src="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_s.jpg" alt="Title #0" />
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

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3205/2538164270_4369bbdd23.jpg" title="Title #7">
								<img src="http://farm4.static.flickr.com/3205/2538164270_4369bbdd23_s.jpg" alt="Title #7" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3205/2538164270_c7d1646ecf_o.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #7</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3211/2538163540_c2026243d2.jpg" title="Title #8">
								<img src="http://farm4.static.flickr.com/3211/2538163540_c2026243d2_s.jpg" alt="Title #8" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3211/2538163540_c2026243d2_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #8</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2315/2537343449_f933be8036.jpg" title="Title #9">
								<img src="http://farm3.static.flickr.com/2315/2537343449_f933be8036_s.jpg" alt="Title #9" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2315/2537343449_f933be8036_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #9</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2167/2082738157_436d1eb280.jpg" title="Title #10">
								<img src="http://farm3.static.flickr.com/2167/2082738157_436d1eb280_s.jpg" alt="Title #10" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2167/2082738157_436d1eb280_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #10</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2342/2083508720_fa906f685e.jpg" title="Title #11">
								<img src="http://farm3.static.flickr.com/2342/2083508720_fa906f685e_s.jpg" alt="Title #11" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2342/2083508720_fa906f685e_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #11</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2132/2082721339_4b06f6abba.jpg" title="Title #12">
								<img src="http://farm3.static.flickr.com/2132/2082721339_4b06f6abba_s.jpg" alt="Title #12" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2132/2082721339_4b06f6abba_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #12</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60.jpg" title="Title #13">
								<img src="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60_s.jpg" alt="Title #13" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2139/2083503622_5b17f16a60_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #13</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2041/2083498578_114e117aab.jpg" title="Title #14">
								<img src="http://farm3.static.flickr.com/2041/2083498578_114e117aab_s.jpg" alt="Title #14" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2041/2083498578_114e117aab_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #14</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2149/2082705341_afcdda0663.jpg" title="Title #15">
								<img src="http://farm3.static.flickr.com/2149/2082705341_afcdda0663_s.jpg" alt="Title #15" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2149/2082705341_afcdda0663_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #15</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2014/2083478274_26775114dc.jpg" title="Title #16">
								<img src="http://farm3.static.flickr.com/2014/2083478274_26775114dc_s.jpg" alt="Title #16" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2014/2083478274_26775114dc_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #16</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2194/2083464534_122e849241.jpg" title="Title #17">
								<img src="http://farm3.static.flickr.com/2194/2083464534_122e849241_s.jpg" alt="Title #17" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2194/2083464534_122e849241_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #17</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm4.static.flickr.com/3127/2538173236_b704e7622e.jpg" title="Title #18">
								<img src="http://farm4.static.flickr.com/3127/2538173236_b704e7622e_s.jpg" alt="Title #18" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm4.static.flickr.com/3127/2538173236_b704e7622e_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #18</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2375/2538172432_3343a47341.jpg" title="Title #19">
								<img src="http://farm3.static.flickr.com/2375/2538172432_3343a47341_s.jpg" alt="Title #19" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2375/2538172432_3343a47341_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #19</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2353/2083476642_d00372b96f.jpg" title="Title #20">
								<img src="http://farm3.static.flickr.com/2353/2083476642_d00372b96f_s.jpg" alt="Title #20" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2353/2083476642_d00372b96f_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #20</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34.jpg" title="Title #21">
								<img src="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34_s.jpg" alt="Title #21" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm3.static.flickr.com/2201/1502907190_7b4a2a0e34_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #21</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm2.static.flickr.com/1116/1380178473_fc640e097a.jpg" title="Title #22">
								<img src="http://farm2.static.flickr.com/1116/1380178473_fc640e097a_s.jpg" alt="Title #22" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm2.static.flickr.com/1116/1380178473_fc640e097a_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #22</div>
								<div class="image-desc">Description</div>
							</div>
						</li>

						<li>
							<a class="thumb" href="http://farm2.static.flickr.com/1260/930424599_e75865c0d6.jpg" title="Title #23">
								<img src="http://farm2.static.flickr.com/1260/930424599_e75865c0d6_s.jpg" alt="Title #23" />
							</a>
							<div class="caption">
								<div class="download">
									<a href="http://farm2.static.flickr.com/1260/930424599_e75865c0d6_b.jpg">Download Original</a>
								</div>
								<div class="image-title">Title #23</div>
								<div class="image-desc">Description</div>
							</div>
						</li>
					</ul>
				</div>
				<!-- End Advanced Gallery Html Containers -->

				<!-- Add image link -->				
				<div style="clear: left; float: left; padding-top: 10px;">
					<a href="#addImage" id="addImageLink">Insert Image into Position 5</a>
					<br />
					<a href="#removeImageByIndex" id="removeImageByIndexLink">Remove Image at Position 5</a>
					<br />
					<a href="#removeImageByHash" id="removeImageByHashLink">Remove the Lizard Image (by its hash value 'lizard')</a>
				</div>

				<div style="clear: both;"></div>
			</div>
		</div>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '300px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});

				// Enable toggling of the caption
				var captionOpacity = 0.0;
				$('#captionToggle a').click(function(e) {
					var link = $(this);
					
					var isOff = link.hasClass('off');
					var removeClass = isOff ? 'off' : 'on';
					var addClass = isOff ? 'on' : 'off';
					var linkText = isOff ? 'Hide Caption' : 'Show Caption';
					captionOpacity = isOff ? 0.7 : 0.0;

					link.removeClass(removeClass).addClass(addClass).text(linkText).attr('title', linkText);
					$('#caption span.image-caption').fadeTo(1000, captionOpacity);
					
					e.preventDefault();
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 15,
					preloadAhead:              10,
					enableTopPager:            true,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					playLinkText:              'Play Slideshow',
					pauseLinkText:             'Pause Slideshow',
					prevLinkText:              '&lsaquo; Previous Photo',
					nextLinkText:              'Next Photo &rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             true,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onTransitionOut:           function(slide, caption, isSync, callback) {
						slide.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0, callback);
						caption.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0);
					},
					onTransitionIn:            function(slide, caption, isSync) {
						var duration = this.getDefaultTransitionDuration(isSync);
						slide.fadeTo(duration, 1.0);
						
						// Position the caption at the bottom of the image and set its opacity
						var slideImage = slide.find('img');
						caption.width(slideImage.width())
							.css({
								'bottom' : Math.floor((slide.height() - slideImage.outerHeight()) / 2),
								'left' : Math.floor((slide.width() - slideImage.width()) / 2) + slideImage.outerWidth() - slideImage.width()
							})
							.fadeTo(duration, captionOpacity);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					},
					onImageAdded:              function(imageData, $li) {
						$li.opacityrollover({
							mouseOutOpacity:   onMouseOutOpacity,
							mouseOverOpacity:  1.0,
							fadeSpeed:         'fast',
							exemptionSelector: '.selected'
						});
					}
				});

				/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

				// PageLoad function
				// This function is called when:
				// 1. after calling $.historyInit();
				// 2. after calling $.historyLoad();
				// 3. after pushing "Go Back" button of a browser
				function pageload(hash) {
					// alert("pageload: " + hash);
					// hash doesn't contain the first # character.
					if(hash) {
						$.galleriffic.gotoImage(hash);
					} else {
						gallery.gotoIndex(0);
					}
				}

				// Initialize history plugin.
				// The callback is called at once by present location.hash. 
				$.historyInit(pageload, "advanced.html");

				// set onlick event for buttons using the jQuery 1.3 live method
				$("a[rel='history']").live('click', function() {
					if (e.button != 0) return true;

					var hash = this.href;
					hash = hash.replace(/^.*#/, '');

					// moves to a new page. 
					// pageload is called at once. 
					// hash don't contain "#", "?"
					$.historyLoad(hash);

					return false;
				});

				/****************************************************************************************/

				/********************** Attach click event to the Add Image Link ************************/

				$('#addImageLink').click(function(e) {
					gallery.insertImage('<li>																							\
							<a class="thumb" href="http://farm1.static.flickr.com/79/263120676_2518b40e5b.jpg" title="Dynamically Added Image">\
								<img src="http://farm1.static.flickr.com/79/263120676_2518b40e5b_s.jpg" alt="Dynamically Added Image" />\
							</a>																										\
							<div class="caption">																						\
								<div class="download">																					\
									<a href="http://farm1.static.flickr.com/79/263120676_2518b40e5b_o_d.jpg">Download Original</a>		\
								</div>																									\
								<div class="image-title">Dynamically Added Image</div>													\
								<div class="image-desc">																				\
									<img src="http://farm1.static.flickr.com/38/buddyicons/77261001@N00.jpg" alt="ringydingydo" />		\
									Photo taken by <a href="http://www.flickr.com/photos/ringydingydo/">ringydingydo</a>				\
								</div>																									\
							</div>																										\
						</li>', 5);
					
					e.preventDefault();
				});
				
				/****************************************************************************************/
				
				/***************** Attach click event to the Remove Image By Index Link *****************/
				
				$('#removeImageByIndexLink').click(function(e) {
					if (!gallery.removeImageByIndex(5))
						alert('There is no longer an image at position 5 to remove!');

					e.preventDefault();
				});
				
				/****************************************************************************************/

				/***************** Attach click event to the Remove Image By Hash Link ******************/
				
				$('#removeImageByHashLink').click(function(e) {
					if (!gallery.removeImageByHash('lizard'))
						alert('The lizard image has already been removed!');

					e.preventDefault();
				});
				
				/****************************************************************************************/
			});
		</script>
<!-- End K2 Item Layout -->
