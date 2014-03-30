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
        $urlBits = $this->registry->url->getURLBits();
        if ($this->registry->auth->isLoggedIn()) {
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
            $this->registry->url->redirectURL($this->registry->url->buildURL($redirectBits), '{lang_pleaseLogIn}', 'alert');
        }
    }

    private function uiMe() {
        header('Location: https://plus.google.com/u/1/' . $this->registry->auth->getUser()->getId() . '/about');
    }

    private function profileSettings() {
        $tags = array();
        $tags['title'] = "{lang_profileSettings} - " . $this->registry->getSetting('sitename');
        $this->registry->template->buildFromTemplate('header', false);
        $tags['header'] = $this->registry->template->parseOutput();
        $this->registry->template->buildFromTemplate('profileSettings');
        require_once(FRAMEWORK_PATH . 'models/newsletterList.php');
        $newsletterList = new NewsletterList($this->registry);
        $cache = $newsletterList->getNewsletterForUser($this->registry->auth->getUser()->getId());
        if ($this->registry->db->numRowsFromCache($cache) > 0) {
            $output = '';
            while ($row = $this->registry->db->resultsFromCache($cache)) {
                $output .= '<tr>' . "\n";
                $output .= '<td>' . $row['nwt_email'] . '</td>' . "\n";
                if ($row['nwt_announcements']) {
                    $output .= '<td><span class="success label">{lang_active}</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">{lang_inactive}</span></td>' . "\n";
                }
                if ($row['nwt_events']) {
                    $output .= '<td><span class="success label">{lang_active}</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">{lang_inactive}</span></td>' . "\n";
                }
                if ($row['nwt_suploAll']) {
                    $output .= '<td><span class="success label">{lang_active}</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">{lang_inactive}</span></td>' . "\n";
                }
                if ($row['nwt_suploMy']) {
                    $output .= '<td><span class="success label">{lang_active}</span></td>' . "\n";
                }
                else {
                    $output .= '<td><span class="alert label">{lang_inactive}</span></td>' . "\n";
                }
                $output .= '<td style="text-align: center;">' . "\n";
                $output .= '<a href="' . $this->registry->getSetting('siteurl') . '/newsletter/remove/' . $row['id_newsletter'] . '" class="tiny button alert" style="margin: 0;">{lang_delete}</a>' . "\n";
                $output .= '<a href="' . $this->registry->getSetting('siteurl') . '/newsletter/edit/' . $row['id_newsletter'] . '" class="tiny button" style="margin: 0;">{lang_edit}</a>' . "\n";
                $output .= '</td>' . "\n";
                $output .= '</tr>' . "\n";
            }
        }
        else {
            $output = '<tr><td colspan="6" style="text-align: center;"><span class="label">{lang_noEmailRegistered}</span></td></tr>';
        }
        $tags['newsletterTable'] = $output;
        $this->registry->template->replaceTags($tags);
        echo $this->registry->template->parseOutput();
    }
}

?>