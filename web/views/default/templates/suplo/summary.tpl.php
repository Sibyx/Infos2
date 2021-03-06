<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>{title}</title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Jakub Dubec" />
    <meta name="viewport" content="width=device-width" />

    <!-- CSS -->
    <link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/normalize.css" />
    <link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/fonts.css" />
    <link rel="stylesheet" href="{siteurl}/views/{defaultView}/css/print.css" />

</head>
<body>
<table>
    <caption>{lang_suploHistoryHeader}</caption>
    <thead>
    <tr>
        <td colspan="3" class="text-center">{lang_month}: {month}</td>
        <td colspan="2" class="text-center">{lang_teacherName}: {teacherName}</td>
    </tr>
    <tr>
        <th>{lang_date}</th>
        <th>{lang_class}</th>
        <th>{lang_subject}</th>
        <th>{lang_instead}</th>
        <th>{lang_note}</th>
    </tr>
    </thead>
    <tbody>
    {suploHistory}
    </tbody>
    <tfoot>
    <tr>
        <td colspan="5" class="text-center">{lang_suploHistoryMessage}</td>
    </tr>
    <tr>
        <td colspan="4" class="text-right">{lang_signature}:</td>
        <td colspan="1" style="border-bottom: 1px dotted #000000"></td>
    </tr>
    </tfoot>
</table>
<script>
	var _paq = _paq || [];
	_paq.push(["trackPageView"]);
	_paq.push(['trackGoal', 3]);
	_paq.push(["enableLinkTracking"]);
	(function() {
		var u=(("https:" == document.location.protocol) ? "https" : "http") + "://gymmt.sk/piwik/";
		_paq.push(["setTrackerUrl", u+"piwik.php"]);
		_paq.push(["setSiteId", "1"]);
		var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
		g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
	})();
    window.print();
</script>
<!-- This page was generated with my custom PHP framework -->
</body>
</html>