<?php
/**@package     Joomla.Site
 * @subpackage  Templates.protostar
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt*/
defined('_JEXEC') or die;
// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');
if($task == "edit" || $layout == "form" ){$fullWidth = 1;}
else{$fullWidth = 0;}
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript('templates/' .$this->template. '/js/template.js');
// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);
// Add current user information
$user = JFactory::getUser();
// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8')){$span = "span6";}
elseif ($this->countModules('position-7') && !$this->countModules('position-8')){$span = "span9";}
elseif (!$this->countModules('position-7') && $this->countModules('position-8')){$span = "span9";}
else{$span = "span12";}
// Logo file or site title param
if ($this->params->get('logoFile')){$logo = '<img src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';}
elseif ($this->params->get('sitetitle')){$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';}
else{$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
	function IsMobileBrowser(){
	   global $_SERVER;
	   if (isset($_SERVER['HTTP_X_WAP_PROFILE']))return true;
	   if (isset($_SERVER['HTTP_ACCEPT'])){
		  $userAccept = strtolower($_SERVER['HTTP_ACCEPT']);
		  if (!(stristr($userAccept, 'wap')===FALSE))return true;
	   }
	   $userBrowser = strtolower($_SERVER['HTTP_USER_AGENT']);
	   $mobiles = array(
		  "midp", "j2me", "avant", "docomo","novarra", "palmos", "palmsource","240x320", "opwv", "chtml",
		  "pda", "windows ce", "mmp/","blackberry", "mib/", "symbian","wireless", "nokia", "hand", "mobi",
		  "phone", "cdm", "up.b", "audio","SIE-", "SEC-", "samsung", "HTC","mot-", "mitsu", "sagem", "sony", 
		  "alcatel", "lg", "eric", "vx","NEC", "philips", "mmm", "xx","panasonic", "sharp", "wap", "sch","rover", 
		  "pocket", "benq", "java","pt", "pg", "vox", "amoi","bird", "compal", "kg", "voda","sany", "kdd", "dbt", "sendo",
		  "sgh", "gradi", "jb", "dddi","moto", "iphone"
	   );
	   foreach ($mobiles as $value){if (!(stristr($userBrowser, $value)===FALSE)) return true;}
	   return false;
	}
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false){
		if(IsMobileBrowser()){ // neu la mobile chuyen link qua mobile
		?>
			<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/protostar/css/mobile.css" type="text/css" />
		<?php
		}
	}
	else{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/protostar/css/tablet.css" type="text/css" />
	<?php
	}
	?>
	<jdoc:include type="head" />
	<?php if ($this->params->get('googleFont')){?>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,700,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
	<style type="text/css">h1,h2,h3,h4,h5,h6,.site-title{font-family: 'Open Sans',sans-serif;}</style>
	<?php }?>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/jquery.easing.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/jquery.flexslider.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/jquery.carouFredSel.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/superfish.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/custom.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/protostar/js/jquery.slides.min.js"></script>
	<script src="http://www.jscache.com/wejs?wtype=tchotel&uniq=136&locationId=1026679&lang=en_US&year=2014"></script>
	<?php
	// Template color
	if ($this->params->get('templateColor')){
	?>
	<style type="text/css">
		body.site{
			border-top: 3px solid <?php echo $this->params->get('templateColor');?>;
			background-color: <?php echo $this->params->get('templateBackgroundColor');?>
		}
		a{color: <?php echo $this->params->get('templateColor');?>;}
		.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover,
		.btn-primary{background: <?php echo $this->params->get('templateColor');?>;}
		.navbar-inner{
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
		}
	</style>
	<?php
	}
	?>
	<script> 
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-10021209-1', 'auto');
		ga('require', 'displayfeatures');
		ga('send', 'pageview');
	</script>
	<!--[if lt IE 9]><script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script><![endif]-->
</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">
	<!-- Body -->
	<div class="body">
    	<div id="top-bar">
            <div class="container">
                <jdoc:include type="modules" name="position-0" style="well" />
            </div>
        </div>
        <div id="header">
        	<div class="container">
            	<?php if ($this->countModules('logo')) : ?>
                <div id="logo">
                    <jdoc:include type="modules" name="logo" style="none" />
                </div>
                <?php endif; ?>
                
                <div id="primary-menu" class="navigation" role="navigation">
                <?php
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false){
                    if(IsMobileBrowser()){?><a class="getdisrections" href="http://windsorplazahotel.com/wphnew/mobile_app/"></a><jdoc:include type="modules" name="menumobile" style="none" /><?php }
					else{?><jdoc:include type="modules" name="position-1" style="none" /><jdoc:include type="modules" name="menumobile" style="none" /><?php }
                }
                else{?><jdoc:include type="modules" name="position-1" style="none" /><jdoc:include type="modules" name="menumobile" style="none" /><?php }?>
                </div>
            </div>
        </div>
        <div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
            <header class="header" role="banner">
                <jdoc:include type="modules" name="banner" style="xhtml" />
                <?php
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false){
                    if(IsMobileBrowser()){?><a class="getdisrections" href="http://windsorplazahotel.com/wphnew/mobile_app/"></a><jdoc:include type="modules" name="bookingmobile" style="none" /><?php }
					else{?><jdoc:include type="modules" name="booking" style="none" /><?php }
                }
                else{?><jdoc:include type="modules" name="booking" style="none" /><?php }?>
            </header>
            <div class="row-fluid">
                <jdoc:include type="modules" name="position-2" style="none" />
                <?php if ($this->countModules('position-8')) : ?>
                <div id="sidebar" class="span3">
                    <div class="sidebar-nav">
                        <jdoc:include type="modules" name="position-8" style="aimod" />
                    </div>
                </div>
                <?php endif; ?>
                <main id="content" role="main" class="<?php echo $span;?>">
                    <!-- Begin Content -->
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                    <jdoc:include type="modules" name="position-3" style="xhtml" />
                    <!-- End Content -->
                </main>
                <?php if ($this->countModules('position-9')) : ?>
                    <jdoc:include type="modules" name="position-9" style="aimod" />
                <?php endif; ?>
            </div>
        </div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
        	<div class="footercontent">
			<?php if ($this->countModules('position-7')) : ?>
                <jdoc:include type="modules" name="position-7" style="aimod" />
            <?php endif; ?>
			</div>
        	<div class="footercontent bottomfooterfooter">
                <jdoc:include type="modules" name="position-10" style="aimod" />
                <p>&copy; <?php echo date('Y'); ?> <?php echo JText::_('Sherwood Residence'); ?></p>
                <jdoc:include type="modules" name="footer" style="aimod" />
        	</div>
        </div>
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>