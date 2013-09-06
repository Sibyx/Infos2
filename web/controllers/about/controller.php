<?php
class aboutController {
	
	public function __construct(Registry $registry){
		$this->registry = $registry;
		$urlBits = $this->registry->getObject('url')->getURLBits();
		switch(isset($urlBits[1]) ? $urlBits[1] : '') {
			case 'history':
				$this->aboutHistory();
			break;
			case 'members':
				$this->aboutMembers();
			break;
			case 'technics':
				$this->aboutTechnics();
			break;
			case 'calendar':
				$this->aboutCalendar();
			break;
			default:				
				$this->listAbout();
			break;
		}
	}
	
	private function listAbout() {
		//icons = http://www.iconfinder.com/icondetails/46831/256/calendar_recycle_icon
		echo '<div class="twelve columns">' . "\n";
		echo '<section>' . "\n";
		
		echo '<article class="row">' . "\n";
		echo '<div class="eight columns">' . "\n";
		echo '<h2><a href="' . $this->registry->getSetting('siteurl') . '/about/members">Členovia</a></h2>' . "\n";
		echo '<h3 class="no-marg-top subheader">In Foundation, paragraphs fall within the modular scale. This includes their line-height, giving the page a feeling of harmony as you scroll.</h3>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="four columns text-center">' . "\n";
		echo '<img src="' . $this->registry->getSetting('siteurl') . '/views/' . $this->registry->getSetting('view') . '/images/members.png'  . '" alt="Histroy"/>' . "\n";
		echo '</div>' . "\n";
		echo '</article>' . "\n";
		
		echo '<hr />' . "\n";
		
		echo '<article class="row">' . "\n";
		echo '<div class="four columns text-center">' . "\n";
		echo '<img src="' . $this->registry->getSetting('siteurl') . '/views/' . $this->registry->getSetting('view') . '/images/technics.png'  . '" alt="Histroy"/>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="eight columns">' . "\n";
		echo '<h2><a href="' . $this->registry->getSetting('siteurl') . '/about/technics">Technika</a></h2>' . "\n";
		echo '<h3 class="no-marg-top subheader">In Foundation, paragraphs fall within the modular scale. This includes their line-height, giving the page a feeling of harmony as you scroll.</h3>' . "\n";
		echo '</div>' . "\n";
		echo '</article>' . "\n";
		
		echo '<hr />' . "\n";
		
		echo '<article class="row">' . "\n";
		echo '<div class="eight columns">' . "\n";
		echo '<h2><a href="' . $this->registry->getSetting('siteurl') . '/about/history">História</a></h2>' . "\n";
		echo '<h3 class="no-marg-top subheader">In Foundation, paragraphs fall within the modular scale. This includes their line-height, giving the page a feeling of harmony as you scroll.</h3>' . "\n";
		echo '</div>' . "\n";
		echo '<div class="four columns text-center">' . "\n";
		echo '<img src="' . $this->registry->getSetting('siteurl') . '/views/' . $this->registry->getSetting('view') . '/images/history.png'  . '" alt="Histroy"/>' . "\n";
		echo '</div>' . "\n";
		echo '</article>' . "\n";
		
		echo '</section>' . "\n";
		echo '</div>' . "\n";
	}
	
	private function aboutHistory() {
		echo '<div class="twelve columns">' . "\n";
		echo '<section>' . "\n";
		echo '<header><h2>História klubu</h2></header>' . "\n";
		echo file_get_contents(FRAMEWORK_PATH . '/controllers/about/history.html');
		echo '</section>' . "\n";
		echo '</div>' . "\n";
	}
	
	private function aboutMembers() {
		echo '<div class="twelve columns">' . "\n";
		echo '<section>' . "\n";
		echo '<header><h2>Členovia klubu</h2></header>' . "\n";
		echo '<div id="membersSlideshow">' . "\n";
		for ($i = 1;$i <= 25; $i++) {
			echo '<div class="slide"><div><h3>' . $i . '</h3><a href="#' . $i . '"><img src="http://lorempixel.com/400/200" class="memberThumb"/></a></div></div>' . "\n";
		}
		echo '</div>' . "\n";
		echo "<script type='text/javascript'>$(window).load(function() {\$('#membersSlideshow').orbit({ fluid: '16x6' });});</script>" . "\n";
		//include 'slideshow.php';
		echo '</section>' . "\n";
		echo '</div>' . "\n";
	}
	
	private function aboutTechnics() {
		echo '<div class="nine columns">' . "\n";
		echo '<section>' . "\n";
		echo '<header><h2>Technika klubu</h2></header>' . "\n";
		echo '</section>' . "\n";
		echo '</div>' . "\n";
		echo '<aside class="three columns">' . "\n";
		echo '</aside>' . "\n";
	}
	
	private function aboutCalendar() {
		echo '<div class="twelve columns">' . "\n";
		echo '<section>' . "\n";
		echo '<header><h2>Kalendár</h2></header>' . "\n";
		echo '</section>' . "\n";
		echo '</div>' . "\n";
	}

}
?>