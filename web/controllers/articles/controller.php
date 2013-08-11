<?php
class articlesController {
	
	public function __construct($registry){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		switch(isset($urlBits[1]) ? $urlBits[1] : '') {
			case 'view':
				$this->viewArticle(intval($urlBits[2]));
			break;
			case 'edit':
				$this->editArticle(intval($urlBits[2]));
			break;
			case 'new':
				$this->newArticle();
			break;
			case 'newComment':
				$this->newComment(intval($urlBits[2]));
			break;
			case 'archive':
				$this->selectByYear(intval($urlBits[2]), intval($urlBits[3]));
			break;
			default:				
				$this->listArticles(intval($urlBits[1]));
			break;
		}
	}
	
	private function getFirstPara($string) {
		$string = strip_tags($string);
		return substr($string, 0, 800) . "... ";
	}
	
	private function listArticles($offset) {
		echo '<div class="nine columns">' . "\n";
		echo '<section>' . "\n";
		require_once(FRAMEWORK_PATH . 'models/articles.php');
		require_once(FRAMEWORK_PATH . 'models/comments.php');
		$articles = new Articles($this->registry);
		$pagination = $articles->listArticles($offset);
		if ($pagination->getNumRowsPage() == 0) {
			echo '<div class="alert-box columns six centered">Žiadne články</div>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
				$comments = new Comments($this->registry, $row['id_article']);
				echo '<article>' . "\n";
				echo '<header><h2><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">' . $row['article_title'] . '</a></h2></header>' . "\n";
				echo '<div class="row articleInfo">' . "\n";
				echo '<div class="nine columns">' . $row['user_nick'] . ', publikované dňa <time datetime="' . $row['article_date'] . '">' . $row['dateFriendly'] . '</time></div>' . "\n";
				echo '<div class="three columns right-text"><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '#comments">' . $comments->printCommentsNumber() . '</a></div>' . "\n";
				echo '</div>' . "\n";
				echo '<div class="row">' . "\n";
				echo '<div class="twelve columns mt">' . "\n";
				echo $this->getFirstPara($row['article_text']) . ' <a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">Celý článok</a>' . "\n";
				echo '</div>' . "\n";
				echo '</div>' . "\n";
				echo '</article>' . "\n";
				echo '<hr />' . "\n";
			}
		}
		echo '</section>' . "\n";
		echo '<div class="pagination-centered">' . "\n";
		echo '<ul class="pagination">' . "\n";
		if ($pagination->isFirst()) {
			echo '<li class="arrow unavailable"><a href="">&laquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($pagination->getCurrentPage()-2) . '">&laquo;</a></li>' . "\n";
		}
		for ($i = 1;$i <= $pagination->getNumPages(); $i++) {
			if ($i == $pagination->getCurrentPage()) {
				echo '<li class="current"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
			else {
				echo '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
		}
		if ($pagination->isLast()) {
			echo '<li class="arrow unavailable"><a href="">&raquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . '/articles/' . ($pagination->getCurrentPage()) . '">&raquo;</a></li>' . "\n";
		}
		echo '</ul>' . "\n";
		echo '</div>' . "\n";
		echo '</div>' . "\n";
		echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";
	}
	
	private function viewArticle($articleId) {
		/*require_once(FRAMEWORK_PATH . 'models/article.php');
		$article = new Article($this->registry, $articleId);
		require_once(FRAMEWORK_PATH . 'models/comments.php');
		$comments = new Comments($this->registry, $articleId);
		echo '<div class="nine columns">' . "\n";
		if ($article->isValid()) {
			$data = $article->toArray();
			echo '<article>' . "\n";
			echo '<header><h2>' . $data['title'] . '</h2></header>' . "\n";
			echo '<div class="row articleInfo">' . "\n";
			echo '<div class="nine columns">' . $data['ownerNick'] . ', publikované dňa <time datetime="' . $data['date'] . '">' . $data['dateFriendly'] . '</time></div>' . "\n";
			echo '<div class="three columns right-text"><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $data['id'] . '#comments">' . $comments->printCommentsNumber() . '</a></div>' . "\n";
			echo '</div>' . "\n";
			echo '<div class="row">' . "\n";
			echo '<div class="twelve columns mt">' . "\n";
			echo $data['text'];
			echo '</div>' . "\n";
			echo '</div>' . "\n";
			echo '</article>' . "\n";
			
			echo '<section>' . "\n";
			echo '<header><h3 id="comments"><small>Komentáre</h3></small></header>' . "\n";
			if ($comments->numComments() > 0) {
				$pagination = $comments->listComments();
				while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
					echo '<article class="comment">' . "\n";
					echo '<header><time datetime="' . $row['comment_date'] . '" pubdate>' . $row['dateFriendly'] . '</time> - ' . $row['comment_name'] . ' povedal:</header>' . "\n";
					echo '<p>' . $row['comment_text'] . '</p>' . "\n";
					echo '</article>' . "\n";
					echo '<hr />' . "\n";
				}
			}
			else {
				echo '<div class="alert-box columns six centered">Zatiaľ žiadny komentár! Buď prvý!</div>' . "\n";
			}
			echo '</section>' . "\n";
			
			echo '<section>' . "\n";
			echo '<header><h3><small>Nový komentár</h3></small></header>' . "\n";
			$form = array(
				'name'		=> 'Comment',
				'action'	=> $this->registry->getSetting('siteurl') . '/articles/newComment/' . $articleId,
				'enctype'	=> 'multipart/form-data',
				'method'	=> 'POST',
				'rows'		=> array (
					0	=> array(
						'elements'	=> array (
							0	=> array(
								'tag'		=> 'input',
								'type'		=> 'text',
								'name'		=> 'nick',
								'class'		=> 'six',
								'label'		=> 'OM6AW',
								'required'	=> 'required'
							)
						)
					),
					1	=> array (
						'elements'	=> array (
							0	=> array(
								'tag'		=> 'input',
								'type'		=> 'email',
								'name'		=> 'email',
								'class'		=> 'six',
								'label'		=> 'E-mail',
								'required'	=> 'required'
							)
						)
					),
					2	=> array (
						'elements'	=> array (
							0	=> array(
								'tag'		=> 'textarea',
								'name'		=> 'message',
								'class'		=> 'twelve contactTextarea',
								'label'		=> 'Komentar',
								'required'	=> 'required'
							)
						)
					)
				),
				'submit'	=> array (
					'label'	=> 'Odoslať',
					'class'	=> 'button six offset-by-three'
				)
			);
			$this->registry->getObject('render')->createForm($form);
			echo '</section>' . "\n";
		}
		else {
			echo '<div class="alert-box alert columns six centered">Nepodarilo sa nám nájsť žiadaný článok.</div>' . "\n";
		}
		
		echo '</div>' . "\n";
		
		//aside
		echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";*/
		$tags = array();
		$tags['title'] = 'Article name - Jakub Dubec';
		$this->registry->getObject('template')->buildFromTemplate('article');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function newArticle() {
		if (isset($_POST['newArticle_title'])) {
			require_once(FRAMEWORK_PATH . 'models/article.php');
			$article = new Article($this->registry);
			$article->setTitle($_POST['newArticle_title']);
			$article->setText($_POST['newArticle_text']);
			$article->setKeywords($_POST['newArticle_keywords']);
			$article->setCategory($_POST['newArticle_category']);
			$article->save();
			$this->registry->getObject('log')->insertLog('SQL', 'INF', 'Posted new article: ' . $_POST['newArticle_title']);
			$redirectBits[] = 'articles';
			$redirectBits[] = 'view';
			$redirectBits[] = $article->getId();
			$redirectBits[] = $article->getURLTitle();
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Článok bol vytvorený!', 'success');
		}
		else {
			if($this->registry->getObject('auth')->isLoggedIn()) {
				$this->uiNew();
			}
			else {
				$redirectBits[] = 'authenticate';
				$redirectBits[] = 'login';
				$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'You must be logged in!', 'error');
			}
		}
	}
	
	private function uiNew() {
		$tags = array();
		$tags['title'] = 'New Article - Jakub Dubec';
		//categories
		require_once(FRAMEWORK_PATH . 'models/categories.php');
		$categories = new Categories($this->registry);
		$cache = $categories->listCategories();
		$options = "";
		while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
			$options .= '<option value="' . $row['id_category'] . '">' . $row['category_title'] . "</option> \n";
		}
		$tags['categories'] = $options;
		$this->registry->getObject('template')->buildFromTemplate('newArticle');
		$this->registry->getObject('template')->replaceTags($tags);
		$this->registry->getObject('template')->parseOutput();
	}
	
	private function newComment($articleId) {
		if (isset($_POST['comment_nick'])) {
			require_once(FRAMEWORK_PATH . 'models/comment.php');
			$comment = new Comment($this->registry);
			$comment->setOwnerNick($_POST['comment_nick']);
			$comment->setOwnerEmail($_POST['comment_email']);
			$comment->setText($_POST['comment_message']);
			$comment->setArticleId($articleId);
			$comment->save();
			$redirectBits[] = 'articles';
			$redirectBits[] = 'view';
			$redirectBits[] = $articleId;
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Komenár bol vytvorený!', 'alert-box columns six centered');	
		}
		else {
			$redirectBits[] = 'articles';
			$redirectBits[] = 'view';
			$redirectBits[] = $articleId;
			$this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Niekde nastala chyba', 'alert-box alert columns six centered');	
		}
	}
	
	private function selectByYear($year, $offset) {
		echo '<div class="nine columns">' . "\n";
		echo '<section>' . "\n";
		require_once(FRAMEWORK_PATH . 'models/articles.php');
		require_once(FRAMEWORK_PATH . 'models/comments.php');
		$articles = new Articles($this->registry);
		$pagination = $articles->listArticlesByYear($year, $offset);
		if ($pagination->getNumRowsPage() == 0) {
			echo '<div class="alert-box columns six centered">Žiadne články</div>' . "\n";
		}
		else {
			while ($row = $this->registry->getObject('db')->resultsFromCache($pagination->getCache())) {
				$comments = new Comments($this->registry, $row['id_article']);
				echo '<article>' . "\n";
				echo '<header><h2><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">' . $row['article_title'] . '</a></h2></header>' . "\n";
				echo '<div class="row articleInfo">' . "\n";
				echo '<div class="nine columns">' . $row['user_nick'] . ', publikované dňa <time datetime="' . $row['article_date'] . '">' . $row['dateFriendly'] . '</time></div>' . "\n";
				echo '<div class="three columns right-text"><a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '#comments">' . $comments->printCommentsNumber() . '</a></div>' . "\n";
				echo '</div>' . "\n";
				echo '<div class="row">' . "\n";
				echo '<div class="twelve columns mt">' . "\n";
				echo $this->getFirstPara($row['article_text']) . ' <a href="' . $this->registry->getSetting('siteurl') . '/articles/view/' . $row['id_article'] . '">Celý článok</a>' . "\n";
				echo '</div>' . "\n";
				echo '</div>' . "\n";
				echo '</article>' . "\n";
				echo '<hr />' . "\n";
			}
		}
		echo '</section>' . "\n";
		echo '<div class="pagination-centered">' . "\n";
		echo '<ul class="pagination">' . "\n";
		if ($pagination->isFirst()) {
			echo '<li class="arrow unavailable"><a href="">&laquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . "/articles/archive/$year/" . ($pagination->getCurrentPage()-2) . '">&laquo;</a></li>' . "\n";
		}
		for ($i = 1;$i <= $pagination->getNumPages(); $i++) {
			if ($i == $pagination->getCurrentPage()) {
				echo '<li class="current"><a href="' . $this->registry->getSetting('siteurl') . "/articles/archive/$year/" . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
			else {
				echo '<li><a href="' . $this->registry->getSetting('siteurl') . "/articles/archive/$year/" . ($i-1) . '">' . $i . '</a></li>' . "\n";
			}
		}
		if ($pagination->isLast()) {
			echo '<li class="arrow unavailable"><a href="">&raquo;</a></li>' . "\n";
		}
		else {
			echo '<li class="arrow"><a href="' . $this->registry->getSetting('siteurl') . "/articles/archive/$year/" . ($pagination->getCurrentPage()) . '">&raquo;</a></li>' . "\n";
		}
		echo '</ul>' . "\n";
		echo '</div>' . "\n";
		echo '</div>' . "\n";
		echo '<aside class="three columns">' . "\n";
			include 'aside.php';
		echo '</aside>' . "\n";
	}

}
?>