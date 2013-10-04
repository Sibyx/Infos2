<div class="row">
    <div class="large-12 columns">
        <h2>{title}</h2>
    </div>
</div>

<div class="row">
    <div class="large-10 columns">
        <span style="margin-right: 5px;"><b>Od: </b> {startDate}</span>
        <span style="margin-right: 5px;"><b>Do: </b> {endDate}</span>
        <span style="margin-right: 5px;"><b>Kde: </b> {location}</span>
    </div>

    <div class="large-2 columns">
        <a href="{siteurl}/events/edit/{eventId}"><img src="{siteurl}/views/{defaultView}/images/edit.png" alt="Upravit udalost"/></a>
        <a href="{siteurl}/events/remove/{eventId}"><img src="{siteurl}/views/{defaultView}/images/delete.png" alt="Odstranit udalost" style="margin-left: 10px"/></a>
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