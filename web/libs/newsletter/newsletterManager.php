<?php
/**
 * User: admin
 * Date: 23.11.2013
 * Time: 12:04
 */

class newsletterManager {

    private $registry;
    private $newsletterList;

    public function __construct(Registry $registry, $action, $data = '') {
        require_once(FRAMEWORK_PATH . 'models/newsletterList.php');
        require_once(FRAMEWORK_PATH . 'models/email.php');
        $this->registry = $registry;
        $this->newsletterList = new NewsletterList($this->registry);
        switch ($action) {
            case 'newAnnouncement':
                $this->newAnnouncement($data);
                break;
            case 'newEvent':
                $this->newEvent($data);
                break;
            case 'newSuplo':
                $this->newSuplo($data);
                break;
        }
    }

    private function newAnnouncement($data) {
        $cache = $this->newsletterList->getAnnMails();
        $email = new Email($this->registry);
        $email->setSender();
        $email->setSubject($data['title']);
        $email->buildFromTemplate('newAnnouncement.html');
        $tags = array();
        $tags['annText'] = $data['text'];
        $tags['annTitle'] = $data['title'];
        $tags['siteurl'] = $this->registry->getSetting('siteurl');
        $tags['defaultView'] = $this->registry->getSetting('view');
        $email->replaceTags($tags);
        while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
            $email->addBCC($row['nwt_email']);
        }
        $email->send();
    }

    private function newEvent($data) {
        $cache = $this->newsletterList->getEventMails();
        $email = new Email($this->registry);
        $email->setSender();
        $email->setSubject($data['title']);
        $email->buildFromTemplate('newEvent.html');
        $tags = array();
        $tags['eventText'] = $data['text'];
        $tags['eventTitle'] = $data['title'];
        $tags['eventStart'] = $data['startDate']->format("j. n. Y - H:i");
        $tags['eventEnd'] = $data['endDate']->format("j. n. Y - H:i");
        $tags['eventLocation'] = $data['location'];
        $tags['siteurl'] = $this->registry->getSetting('siteurl');
        $tags['defaultView'] = $this->registry->getSetting('view');
        $email->replaceTags($tags);
        while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
            $email->addBCC($row['nwt_email']);
        }
        $email->send();
    }

    private function newSuplo($date) {
        require_once(FRAMEWORK_PATH . 'models/suploTable.php');

        //SuploAll
        $cache = $this->newsletterList->getSuploMails();
        $email = new Email($this->registry);
        $email->setSender();
        $email->setSubject('{lang_suplo} ' . $date->format("j. n. Y"));
        $email->buildFromTemplate('newSuplo.html');
        $tags['siteurl'] = $this->registry->getSetting('siteurl');
        $tags['defaultView'] = $this->registry->getSetting('view');
        $suploTable = new suploTable($this->registry, $date);
        $suploRecords = $suploTable->getRecords();
        $output = "";
        foreach ($suploRecords as $record) {
            $data = $record->toArray();
            $this->registry->firephp->log($data);
            $row = '<tr>' . "\n";
            $row .= "<td>" . $data['hour'] . "</td>";
            $row .= "<td>" . $data['missing']->name . "</td>";
            $row .= "<td>" . $record->getClassesShort() . "</td>";
            $row .= "<td>" . $data['subject'] . "</td>";
            $row .= "<td>" . $data['classroom'] . "</td>";
            $row .= "<td>" . $data['owner']->name . "</td> \n";
            $row .= "<td>" . $data['note'] . "</td> \n";
            $row .= "</tr>" . "\n";
            $output .= $row;
        }
        $tags['suploTable'] = $output;
        $tags['suploTitle'] = $this->registry->getObject('template')->getLocaleValue("lang_suplo") . ' ' . $date->format("j. n. Y");
        $email->replaceTags($tags);
        while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
            $email->addBCC($row['nwt_email']);
        }
        $email->send();

        //SuploSolo
        $cache = $this->newsletterList->getSoloSuploMails();
        while ($row = $this->registry->getObject('db')->resultsFromCache($cache)) {
            $userId = $row['id_user'];
            $supDate = $date->format("Y-m-d");
            $this->registry->getObject('db')->executeQuery("SELECT id_suplo FROM suplo WHERE id_user = '$userId' AND sup_date = '$supDate'");
            if ($this->registry->getObject('db')->numRows() > 0) {
                $email = new Email($this->registry);
                $email->setSender();
                $email->setSubject('Suplovanie na ' . $date->format("j. n. Y"));
                $email->buildFromTemplate('newSuplo.html');
                $tags['siteurl'] = $this->registry->getSetting('siteurl');
                $tags['defaultView'] = $this->registry->getSetting('view');
                $tags['suploTitle'] = '{lang_suplo} ' . $date->format("j. n. Y");
                $output = '';
                while ($suploRow = $this->registry->getObject('db')->getRows()) {
                    $suploRecord = new suploRecord($this->registry, $suploRow['id_suplo']);
                    $data = $suploRecord->toArray();
                    $suploRow = '<tr>' . "\n";
                    $suploRow .= "<td>" . $data['hour'] . "</td>";
                    $suploRow .= "<td>" . $data['missing']->name . "</td>";
                    $suploRow .= "<td>" . $suploRecord->getClassesShort() . "</td>";
                    $suploRow .= "<td>" . $data['subject'] . "</td>";
                    $suploRow .= "<td>" . $data['classroom'] . "</td>";
                    $suploRow .= "<td>" . $data['owner']->name . "</td> \n";
                    $suploRow .= "<td>" . $data['note'] . "</td> \n";
                    $suploRow .= "</tr>" . "\n";
                    $output .= $suploRow;
                }
                $tags['suploTable'] = $output;
                $email->replaceTags($tags);
                $email->setRecipient($row['nwt_email']);
                $email->send();
            }
        }
    }
}
?>