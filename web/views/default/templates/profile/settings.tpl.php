{header}
<div class="row">
    <div class="large-12 columns">
        <div class="row">
            <div class="large-12">
                <h2>{lang_profileSettings}</h2>
            </div>
        </div>
		<section class="row">
			<div class="large-12 columns">
				<header><h3>{lang_language}</h3></header>
				<form action="{siteurl}/profile/language" method="post" name="formSetLanguage" id="formSetLanguage">
					<div class="row">
						<div class="large-2 columns">
							<label class="hide">{lang_selectLanguage}:</label>
						</div>
					</div>
					<div class="row">
						<div class="large-4 columns">
							<select id="setLanguage_value" name="setLanguage_value">
								{languageList}
							</select>
						</div>
					</div>
				</form>
			</div>
		</section>
        <section class="row">
            <div class="large-12 columns">
                <header><h3>{lang_newsletter}</h3></header>
                <table>
                    <thead>
                    <tr>
                        <th>{lang_email}</th>
                        <th>{lang_announcements}</th>
                        <th>{lang_events}</th>
                        <th>{lang_suploAll}</th>
                        <th>{lang_mySuplo}</th>
                        <th>{lang_action}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {newsletterTable}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="text-align: right; border-top: 1px solid gray" colspan="6">
                                <a href="{siteurl}/newsletter/new" class="small button" style="margin: 5px 0">{lang_addEmail}</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </div>
</div>
<script type="text/javascript">
	$(function(){
		$("#setLanguage_value").change(function(){
			$("#formSetLanguage").submit();
		});
	});
</script>
