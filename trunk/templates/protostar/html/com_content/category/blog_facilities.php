<?php
/**@package     Joomla.Site
 * @subpackage  Layout
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;?>
<?php
// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
JHtml::_('behavior.framework');
?>
<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
	|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
	<div class="system-unpublished">
<?php endif; ?>

<div class="imgintro">
<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
</div>
<div class="contentcatintro">
    <div class="page-header">
        <h2>
            <strong><?php echo $this->item->title;?></strong>
        </h2>
        <span class="tabnote"><?php echo $this->item->metakey;?></span>
    </div>
    <div class="contentarticle">
        <?php echo $this->item->introtext; ?>
    </div>
    <!--<a class="roomdetail" href="index.php?option=com_content&view=article&id=<?php echo $this->item->id?>&catid=<?php echo $this->item->catid?>&Itemid=108"><?php echo JText::_('Read More'); ?></a>-->
</div>