{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>Upraviť udalosť</h2>
        </header>
        <form id="formEditEvent" name="formEditEvent" action="{siteurl}/events/edit/{eventId}" enctype="application/x-www-form-urlencoded" method="post" class="custom">
            <!-- Datum -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">Dátum</label>
                </div>

                <div class="large-11 columns">
                    <input type="text" id="editEvent_date" name="editEvent_date" value="{dateFormated}" required />
                </div>

            </div>

            <!-- Cas -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">Čas</label>
                </div>

                <div class="large-5 columns">
                    <select id="editEvent_time">
                        <option value="custom">Vlastné</option>
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
                    <label class="left inline">Titulok</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="editEvent_title" name="editEvent_title" required value="{eventTitle}"/>
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">Miesto</label>
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
                    <button type="submit">Odoslať zmeny</button>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
    $(function(){
        $("#editEvent_date").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["Ne", "Po", "Ut", "St", "Št", "Pi", "So"],
            monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"]
        });

        $('#editEvent_startTime').timepicker();
        $('#editEvent_endTime').timepicker();
    });
</script>
{userreport}