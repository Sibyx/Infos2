<?php
/*
 * 16.03.2013
 * Class Renderer v1.1
 * Generuje HTML
*/
class Renderer {

    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    public function createUserPanel() {
        $result = '';
        if ($this->registry->getObject('auth')->isLoggedIn()) {
            $result = '<section class="row">' . "\n";
            $result .= '<div class="large-12 columns">' . "\n";
            $result .= '<header><h2><small>' . $this->registry->getObject('auth')->getUser()->getFullName() . '</small></h2></header>' . "\n";
            $result .= '<ul class="side-nav">' . "\n";
            $result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/new">New Article</a></li>' . "\n";
            $result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/articles/manage">Manage Articles</a></li>' . "\n";
            $result .= '<li><hr /></li>' . "\n";
            $result .= '<li><a href="' . $this->registry->getSetting('siteurl') . '/authenticate/logout">Logout</a></li>' . "\n";
            $result .= '</ul>' . "\n";
            $result .= '</div>' . "\n";
            $result .= '</section>' . "\n";
        }
        return $result;
    }
}
?>