{header}
<section class="row">
	<div class="large-12 collumns">
		<header class="row">
            <div class="small-12 columns">
                <h2>{lang_suplo} {dateFormated}</h2>
            </div>
        </header>
        <div class="row">
            <div class="small-5 columns">
                <form action="{siteurl}/suplo/view/" name="formSuploFilter" id="formSuploFilter" method="post">
                    <input id="suploFilter_date" name="suploFilter_date" type="text" value="{dateInput}" required />
                </form>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <th>{lang_hour}</th>
                        <th>{lang_teacher}</th>
                        <th>{lang_class}</th>
                        <th>{lang_subject}</th>
                        <th>{lang_classroom}</th>
                        <th>{lang_supplied}</th>
                        <th>{lang_note}</th>
                    </tr>
                    </thead>
                    <tbody id="suploContainer">
                        {suploTable}
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</section>

<script>
    $("#suploFilter_date").datepicker({
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
        monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"],
        beforeShowDay: $.datepicker.noWeekends
    });
</script>
{userreport}