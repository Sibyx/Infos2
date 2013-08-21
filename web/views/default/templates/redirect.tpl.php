<section class="row">
	<div class="large-12 columns">
	
		<header class="row">
			<div class="small-8 large-centered columns text-center">
				<div class="row">
					<h2 class="large-12 columns">Presmerovanie</h2>
				</div>
			</div>
		</header>
		
		<div class="row">
			<div class="small-6 large-centered columns text-center">
				<div class="row">
					<div id="timer" class="large-12 columns">3</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="small-8 large-centered columns text-center">
				<div class="row">
					<div class="alert-box {class} large-12 columns" id="message">{message}</div>
				</div>
			</div>
		</div>
		
	</div>
</section>
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