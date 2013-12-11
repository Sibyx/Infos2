<h1 class="hide">Infos2</h1>
<div class="row">
	<div class="large-7 columns">
		<section id="panelAnnouncements">
            <div class="boardPanel large-12 columns">
                <header class="row">
                    <div class="small-10 columns">
                        <a href="{siteurl}/announcements/"><h2>{lang_announcements}</h2></a>
                    </div>
                    <div class="small-2 columns text-right">
                        <a href="{siteurl}/announcements/new"><img src="{siteurl}/views/{defaultView}/images/add.png" title="{lang_addAnnouncement}" alt="{lang_addAnnouncement}" style="margin-top: 13px"/></a>
                    </div>
                </header>
                {announcements}
            </div>
		</section>
	</div>
	
	<div class="large-5 columns">
		<section class="userPanel" id="panelUser">
			<header><h2>{userFullName}</h2></header>
			<time datetime="{serverTime}" id="serverTime">{serverTimeFormated}</time>
			<table id="aktualne">
				<tr>
					<td>{lang_actual}:</td>
					<td id="current">{current}</td>
				</tr>
				<tr>
					<td>{lang_next}:</td>
					<td id="next">{next}</td>
				</tr>
			</table>
			<hr />
            <div class="text-right">
                <a href="https://gymmt.edupage.org/login/index.php" target="_blank"><img src="{siteurl}/views/{defaultView}/images/izk.png" alt="Žiacka knižka" title="Žiacka knižka" class="icon"/></a>
                <a href="{siteurl}/profile/settings"><img src="{siteurl}/views/{defaultView}/images/settings.png" alt="{lang_settings}" title="{lang_settings}" class="icon"/></a>
                <a href="{siteurl}/authenticate/logout"><img src="{siteurl}/views/{defaultView}/images/logout.png" alt="{lang_logout}" title="{lang_logout}" class="icon"/></a>
            </div>

		</section>

		<section class="boardPanel" id="panelSuplo">
            <header>
                <div class="small-10 columns">
                    <a href="{siteurl}/suplo/"><h2>{lang_suplo}</h2></a>
                </div>
                <div class="small-2 columns text-right">
                    <a href="{siteurl}/suplo/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="{lang_addSuplo}" title="{lang_addSuplo}" style="margin-top: 13px"/></a>
                </div>
            </header>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td>{lang_hour}</td>
                        <td>{lang_class}</td>
                        <td>{lang_subject}</td>
                        <td>{lang_classroom}</td>
                        <td>{lang_instead}</td>
                    </tr>
                </thead>
                <tbody>
                    {suploToday}
                    {suploTomorow}
                </tbody>
            </table>
		</section>
		
		<section class="boardPanel" id="panelEvents">
            <header>
                <div class="small-10 columns">
                    <a href="{siteurl}/events/"><h2>{lang_events}</h2></a>
                </div>
                <div class="small-2 columns text-right">
                    <a href="{siteurl}/events/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="{lang_addEvent}" title="{lang_addEvent}" style="margin-top: 13px"/></a>
                </div>
            </header>
            <table style="width: 100%">
                <thead>
                <tr>
                    <td>{lang_title}</td>
                    <td>{lang_time}</td>
                    <td>{lang_place}</td>
                </tr>
                </thead>
                <tbody>
                    {events}
                </tbody>
            </table>
		</section>

        <section class="boardPanel" id="panelDocs">
            <header>
                <div class="small-12 columns">
                    <h2>{lang_docs}</h2>
                </div>
            </header>
            <table style="width: 100%">
                <tbody>
                <tr>
                    <td><a target="_blank" href="https://drive.google.com/a/gymmt.sk/folderview?id=0B30M7T6N6wkbTWZlY2FBTHJhN2M&usp=sharing"><img src="{siteurl}/views/{defaultView}/images/folder.png" alt="Folder" /> Pracovné normy</a></td>
                </tr>
                <tr>
                    <td><a target="_blank" href="https://drive.google.com/a/gymmt.sk/folderview?id=0B30M7T6N6wkbSmJrczZPX1JYRG8&usp=sharing"><img src="{siteurl}/views/{defaultView}/images/folder.png" alt="Folder" /> Formuláre pre žiakov</a></td>
                </tr>
                </tbody>
            </table>
        </section>
		
        <section class="boardPanel" id="panelSuploHistory">
            <header>
                <div class="small-10 columns">
                    <h2>{lang_suploHistory}</h2>
                </div>
                <div class="small-2 columns text-right">
                    <a href="{siteurl}/suplo/monthSummary" target="_blank"><img src="{siteurl}/views/{defaultView}/images/print.png" alt="{lang_print}" title="{lang_print}" style="margin-top: 12px"/></a>
                </div>
            </header>
            <table style="width: 100%">
                <thead>
                <tr>
                    <td>{lang_date}</td>
                    <td>{lang_hour}</td>
                    <td>{lang_classroom}</td>
                    <td>{lang_subject}</td>
                    <td>{lang_instead}</td>
                </tr>
                </thead>
                <tbody>
                {suploHistory}
                </tbody>
            </table>
		</section>
	</div>
</div>

<script>
	var t = window.setInterval(updateClock, 1000);
</script>
{userreport}