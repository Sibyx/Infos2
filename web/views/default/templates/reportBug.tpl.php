<nav class="top-bar" data-topbar>
	<ul class="title-area">
		<li class="name">
			<h1><a href="{siteurl}"><img src="{siteurl}/views/{defaultView}/images/logo.png" style="height: 30px;" alt="{lang_logo}"/></a></h1>
		</li>
		<li class="toggle-topbar"><a href="#"><img src="{siteurl}/views/{defaultView}/images/menu.png" alt="{lang_menu}"/></a></li>
	</ul>
</nav>

<section class="row">
	<div class="large-12 columns">
		<header><h2>Nahlásiť chybu</h2></header>
		<form name="formReportBug" action="{siteurl}/about/bug" method="post">
			<div class="row">
				<div class="large-8 columns">
					<label>Stručný názov chyby | jedna veta</label>
					<input type="text" placeholder="Stručný názov chyby" name="reportBug_title" id="reportBug_title"/>
				</div>
			</div>
			<div class="row">
				<div class="large-6 columns">
					<label>Kabinet</label>
					<input type="text" placeholder="Kabinet" name="reportBug_room" id="reportBug_room"/>
				</div>
			</div>
			<div class="row">
				<div class="large-8 columns">
					<label>Kontaktný e-mail</label>
					<input type="email" placeholder="Kontaktný e-mail" name="reportBug_email" id="reportBug_email"/>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<label>Detailný opis chyby</label>
					<textarea placeholder="Detailný opis chyby" name="reportBug_description" id="reportBug_description" style="min-height: 200px; min-width: 100%; max-width: 100%"></textarea>
				</div>
			</div>
			<div class="row">
				<div class="small-3 small-centered columns">
					<button type="submit" style="margin-top: 20px;">{lang_send}</button>
				</div>
			</div>
		</form>
	</div>
</section>