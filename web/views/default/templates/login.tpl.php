<section class="row">
	<div class="large-12 columns">
		<header><h2>Login</h2></header>
		<form id="formLogin" name="formLogin" action="{siteurl}/authenticate/login" enctype="application/x-www-form-urlencoded" method="post">
			<div class="field">
				<input type="text" id="login_nick" name="login_nick" placeholder="User name" required/>
			</div>
			<div class="field">
				<input type="password" id="login_password" name="login_password" placeholder="Password" required/>
			</div>
			<div class="field">
				<button type="submit">Login</button>
			</div>
		</form>
	</div>
</section>