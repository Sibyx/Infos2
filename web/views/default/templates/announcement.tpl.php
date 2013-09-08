<div class="row">
	<div class="large-12 columns">
		<section class="boardPanel" id="panelAnnouncements">
			<header><h2>Oznamy</h2></header>
			<article>
				<header><h3>{ann_title}</h3></header>
				{ann_text}
				<hr />
				<footer>
					<small> <a href="https://plus.google.com/u/1/{author_id}/about" target="_blank">{author_name}</a> - <time pubdate="{ann_createdRaw}">{ann_createdFriendly}</time></small>
				</footer>
				<br />
                <div class="g-comments"
                     data-href="{currentURL}"
                     data-width="800"
                     data-first_party_property="BLOGGER"
                     data-view_type="FILTERED_POSTMOD">
                </div>
				<br />
			</article>
		</section>
	</div>
</div>