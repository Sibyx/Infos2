{header}
<section class="row">
	<div class="large-12 columns">
		<header><h2>Upraviť oznam</h2></header>
		<form id="formEditAnn" name="formEditAnn" action="{siteurl}/announcements/edit/{id_ann}" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">
                <div class="large-12 columns">
                    <input type="text" id="editAnn_title" name="editAnn_title" placeholder="Titulok" required value="{ann_title}"/>
                </div>
			</div>
			<div class="row">
                <div class="large-12 columns">
                    <textarea id="editAnn_text" name="editAnn_text">{ann_text}</textarea>
                </div>

			</div>
			<div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit" style="margin-top: 20px;">Odoslať úpravu</button>
                </div>
			</div>
		</form>
	</div>
</section>
<script type="text/javascript">
	tinymce.init({
		selector: "#editAnn_text",
		plugins: ["autolink link charmap anchor","searchreplace visualblocks code ","insertdatetime table paste"],
		toolbar: "styleselect | bold italic underline| link",
		entity_encoding : "raw",
		height: "250",
		width: "600"
	});
</script>
{userreport}