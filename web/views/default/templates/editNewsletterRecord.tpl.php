{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>{lang_newsletterSubscribe}</h2>
        </header>
        <form id="formNewNewsletterRecord" name="formNewNewsletterRecord" action="{siteurl}/newsletter/edit/{id}" method="post">
            <div class="row">
                <div class="small-1 columns">
                    <label for="right-label" class="left inline">{lang_email}</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="editNewsletterRecord_email" name="editNewsletterRecord_email" value="{email}" required />
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label for="editNewsletterRecord_announcements"><input type="checkbox" id="editNewsletterRecord_announcements" name="editNewsletterRecord_announcements" value="1" {announcements}>{lang_announcements}</label>
                    <label for="editNewsletterRecord_events"><input type="checkbox" id="editNewsletterRecord_events" name="editNewsletterRecord_events" value="1" {events}>{lang_events}</label>
                    <label for="editNewsletterRecord_suploMy"><input type="checkbox" id="editNewsletterRecord_suploMy" name="editNewsletterRecord_suploMy" value="1" {suploMy}>{lang_mySuplo}</label>
                    <label for="editNewsletterRecord_suploAll"><input type="checkbox" id="editNewsletterRecord_suploAll" name="editNewsletterRecord_suploAll" value="1" {suploAll}>{lang_suploAll}</label>
                </div>
            </div>
            <div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit">{lang_send}</button>
                </div>
            </div>
        </form>
    </div>
</section>
{userreport}