<?php

class Newsletter {

    private $subject = null;
    private $content = null;
    private $receivers = '';
    private $receivers_mails = array();
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

    public function getReceiversMails() {
        return $this->receivers_mails;
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

    public function setReceiversMails($receivers_mails) {
        $this->receivers_mails = $receivers_mails;
    }

    //tworzenie listy odbiorców
    public function completeMailsList(DBC $dbc) {

        //receivers mówi nam o grupie docelowej , względem której pobierane są maile z bazy , a następnie do których nastąpi mailing
        if (isset($this->receivers)) {
            $sql = Query::getEmails($this->receivers);
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
            $this->setReceiversMails($receivers_mails);
        }
    }

    //pobieranie pojedyńczego odbiorcy po kolei przy każdokrotnym użyciu funkcji (interator)
    public function getReceiver() {

        if ($this->receivers_mails_it == count($this->receivers)) {
            $this->receivers_mails_it = 0;
            return null;
        } else
            $this->receivers_mails_it++;
        return $this->receivers[$this->receivers_mails_it - 1];
    }

    /** pobieranie pojedyńczej usługi po kolei przy każdokrotnym użyciu funkcji (interator)
     *
     * @return ($this->promoted_servs[$this->promoted_servs_it-1]) pojedyńcza usługa
     */
    public function getService() {

        if ($this->promoted_servs_it == count($this->promoted_servs)) {
            $promoted_servs_it = 0;
            return null;
        } else
            $this->promoted_servs_it++;
        return $this->promoted_servs[$this->promoted_servs_it - 1];
    }

}

?>
