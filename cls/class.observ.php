<?php

class Observ
{
    private $db = null;
    
    public function __construct()
    {
        //echo 'xD';
    }

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function addObserverToComm($cid, $uid)
    {
        if(is_null($this->db)) $this->connectDB();

        $date = time();

        $sql = 'INSERT INTO `commisions_observers` (`id_comm`, `id_user`, `date_add`) VALUES (\''.$cid.'\', \''.$uid.'\', \''.$date.'\')';

        $this->db->query($sql);
    }

    public function addObserverToCat($id, $uid)
    {
        if(is_null($this->db)) $this->connectDB();

        $date = time();

        $sql = 'INSERT INTO `commisions_cat_observers` (`id`, `id_user`, `date_add`) VALUES (\''.$id.'\', \''.$uid.'\', \''.$date.'\')';

        $this->db->query($sql);
    }

    public function notifyObserversAboutNewOFE($cid)
    {
        $obs = $this->ifObserversExist($cid);

        if(is_array($obs))
        {
            // sa obserwatorzy wiec wysylamy maile

            pre($obs);
        }
    }

    public function notifyObserversAboutNewPeople($cid)
    {
        $obs = $this->ifObserversExist($cid);

        if(is_array($obs))
        {
            // sa obserwatorzy wiec wysylamy maile

            pre($obs);
        }
    }

    public function notifyObserversAboutNewStuffInCat($cid)
    {
        $obs = $this->ifObserversExistForCats($cid);

        if(is_array($obs))
        {
            // sa obserwatorzy wiec wysylamy maile
            echo '<em>'.$cid.'</em>';
            pre($obs);
        }
    }

    /**
     * Spr czy sa w ogole jacys obserwatorzy do powiadomienia
     * 
     * @param <type> $cid
     */
    private function ifObserversExist($cid)
    {
        if(is_null($this->db)) $this->connectDB();

        $sql = 'SELECT * FROM `commisions_observers` CO LEFT JOIN users_324 U ON CO.id_user=U.id_user WHERE id_comm='.$cid;

        $cm = $this->db->query($sql);

        if($this->db->affected_rows > 0)
        {
            $obs = array();

            while($t = $cm->fetch_assoc())
            {
                $obs[$t['id_user']] = $t['email'];
            }

            return $obs;
        }
        else return false;
    }

    /**
     * Spr czy sa w ogole jacys obserwatorzy do powiadomienia
     *
     * @param <type> $cid
     */
    private function ifObserversExistForCats($id)
    {
        if(is_null($this->db)) $this->connectDB();

        $sql = 'SELECT * FROM `commisions_cat_observers` CCO LEFT JOIN users_324 U ON CCO.id_user=U.id_user WHERE id=\''.$id.'\'';

        $cm = $this->db->query($sql);

        if($this->db->affected_rows > 0)
        {
            $obs = array();

            while($t = $cm->fetch_assoc())
            {
                $obs[$t['id_user']] = $t['email'];
            }

            return $obs;
        }
        else return false;
    }
}

?>
