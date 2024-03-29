<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$type 		= $params->get('type',	1);
$catid 		= $params->get('catid',  '');
require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JUri::base();
if($type==0){
?>
<script type="text/javascript" src="templates/protostar/js/jquery.flexslider.js"></script>
<div id="slider">
    <div class="flexslider">
        <div class="slider-wrap">
        <?php 
		for($i=0;$i<count($list);$i++){
		$item = $list[$i];
		?>
            <div class="slide">
                <img src="<?php echo $item->params->get('imageurl');?>" title="<?php echo $item->name;?>" alt="<?php echo $item->params->get('alt');?> | Windsor Plaza Hotel | Ho Chi Minh City (Saigon), Vietnam" longdesc="<?php echo $list[$i]->description;?>" />
                <div class="slide-caption"><?php echo $item->name;?></div>
            </div>
		<?php 
		}
		?>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(window).load(function(){
		jQuery(".flexslider").flexslider({
			selector:".slider-wrap > .slide",
			animation:"slide",
			easing:"easeOutExpo",
			direction:"horizontal",
			slideshowSpeed:5000,
			animationSpeed:1000,
			pauseOnAction:true,
			pauseOnHover:true,
			useCSS:true,
			touch:true,
			video:true,
			controlNav:false,
			directionNav:true,
			keyboard:true
		})
	});
</script>
<?php
}
if($type==1){
	
}
if($type==2){
?>
<div class="bannergroup<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) : ?>
	<?php echo $headerText; ?>
<?php endif; ?>

<?php foreach ($list as $item) : ?>
	<div class="banneritem">
		<?php $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);?>
		<?php if ($item->type == 1) :?>
			<?php // Text based banners ?>
			<?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
		<?php else:?>
			<?php $imageurl = $item->params->get('imageurl');?>
			<?php $width = $item->params->get('width');?>
			<?php $height = $item->params->get('height');?>
			<?php if (BannerHelper::isImage($imageurl)) :?>
				<?php // Image based banner ?>
				<?php $alt = $item->params->get('alt');?>
				<?php $alt = $alt ? $alt : $item->name; ?>
				<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
				<?php if ($item->clickurl) :?>
					<?php // Wrap the banner in a link?>
					<?php $target = $params->get('target', 1);?>
					<?php if ($target == 1) :?>
						<?php // Open in a new window?>
						<a
							href="<?php echo $link; ?>" target="_blank"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php elseif ($target == 2):?>
						<?php // open in a popup window?>
						<a
							href="<?php echo $link;?>" onclick="window.open(this.href, '',
								'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
								return false"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php else :?>
						<?php // open in parent window?>
						<a
							href="<?php echo $link;?>"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
								<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
							/>
						</a>
					<?php endif;?>
				<?php else :?>
					<?php // Just display the image if no link specified?>
					<img
						src="<?php echo $baseurl . $imageurl;?>"
						alt="<?php echo $alt;?>"
						<?php if (!empty($width)) echo 'width ="' . $width . '"';?>
						<?php if (!empty($height)) echo 'height ="' . $height . '"';?>
					/>
				<?php endif;?>
			<?php elseif (BannerHelper::isFlash($imageurl)) :?>
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
					<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
					<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
				>
					<param name="movie" value="<?php echo $imageurl;?>" />
					<embed
						src="<?php echo $imageurl;?>"
						loop="false"
						pluginspage="http://www.macromedia.com/go/get/flashplayer"
						type="application/x-shockwave-flash"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				</object>
			<?php endif;?>
		<?php endif;?>
		<div class="clr"></div>
	</div>
<?php endforeach; ?>

<?php if ($footerText) : ?>
	<div class="bannerfooter">
		<?php echo $footerText; ?>
	</div>
<?php endif; ?>
</div>
<?php
}
?>