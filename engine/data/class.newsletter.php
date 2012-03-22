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

    /*     * Funkcja pobierająca i tworząca tablicę promowanych usług z ich ID i NAZWĄ
     *
     * @param DBC $dbc
     * @return Array() $promoted - tablica z numerami i nazwami usług
     * @throws DBQueryException 
     */

    public function completePromotedServList(DBC $dbc) {
        $n = new Newsletter();
        $promoted = array();

        $sql = Query::getPromotedServs();
        $result = $dbc->query($sql);
        if (!$result)
            throw new DBQueryException($dbc->error, $sql, $dbc->errno);
        if ($result->num_rows <= 0)
            $promoted = array();
        else {
            $i = 0;
            //tworzymy tablicę z numerami i nazwami usług
            while ($row = $result->fetch_assoc()) {
                $promoted[$i]['id_serv'] = $row['id_serv'];
                $promoted[$i]['name'] = $row['name'];
                $i++;
            }
        }
        return $promoted;
    }

}

?>
