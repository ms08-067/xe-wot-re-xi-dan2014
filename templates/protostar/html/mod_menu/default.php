<?php
/**@package     Joomla.Site
 * @subpackage  mod_menu
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;
$type 		= $params->get('type',	0);
if($type==0){?>
<?php // The menu class is deprecated. Use nav instead. ?>
<ul class="nav menu<?php echo $class_sfx;?>"<?php $tag='';if($params->get('tag_id')!=null){$tag=$params->get('tag_id').'';echo ' id="'.$tag.'"';}?>>
<?php
foreach ($list as $i => &$item){
	$class = 'item-' . $item->id;
	if ($item->id == $active_id){
		$class .= ' current';
	}
	if (in_array($item->id, $path)){
		$class .= ' active';
	}
	elseif ($item->type == 'alias'){
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path) - 1]){
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)){
			$class .= ' alias-parent-active';
		}
	}
	if ($item->type == 'separator'){
		$class .= ' divider';
	}
	if ($item->deeper){
		$class .= ' deeper';
	}
	if ($item->parent){
		$class .= ' parent';
	}
	if (!empty($class)){
		$class = ' class="' . trim($class) . '"';
	}
	echo '<li' . $class . '>';
	// Render the menu item.
	switch ($item->type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
			break;
		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;
	// The next item is deeper.
	if ($item->deeper){
		echo '<ul class="nav-child unstyled small">';
	}
	elseif ($item->shallower){
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	else{
		// The next item is on the same level.
		echo '</li>';
	}
}
?></ul>
<?php
}
if($type==1){
	$db = & JFactory::getDBO();
	$sql="SELECT * from #__menu where menutype='mainmenu' and parent_id=1 and published=1 order by id asc";
	$db->setQuery($sql);
	$lists = $db->loadObjectList();
?>
<div class="jm-drillmenu">
    <div class="wrap clearfix">
        <div class="navbar clearfix">
            <div class="navbar-title clearfix">
                <a class="jm-navbar collapsed" data-toggle="collapse" data-target=".menusys_drill">
                    <span>Main Menu</span>
                </a>
            </div>
            <div style="top: 0px; height: 0px;" class="menusys_drill collapse">
                <ul style="margin-top:5px;" id="drilldown" class="jm-menu nav-drilldown level-0 test">
                    <?php
                    for($i=0;$i<count($lists);$i++){
					?>
                    <li class="menu-item first parent ">
                        <a href="<?php echo $lists[$i]->link;?>&Itemid=<?php echo $lists[$i]->id;?>" class="menu-item menu-item first parent  parent">
                            <span class="menu"><span class="menu-title"><?php echo $lists[$i]->title;?></span></span>
                        </a>
                        <?php
                        $sqlsubmenu="SELECT * from #__menu where menutype='mainmenu' and parent_id='".$lists[$i]->id."' and published=1";
						$db->setQuery($sqlsubmenu);
						$listsubmenus = $db->loadObjectList();
						if(count($listsubmenus)>0){
						?>
                        <span class="arrow expand">expand</span>
                        <ul style="" class="detail-parent level-1">
                        	<?php
                            for($j=0;$j<count($listsubmenus);$j++){
							?>
                            <li class="menu-item">
                                <a href="<?php echo $listsubmenus[$j]->link;?>&Itemid=<?php echo $listsubmenus[$j]->id;?>" class="menu-item menu-item">
                                    <span class="menu"><span class="menu-title"><?php echo $listsubmenus[$j]->title;?></span></span>
                                </a>
                            </li>
                            <?php
							}
							?>
                        </ul>
                        <?php
						}
						?>
                    </li>
                    <?php
					}
					?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
}
?>