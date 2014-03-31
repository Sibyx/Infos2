{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>{lang_newEvent}</h2>
        </header>
        <form id="formNewEvent" name="formNewEvent" action="{siteurl}/events/new/" enctype="application/x-www-form-urlencoded" method="post" class="custom">
            <!-- Datum -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">{lang_date}</label>
                </div>

                <div class="large-11 columns">
                    <input type="text" id="newEvent_date" name="newEvent_date" value="{dateFormated}" required />
                </div>

            </div>

            <!-- Cas -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">{lang_date}</label>
                </div>

                <div class="large-5 columns">
                    <select id="newEvent_time">
                        <option value="custom">{lang_custom}</option>
                    </select>
                </div>

                <div class="large-3 columns">
                    <input type="text" id="newEvent_startTime" name="newEvent_startTime" required disabled/>
                </div>

                <div class="large-3 columns">
                    <input type="text" id="newEvent_endTime" name="newEvent_endTime" required disabled/>
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">{lang_title}</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="newEvent_title" name="newEvent_title" required />
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">{lang_place}</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="newEvent_location" name="newEvent_location" required />
                </div>
            </div>

            <div class="row">
                <div class="large-12 columns">
                    <textarea id="newEvent_text" name="newEvent_text" style="height: 350px; margin-bottom: 20px;"></textarea>
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
        $("#newEvent_date").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["{lang_sundayShor}", "{lang_mondayShort}", "{lang_tuesdayShort}", "{lang_wednesdayShort}", "{lang_thursdayShort}", "{lang_fridayShort}", "{lang_saturdayShort}"],
            monthNames: ["{lang_january}", "{lang_february}", "{lang_march}", "{lang_april}", "{lang_may}", "{lang_june}", "{lang_july}", "{lang_august}", "{lang_september}", "{lang_october}", "{lang_november}", "{lang_december}"]
        });

        $('#newEvent_startTime').timepicker();
        $('#newEvent_endTime').timepicker();
    });
</script>
{userreport}