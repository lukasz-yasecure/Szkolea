<?php

class Newsletter {

    private $subject = null;
    private $content = null;
    private $receivers = array();
    private $promoted_servs = array();
    
    //iteratory
    private $receivers_mails_it = 0;
    private $promoted_servs_it = 0;

    public function getSubject() {
        return $this->subject;
    }

    public function getContent() {
        return $this->content;
    }

    public function getReceivers() {
        return $this->receivers;
    }
        public function setPromotedServs($promoted) {
        $this->promoted_servs = $promoted;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setReceivers($receivers) {
        $this->receivers = $receivers;
    }

    public function completeMailsList(DBC $dbc) {

        $receivers_mails = array();


        //receivers mówi nam o grupie docelowej , względem której pobierane są maile z bazy , a następnie do których nastąpi mailing
        if (isset($_POST['receivers'])) {
            $sql = Query::getEmails($_POST['receivers']);
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $receivers_mails = array();
            else {
                $i = 0;

                //tworzymi listę wszystkich adresów e-mail z grupy docelowej
                while ($row = $result->fetch_assoc()) {
                    $receivers_mails[$i] = $row['email'];
                    $i++;
                }
            }
            $this->setReceivers($receivers_mails);
        }
    }

    public function getReceiver() {

        if ($this->receivers_mails_it == count($this->receivers))
            return null;
        else
            $this->receivers_mails_it++;
        return $this->receivers[$this->receivers_mails_it - 1];
    }
    
        public function getService() {

        if ($this->promoted_servs_it == count($this->promoted_servs))
            return null;
        else
            $this->promoted_servs_it++;
        return $this->promoted_servs[$this->promoted_servs_it - 1];
    }

}

?>
