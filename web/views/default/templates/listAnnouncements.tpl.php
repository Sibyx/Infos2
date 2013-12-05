{header}
<div class="row">
    <div class="large-12 columns">
        <section id="panelAnnouncements">
            <div class="boardPanel large-12 columns">
                <header class="row">
                    <div class="small-10 columns">
                        <a href="{siteurl}/announcements/"><h2>{lang_announcements}</h2></a>
                    </div>
                    <div class="small-2 columns text-right">
                        <a href="{siteurl}/announcements/new"><img src="{siteurl}/views/{defaultView}/images/add.png" alt="{lang_addAnnouncement}" style="margin-top: 13px"/></a>
                    </div>
                </header>
                {announcements}
            </div>
            <div class="pagination-centered">
                <ul class="pagination">
                    {pagination}
                </ul>
            </div>
            <br />
        </section>
    </div>
</div>
{userreport}