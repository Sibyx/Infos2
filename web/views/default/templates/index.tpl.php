<h1>Gymmt Infos2</h1>
<div class="row">
	<div class="large-7 columns">
		<section id="panelAnnouncements">
            <div class="boardPanel large-12 columns">
                <header class="row">
                    <div class="small-10 columns">
                        <a href="{siteurl}/announcements/"><h2>Oznamy</h2></a>
                    </div>
                    <div class="small-2 columns text-right">
                        <a href="{siteurl}/announcements/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="Pridat oznam" style="margin-top: 13px"/></a>
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
					<td>Aktuálne:</td>
					<td id="current">{current}</td>
				</tr>
				<tr>
					<td>Nasleduje:</td>
					<td id="next">{next}</td>
				</tr>
			</table>
			<hr />
            <div class="text-right">
                <a href="{siteurl}/user/settings"><img src="{siteurl}/views/{defaultView}/images/settings.png" alt="Settings" class="icon"/></a>
                <a href="{siteurl}/authenticate/logout"><img src="{siteurl}/views/{defaultView}/images/logout.png" alt="Logout" class="icon"/></a>
            </div>

		</section>

		<section class="boardPanel" id="panelSuplo">
            <header>
                <div class="small-10 columns">
                    <a href="{siteurl}/suplo/"><h2>Suplovanie</h2></a>
                </div>
                <div class="small-2 columns text-right">
                    <a href="{siteurl}/suplo/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="Pridat oznam" style="margin-top: 13px"/></a>
                </div>
            </header>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td>Hodina</td>
                        <td>Trieda</td>
                        <td>Predmet</td>
                        <td>Učebňa</td>
                        <td>Namiesto</td>
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
                    <a href="{siteurl}/events/"><h2>Udalosti</h2></a>
                </div>
                <div class="small-2 columns text-right">
                    <a href="{siteurl}/events/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="Pridat oznam" style="margin-top: 13px"/></a>
                </div>
            </header>
		</section>
		
		<section class="boardPanel" id="panelDocs">
            <header>
                <div class="small-12 columns">
                    <a href="{siteurl}/events/"><h2>Dokumenty</h2></a>
                </div>
            </header>

		</section>
	</div>
</div>
<div id="myModal" class="reveal-modal">
</div>
<script>
	//var t = window.setInterval(updateClock, 1000);
</script>