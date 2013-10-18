{header}
<section class="row">
	<div class="large-12 collumns">
		<header class="row">
            <div class="small-12 columns">
                <h2>Suplovanie {dateFormated}</h2>
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
                        <th>Hodina</th>
                        <th>Vyučujúci</th>
                        <th>Trieda</th>
                        <th>Predmet</th>
                        <th>Učebňa</th>
                        <th>Zastupujúci</th>
                        <th>Poznámka</th>
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
        dayNamesMin:["Ne", "Po", "Ut", "St", "Št", "Pi", "So"],
        monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"],
        beforeShowDay: $.datepicker.noWeekends
    });
</script>
{userreport}