<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>{title}</title>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="author" content="Jakub Dubec" />
	<meta name="viewport" content="width=device-width" />
    <link href="https://plus.google.com/113484997719165826845" rel="publisher" />
	
	<!-- CSS -->
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/foundation.min.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/normalize.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/fonts.css" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/webicons.css" />
	<link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/style.css" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{siteurl}/views/{defaultView}/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="{siteurl}/views/{defaultView}/images/favicon.ico" type="image/x-icon">
		
	<!-- JS -->
	<script src="{siteurl}/views/{defaultView}/js/vendor/jquery.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/jquery-ui-timepicker-addon.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/moment.min.js"></script>
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script src="https://apis.google.com/js/plusone.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/vendor/custom.modernizr.js"></script>
	<script src="{siteurl}/views/{defaultView}/js/html5ext.js"></script>
    <script src="{siteurl}/views/{defaultView}/js/scripts.js"></script>

	<!-- Jebnuty explorer! -->
	<!--[if lt IE 8]><script type="text/javascript">alert("Your browser is obsolete, please use Mozilla Firefox!");</script><![endif]-->
	<!--[if IE]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<script>
		var _paq = _paq || [];
		_paq.push(['setCustomVariable',
			1,
			"UserName",
			"{userFullName}",
			"visit"
		]);
	</script>
	
</head>
<body>
	{content}
	<footer>
		<div class="row">
			<div class="large-3 columns">
				<img src="{siteurl}/views/{defaultView}/images/logo.png" alt="Infos" style="height:30px; width: auto; margin:5px;"/>
			    <p class="text-right white"><small style="font-size: x-small">by <a href="http://jakubdubec.me" target="_blank" rel="author">Jakub Dubec</a> &copy; 2014</small></p>
            </div>
			<div class="large-5 columns">
                <ul class="inline-list text-left">
                    <li><a href="{siteurl}/about">{lang_aboutProject}</a></li>
                    <li><a target="_blank" href="{siteurl}/about/blog">{lang_aboutBlog}</a></li>
                    <li><a href="{siteurl}/about/bug">{lang_reportBug}</a></li>
                </ul>
			</div>
			<div class="large-4 columns text-right">
                <a href="https://plus.google.com/113484997719165826845" rel="publisher" class="webicon googleplus large svg" target="_blank">Follow us on Google+</a>
			</div>
		</div>
	</footer>
    <div id="myModal" class="reveal-modal medium" data-reveal>
    </div>
    <div id="loader" class="reveal-modal text-center small" data-reveal>
        <span>{lang_loading}</span>
        <br />
        <img src="{siteurl}/views/{defaultView}/images/ajax-loader.gif" alt="AJAX Loader"/>
    </div>
    <div class="reveal-modal-bg" style="display: none"></div>
    <script src="{siteurl}/views/{defaultView}/js/foundation.min.js"></script>
    <script>
		$(document).foundation();
	</script>
	<!-- This page was generated with my custom PHP framework -->
</body>
</html>