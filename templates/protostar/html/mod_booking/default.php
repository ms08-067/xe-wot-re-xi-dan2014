<?php
/**@package     Joomla.Site
 * @subpackage  mod_booking
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;
$db = & JFactory::getDBO();
$type 		= $params->get('type',	1);
$catid 		= $params->get('catid',  '');
$lang = JRequest::getVar('lang','');
$Itemid = JRequest::getVar('Itemid','101');
$idbaiviet = JRequest::getVar('id','101');
$Itemidmenu="";
if($type==0){
	$sql="SELECT * from #__content where catid='".$catid[0]."' and state=1 limit 0,1";
	$db->setQuery($sql);
	$lists = $db->loadObjectList();
	/*--Lay menu item--*/
	$keyarticle = "option=com_content&view=article";
	$keyblog = "option=com_content&view=category";
	$sqlmenuarticle="SELECT id,link from #__menu where link like '%$keyarticle%' and published=1";
	$db->setQuery($sqlmenuarticle);
	$listsmenuarticle = $db->loadObjectList();
	for($i=0;$i<count($listsmenuarticle);$i++){
		$idlinkarticle = $listsmenuarticle[$i]->link;
		$idlinkarticle = explode('&id=',$idlinkarticle);
		$idlinkarticle = $idlinkarticle[1];
		if($idlinkarticle==$lists[0]->id){
			$Itemidmenu = $listsmenuarticle[$i]->id;
		}
	}
	$sqlmenublog="SELECT id,link from #__menu where link like '%$keyblog%' and published=1";
	$db->setQuery($sqlmenublog);
	$listsmenublog = $db->loadObjectList();
	for($i=0;$i<count($listsmenublog);$i++){
		$idlinkblog = $listsmenublog[$i]->link;
		$idlinkblog = explode('&id=',$idlinkblog);
		$idlinkblog = $idlinkblog[1];
		if($idlinkblog==$catid[0]){
			$Itemidmenu = $listsmenublog[$i]->id;
		}
	}
	$imgintro = $lists[0]->images;
	$imgintro = explode('image_intro":"',$imgintro);
	$imgintro = explode('","',$imgintro[1]);
	$imgintro = str_replace('\/','/',$imgintro[0]);
	?>
    <div class="contentitem">
    	<a href="index.php?option=com_content&view=article&id=<?php echo $lists[0]->id?>&catid=<?php echo $catid[0];?>&Itemid=<?php echo $Itemidmenu?>">
    	<img src="<?php echo $imgintro; ?>" alt="<?php echo $lists[0]->alias; ?>" title="<?php echo $lists[0]->title; ?>" />
        </a>
        <h2 class="titlearticle"><strong><?php echo $lists[0]->title; ?></strong></h2>
        <?php 
		$textfull = $lists[0]->introtext.$lists[0]->fulltext;
		$intro = strip_tags($textfull);
		if (strlen($intro) > 120){
			$intro = substr($intro, 0, 120);
			$endintro = strrpos($intro,' ');
			$intro = substr($intro, 0, $endintro);
		}
		echo $intro;
		?>
        <p class="viewreadmore"><a href="index.php?option=com_content&view=article&id=<?php echo $lists[0]->id?>&catid=<?php echo $catid[0];?>&Itemid=<?php echo $Itemidmenu?>"><?php echo JText::_('Read More'); ?></a></p>
    </div>
    <?php
}
elseif($type==1){
?>
<script>
function submitgettouch(){
	var f=document.getElementById('formgetintouch');
	var touch_name=f.touch_name.value;
	var touch_email=f.touch_email.value;
	var touch_message=f.touch_message.value;
	if (touch_name == ''){alert('<?php echo JText::_('Please input your name!');?>');f.touch_name.focus();}
	else if (touch_email == ''){
			alert('<?php echo JText::_('Please input your email!');?>');f.touch_email.focus();
		}
		else if ((touch_email != '') && ((touch_email.indexOf('@', 0) == -1) || (touch_email.indexOf('.') == -1)|| touch_email.length<5)){
			alert('<?php echo JText::_('Your email is error, please input again!');?>');f.touch_email.focus();
		}
	else if (touch_message == ''){alert('<?php echo JText::_('Please input your message!');?>');f.touch_message.focus();}
	else{f.submit();window.location.href = "http://www.sherwoodresidence.com/index.php?option=com_contact&view=contact&id=1&Itemid=114";}
}
</script>
<div class="rightfootercontent">
<form id="formgetintouch" class="formgetintouch" name="formgetintouch" action="contact-us" method="post">
	<label class="" id=""><?php echo JText::_('Contact label');?></label>
    <a class="btn_send" href="index.php?option=com_contact&view=contact&id=1&Itemid=114"><?php echo JText::_('Contact Us'); ?></a>
	<!--<input type="text" id="touch_name" name="touch_name" class="txttouch" placeholder="<?php echo JText::_('Name'); ?>" value="" />
    <input type="email" id="touch_email" name="touch_email" class="txttouch" placeholder="<?php echo JText::_('Email'); ?>" value="" />
    <textarea id="touch_message" name="touch_message" class="txttouch messagetxt" placeholder="<?php echo JText::_('Message'); ?>"></textarea>
    <input type="button" id="touch_btn" class="btn_send" value="<?php echo JText::_('Contact Us'); ?>" onclick="submitgettouch();" />
    <input type="hidden" name="option" value="com_contact" />
    <input type="hidden" name="task" value="contact.submitgetintouch" />
    <input type="hidden" name="return" value="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" />-->
</form>
</div>
<?php
}
elseif($type==2){
?>
<div class="bookkingroom">
	<link rel="stylesheet" href="templates/protostar/css/daterangepicker.css" />
    <script src="templates/protostar/js/date/jquery-ui.js"></script>
<?php if($lang=="vn"||$lang=="vi-VN"){?>
	<script>
    jQuery(function(){
        var language = document.getElementsByTagName("html")[0].getAttribute("lang");
        var contant = 24*60*60*1000;
        var datepickersOpt = {dateFormat:'d-mm-yy',minDate:0,autoclose:true}
        var datepickersOptout = {dateFormat:'d-mm-yy',minDate:1,autoclose:true}
        jQuery("#date-checkin").datepicker(jQuery.extend({
            onSelect: function() {
                jQuery( "#date-checkin" ).datepicker( "option", "closeText", "Close" );
                var minDate = jQuery(this).datepicker('getDate');
                var maxDate = jQuery("#date-checkout").datepicker('getDate');
                var monthstart = (minDate.getMonth() + 1);if(monthstart <= 9){monthstart = "0"+monthstart;}
                var dateStringst = minDate.getFullYear() +"-"+ monthstart +"-"+ minDate.getDate() ;
                var dstart = minDate.getTime();
                var dend = maxDate.getTime();
                var alldate = Math.round(Math.abs((dstart - dend)/(contant)));
                jQuery('#datestart-hidden').val(dateStringst);
                jQuery('#alldate-hidden').val(alldate);
                minDate.setDate(minDate.getDate()+1);
                jQuery("#date-checkout").datepicker("option", "minDate", minDate);
            }
        },datepickersOpt));
        jQuery("#date-checkout").datepicker(jQuery.extend({
            onSelect: function() {
                jQuery( "#date-checkout" ).datepicker( "option", "closeText", "Close" );
                var minDate = jQuery("#date-checkin").datepicker("getDate");
                var maxDate = jQuery(this).datepicker('getDate');
                var monthstart = (minDate.getMonth() + 1);if(monthstart <= 9){monthstart = "0"+monthstart;}
                var dateStringst = minDate.getFullYear() +"-"+ monthstart +"-"+ minDate.getDate() ;
                var dstart = minDate.getTime();
                var dend = maxDate.getTime();
                var alldate = Math.round(Math.abs((dstart - dend)/(contant)));
                if(dend<dstart){
                    var datestarthere = jQuery(this).datepicker('getDate');
                    datestarthere.setDate(datestarthere.getDate()-1);
                    datestarthere = jQuery.datepicker.formatDate("d-mm-yy",datestarthere);
                    jQuery('#date-checkin').val(datestarthere);
                }
                jQuery('#datestart-hidden').val(dateStringst);
                jQuery('#alldate-hidden').val(alldate);
            }
        },datepickersOptout));
    });
    </script>
<?php }else{?>
	<script>
    jQuery(function(){
        var contant = 24*60*60*1000;
        var datepickersOpt = {dateFormat:'d MM yy',minDate:0,autoclose:true}
        var datepickersOptout = {dateFormat:'d MM yy',minDate:1,autoclose:true}
        jQuery("#date-checkin").datepicker(jQuery.extend({
            onSelect: function() {
                jQuery( "#date-checkin" ).datepicker( "option", "closeText", "Close" );
                var minDate = jQuery(this).datepicker('getDate');
                var maxDate = jQuery("#date-checkout").datepicker('getDate');
                var monthstart = (minDate.getMonth() + 1);if(monthstart <= 9){monthstart = "0"+monthstart;}
                var dateStringst = minDate.getFullYear() +"-"+ monthstart +"-"+ minDate.getDate() ;
                var dstart = minDate.getTime();
                var dend = maxDate.getTime();
                var alldate = Math.round(Math.abs((dstart - dend)/(contant)));
                jQuery('#datestart-hidden').val(dateStringst);
                jQuery('#alldate-hidden').val(alldate);
                minDate.setDate(minDate.getDate()+1);
                jQuery("#date-checkout").datepicker("option", "minDate", minDate);
            }
        },datepickersOpt));
        jQuery("#date-checkout").datepicker(jQuery.extend({
            onSelect: function() {
                jQuery( "#date-checkout" ).datepicker( "option", "closeText", "Close" );
                var minDate = jQuery("#date-checkin").datepicker("getDate");
                var maxDate = jQuery(this).datepicker('getDate');
                var monthstart = (minDate.getMonth() + 1);if(monthstart <= 9){monthstart = "0"+monthstart;}
                var dateStringst = minDate.getFullYear() +"-"+ monthstart +"-"+ minDate.getDate() ;
                var dstart = minDate.getTime();
                var dend = maxDate.getTime();
                var alldate = Math.round(Math.abs((dstart - dend)/(contant)));
                if(dend<dstart){
                    var datestarthere = jQuery(this).datepicker('getDate');
                    datestarthere.setDate(datestarthere.getDate()-1);
                    datestarthere = jQuery.datepicker.formatDate("d MM yy",datestarthere);
                    jQuery('#date-checkin').val(datestarthere);
                }
                jQuery('#datestart-hidden').val(dateStringst);
                jQuery('#alldate-hidden').val(alldate);
            }
        },datepickersOptout));
    });
    </script>
<?php }?>
	<script type="text/javascript">
	function frmSub(){
		var language = document.getElementsByTagName("html")[0].getAttribute("lang");
		var f=document.getElementById('searchforma');
		var no_adult=f.no_adult.value;
		var no_child = f.no_child.value;
		if(no_child==1){var childrenage="0";}
		else if(no_child==2){var childrenage="0,0";}
		else{var childrenage="";}
		var datestart=document.getElementById('datestart-hidden').value;
		var alldate=document.getElementById('alldate-hidden').value;
		if(language=="en-gb"){ga('send', 'event', 'Booking Button', 'click', '/WPH/PC/EN');ga('send', 'pageview', '/WPH/PC/EN');}
		else{ga('send', 'event', 'Booking Button', 'click', '/WPH/PC/VN');ga('send', 'pageview', '/WPH/PC/VN');}
		window.open("https://www.yourreservation.net/tb3/index.cfm?bf=HELSGNWP&arrivalDate="+datestart+"&adults="+no_adult+"&nights="
						+alldate+"&children="+no_child+"&childrenages="+childrenage);
	}
	</script>
    <div class="calendar">
    <form id="searchforma" name="hotels" method="post" action="/hotels">
        <span id="two-inputs">
            <input id="date-checkin" class="r9-datepicker-input" size="20" value="" placeholder="<?php echo JText::_('Check-in');?>" readonly="readonly" >
            <input id="date-checkout" class="r9-datepicker-input" size="20" value="" placeholder="<?php echo JText::_('Check-out');?>" readonly="readonly" >
            <input id="datestart-hidden" type="hidden" />
            <input id="alldate-hidden" type="hidden" />
        </span>
        <div class="selectnumber">
            <div class="numadult">
                <select id="no_adult" name="no_adult">
                    <option value="0"><?php echo JText::_('Adults');?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="numchild">
                <select id="no_child" name="no_child" >
                    <option value="0"><?php echo JText::_('Children');?></option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
        </div>
        <div class="formsubmit"><input onclick="frmSub();" type="button" name="submit" class="submit" id="submit" value="<?php echo JText::_('Book Now');?>" /></div>
    </form>
    </div>
</div>
<?php
}
elseif($type==3){
	echo 'bbbbbbbbbbbb';
}
elseif($type==4){
?>
<script type="text/javascript" src="templates/protostar/js/jquery-1.7.1.min.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<div id="map_canvas" style="height:600px;"><div id="map"><span style="color:Gray;">Loading map...</span></div></div><!-- Map Ends display -->		
<script type="text/javascript">
var locations = [
['<div class=info><h4>Sherwood Residence</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_sherwood.jpg><div class="contentmap">127 Pasteur, Dist 3, HCMC</div></div>', 10.782815,106.693156],
['<div class=info><h4>Notre Dame Cathedral</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_duc_ba_church.jpg><div class="contentmap">Han Thuyen, facing down Dong Khoi</div></div>', 10.779786,106.698994],
['<div class=info><h4>Reunification Place</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_dinh_doc_lap.jpg><div class="contentmap">135 Nam Ki Khoi Nghia, Dist 1, HCMC<br>Tel: (848) 3822 3652<br>Fax: (848) 0808 5066</div></div>', 10.777123,106.695457],
['<div class=info><h4>Ho Chi Minh City Post Office</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_hcmc_post_office.jpg><div class="contentmap">125 Hai Ba Trung, Dist 1, HCMC<br>Tel: (848) 3828 2828<br>Fax: ( 848) 3824 2628</div></div>', 10.779487,106.699987],
['<div class=info><h4>War Remnants Museum</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_war_museum.jpg><div class="contentmap">28 Vo Van Tan, Dist 1, HCMC<br>Tel: (848) 3930 6325</div></div>', 10.779422,106.692073],
['<div class=info><h4>People&acute;s Committee Hall</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_ubnd_tphcm.jpg><div class="contentmap">86 Le Thanh Ton, Dist 1, HCMC<br>Tel: (848) 3829 1054</div></div>', 10.7756965,106.7003131],
['<div class=info><h4>The International Primary School</h4><div class="contentmap introtext">21 Ngo Thoi Nhiem, Dist 1, HCMC</div></div>', 10.779716,106.704326],
['<div class=info><h4>Stamford Grammar SLC Kindergarten</h4><div class="contentmap introtext">214 Nam Ky Khoi Nghia</div></div>', 10.7833218,106.6915262],
['<div class=info><h4>Singapore International School & Kinderworld International</h4><div class="contentmap introtext">44 Truong Dinh, Dist 3, HCMC</div></div>', 10.7806079,106.686446],
['<div class=info><h4>Saigon Kids</h4><div class="contentmap introtext">104a Tran Quoc Toan, Dist 3, HCMC</div></div>', 10.784846,106.686444],
['<div class=info><h4>ABC International School</h4><div class="contentmap introtext">28 Truong Dinh, Dist 3,HCMC</div></div>', 10.7789795,106.6883719],
['<div class=info><h4>Australian International School Saigon</h4><div class="contentmap introtext">21 Pham Ngoc Thach, Dist 3, HCMC</div></div>', 10.7840122,106.6943639],
['<div class=info><h4>Diamond Plaza</h4><div class="contentmap introtext">34 Le Duan, Dist 1, HCMC</div></div>', 10.781253,106.698668],
['<div class=info><h4>Co.op Mart</h4><div class="contentmap introtext">168 Nguyen Dinh Chieu, Dist 3, HCMC</div></div>', 10.780359,106.6917735],
['<div class=info><h4>Ben Thanh Market</h4><div class="contentmap introtext">Le Loi, Dist 1, HCMC</div></div>', 10.7725662,106.6979917],
['<div class=info><h4>Parkson Saigontourist Plaza</h4><div class="contentmap introtext">28 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7794381,106.7037517],
['<div class=info><h4>Vincom Center Shopping Mall</h4><div class="contentmap introtext">66 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7781548,106.7019344],
['<div class=info><h4>Saigon Tax Center</h4><div class="contentmap introtext">135 Nguyen Hue, Dist 1, HCMC</div></div>', 10.774901,106.702109],
['<div class=info><h4>Galaxy Cinema</h4><div class="contentmap introtext">116 Nguyen Du, Dist 1, HCMC</div></div>', 10.772962,106.693704],
['<div class=info><h4>Le Van Tam Park</h4><div class="contentmap introtext">Vo Thi Sau and Hai Ba Trung, Dist 3, HCMC</div></div>', 10.7880937,106.6936908],
['<div class=info><h4>April 30th Park</h4><div class="contentmap introtext">Le Duan and Pasteur, Dist 1, HCMC</div></div>', 10.7791832,106.6972834],
['<div class=info><h4>Tao Dan Park</h4><div class="contentmap introtext">55C Nguyen Thi Minh Khai, Dist 3, HCMC</div></div>', 10.7743726,106.6925157],
['<div class=info><h4>Saigon Opera House</h4><div class="contentmap introtext">7 Lam Son Square, Dist 1, HCMC</div></div>', 10.77699,106.703481],
['<div class=info><h4>Parkson Bowling Alley</h4><div class="contentmap introtext">Parkson Plaza, Floor 4<br>28 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7786305,106.702838],
['<div class=info><h4>Phan Dinh Phung Sports Club</h4><div class="contentmap introtext">6 Vo Van Tan, Dist 3, HCMC</div></div>', 10.7814458,106.6948199],
['<div class=info><h4>Marie Curie Sports Center</h4><div class="contentmap introtext">26 Le Quy Don, Dist 3, HCMC</div></div>', 10.7820677,106.6899705],
['<div class=info><h4>United States Consulate</h4><div class="contentmap introtext">4 Le Duan, Dist 1, HCMC</div></div>', 10.783178,106.70043],
['<div class=info><h4>PR China Consulate</h4><div class="contentmap introtext">175 Hai Ba Trung, Dist 1, HCMC</div></div>', 10.7841132,106.6996105],
['<div class=info><h4>Republic of Korea Consulate</h4><div class="contentmap introtext">107 Nguyen Du, Dist 1, HCMC</div></div>', 10.7740672,106.6953574],
['<div class=info><h4>Columbia Saigon Medical Clinic</h4><div class="contentmap introtext">08 Alexandre de Rhodes, Dist 1, HCMC</div></div>', 10.779511,106.696663],
['<div class=info><h4>Family Medical Practice</h4><div class="contentmap introtext">Diamond Plaza, 34 Le Duan, Dist 1, HCMC</div></div>', 10.781471,106.698551],
];
    // Setup the different icons and shadows
    var iconURLPrefix = 'http://sherwoodresidence.com/templates/protostar/images/iconmap/';
    var icons = [  
	  iconURLPrefix + 'img_sherwood_logo.png',
      iconURLPrefix + 'cathedral2.png',
      iconURLPrefix + 'museum-war.png',
      iconURLPrefix + 'postal.png',
      iconURLPrefix + 'museum-war.png',
      iconURLPrefix + 'palace.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'supermarket.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'shoppingmall.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'shoppingmall.png',      
      iconURLPrefix + 'cinema.png',
      iconURLPrefix + 'park.png',      
      iconURLPrefix + 'park.png',
      iconURLPrefix + 'park.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'gym.png',      
      iconURLPrefix + 'gym.png',
      iconURLPrefix + 'embassy.png',      
      iconURLPrefix + 'embassy.png',
      iconURLPrefix + 'embassy.png',      
      iconURLPrefix + 'firstaid.png',
      iconURLPrefix + 'shoppingmall.png',    
      iconURLPrefix + 'firstaid.png'
    ]
    var icons_length = icons.length;
    var shadow = {
      anchor: new google.maps.Point(5,13),
      url: iconURLPrefix + 'msmarker.shadow.png'
    };
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom:17,
      center: new google.maps.LatLng(10.782815,106.693156),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
	  disableDefaultUI: true,
      panControl: false,
	  zoomControl: true
      //zoomControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM}
    });
    var infowindow = new google.maps.InfoWindow({
      maxWidth:745,
	  maxHeight:600
    });
    var marker;
    var markers = new Array();
    var iconCounter = 0;
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2], locations[i][3], locations[i][4], locations[i][5]),
        map: map,
        icon : icons[iconCounter],
        shadow: shadow
      });
      markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      iconCounter++;
      if(iconCounter >= icons_length){
      	iconCounter = 0;
      }
    }
    function mapclick(sott){
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[sott][1],locations[sott][2]),
			map: map,
			icon : icons[sott],
			shadow: shadow
		});
		markers.push(marker);
		infowindow.setContent(locations[sott][0]);
		infowindow.open(map, marker);
    };
    function AutoCenter() {
		var bounds = new google.maps.LatLngBounds();
		$.each(markers, function (index, marker) { bounds.extend(marker.position);});
		map.fitBounds(bounds);
    }
    AutoCenter();
  </script> 
<?php
}
elseif($type==5){
?>
<script type="text/javascript" src="templates/protostar/js/date/date.js"></script>
<script type="text/javascript" src="templates/protostar/js/date/jquery-ui.js"></script>
<script type="text/javascript" src="templates/protostar/js/date/jquery_002.js"></script>
<script type="text/javascript" charset="utf-8">
	$(function(){
		$('#date-pickinquiry')
			.datePicker({createButton:false})
			.bind('click',function(){updateSelects($(this).dpGetSelected()[0]);$(this).dpDisplay();return false;})
			.bind('dateSelected',function(e, selectedDate, $td, state){updateSelects(selectedDate);})
			.bind('dpClosed',function(e, selected){updateSelects(selected[0]);});
		var updateSelects = function (selectedDate){
			var selectedDate = new Date(selectedDate);
			$('#d option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
			$('#m option[value=' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
			$('#y option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
		}
		$('#d, #m, #y').bind('change',function(){
			var d = new Date(
						$('#y').val(),
						$('#m').val()-1,
						$('#d').val()
			);
			$('#date-pickinquiry').dpSetSelected(d.asString());
		});
		var today = new Date();
		updateSelects(today.getTime());
		$('#d').trigger('change');
	});
	function submitinquiry(){
		var f=document.getElementById('chooseDateForm');
		var subject=f.forminquiry_subject.value;
		var name=f.forminquiry_name.value;
		var email=f.forminquiry_email.value;
		var phone=f.forminquiry_phone.value;
		var company=f.forminquiry_company.value;
		var jobtitle=f.forminquiry_jobtitle.value;
		var date=f.d.value;
		var month=f.m.value;
		var year=f.y.value;
		var estmove=month+' '+date+' '+year;
		var estmovedisplay = month+'-'+date+'-'+year;
		var nowdate = new Date();
		var nowtime = nowdate.getTime(); 
		var estmovedate = new Date(estmove);
		var estmovetime = estmovedate.getTime();
		var unittypes1=document.getElementById('unittypes1').checked;
		var unittypes2=document.getElementById('unittypes2').checked;
		var unittypes3=document.getElementById('unittypes3').checked;
		var unittypes4=document.getElementById('unittypes4').checked;
		var unittypes5=document.getElementById('unittypes5').checked;
		var interestin1=document.getElementById('interestin1').checked;
		var interestin2=document.getElementById('interestin2').checked;
		var interestin3=document.getElementById('interestin3').checked;
		var interestin4=document.getElementById('interestin4').checked;
		if (subject == ''){alert('<?php echo JText::_('Please input your subject!');?>');f.forminquiry_subject.focus();}
		else if (name == ''){alert('<?php echo JText::_('Please input your name!');?>');f.forminquiry_name.focus();}
		else if (email == ''){alert('<?php echo JText::_('Please input your email!');?>');f.forminquiry_email.focus();}
			 else if ((email != '') && ((email.indexOf('@', 0) == -1) || (email.indexOf('.') == -1)|| email.length<5))
			 		{alert('<?php echo JText::_('Your email is error, please input again!');?>');f.forminquiry_email.focus();}
		else if (phone == ''){alert('<?php echo JText::_('Please input your number phone!');?>');f.forminquiry_phone.focus();}
		else if(nowtime>estmovetime){alert('<?php echo JText::_('Please input Estimated Move-in!');?>');f.d.focus();}
		else if(unittypes1==false && unittypes2==false && unittypes3==false && unittypes4==false && unittypes5==false){
			alert('<?php echo JText::_('Please check Unit Types!');?>');f.unittypes1.focus();
		}
		else if(interestin1==false && interestin2==false && interestin3==false && interestin4==false){
			alert('<?php echo JText::_('Please check Interested in!');?>');f.interestin1.focus();
		}
		else{
			document.getElementById('datemovein').value=estmovedisplay;
			f.submit();
		}
	}
</script>
<div class="contact-forminquiry">
	<form name="chooseDateForm" id="chooseDateForm" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate form-horizontal">
		<fieldset>
    	<div class="forminquiry_left">
			<?php echo '<h2>'. JText::_('Leasing Inquiry Form').'</h2>';  ?>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Subject *'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_subject" name="forminquiry_subject" class="txt_forminquiry" /></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Name *'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_name" name="forminquiry_name" class="txt_forminquiry" /></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Email *'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_email" name="forminquiry_email" class="txt_forminquiry" /></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Phone *'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_phone" name="forminquiry_phone" class="txt_forminquiry" /></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Company'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_company" name="forminquiry_company" class="txt_forminquiry" /></div>
            </div>
            <div class="control-group">
                <div class="control-label"><?php echo JText::_('Job Title'); ?></div>
                <div class="controls"><input type="text" id="forminquiry_jobtitle" name="forminquiry_jobtitle" class="txt_forminquiry" /></div>
            </div>
    	</div>
    	<div class="forminquiry_right">
        	<?php echo '<h2>'. JText::_('Sherwood Residence').'</h2>';  ?>
            <div class="control-group estimatedmovein">
                <div class="control-label"><?php echo JText::_('Estimated Move-in'); ?></div>
                <div class="controls">
                	<select name="d" id="d" style="width:60px">
                        <option selected="selected" value="0"><?php echo JText::_('Date'); ?></option>
                        <?php 
						for($i=1;$i<=31;$i++){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						?>
                    </select>
                    <select name="m" id="m" style="width:65px">
                        <option selected="selected" value="0"><?php echo JText::_('Month'); ?></option>
                        <option value="1"><?php echo JText::_('month1'); ?></option>
                        <option value="2"><?php echo JText::_('month2'); ?></option>
                        <option value="3"><?php echo JText::_('month3'); ?></option>
                        <option value="4"><?php echo JText::_('month4'); ?></option>
                        <option value="5"><?php echo JText::_('month5'); ?></option>
                        <option value="6"><?php echo JText::_('month6'); ?></option>
                        <option value="7"><?php echo JText::_('month7'); ?></option>
                        <option value="8"><?php echo JText::_('month8'); ?></option>
                        <option value="9"><?php echo JText::_('month9'); ?></option>
                        <option value="10"><?php echo JText::_('month10'); ?></option>
                        <option value="11"><?php echo JText::_('month11'); ?></option>
                        <option value="12"><?php echo JText::_('month12'); ?></option>
                    </select>
                    <select name="y" id="y" style="width:65px">
                        <option selected="selected" value="0"><?php echo JText::_('Year'); ?></option>
                        <?php 
						for($i=2014;$i<=2020;$i++){
							echo '<option value="'.$i.'">'.$i.'</option>';
						}
						?>
                    </select>
                    <!--<a class="dp-applied" href="javascript:void(0);" id="date-pickinquiry">date</a>-->
                    <span class="dp-applied calendaricon" id="date-pickinquiry"></span>
                </div>
            </div>
            <div class="control-group unittypes">
                <div class="control-label"><?php echo JText::_('Unit Types'); ?></div>
                <div class="controls">
                	<span class="checkboxunittypes"><input type="checkbox" id="unittypes1" name="unittypes1" value="<?php echo JText::_('2 bedroom1'); ?>"><?php echo JText::_('2 bedroom1'); ?></span>
                    <span class="checkboxunittypes right"><input type="checkbox" id="unittypes2" name="unittypes2" value="<?php echo JText::_('2 bedroom2'); ?>"><?php echo JText::_('2 bedroom2'); ?></span>
                    <span class="checkboxunittypes"><input type="checkbox" id="unittypes3" name="unittypes3" value="<?php echo JText::_('3 bedroom1'); ?>"><?php echo JText::_('3 bedroom1'); ?></span>
                    <span class="checkboxunittypes right"><input type="checkbox" id="unittypes4" name="unittypes4" value="<?php echo JText::_('3 bedroom2'); ?>"><?php echo JText::_('3 bedroom2'); ?></span>
                    <span class="checkboxunittypes"><input type="checkbox" id="unittypes5" name="unittypes5" value="<?php echo JText::_('Penthouse'); ?>"><?php echo JText::_('Penthouse'); ?></span>
                </div>
            </div>
            <div class="control-group interestedin">
                <div class="control-label"><?php echo JText::_('Interested in'); ?></div>
                <div class="controls">
                	<span class="checkboxinterestin"><input type="checkbox" id="interestin1" name="2room82" value="<?php echo JText::_('Less than 3 months'); ?>"><?php echo JText::_('Less than 3 months'); ?></span>
                    <span class="checkboxinterestin right"><input type="checkbox" id="interestin2" name="2room124" value="<?php echo JText::_('6 months – 1 year'); ?>"><?php echo JText::_('6 months – 1 year'); ?></span>
                    <span class="checkboxinterestin"><input type="checkbox" id="interestin3" name="3room138" value="<?php echo JText::_('1 year – 2 years'); ?>"><?php echo JText::_('1 year – 2 years'); ?></span>
                    <span class="checkboxinterestin right"><input type="checkbox" id="interestin4" name="3room143" value="<?php echo JText::_('2 years – longer'); ?>"><?php echo JText::_('2 years – longer'); ?></span>
                </div>
            </div>
            <div class="control-group comment">
                <div class="control-label"><?php echo JText::_('Comment'); ?></div>
                <div class="controls"><textarea name="textarea_comment" id="textarea_comment" class="textarea_comment"></textarea></div>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary validate" type="button" onclick="submitinquiry();"><?php echo JText::_('Send'); ?></button>
        	<button class="btn btn-primary validate btnreset" type="reset"><?php echo JText::_('Clear'); ?></button>
        </div>
        <input type="hidden" name="datemovein" id="datemovein" value="" />
        <input type="hidden" name="option" value="com_contact" />
        <input type="hidden" name="task" value="contact.submitinquiry" />
        <input type="hidden" name="return" value="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" />
        <input type="hidden" name="id" value="2:inquiry" />
        </fieldset>
    </form>
</div>
<?php
}
elseif($type==6){
?>

<script type="text/javascript" src="templates/protostar/js/date/jquery-ui.js"></script>
	<script type="text/javascript">
        jQuery(function(){
            var tabCounter = 2;
            var tabs = jQuery("#tabs").tabs();
            tabs.bind( "keyup", function( event ){
                if ( event.altKey && event.keyCode === jQuery.ui.keyCode.BACKSPACE ){
                    var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );jQuery( "#" + panelId ).remove();tabs.tabs( "refresh" );}});});
    </script>
    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all pagenewslist" id="tabs">
        <ul role="tablist" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li aria-selected="true" aria-controls="tabs-1" class="ui-state-default ui-corner-top">
                <div class="page_header catroom">
                    <h2><strong><a id="ui-id-1" class="ui-tabs-anchor" href="#tabs-1"><?php echo JText::_('Recent Post'); ?></a></strong></h2>
                </div>
            </li>
            <li aria-selected="true" aria-controls="tabs-2" class="ui-state-default ui-corner-top">
                <div class="page_header catroom">
                    <h2><strong><a id="ui-id-2" class="ui-tabs-anchor" href="#tabs-2"><?php echo JText::_('Year'); ?></a></strong></h2>
                </div>
            </li>
        </ul>
        <div class="ui-tabs-panel" id="tabs-1">
		<?php
			$keywordyearright = date(Y);
            $queryrooms = "SELECT * FROM #__content WHERE catid=".$catid[0]." and publish_up like '%$keywordyearright%' and state=1 order by publish_up desc";
            $db->setQuery($queryrooms);
            $temmenurooms = $db->loadObjectList();
            $querycatroom = 'SELECT * FROM #__categories WHERE parent_id="'.$catid[0].'" and published=1';
            $db->setQuery($querycatroom);
            $temmenuquerycatroom = $db->loadObjectList();
        ?>
            <div class="roomsright">
                <ul>
                <?php 
                if(count($temmenurooms)>=1){
                for($j=0;$j<count($temmenurooms);$j++){
                ?>
                    <li>
                    <a href="index.php?option=com_content&view=article&id=<?php echo $temmenurooms[$j]->id?>&catid=<?php echo $temmenurooms[$j]->catid?>&Itemid=109">
                    <?php echo $temmenurooms[$j]->title;?>
                    <i><?php $datepublishup = strtotime($temmenurooms[$j]->publish_up);echo date("F d, Y",$datepublishup);?></i>
                    </a>
                    </li>
                <?php 
                }}
                if(count($temmenuquerycatroom)>=1){
				for($i=0;$i<count($temmenuquerycatroom);$i++){
					$queryrooms = "";
					$temmenurooms = "";
					$queryrooms = "SELECT * FROM #__content WHERE catid=".$temmenuquerycatroom[$i]->id." and publish_up like '%$keywordyearright%' and state=1";
					$db->setQuery( $queryrooms );
					$temmenurooms = $db->loadObjectList();
					for($k=0;$k<count($temmenurooms);$k++){
			?>
                    <li>
                    <a href="index.php?option=com_content&view=article&id=<?php echo $temmenurooms[$k]->id?>&catid=<?php echo $temmenurooms[$k]->catid?>&Itemid=109">
                    <?php echo $temmenurooms[$k]->title;?>
                    <i><?php $datepublishup = strtotime($temmenurooms[$k]->publish_up);echo date("F d, Y",$datepublishup);?></i>
                    </a>
                    </li>
			<?php 
					}
				}}
                ?>
                </ul>
            </div>
        </div>
        <div class="ui-tabs-panel" id="tabs-2">
        	<div class="roomsright">
        <?php
		$wordfind = $_POST['yearnewsofinput'];
        if($lang=="en"){$linkform = "http://sherwoodresidence.com/index.php/en/news-year";}else if($lang=="vn"){$linkform = "http://sherwoodresidence.com/index.php/en/news-year";}else{$linkform = "http://sherwoodresidence.com/index.php/en/news-year";}?><script>function formyearnews(yearnewsinput){var f=document.getElementById('yearnews');document.getElementById('yearnewsofinput').value = yearnewsinput;f.submit();}</script>
        <form action="<?php echo $linkform;?>" method="post" id="yearnews" class="yearnews" name="yearnews" >
		<?php 
		$datenow = date('Y-d-m');
		$datenow = explode('-',$datenow);
		$datenow = $datenow[0];
		for($i=$datenow;$i>=2011;$i--){
			$nameclass="";
			if($wordfind==$i){$nameclass = "active";}
		?>
        	<button id="yearnewsof" name="yearnewsof" class="yearnewsof <?php echo $nameclass;?>" value="<?php echo $i;?>" onclick="formyearnews(<?php echo $i;?>);">
			<?php echo $i;?>
            </button>
		<?php }?>
            <input type="hidden" name="yearnewsofinput" id="yearnewsofinput" value="" />
        </form>
        	</div>
        </div>
    </div>
<?php
}
elseif($type==7){
?>
<script type="text/javascript" src="templates/protostar/js/jquery-1.7.1.min.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<div id="map_canvas" style="height:240px;"><div id="map"><span style="color:Gray;">Loading map...</span></div></div><!-- Map Ends display -->
<div class="zoominzoomin">
	<a 
</div>
		
<script type="text/javascript">
var locations = [
['<div class=info><h4>Sherwood Residence</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_sherwood.jpg><div class="contentmap">127 Pasteur, Dist 3, HCMC</div></div>', 10.782815,106.693156],
['<div class=info><h4>Notre Dame Cathedral</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_duc_ba_church.jpg><div class="contentmap">Han Thuyen, facing down Dong Khoi</div></div>', 10.779786,106.698994],
['<div class=info><h4>Reunification Place</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_dinh_doc_lap.jpg><div class="contentmap">135 Nam Ki Khoi Nghia, Dist 1, HCMC<br>Tel: (848) 3822 3652<br>Fax: (848) 0808 5066</div></div>', 10.777123,106.695457],
['<div class=info><h4>Ho Chi Minh City Post Office</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_hcmc_post_office.jpg><div class="contentmap">125 Hai Ba Trung, Dist 1, HCMC<br>Tel: (848) 3828 2828<br>Fax: ( 848) 3824 2628</div></div>', 10.779487,106.699987],
['<div class=info><h4>War Remnants Museum</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_war_museum.jpg><div class="contentmap">28 Vo Van Tan, Dist 1, HCMC<br>Tel: (848) 3930 6325</div></div>', 10.779422,106.692073],
['<div class=info><h4>People&acute;s Committee Hall</h4><img src=http://www.windsorplazahotel.com/emarketing/various/img_ubnd_tphcm.jpg><div class="contentmap">86 Le Thanh Ton, Dist 1, HCMC<br>Tel: (848) 3829 1054</div></div>', 10.7756965,106.7003131],
['<div class=info><h4>The International Primary School</h4><div class="contentmap introtext">21 Ngo Thoi Nhiem, Dist 1, HCMC</div></div>', 10.779716,106.704326],
['<div class=info><h4>Stamford Grammar SLC Kindergarten</h4><div class="contentmap introtext">214 Nam Ky Khoi Nghia</div></div>', 10.7833218,106.6915262],
['<div class=info><h4>Singapore International School & Kinderworld International</h4><div class="contentmap introtext">44 Truong Dinh, Dist 3, HCMC</div></div>', 10.7806079,106.686446],
['<div class=info><h4>Saigon Kids</h4><div class="contentmap introtext">104a Tran Quoc Toan, Dist 3, HCMC</div></div>', 10.784846,106.686444],
['<div class=info><h4>ABC International School</h4><div class="contentmap introtext">28 Truong Dinh, Dist 3,HCMC</div></div>', 10.7789795,106.6883719],
['<div class=info><h4>Australian International School Saigon</h4><div class="contentmap introtext">21 Pham Ngoc Thach, Dist 3, HCMC</div></div>', 10.7840122,106.6943639],
['<div class=info><h4>Diamond Plaza</h4><div class="contentmap introtext">34 Le Duan, Dist 1, HCMC</div></div>', 10.781253,106.698668],
['<div class=info><h4>Co.op Mart</h4><div class="contentmap introtext">168 Nguyen Dinh Chieu, Dist 3, HCMC</div></div>', 10.780359,106.6917735],
['<div class=info><h4>Ben Thanh Market</h4><div class="contentmap introtext">Le Loi, Dist 1, HCMC</div></div>', 10.7725662,106.6979917],
['<div class=info><h4>Parkson Saigontourist Plaza</h4><div class="contentmap introtext">28 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7794381,106.7037517],
['<div class=info><h4>Vincom Center Shopping Mall</h4><div class="contentmap introtext">66 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7781548,106.7019344],
['<div class=info><h4>Saigon Tax Center</h4><div class="contentmap introtext">135 Nguyen Hue, Dist 1, HCMC</div></div>', 10.774901,106.702109],
['<div class=info><h4>Galaxy Cinema</h4><div class="contentmap introtext">116 Nguyen Du, Dist 1, HCMC</div></div>', 10.772962,106.693704],
['<div class=info><h4>Le Van Tam Park</h4><div class="contentmap introtext">Vo Thi Sau and Hai Ba Trung, Dist 3, HCMC</div></div>', 10.7880937,106.6936908],
['<div class=info><h4>April 30th Park</h4><div class="contentmap introtext">Le Duan and Pasteur, Dist 1, HCMC</div></div>', 10.7791832,106.6972834],
['<div class=info><h4>Tao Dan Park</h4><div class="contentmap introtext">55C Nguyen Thi Minh Khai, Dist 3, HCMC</div></div>', 10.7743726,106.6925157],
['<div class=info><h4>Saigon Opera House</h4><div class="contentmap introtext">7 Lam Son Square, Dist 1, HCMC</div></div>', 10.77699,106.703481],
['<div class=info><h4>Parkson Bowling Alley</h4><div class="contentmap introtext">Parkson Plaza, Floor 4<br>28 Le Thanh Ton, Dist 1, HCMC</div></div>', 10.7786305,106.702838],
['<div class=info><h4>Phan Dinh Phung Sports Club</h4><div class="contentmap introtext">6 Vo Van Tan, Dist 3, HCMC</div></div>', 10.7814458,106.6948199],
['<div class=info><h4>Marie Curie Sports Center</h4><div class="contentmap introtext">26 Le Quy Don, Dist 3, HCMC</div></div>', 10.7820677,106.6899705],
['<div class=info><h4>United States Consulate</h4><div class="contentmap introtext">4 Le Duan, Dist 1, HCMC</div></div>', 10.783178,106.70043],
['<div class=info><h4>PR China Consulate</h4><div class="contentmap introtext">175 Hai Ba Trung, Dist 1, HCMC</div></div>', 10.7841132,106.6996105],
['<div class=info><h4>Republic of Korea Consulate</h4><div class="contentmap introtext">107 Nguyen Du, Dist 1, HCMC</div></div>', 10.7740672,106.6953574],
['<div class=info><h4>Columbia Saigon Medical Clinic</h4><div class="contentmap introtext">08 Alexandre de Rhodes, Dist 1, HCMC</div></div>', 10.779511,106.696663],
['<div class=info><h4>Family Medical Practice</h4><div class="contentmap introtext">Diamond Plaza, 34 Le Duan, Dist 1, HCMC</div></div>', 10.781471,106.698551],
];
    // Setup the different icons and shadows
    var iconURLPrefix = 'http://sherwoodresidence.com/templates/protostar/images/iconmap/';
    var icons = [  
	  iconURLPrefix + 'img_sherwood_logo.png',
      iconURLPrefix + 'cathedral2.png',
      iconURLPrefix + 'museum-war.png',
      iconURLPrefix + 'postal.png',
      iconURLPrefix + 'museum-war.png',
      iconURLPrefix + 'palace.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'school.png',
      iconURLPrefix + 'school.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'supermarket.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'shoppingmall.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'shoppingmall.png',      
      iconURLPrefix + 'cinema.png',
      iconURLPrefix + 'park.png',      
      iconURLPrefix + 'park.png',
      iconURLPrefix + 'park.png',      
      iconURLPrefix + 'shoppingmall.png',
      iconURLPrefix + 'gym.png',      
      iconURLPrefix + 'gym.png',
      iconURLPrefix + 'embassy.png',      
      iconURLPrefix + 'embassy.png',
      iconURLPrefix + 'embassy.png',      
      iconURLPrefix + 'firstaid.png',
      iconURLPrefix + 'shoppingmall.png' ,   
      iconURLPrefix + 'firstaid.png'
    ]
    var icons_length = icons.length;
    var shadow = {
      anchor: new google.maps.Point(5,13),
      url: iconURLPrefix + 'msmarker.shadow.png'
    };
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom:17,
      center: new google.maps.LatLng(10.782815,106.693156),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
	  disableDefaultUI: true,
      panControl: false,
	  zoomControl: true
      //zoomControlOptions: {position: google.maps.ControlPosition.LEFT_BOTTOM}
    });
    var infowindow = new google.maps.InfoWindow({
      maxWidth:745,
	  maxHeight:240
    });
    var marker;
    var markers = new Array();
    var iconCounter = 0;
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2], locations[i][3], locations[i][4], locations[i][5]),
        map: map,
        icon : icons[iconCounter],
        shadow: shadow
      });
      markers.push(marker);
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      iconCounter++;
      if(iconCounter >= icons_length){
      	iconCounter = 0;
      }
    }
    function mapclick(sott){
	   infowindow.setContent(locations[sott][0]);
	   infowindow.open(map, marker);
    };
    function AutoCenter() {
		var bounds = new google.maps.LatLngBounds();
		$.each(markers, function (index, marker) { bounds.extend(marker.position);});
		map.fitBounds(bounds);
    }
    AutoCenter();
  </script> 
<?php
}
elseif($type==8){
	$sql="SELECT * from #__content where catid='".$catid[0]."' and state=1";
	$db->setQuery($sql);
	$lists = $db->loadObjectList();
	/*--Lay menu item--*/
	$keyarticle = "option=com_content&view=article";
	$keyblog = "option=com_content&view=category";
	$sqlmenuarticle="SELECT id,link from #__menu where link like '%$keyarticle%' and published=1";
	$db->setQuery($sqlmenuarticle);
	$listsmenuarticle = $db->loadObjectList();
	for($i=0;$i<count($listsmenuarticle);$i++){
		$idlinkarticle = $listsmenuarticle[$i]->link;
		$idlinkarticle = explode('&id=',$idlinkarticle);
		$idlinkarticle = $idlinkarticle[1];
		if($idlinkarticle==$lists[0]->id){
			$Itemidmenu = $listsmenuarticle[$i]->id;
		}
	}
	$sqlmenublog="SELECT id,link from #__menu where link like '%$keyblog%' and published=1";
	$db->setQuery($sqlmenublog);
	$listsmenublog = $db->loadObjectList();
	for($i=0;$i<count($listsmenublog);$i++){
		$idlinkblog = $listsmenublog[$i]->link;
		$idlinkblog = explode('&id=',$idlinkblog);
		$idlinkblog = $idlinkblog[1];
		if($idlinkblog==$catid[0]){
			$Itemidmenu = $listsmenublog[$i]->id;
		}
	}
	echo '<ul>';
	for($i=0;$i<count($lists);$i++){
		$classactive = "";
		if($Itemid==$Itemidmenu){
			if($idbaiviet==$lists[$i]->id){
				$classactive = " class='active'";
			}
		}
		echo "<li".$classactive."><a href='index.php?option=com_content&view=article&id=".$lists[$i]->id."&catid=".$lists[$i]->catid."&Itemid=".$Itemidmenu."'>".$lists[$i]->title."</a></li>";
	}
	echo '</ul>';
}
elseif($type==9){
	$sql="SELECT * from #__content where catid='".$catid[0]."' and state=1 limit 0,1";
	$db->setQuery($sql);
	$lists = $db->loadObjectList();
	/*--Lay menu item--*/
	$keyarticle = "option=com_content&view=article";
	$keyblog = "option=com_content&view=category";
	$sqlmenuarticle="SELECT id,link from #__menu where link like '%$keyarticle%' and published=1";
	$db->setQuery($sqlmenuarticle);
	$listsmenuarticle = $db->loadObjectList();
	for($i=0;$i<count($listsmenuarticle);$i++){
		$idlinkarticle = $listsmenuarticle[$i]->link;
		$idlinkarticle = explode('&id=',$idlinkarticle);
		$idlinkarticle = $idlinkarticle[1];
		if($idlinkarticle==$lists[0]->id){
			$Itemidmenu = $listsmenuarticle[$i]->id;
		}
	}
	$sqlmenublog="SELECT id,link from #__menu where link like '%$keyblog%' and published=1";
	$db->setQuery($sqlmenublog);
	$listsmenublog = $db->loadObjectList();
	for($i=0;$i<count($listsmenublog);$i++){
		$idlinkblog = $listsmenublog[$i]->link;
		$idlinkblog = explode('&id=',$idlinkblog);
		$idlinkblog = $idlinkblog[1];
		if($idlinkblog==$catid[0]){
			$Itemidmenu = $listsmenublog[$i]->id;
		}
	}
	$imgintro = $lists[0]->images;
	$imgintro = explode('image_intro":"',$imgintro);
	$imgintro = explode('","',$imgintro[1]);
	$imgintro = str_replace('\/','/',$imgintro[0]);
	?>
    <div class="contentitem">
    	<a href="index.php?option=com_content&view=article&id=<?php echo $lists[0]->id?>&catid=<?php echo $catid[0];?>&Itemid=<?php echo $Itemidmenu?>">
    	<img src="<?php echo $imgintro; ?>" alt="<?php echo $lists[0]->alias; ?>" title="<?php echo $lists[0]->title; ?>" />
        </a>
        <h2 class="titlearticle"><strong><?php echo $lists[0]->title; ?></strong></h2>
        <?php 
		$textfull = $lists[0]->introtext.$lists[0]->fulltext;
		$intro = strip_tags($textfull);
		if (strlen($intro) > 120){
			$intro = substr($intro, 0, 120);
			$endintro = strrpos($intro,' ');
			$intro = substr($intro, 0, $endintro);
		}
		echo $intro;
		?>
        <p class="viewreadmore"><a href="index.php?option=com_content&view=article&id=29&Itemid=107"><?php echo JText::_('Contact Us'); ?></a></p>
    </div>
    <?php
}
elseif($type==10){
	$sql="SELECT * from #__content where catid='".$catid[0]."' and state=1 limit 0,1";
	$db->setQuery($sql);
	$lists = $db->loadObjectList();
	/*--Lay menu item--*/
	$keyarticle = "option=com_content&view=article";
	$keyblog = "option=com_content&view=category";
	$sqlmenuarticle="SELECT id,link from #__menu where link like '%$keyarticle%' and published=1";
	$db->setQuery($sqlmenuarticle);
	$listsmenuarticle = $db->loadObjectList();
	for($i=0;$i<count($listsmenuarticle);$i++){
		$idlinkarticle = $listsmenuarticle[$i]->link;
		$idlinkarticle = explode('&id=',$idlinkarticle);
		$idlinkarticle = $idlinkarticle[1];
		if($idlinkarticle==$lists[0]->id){
			$Itemidmenu = $listsmenuarticle[$i]->id;
		}
	}
	$sqlmenublog="SELECT id,link from #__menu where link like '%$keyblog%' and published=1";
	$db->setQuery($sqlmenublog);
	$listsmenublog = $db->loadObjectList();
	for($i=0;$i<count($listsmenublog);$i++){
		$idlinkblog = $listsmenublog[$i]->link;
		$idlinkblog = explode('&id=',$idlinkblog);
		$idlinkblog = $idlinkblog[1];
		if($idlinkblog==$catid[0]){
			$Itemidmenu = $listsmenublog[$i]->id;
		}
	}
	$imgintro = $lists[0]->images;
	$imgintro = explode('image_intro":"',$imgintro);
	$imgintro = explode('","',$imgintro[1]);
	$imgintro = str_replace('\/','/',$imgintro[0]);
	?>
    <div class="contentitem">
    	<a href="index.php?option=com_content&view=article&id=<?php echo $lists[0]->id?>&catid=<?php echo $catid[0];?>&Itemid=<?php echo $Itemidmenu?>">
    	<img src="<?php echo $imgintro; ?>" alt="<?php echo $lists[0]->alias; ?>" title="<?php echo $lists[0]->title; ?>" />
        </a>
        <h2 class="titlearticle"><strong><?php echo $lists[0]->title; ?></strong></h2>
        <?php 
		$textfull = $lists[0]->introtext.$lists[0]->fulltext;
		$intro = strip_tags($textfull);
		if (strlen($intro) > 120){
			$intro = substr($intro, 0, 120);
			$endintro = strrpos($intro,' ');
			$intro = substr($intro, 0, $endintro);
		}
		echo $intro;
		?>
        <p class="viewreadmore"><a href="index.php?option=com_content&view=article&id=<?php echo $lists[0]->id?>&catid=<?php echo $catid[0];?>&Itemid=<?php echo $Itemidmenu?>"><?php echo JText::_('Read More'); ?></a></p>
    </div>
    <?php
}
?>