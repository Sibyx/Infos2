<section class="row">
	<div class="large-12 columns">
	
		<header class="row">
			<div class="large-8 large-centered columns">
				<div class="row">
                    <div class="large-12 columns">
					    <h2 class="text-center">{lang_redirect}</h2>
                    </div>
				</div>
			</div>
		</header>
		
		<div class="row">
			<div class="large-6 large-centered columns">
				<div class="row">
					<div class="large-12 columns text-center">
                        <span id="timer">3</span>
                    </div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="large-8 large-centered columns">
				<div class="row">
					<div class="large-12 columns">
                        <div class="alert-box {class} text-center" id="message">{message}</div>
                    </div>
				</div>
			</div>
		</div>
		
	</div>
</section>
{logoutGoogle}
<script>
	var count = 3;
	var counter = setInterval(redirect, 1000);
	function redirect() {
		count = count-1;
		if (count <= 0) {
			clearInterval(counter);
			$(window.location).attr('href', "{url}");
		}
		document.getElementById("timer").innerHTML=count;
	}
</script>