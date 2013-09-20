<h1>Infos}</h1>
<header class="row">
    <div class="large-12 columns">
        <form action="{siteurl}/suplo/getSuploTable" name="formSuploFilter" id="formSuploFilter" method="post">
            <input id="suploFilter_date" name="suploFilter_date" type="text" required />
        </form>
    </div>
</header>
<section class="row">
	<div class="large-12 collumns">
		<header><h2>Suplovanie {dateFormated}</h2></header>
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
			<tbody>
				{suploTable}
			</tbody>
		</table>
	</div>
</section>

<script>
    var Event = function(text, className) {
        this.text = text;
        this.className = className;
    };

    var events = {};
    events[new Date("02/14/2011")] = new Event("Valentines Day", "pink");
    events[new Date("02/18/2011")] = new Event("Payday", "green");
    //TODO: spravit cez ajax, nie cez php array;
    $("#suploFilter_date").datepicker({
        beforeShowDay: function(date) {
            var event = events[date];
            if (event) {
                return [true, event.className, event.text];
            }
            else {
                return [true, '', ''];
            }
        }
    });
</script>