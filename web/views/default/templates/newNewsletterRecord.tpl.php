{header}
<section class="row">
    <div class="large-12 columns">
        <header>
            <h2>Prihlásenie na odber</h2>
        </header>
        <form id="formNewNewsletterRecord" name="formNewNewsletterRecord" action="{siteurl}/newsletter/new/" method="post">
            <div class="row">
                <div class="small-1 columns">
                    <label for="right-label" class="left inline">E-mail</label>
                </div>
                <div class="large-11 columns">
                    <input type="text" id="newNewsletterRecord_email" name="newNewsletterRecord_email" required />
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                    <label for="newNewsletterRecord_announcements"><input type="checkbox" id="newNewsletterRecord_announcements" name="newNewsletterRecord_announcements" value="1">Oznamy</label>
                    <label for="newNewsletterRecord_events"><input type="checkbox" id="newNewsletterRecord_events" name="newNewsletterRecord_events" value="1">Termínovník</label>
                    <label for="newNewsletterRecord_suploMy"><input type="checkbox" id="newNewsletterRecord_suploMy" name="newNewsletterRecord_suploMy" value="1">Suplovanie - Moje</label>
                    <label for="newNewsletterRecord_suploAll"><input type="checkbox" id="newNewsletterRecord_suploAll" name="newNewsletterRecord_suploAll" value="1">Suplovanie - Celé</label>
                </div>
            </div>
            <div class="row">
                <div class="small-3 small-centered columns">
                    <button type="submit">Odoslať</button>
                </div>
            </div>
        </form>
    </div>
</section>
{userreport}