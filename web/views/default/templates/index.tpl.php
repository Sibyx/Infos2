<h1>Gymmt Infos2</h1>
<div class="row">
	<div class="large-7 columns">
		<section class="boardPanel" id="panelAnnouncements">
			<header><h2>Oznamy</h2></header>
			<article>
				<header><h3>Lorem ipsum</h3></header>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc volutpat blandit ligula. Aenean suscipit nibh purus, id pharetra purus euismod a. Cras auctor purus ut orci blandit, sit amet suscipit neque lobortis. Nulla in imperdiet velit. Ut imperdiet nunc non nunc blandit iaculis. Morbi eu orci at nisl bibendum euismod. Maecenas pellentesque orci ac ornare tincidunt. Donec nunc leo, ornare et ligula eget, tempor rhoncus risus. Nulla viverra tincidunt nisl eu ullamcorper. Suspendisse ligula lacus, malesuada quis tempus a, interdum ac odio. Aenean consequat erat in malesuada consectetur. 
				</p>
				<hr />
				<footer><small>11. 8. 2013</small></footer>
			</article>
			
			<article>
				<header><h3>Lorem ipsum</h3></header>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc volutpat blandit ligula. Aenean suscipit nibh purus, id pharetra purus euismod a. Cras auctor purus ut orci blandit, sit amet suscipit neque lobortis. Nulla in imperdiet velit. Ut imperdiet nunc non nunc blandit iaculis. Morbi eu orci at nisl bibendum euismod. Maecenas pellentesque orci ac ornare tincidunt. Donec nunc leo, ornare et ligula eget, tempor rhoncus risus. Nulla viverra tincidunt nisl eu ullamcorper. Suspendisse ligula lacus, malesuada quis tempus a, interdum ac odio. Aenean consequat erat in malesuada consectetur. 
				</p>
				<hr />
				<footer><small>11. 8. 2013</small></footer>
			</article>
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
			<header><h2>Suplovanie</h2></header>
		</section>
		
		<section class="boardPanel" id="panelEvents">
			<header><h2>Udalosti</h2></header>
		</section>
		
		<section class="boardPanel" id="panelDocs">
			<header><h2>Dokumenty</h2></header>
		</section>
	</div>
</div>
<script>
	var t = window.setInterval(updateClock, 1000);
</script>