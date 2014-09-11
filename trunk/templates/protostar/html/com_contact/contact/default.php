<?php
/**@package     Joomla.Site
 * @subpackage  com_contact
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;
$cparams = JComponentHelper::getParams('com_media');
jimport('joomla.html.html.bootstrap');
$Itemid = JRequest::getVar('Itemid','101');
if($Itemid==107){
?>
<div class="contact<?php echo $this->pageclass_sfx?>" itemscope itemtype="http://schema.org/Person">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header"><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1></div>
	<?php endif; ?>
    <div class="articlecontent"><?php  echo $this->loadTemplate('forminquiry');  ?></div>
</div>
<?php
}
else{
?>
<div class="contact<?php echo $this->pageclass_sfx?>" itemscope itemtype="http://schema.org/Person">
	<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header"><h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1></div>
	<?php endif; ?>
    <div class="articlecontent">
	<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>
		<?php if ($this->params->get('presentation_style') == 'plain'):?><?php //echo '<h2>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h2>';  ?><?php endif; ?>
		<?php  echo $this->loadTemplate('form');  ?>
	<?php endif; ?>
    
	<?php if ($this->params->get('presentation_style') == 'plain'):?><?php  //echo '<h2>'. JText::_('Sherwood Residence').'</h2>';  ?><?php endif; ?>
	<?php echo $this->loadTemplate('address'); ?>
    
	<?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
		<?php if ($this->params->get('presentation_style') == 'plain'):?>
			<?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>';  ?>
		<?php endif; ?>
		<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
		<?php if ($this->params->get('presentation_style') == 'plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>';  ?>
		<?php endif; ?>
		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif; ?>
    
	<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
		<?php if ($this->params->get('presentation_style') == 'plain'):?><?php //echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>';  ?><?php endif; ?>
		<div class="contact-miscinfo">
			<dl class="dl-horizontal">
				<dt><span class="<?php echo $this->params->get('marker_class'); ?>"><?php echo $this->params->get('marker_misc'); ?></span></dt>
				<dd><span class="contact-misc"><?php echo $this->contact->misc; ?></span></dd>
			</dl>
		</div>
	<?php endif; ?>
    </div>
</div>
<?php }?>