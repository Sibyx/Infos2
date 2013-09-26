{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>Nová udalosť</h2>
        </header>
        <form id="formNewEvent" name="formNewEvent" action="{siteurl}/events/new/" enctype="application/x-www-form-urlencoded" method="post" class="custom">
            <!-- Datum -->
            <div class="row">

                <div class="small-1 columns">
                    <label class="left inline">Dátum</label>
                </div>

                <div class="small-11 columns">
                    <input type="text" id="newEvent_date" name="newEvent_date" value="{dateFormated}" required />
                </div>

            </div>

            <!-- Cas -->
            <div class="row">

                <div class="small-1 columns">
                    <label class="left inline">Čas</label>
                </div>

                <div class="small-5 columns">
                    <select id="newEvent_time">
                        <option value="8">Veľká prestávka</option>
                        <option value="custom">Vlastné</option>
                    </select>
                </div>

                <div class="small-3 columns">
                    <input type="text" id="newEvent_timeFrom" name="newEvent_timeFrom" required disabled/>
                </div>

                <div class="small-3 columns">
                    <input type="text" id="newEvent_timeTo" name="newEvent_timeTo" required disabled/>
                </div>
            </div>

            <div class="row">
                <div class="small-1 columns">
                    <label class="left inline">Titulok</label>
                </div>
                <div class="small-11 columns">
                    <input type="text" id="newEvent_title" name="newEvent_title" required />
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
            monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"],
            beforeShowDay: $.datepicker.noWeekends
        });

        $('#newEvent_timeFrom').timepicker();
        $('#newEvent_timeTo').timepicker();
    });
</script>