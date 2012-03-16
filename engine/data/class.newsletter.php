<?php

class Newsletter {

    private $subject = null;
    private $content = null;
    private $receivers = array();

    public function getSubject() {
        return $this->subject;
    }

    public function getContent() {
        return $this->content;
    }

    public function getReceivers() {
        return $this->receivers;
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

    //TODO PRZENIEŚĆ DO USER DATA

    /**
     *
     * @param type $post
     * @return Newsletter 
     */
    public function getNewsletterFromPost($post, DBC $dbc) {
        $n = new Newsletter();


        $this->subject = (isset($post['subject']) ? $post['subject'] : null );
        $this->content = (isset($post['content']) ? $post['content'] : null );

        if (isset($post['type'])) {

            $sql = Query::getEmails($post['type']);
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $n->setReceivers(array());
            else {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $this->receivers = $receivers[$i] = $row['email'];
                    $i++;
                }
            }
        }else
            $n->setReceivers(array());



        return $n;
    }

}

?>
