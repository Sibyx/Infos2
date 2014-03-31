{header}
<section class="row">
	<div class="large-12 columns">
		<header>
			<h2>{lang_newSuplo}</h2>
		</header>
		<form id="formNewSuplo" name="formNewSuplo" action="{siteurl}/suplo/new/" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">

                <div class="small-1 columns">
                    <label for="right-label" class="left inline">{lang_date}</label>
                </div>

                <div class="large-4 columns">
                    <input type="text" id="newSuplo_date" name="newSuplo_date" value="{dateFormated}" required data-suplo-url="{siteurl}/suplo/suploExists/"/>
                </div>

                <div class="large-7 columns" id="suploExists">
                    {suploExists}
                </div>

			</div>
			<div class="row">
                <div class="large-12 columns">
				    <textarea id="newSuplo_data" name="newSuplo_data" style="height: 350px; margin-bottom: 20px;"></textarea>
			    </div>
            </div>
			<div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit">{lang_send}</button>
                </div>
			</div>
		</form>
	</div>
</section>
<script>
	$(function(){
		$("#newSuplo_date").datepicker({
			dateFormat: "dd.mm.yy",
			firstDay: 1,
            dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
            monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"],
            beforeShowDay: $.datepicker.noWeekends
		});
	});
</script>
{userreport}