<?php
/**
 * User: admin
 * Date: 26.11.2013
 * Time: 17:13
 */

class profileController {

    private $registry;

    public function __construct(Registry $registry) {
        $this->registry = $registry;
        $urlBits = $this->registry->getObject('url')->getURLBits();
        if ($this->registry->getObject('auth')->isLoggedIn()) {
            switch(isset($urlBits[1]) ? $urlBits[1] : '') {
                case 'me':
                    $this->uiMe();
                    break;
                case 'settings':
                    $this->profileSettings();
                    break;
                default:
                    $this->uiMe();
                    break;
            }
        }
        else {
            $redirectBits = array();
            $redirectBits[] = 'authenticate';
            $redirectBits[] = 'login';
            $this->registry->redirectURL($this->registry->buildURL($redirectBits), 'Musíš byť prihlásený', 'alert');
        }
    }

    private function uiMe() {
        header('Location: https://plus.google.com/u/1/' . $this->registry->getObject('auth')->getUser()->getId() . '/about');
    }

    private function profileSettings() {
        $tags = array();
        $tags['title'] = "Nastavenie profilu - Infos2";
        $this->registry->getObject('template')->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->getObject('template')->parseOutput();
        $this->registry->getObject('template')->buildFromTemplate('profileSettings');
        require_once(FRAMEWORK_PATH . 'models/newsletterList.php');
        $newsletterList = new NewsletterList($this->registry);
        $cache = $newsletterList->getNewsletterForUser($this->registry->getObject('auth')->getUser()->getId());
        if ($this->registry->getObject('db')->numRowsFromCache($cache) > 0) {
            $output = '';
            while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
                $output .= '<tr>' . "\n";
                $output .= '<td>' . $row['nwt_email'] . '</td>' . "\n";
                if ($row['nwt_announcements']) {
                    $output .= '<td><span class="success label">aktívne</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">neaktívne</span></td>' . "\n";
                }
                if ($row['nwt_events']) {
                    $output .= '<td><span class="success label">aktívne</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">neaktívne</span></td>' . "\n";
                }
                if ($row['nwt_suploAll']) {
                    $output .= '<td><span class="success label">aktívne</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">neaktívne</span></td>' . "\n";
                }
                if ($row['nwt_suploMy']) {
                    $output .= '<td><span class="success label">aktívne</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">neaktívne</span></td>' . "\n";
                }
                $output .= '<td style="text-align: center;">' . "\n";
                $output .= '<a href="' . $this->registry->getSetting('siteurl') . '/newsletter/remove/' . $row['id_newsletter'] . '" class="tiny button alert" style="margin: 0;">Odstrániť</a>' . "\n";
                $output .= '<a href="' . $this->registry->getSetting('siteurl') . '/newsletter/edit/' . $row['id_newsletter'] . '" class="tiny button" style="margin: 0;">Upraviť</a>' . "\n";
                $output .= '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output = '<tr><td colspan="6" style="text-align: center;"><span class="label">Nemáte zaregistrovaný žiadny e-mail</span></td></tr>';
        }
        $tags['newsletterTable'] = $output;
        $this->registry->getObject('template')->replaceTags($tags);
        echo $this->registry->getObject('template')->parseOutput();
    }
}

?>