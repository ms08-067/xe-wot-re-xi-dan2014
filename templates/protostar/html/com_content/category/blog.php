<?php
/**@package     Joomla.Site
 * @subpackage  com_content
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');
$Itemid = JRequest::getVar('Itemid','101');
$db = & JFactory::getDBO();
$query = 'SELECT note FROM #__menu WHERE id='.$Itemid;
$db->setQuery( $query );
$temmenu = $db->loadResult();
$tabs1="";
$tabs2="";
if($temmenu == "residences"){
?>
<div class="blogarticle">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<script src="templates/protostar/js/date/jquery-ui.js"></script>
    <script type="text/javascript">
        jQuery(function() {
            var tabCounter = 2;
            var tabs = jQuery( "#tabs" ).tabs();
            tabs.bind( "keyup", function( event ) {
                if ( event.altKey && event.keyCode === jQuery.ui.keyCode.BACKSPACE ) {
                    var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
                    jQuery( "#" + panelId ).remove();
                    tabs.tabs( "refresh" );
                }
            });
        });
        function changlayout1($stylelayout){
            var droom = document.getElementById("roomtemplatestyle");
            var dsuite = document.getElementById("suitetemplatestyle");
            droom.className = ' '+$stylelayout;
            dsuite.className = ' '+$stylelayout;
        }
    </script>
    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs">
        <ul role="tablist" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
        	
			<?php if($Itemid==123){$tabs1=" ui-tabs-active ui-state-active";$tabs2="";}elseif($Itemid==124){$tabs1="";$tabs2=" ui-tabs-active ui-state-active";}else{$tabs1="";$tabs2="";}?>
            <li aria-selected="true" aria-controls="tabs-1" class="ui-state-default ui-corner-top<?php echo $tabs1?>">
                <div class="page_header catroom">
                    <h2><strong><a id="ui-id-1" class="ui-tabs-anchor" href="#tabs-1"><?php echo JText::_('Apartment'); ?></a></strong></h2>
                </div>
            </li>
            <li aria-selected="true" aria-controls="tabs-2" class="ui-state-default ui-corner-top<?php echo $tabs2?>">
                <div class="page_header catroom">
                    <h2><strong><a id="ui-id-2" class="ui-tabs-anchor" href="#tabs-2"><?php echo JText::_('Penthouse'); ?></a></strong></h2>
                </div>
            </li>
        </ul>
        <div class="ui-tabs-panel" id="tabs-1">
        	<?php
			$queryroom = 'SELECT * FROM #__content WHERE catid="18" and state=1 order by ordering';					
			$db->setQuery( $queryroom );
			$listrooms = $db->loadObjectList();
			for($i=0;$i<count($listrooms);$i++){
			?>
            <div class="articleblogitem">
            	<div class="imgintro">
                	<?php
					$cateimage = $listrooms[$i]->metadata;
					$cateimage = explode('xreference":"',$cateimage);
					$cateimage = explode('"',$cateimage[1]);
					$cateimage = $cateimage[0];
					if($cateimage!=""){
						$queryimgroom = 'SELECT * FROM #__banners WHERE catid='.$cateimage.' and state=1';					
						$db->setQuery( $queryimgroom );
						$imgslideroom = $db->loadObjectList();
						if(count($imgslideroom)>=1){
						?>
							<script>
								jQuery(function() {
								  jQuery('#galleryrooms<?php echo $cateimage;?>').slidesjs({
									width: 300,
									height: 190
								  });
								});
							</script>
							<div id="galleryrooms<?php echo $cateimage;?>" class="galleryrooms">
							<?php
							for($j=0;$j<count($imgslideroom);$j++){
								$galimgroom = $imgslideroom[$j]->params;
								$galimgroom = explode('imageurl":"',$galimgroom);
								$galimgroom = explode('","',$galimgroom[1]);
								$galimgroom = str_replace('\/','/',$galimgroom[0]);
							?>
								<ul class="galleryarticle<?php echo $listrooms[$i]->id;?>" id="olympGallery">
									<li><img alt="<?php echo $listrooms[$i]->title;?> - Sherwood Residence | Ho Chi Minh City (Saigon), Vietnam" title="<?php echo $listrooms[$i]->title;?>" src="<?php echo $galimgroom; ?>" /></li>
								</ul>
							<?php
							}
							?>
							</div>
						<?php
						}
					}
					else{
						$imgitro = $listrooms[$i]->images;
						$imgitro = explode('image_intro":"',$imgitro);
						$imgitro = explode('","',$imgitro[1]);
						$imgitro = str_replace('\/','/',$imgitro[0]);
					?>
						<img alt="<?php echo $listrooms[$i]->title;?> - Sherwood Residence | Ho Chi Minh City (Saigon), Vietnam" title="<?php echo $listrooms[$i]->title;?>" src="<?php echo $imgitro;?>" />
					<?php
					}
					?>
                </div>
                <div class="contentcatintro">
                	<div class="page-header">
                        <h2><strong><?php echo $listrooms[$i]->title;?></strong></h2>
                        <span class="tabnote"><?php echo $listrooms[$i]->metakey;?></span>
                    </div>
                    <div class="contentarticle">
                        <?php echo $listrooms[$i]->introtext; ?>
                    </div>
                    <a class="roomdetail" href="index.php?option=com_content&view=article&id=<?php echo $listrooms[$i]->id?>&catid=<?php echo $listrooms[$i]->catid?>&Itemid=123"><?php echo JText::_('Read More'); ?></a>
                    <!--<a class="roomdetail" href="#"><?php echo JText::_('See FloorPlan'); ?></a>
                    <a class="roomdetail black" href="#"><?php echo JText::_('Take A Tour'); ?></a>
                    <a class="roomdetail black last" href="#"><?php echo JText::_('Inquire Now'); ?></a>-->
                </div>
            </div>
            <?php
			}
			?>
        </div>
        <div class="ui-tabs-panel" id="tabs-2">
        	<?php
			$queryroom = 'SELECT * FROM #__content WHERE catid="19" and state=1 order by ordering';					
			$db->setQuery( $queryroom );
			$listrooms = $db->loadObjectList();
			for($i=0;$i<count($listrooms);$i++){
			?>
            <div class="articleblogitem">
            	<div class="imgintro">
                	<?php
					$cateimage = $listrooms[$i]->metadata;
					$cateimage = explode('xreference":"',$cateimage);
					$cateimage = explode('"',$cateimage[1]);
					$cateimage = $cateimage[0];
					if($cateimage!=""){
						$queryimgroom = 'SELECT * FROM #__banners WHERE catid='.$cateimage.' and state=1';					
						$db->setQuery( $queryimgroom );
						$imgslideroom = $db->loadObjectList();
						if(count($imgslideroom)>=1){
						?>
							<script>
								jQuery(function() {
								  jQuery('#galleryrooms<?php echo $cateimage;?>').slidesjs({
									width: 300,
									height: 190
								  });
								});
							</script>
							<div id="galleryrooms<?php echo $cateimage;?>" class="galleryrooms">
							<?php
							for($j=0;$j<count($imgslideroom);$j++){
								$galimgroom = $imgslideroom[$j]->params;
								$galimgroom = explode('imageurl":"',$galimgroom);
								$galimgroom = explode('","',$galimgroom[1]);
								$galimgroom = str_replace('\/','/',$galimgroom[0]);
							?>
								<ul class="galleryarticle<?php echo $listrooms[$i]->id;?>" id="olympGallery">
									<li><img alt="<?php echo $listrooms[$i]->title;?> - Sherwood Residence | Ho Chi Minh City (Saigon), Vietnam" title="<?php echo $listrooms[$i]->title;?>" src="<?php echo $galimgroom; ?>" /></li>
								</ul>
							<?php
							}
							?>
							</div>
						<?php
						}
					}
					else{
						$imgitro = $listrooms[$i]->images;
						$imgitro = explode('image_intro":"',$imgitro);
						$imgitro = explode('","',$imgitro[1]);
						$imgitro = str_replace('\/','/',$imgitro[0]);
					?>
						<img alt="<?php echo $listrooms[$i]->title;?> - Sherwood Residence | Ho Chi Minh City (Saigon), Vietnam" title="<?php echo $listrooms[$i]->title;?>" src="<?php echo $imgitro;?>" />
					<?php
					}
					?>
                </div>
                <div class="contentcatintro">
                	<div class="page-header">
                        <h2><strong><?php echo $listrooms[$i]->title;?></strong></h2>
                        <span class="tabnote"><?php echo $listrooms[$i]->metakey;?></span>
                    </div>
                    <div class="contentarticle">
                        <?php echo $listrooms[$i]->introtext; ?>
                    </div>
                    <a class="roomdetail" href="index.php?option=com_content&view=article&id=<?php echo $listrooms[$i]->id?>&catid=<?php echo $listrooms[$i]->catid?>&Itemid=124"><?php echo JText::_('Read More'); ?></a>
                    <!--<a class="roomdetail" href="#"><?php echo JText::_('See FloorPlan'); ?></a>
                    <a class="roomdetail black" href="#"><?php echo JText::_('Take A Tour'); ?></a>
                    <a class="roomdetail black last" href="#"><?php echo JText::_('Inquire Now'); ?></a>-->
                </div>
            </div>
            <?php
			}
			?>
        </div>
    </div>
</div>
<?php
}
elseif($temmenu == "news"){
?>
<div class="blogarticle">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<?php foreach ($this->lead_items as &$item) : ?>
            <?php
            $this->item = & $item;
            echo $this->loadTemplate('news');
            ?>
    <?php endforeach; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php
}
elseif($temmenu == "facilities"){
?>
<div class="blogarticle">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<?php foreach ($this->lead_items as &$item) : ?>
        <div class="articleblogitem">
            <?php
            $this->item = & $item;
            echo $this->loadTemplate('facilities');
            ?>
        </div>
    <?php endforeach; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php
}
elseif($temmenu == "others"){
?>
<div class="blogarticle">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<?php foreach ($this->lead_items as &$item) : ?>
        <div class="articleblogitem">
            <?php
            $this->item = & $item;
            echo $this->loadTemplate('others');
            ?>
        </div>
    <?php endforeach; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php
}
elseif($temmenu == "offers"){
?>
<div class="blogarticle">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<?php foreach ($this->lead_items as &$item) : ?>
        <div class="articleblogitem">
            <?php
            $this->item = & $item;
            echo $this->loadTemplate('offers');
            ?>
        </div>
    <?php endforeach; ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination"><?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php
}
else{
?>
<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="http://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
		<h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $this->category->title; ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
		<?php $this->category->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="category-desc clearfix">
			<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
				<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
			<?php endif; ?>
			<?php if ($this->params->get('show_description') && $this->category->description) : ?>
				<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
		<div class="items-leading clearfix">
			<?php foreach ($this->lead_items as &$item) : ?>
				<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
					<?php
					$this->item = & $item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<?php $leadingcount++; ?>
			<?php endforeach; ?>
		</div><!-- end items-leading -->
	<?php endif; ?>

	<?php
	$introcount = (count($this->intro_items));
	$counter = 0;
	?>

	<?php if (!empty($this->intro_items)) : ?>
		<?php foreach ($this->intro_items as $key => &$item) : ?>
			<?php $rowcount = ((int) $key % (int) $this->columns) + 1; ?>
			<?php if ($rowcount == 1) : ?>
				<?php $row = $counter / $this->columns; ?>
				<div class="items-row cols-<?php echo (int) $this->columns; ?> <?php echo 'row-' . $row; ?> row-fluid clearfix">
			<?php endif; ?>
			<div class="span<?php echo round((12 / $this->columns)); ?>">
				<div class="item column-<?php echo $rowcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>"
					itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
					<?php
					$this->item = & $item;
					echo $this->loadTemplate('item');
					?>
				</div>
				<!-- end item -->
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
				</div><!-- end row -->
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
		<div class="items-more">
			<?php echo $this->loadTemplate('links'); ?>
		</div>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
		<div class="cat-children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
				<h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?> </div>
	<?php endif; ?>
	<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php endif; ?>
</div>
<?php
}
?>