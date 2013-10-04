{header}
<section class="row">
	<div class="large-12 columns">
		<header>
			<h2>Nové suplovanie</h2>
		</header>
		<form id="formNewSuplo" name="formNewSuplo" action="{siteurl}/suplo/new/" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">

                <div class="small-1 columns">
                    <label for="right-label" class="left inline">Dátum</label>
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
			<div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit">Odoslať suplovanie</button>
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
			dayNamesMin:["Ne", "Po", "Ut", "St", "Št", "Pi", "So"],
			monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"],
            beforeShowDay: $.datepicker.noWeekends
		});
	});
</script>
{userreport}