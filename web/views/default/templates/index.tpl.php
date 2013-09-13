<h1>Gymmt Infos2</h1>
<div class="row">
	<div class="large-7 columns">
		<section class="boardPanel" id="panelAnnouncements">
			<header><a href="{siteurl}/announcements/"><h2>Oznamy</h2></a></header>
				{announcements}
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
		</section>
		
		<section class="boardPanel" id="panelSuplo">
            <header><a href="{siteurl}/suplo/"><h2>Suplovanie</h2></a></header>
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
			<header><h2>Udalosti</h2></header>
		</section>
		
		<section class="boardPanel" id="panelDocs">
			<header><h2>Dokumenty</h2></header>
		</section>
	</div>
</div>
<div id="myModal" class="reveal-modal">
</div>
<script>
	var t = window.setInterval(updateClock, 1000);
</script>