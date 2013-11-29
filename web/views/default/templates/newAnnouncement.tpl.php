{header}
<section class="row">
	<div class="large-12 columns">
		<header><h2>Nový oznam</h2></header>
		<form id="formNewAnn" name="formNewAnn" action="{siteurl}/announcements/new" enctype="application/x-www-form-urlencoded" method="post">
			<div class="row">
                <div class="large-11 columns">
                    <input type="text" id="newAnn_title" name="newAnn_title" placeholder="Titulok" required/>
                </div>
			</div>
            <div class="row">
                <div class="large-12 columns">
                    <input type="text" id="newAnn_deadline" name="newAnn_deadline" placeholder="Zobrazovať do" required />
                </div>
            </div>
			<div class="row">
                <div class="large-12 columns">
				    <textarea id="newAnn_text" name="newAnn_text"></textarea>
                </div>
			</div>
            <div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit" style="margin-top: 20px;">Odoslať oznam</button>
                </div>
            </div>
		</form>
	</div>
</section>
<script type="text/javascript">
    $(function(){
        $("#newAnn_deadline").datepicker({
            dateFormat: "dd.mm.yy",
            firstDay: 1,
            dayNamesMin:["Ne", "Po", "Ut", "St", "Št", "Pi", "So"],
            monthNames: ["Január", "Február", "Marec", "Apríl", "Máj", "Jún", "Júl", "August", "September", "Október", "November", "December"]
        });
    });
	tinymce.init({
		selector: "#newAnn_text",
		plugins: ["autolink link charmap anchor","searchreplace visualblocks code ","insertdatetime table paste"],
		toolbar: "styleselect | bold italic underline| link",
		entity_encoding : "raw",
		height: "250",
		width: "600"
	});
</script>
{userreport}