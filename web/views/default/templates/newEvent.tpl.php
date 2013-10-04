{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>Nová udalosť</h2>
        </header>
        <form id="formNewEvent" name="formNewEvent" action="{siteurl}/events/new/" enctype="application/x-www-form-urlencoded" method="post" class="custom">
            <!-- Datum -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">Dátum</label>
                </div>

                <div class="large-11 columns">
                    <input type="text" id="newEvent_date" name="newEvent_date" value="{dateFormated}" required />
                </div>

            </div>

            <!-- Cas -->
            <div class="row">

                <div class="large-1 columns">
                    <label class="left inline">Čas</label>
                </div>

                <div class="large-5 columns">
                    <select id="newEvent_time">
                        <option value="8">Veľká prestávka</option>
                        <option value="custom">Vlastné</option>
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
                    <label class="left inline">Titulok</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="newEvent_title" name="newEvent_title" required />
                </div>
            </div>

            <div class="row">
                <div class="large-1 columns">
                    <label class="left inline">Miesto</label>
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
                    <button type="submit">Odoslať udalosť</button>
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
            dayNamesMin:["Ne", "Po", "Ut", "St", "Št", "Pi", "So"],
            monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"]
        });

        $('#newEvent_startTime').timepicker();
        $('#newEvent_endTime').timepicker();
    });
</script>
{userreport}