<?php


class Admin
{
    private $db = null;
    private $lev = array('', 'subcats', 'subsubcats', 'moduls');
    private $lev_col = array('', 'subcat', 'subsubcat', 'modul');

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(DBHOST, DBLOG, DBPASS, DBNAME);
            $db->set_charset('utf8');
            $this->db = $db;
        }
    }

    public function getAllCatsFromDB()
    {
        $this->connectDB();

        $sql = 'SELECT * FROM `cats_569` ORDER BY cat ASC';

        $res = $this->db->query($sql);

        $cats = array();

        while($t = $res->fetch_assoc())
        {
            $cats[] = $t;
        }

        return $cats;
    }

    public function getAllSubcatsFromDB($id = '')
    {
        $this->connectDB();

        $sql = '';

        if($id != '') $sql = 'SELECT * FROM `subcats_569` WHERE `id` LIKE \''.$id.'\_%\' ORDER BY subcat ASC';
        else $sql = 'SELECT * FROM `subcats_569` ORDER BY id ASC';

        $res = $this->db->query($sql);

        $scats = array();

        while($t = $res->fetch_assoc())
        {
            $scats[] = $t;
        }

        return $scats;
    }

    public function getAllSubsubcatsFromDB($id = '')
    {
        $this->connectDB();

        $sql = '';

        if($id != '') $sql = 'SELECT * FROM `subsubcats_569` WHERE `id` LIKE \''.$id.'\_%\' ORDER BY subsubcat ASC';
        else $sql = 'SELECT * FROM `subsubcats_569` ORDER BY id ASC';

        $res = $this->db->query($sql);

        $scats = array();

        while($t = $res->fetch_assoc())
        {
            $scats[] = $t;
        }

        return $scats;
    }

    public function getAllModulsFromDB($id = '')
    {
        $this->connectDB();

        $sql = '';

        if($id != '') $sql = 'SELECT * FROM `moduls_569` WHERE `id` LIKE \''.$id.'\_%\' ORDER BY modul ASC';
        else $sql = 'SELECT * FROM `moduls_569` ORDER BY id ASC';

        $res = $this->db->query($sql);

        $scats = array();

        while($t = $res->fetch_assoc())
        {
            $scats[] = $t;
        }

        return $scats;
    }

    public function del($id, $l)
    {
        $this->connectDB();

        $sql = "DELETE FROM `".$this->lev[$l]."_569` WHERE `id`='".$id."'";

        $this->db->query($sql);

        echo $sql.' --- '.$this->db->error.'<br/>';
    }

    public function delDown($id, $l)
    {
        $this->connectDB();
        
        if($l == 1)
        {
            $sql = "DELETE FROM `".$this->lev[2]."_569` WHERE `id` LIKE '".$id."_%'";
            $this->db->query($sql);

            $sql = "DELETE FROM `".$this->lev[3]."_569` WHERE `id` LIKE '".$id."_%'";
            $this->db->query($sql);
        }
        else if($l == 2)
        {
            $sql = "DELETE FROM `".$this->lev[3]."_569` WHERE `id` LIKE '".$id."_%'";
            $this->db->query($sql);
        }
    }

    public function update($id, $s, $l)
    {
        $this->connectDB();
        
        $sql = "UPDATE `".$this->lev[$l]."_569` SET `".substr($this->lev[$l], 0, -1)."` = '".$s."' WHERE `id`='".$id."'";

        $this->db->query($sql);
    }

    public function add($id, $s, $l)
    {
        $this->connectDB();

        $sql = "INSERT INTO `".$this->lev[$l]."_569` (`id_".substr($this->lev[$l], 0, -1)."`, `".substr($this->lev[$l], 0, -1)."`, `id`) VALUES(NULL, '".$s."', '".$id."')";

        $this->db->query($sql);
    }

    public function getLastID($id, $l)
    {
        $this->connectDB();
        
        $sql = "SELECT id FROM `".$this->lev[$l]."_569` WHERE id LIKE '".$id."_%' ORDER BY id_".$this->lev_col[$l]." DESC LIMIT 1";

        $res = $this->db->query($sql);

        if($this->db->affected_rows > 0)
        {
            $r = $res->fetch_assoc();
            return $r['id'];
        }
        else
        {
            return $id.'_0';
        }
    }
}

?>
