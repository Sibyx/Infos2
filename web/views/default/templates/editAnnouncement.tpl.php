{header}
<section class="row">
	<div class="large-12 columns">
		<header><h2>{lang_editAnnouncement}</h2></header>
		<form id="formEditAnn" name="formEditAnn" action="{siteurl}/announcements/edit/{id_ann}" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">
                <div class="large-12 columns">
                    <input type="text" id="editAnn_title" name="editAnn_title" placeholder="{lang_title}" required value="{ann_title}"/>
                </div>
			</div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="text" id="editAnn_deadline" name="editAnn_deadline" placeholder="{lang_displayTo}" required value="{ann_deadline}"/>
                </div>
            </div>
			<div class="row">
                <div class="large-12 columns">
                    <textarea id="editAnn_text" name="editAnn_text">{ann_text}</textarea>
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
<script type="text/javascript">
    $(function(){
        $("#editAnn_deadline").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
            monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"]
        });
    });
	tinymce.init({
		selector: "#editAnn_text",
		plugins: ["autolink link charmap anchor hr","searchreplace visualblocks code ","insertdatetime table paste"],
		toolbar: "styleselect | bold italic underline| link",
		entity_encoding : "raw",
		height: "250",
		width: "600"
	});
</script>
{userreport}