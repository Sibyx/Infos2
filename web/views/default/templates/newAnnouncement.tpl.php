{header}
<section class="row">
	<div class="large-12 columns">
		<header><h2>{lang_newAnnouncement}</h2></header>
		<form id="formNewAnn" name="formNewAnn" action="{siteurl}/announcements/new" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">
                <div class="large-11 columns">
                    <input type="text" id="newAnn_title" name="newAnn_title" placeholder="{lang_title}" required/>
                </div>
			</div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="text" id="newAnn_deadline" name="newAnn_deadline" placeholder="{lang_displayTo}" required />
                </div>
            </div>
			<div class="row">
                <div class="large-12 columns">
				    <textarea id="newAnn_text" name="newAnn_text"></textarea>
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
        $("#newAnn_deadline").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
            monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"]
        });
    });
	tinymce.init({
		selector: "#newAnn_text",
		plugins: ["autolink link charmap anchor hr","searchreplace visualblocks code ","insertdatetime table paste"],
		toolbar: "styleselect | bold italic underline| link",
		entity_encoding : "raw",
		height: "250",
		width: "600"
	});
</script>
{userreport}