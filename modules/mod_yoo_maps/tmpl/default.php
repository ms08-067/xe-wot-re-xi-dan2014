<?php

/**

* YOOmaps Joomla! Module

*

* @author    yootheme.com

* @copyright Copyright (C) 2007 YOOtheme Ltd. & Co. KG. All rights reserved.

* @license	 GNU/GPL

*/



// no direct access

defined('_JEXEC') or die('Restricted access');

?>

<div class="yoo-maps" style="<?php echo $css_module_width ?>">

	<div id="<?php echo $maps_id ?>" style="<?php echo $css_module_width . $css_module_height ?>"></div>

	<?php foreach ($messages as $message) : ?>

	<div class="alert"><strong><?php echo $message; ?></strong></div>

	<?php endforeach; ?>

</div>

<script>

	jQuery(function() 

	{

		jQuery('td>ul>li>a').click(function(){

			var offsettop = parseInt(jQuery("body").css("height"));

			window.scrollTo(0,offsettop);

		});

     });

</script>