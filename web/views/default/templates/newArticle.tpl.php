<section class="row">
	<div class="large-12 columns">
		<header><h2><small>New article</small></h2></header>
		<form id="formNewArticle" name="formNewArticle" action="{siteurl}/articles/new" enctype="application/x-www-form-urlencoded" method="post">
			<div class="field">
				<input type="text" id="newArticle_title" name="newArticle_title" placeholder="Article title" required/>
			</div>
			<div class="field">
				<select id="newArticle_category" name="newArticle_category">
					{categories}
				</select>
			</div>
			<div class="field">
				<input type="text" id="newArticle_keywords" name="newArticle_keywords" placeholder="Keywords" />
			</div>
			<div>
				<textarea id="newArticle_text" name="newArticle_text"></textarea>
			</div>
			<div class="field">
				<button type="submit">Vytvorit clanok</button>
			</div>
		</form>
	</div>
</section>
<script type="text/javascript">
tinymce.init({
	selector: "#newArticle_text",
	plugins: [
		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
		"searchreplace wordcount visualblocks visualchars code fullscreen",
		"insertdatetime media nonbreaking save table contextmenu directionality",
		"emoticons paste"
	],
	toolbar: "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image emoticons",
	height: "500"
});
</script>
