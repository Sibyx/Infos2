<div class="row">
    <div class="large-12 columns">
        <h2>{title}</h2>
    </div>
</div>

<div class="row">
    <div class="large-10 columns">
        <span style="margin-right: 5px;"><b>{lang_from}: </b> {startDate}</span>
        <span style="margin-right: 5px;"><b>{lang_to}: </b> {endDate}</span>
        <span style="margin-right: 5px;"><b>{lang_where}: </b> {location}</span>
    </div>

    <div class="large-2 columns">
        <a href="{siteurl}/events/edit/{eventId}"><img src="{siteurl}/views/{defaultView}/images/edit.png" alt="{lang_editEvent}"/></a>
        <a href="{siteurl}/events/remove/{eventId}"><img src="{siteurl}/views/{defaultView}/images/delete.png" alt="{lang_deleteEvent}" style="margin-left: 10px"/></a>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <hr />
        {description}
    </div>
</div>

<a class="close-reveal-modal">&#215;</a>
{userreport}