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
				<div id="disqus_thread"></div>
				<br />
			</article>
		</section>
	</div>
</div>
<script type="text/javascript">
	var disqus_shortname = 'jakubdubecsblog';
	(function() {
		var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	})();
</script>
<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
<!--
	-- Komentare --
-->