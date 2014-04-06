{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>{lang_editEvent}</h2>
        </header>
        <form id="formEditEvent" name="formEditEvent" action="{siteurl}/events/edit/{eventId}" enctype="application/x-www-form-urlencoded" method="post" class="custom">
            <!-- Datum -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">{lang_date}</label>
                </div>

                <div class="large-11 columns">
                    <input type="text" id="editEvent_date" name="editEvent_date" value="{dateFormated}" required />
                </div>

            </div>

            <!-- Cas -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">{lang_time}</label>
                </div>

                <div class="large-5 columns">
                    <select id="editEvent_time">
                        <option value="custom">{lang_custom}</option>
                    </select>
                </div>

                <div class="large-3 columns">
                    <input type="text" id="editEvent_startTime" name="editEvent_startTime" required value="{startTime}"/>
                </div>

                <div class="large-3 columns">
                    <input type="text" id="editEvent_endTime" name="editEvent_endTime" required value="{endTime}"/>
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">{lang_title}</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="editEvent_title" name="editEvent_title" required value="{eventTitle}"/>
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">{lang_place}</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="editEvent_location" name="editEvent_location" required value="{location}"/>
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <textarea id="editEvent_text" name="editEvent_text" style="height: 350px; margin-bottom: 20px;">{text}</textarea>
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
	$(document).ready(function() {
        $("#editEvent_date").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
            monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"]
		});
		$('#editEvent_startTime').timepicker();
		$('#editEvent_endTime').timepicker();
    });
</script>
{userreport}