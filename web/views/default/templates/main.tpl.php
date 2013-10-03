<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>{title}</title>
	<meta name="robots" content="index, follow" />
	<meta name="description" content="{meta-description}" />
	<meta name="keywords" content="{meta-keywords}" />
	<meta name="author" content="Jakub Dubec" />
	<meta name="viewport" content="width=device-width" />
	
	<!-- CSS -->
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/foundation.min.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/normalize.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/fonts.css" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/style.css" />
		
	<!-- JS -->

	<script src="{siteurl}/views/{defaultView}/js/jquery-2.0.0.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/jquery-ui-timepicker-addon.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/moment.min.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/scripts.js"></script>
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script src="https://apis.google.com/js/plusone.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/vendor/custom.modernizr.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/foundation/foundation.abide.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/foundation/foundation.alerts.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.forms.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.orbit.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.placeholder.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.reveal.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/foundation/foundation.section.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/foundation/foundation.tooltips.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/foundation/foundation.topbar.js"></script>

	<!-- Jebnuty explorer! -->
	<!--[if lt IE 8]><script type="text/javascript">alert("Your browser is obsolete, please use Mozilla Firefox!");</script><![endif]-->
	<!--[if IE]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
</head>
<body>
	{content}
	<footer>
		<div class="row">
			<div class="large-3 columns">
				<img src="{siteurl}/views/{defaultView}/images/logoSkola.png" alt="GVPT" style="height:80px; margin:5px;"/>
			</div>
			<div class="large-6 columns">
				<strong>Jakub Dubec</strong>
				<br />
				All rights reserved. &copy;
			</div>
			<div class="large-3 columns">
				<a href="http://www.w3.org/html/logo/" rel="external" target="_blank"><img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-performance-semantics.png" width="197" height="64" alt="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics" title="HTML5 Powered with CSS3 / Styling, Performance &amp; Integration, and Semantics" /></a>
			</div>
		</div>
	</footer>
    <div id="myModal" class="reveal-modal medium">
    </div>
    <div id="loader" class="reveal-modal text-center small">
        <span>Načítavam</span>
        <br />
        <img src="{siteurl}/views/{defaultView}/images/ajax-loader.gif" alt="AJAX Loader"/>
    </div>
    <div class="reveal-modal-bg" style="display: none"></div>
    <script>
		$(document).foundation();
	</script>
	<!-- This page was generated with my custom PHP framework -->
</body>
</html>