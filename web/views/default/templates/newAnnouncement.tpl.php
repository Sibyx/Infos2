{header}
<section class="row">
	<div class="large-12 columns">
		<header><h2>Nový oznam</h2></header>
		<form id="formNewAnn" name="formNewAnn" action="{siteurl}/announcements/new" enctype="application/x-www-form-urlencoded" method="post">
			<div class="field">
				<input type="text" id="newAnn_title" name="newAnn_title" placeholder="Titulok" required/>
			</div>
			<div>
				<textarea id="newAnn_text" name="newAnn_text"></textarea>
			</div>
			<div class="field">
				<button type="submit">Odoslať oznam</button>
			</div>
		</form>
	</div>
</section>
<script type="text/javascript">
	tinymce.init({
		selector: "#newAnn_text",
		plugins: ["autolink link charmap anchor","searchreplace visualblocks code ","insertdatetime table paste"],
		toolbar: "styleselect | bold italic underline| link",
		entity_encoding : "raw",
		height: "250",
		width: "600"
	});
</script>
