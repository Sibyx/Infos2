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
