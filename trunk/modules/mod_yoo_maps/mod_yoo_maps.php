<?php
/**
* YOOmaps Joomla! Module
* @author    yootheme.com
* @copyright Copyright (C) 2007 YOOtheme Ltd. & Co. KG. All rights reserved.
* @license	 GNU/GPL*/
defined('_JEXEC') or die('Restricted access');
global $mainframe;
// count instances
if (!isset($GLOBALS['yoo_maps'])) {
	$GLOBALS['yoo_maps'] = 1;
} 
else {
	$GLOBALS['yoo_maps']++;
}
// include helper
require_once (dirname(__FILE__).DS.'helper.php');
// disable edit ability icon
$access = new stdClass();
$access->canEdit	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;
$list  = modYOOmapsHelper::getList($params, $access);
$items = count($list);
// init vars
$google_api_key     = $params->get('google_api_key', 'abcdefg');
$location           = $params->get('location', 'Hamburg, Germany');
$marker_popup       = $params->get('marker_popup', 0);
$marker_text        = $params->get('marker_text', '');
$main_icon          = $params->get('main_icon', 'red-dot');
$other_icon         = $params->get('other_icon', 'blue-dot');
$zoom_level         = $params->get('zoom_level', 13);
$map_controls       = $params->get('map_controls', 2);
$scroll_wheel_zoom  = $params->get('scroll_wheel_zoom', 1);
$map_type           = $params->get('map_type', 0);
$type_controls      = $params->get('type_controls', 1);
$overview_controls  = $params->get('overview_controls', 1);
$geocode_cache      = $params->get('geocode_cache', 1);
$directions         = $params->get('directions', 1);
$directions_dest_up = $params->get('directions_dest_update', 0);
$locale             = $params->get('locale', 'en');
$module_width       = $params->get('module_width', 500);
$module_height      = $params->get('module_height', 400);
$from_address       = JText::_('FROM_ADDRESS');
$get_directions     = JText::_('GET_DIRECTIONS');
$empty              = JText::_('EMPTY');
$not_found          = JText::_('NOT_FOUND');
$address_not_found  = JText::_('ADDRESS_NOT_FOUND');
$module_base        = JURI::base() . 'modules/mod_yoo_maps/';
// init cache
$cache = $geocode_cache ? new modYOOmapsCache(dirname(__FILE__).DS.'geocode_cache.txt') : null;
if ($cache && !$cache->check()) { 
	echo "<div class=\"alert\"><strong>Cache not writeable please update the file permissions! (geocode_cache.txt)</strong></div>\n";
	return;
}
// get map center coordinates
$center = modYOOmapsHelper::locate($google_api_key, $location, $cache);
if (!$center) { 
	echo "<div class=\"alert\"><strong>Unable to get map center coordinates, please verify your location! (" . $location . ")</strong></div>\n";
	return;
}
// css parameters
$maps_id           = 'yoo-maps-' . $GLOBALS['yoo_maps'];
$css_module_width  = 'width: ' . $module_width . 'px;';
$css_module_height = 'height: ' . $module_height . 'px;';
$data = array();
for ($i=0; $i < $items; $i++) {
	if (array_key_exists($i, $list)){
		if ($coordinates = modYOOmapsHelper::locate($google_api_key, $list[$i]->title, $cache)){
			$data[$list[$i]->id]['lat'] = $coordinates['lat'];
			$data[$list[$i]->id]['lng'] = $coordinates['lng'];
			$data[$list[$i]->id]['text'] = urlencode(str_replace('"',"'",$list[$i]->introtext));
		}
	}
}
$tmp['json']=$data;
$data = json_encode($tmp);
// js parameters
$messages    = array();
$maps_var    = 'yoomap' . $GLOBALS['yoo_maps'];
$javascript  = "var $maps_var = new YOOmaps('" . $maps_id . "', { lat:" . $center['lat'] . ", lng:" . $center['lng'] . ", popup: " . $marker_popup . ", text: '" . modYOOmapsHelper::stripText($marker_text) . "', zoom: " . $zoom_level . ", mapCtrl: " . $map_controls . ", zoomWhl: " . $scroll_wheel_zoom . ", mapType: " . $map_type . ", typeCtrl: " . $type_controls . ", overviewCtrl: " . $overview_controls . ", directions: " . $directions . ", directionsDestUpdate: " . $directions_dest_up . ", locale: '" . $locale . "', mainIcon:'" . $main_icon . "', otherIcon:'" . $other_icon . "', msgFromAddress: '" . $from_address . "', msgGetDirections: '" . $get_directions . "', msgEmpty: '" . $empty . "', msgNotFound: '" . $not_found . "', msgAddressNotFound: '" . $address_not_found . "', arr: '".$data."',customiconurl: '".JURI::base().'modules/mod_yoo_maps/images/'."' });";
for ($i=0; $i < $items; $i++) {
	if (array_key_exists($i, $list)){
		if ($coordinates = modYOOmapsHelper::locate($google_api_key, $list[$i]->title, $cache)){
			$customIcon =  modYOOmapsHelper::queryIcon($list[$i]->title);
			if($customIcon != '')
				$javascript .= "$maps_var.addMarkerLatLng(" . $coordinates['lat'] . ", " . $coordinates['lng'] . ", '" . modYOOmapsHelper::stripText($list[$i]->introtext) . "','','".$customIcon."');\n\r";
			else
				$javascript .= "$maps_var.addMarkerLatLng(" . $coordinates['lat'] . ", " . $coordinates['lng'] . ", '" . modYOOmapsHelper::stripText($list[$i]->introtext) . "','','');\n\r";
		}
		else {
			$messages[]  = $list[$i]->title . $not_found;
		}
	}
}
if ($cache) $cache->save();
require(JModuleHelper::getLayoutPath('mod_yoo_maps', 'default'));
$document =& JFactory::getDocument();
$document->addScript('http://maps.google.com/maps?file=api&amp;v=3&amp;key=' . $google_api_key);
//$document->addScript($module_base . 'scroll-startstop.events.jquery.js');
$document->addScript($module_base . 'mod_yoo_maps.js');
$document->addScript($module_base . 'json.js');
echo "<script type=\"text/javascript\" defer=\"defer\">\n// <!--\n$javascript\n// -->\n</script>\n";