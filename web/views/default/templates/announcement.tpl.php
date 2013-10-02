<article class="row">
    <div class="large-12 columns">
        <header class="row">
            <div class="small-8 columns"><h3><a href="{siteurl}/announcements/view/{announcementId}">{annTitle}</a></h3></div>
            <div class="small-4 columns text-right">
                <a href="{siteurl}/announcements/edit/{announcementId}"><img src="{siteurl}/views/{defaultView}/images/edit.png" alt="Upravit oznam" /></a>
                <a href="{siteurl}/announcements/remove/{announcementId}"><img src="{siteurl}/views/{defaultView}/images/delete.png" alt="Odstranit oznam" style="margin-left: 10px"/></a>
            </div>
        </header>
        <div class="row">
            <div class="large-12 columns">
                {annText}
            </div>
        </div>
        <hr />
        <footer class="row">
            <div class="large-6 columns">
                <small><a href="https://plus.google.com/u/1/{userId}/about" target="_blank">{userName}</a> - <time pubdate="{createdRaw}">{createdFriendly}</time></small>
            </div>
            <div class="large-6 columns text-right">
                <a href="{siteurl}/announcements/like/{announcementId}" class="vote has-tip" data-tooltip title="{likers}"><small>{likes}</small> <img src="{siteurl}/views/{defaultView}/images/like.png" alt="Likes" /></a>
                <small>  |  </small>
                <a href="{siteurl}/announcements/dislike/{announcementId}" class="vote has-tip" data-tooltip title="{dislikers}"><small>{dislikes}</small> <img src="{siteurl}/views/{defaultView}/images/dislike.png" alt="Dislikes" /></a>
            </div>
        </footer>
    </div>
</article>