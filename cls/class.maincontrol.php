<?php

class MainControl
{
    private $db;
    private $sscat;

    public function connectDB()
    {
        if(!is_null($this->db)) return true;

        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function getDoKonca($de)
    {
        $dni = floor(($de-time())/(24*3600));

        if($dni == 0)
        {
            $g = floor(($de-time())/(3600));

            switch($g)
            {
                case 0:
                    return 'mniej niż godzina';
                case 1:
                    return '1 godzina';
                case 2:
                case 3:
                case 4:
                case 22:
                case 23:
                    return $g.' godziny';
                default:
                    return $g.' godzin';
            }
        }
        else if($dni == 1) return '1 dzień';
        else
        {
            return $dni.' dni';
        }
    }

    public function getNamesForCatEtcFromDB($m, $array = false)
    {
        /*
         * podajemy cos w formacie 1_1_1 lub 1_1_1_1 (modul opcjonalny)
         * i dostajemy nazwy w formacie stringa (wszystko razem) albo tablica stringow
         * [0] kategoria [1] obszar [2] tematyka [3] modul
         */

        $this->connectDB();

        $t = explode('_', $m);

        $aret = array();
        $ret = '';

        $sql = 'SELECT cat FROM cats_569 WHERE id_cat=\''.$t[0].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $ret.= $r['cat'];
        $aret[] = $r['cat'];

        $sql = 'SELECT subcat FROM subcats_569 WHERE id=\''.$t[0].'_'.$t[1].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $ret.= ' '.$r['subcat'];
        $aret[] = $r['subcat'];

        $sql = 'SELECT subsubcat FROM subsubcats_569 WHERE id=\''.$t[0].'_'.$t[1].'_'.$t[2].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $ret.= ' '.$r['subsubcat'];
        $this->sscat = $r['subsubcat'];
        $aret[] = $r['subsubcat'];

        if(count($t) > 3)
        {
            $sql = 'SELECT modul FROM moduls_569 WHERE id=\''.$t[0].'_'.$t[1].'_'.$t[2].'_'.$t[3].'\'';
            $res = $this->db->query($sql);
            $r = $res->fetch_assoc();
            $ret.= ' '.$r['modul'];
        }

        if($array) return $aret;
        else return $ret;
    }
    
    public function getNameForCat($m)
    {
        $this->connectDB();

        $t = explode('_', $m);
        $sql = 'SELECT cat FROM cats_569 WHERE id_cat=\''.$t[0].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $this->cat = $r['cat'];

        return $this->cat;
    }

    public function getNameForSubcat($m)
    {
        $this->connectDB();

        $t = explode('_', $m);
        $sql = 'SELECT subcat FROM subcats_569 WHERE id=\''.$t[0].'_'.$t[1].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $this->scat = $r['subcat'];

        return $this->scat;
    }

    public function getNameForSubsubcat($m)
    {
        $this->connectDB();

        $t = explode('_', $m);
        $sql = 'SELECT subsubcat FROM subsubcats_569 WHERE id=\''.$t[0].'_'.$t[1].'_'.$t[2].'\'';
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $this->sscat = $r['subsubcat'];

        return $this->sscat;
    }

    public function getCatTree()
    {
        $this->connectDB();

        $tree = array();

        $sql = 'SELECT * FROM `cats_569`';
        $res = $this->db->query($sql);

        while(($r = $res->fetch_assoc()) != false)
        {
            $tree[$r['id_cat']]['cat'] = $r['cat'];
        }

        $sql = 'SELECT * FROM `subcats_569`';
        $res = $this->db->query($sql);

        while(($r = $res->fetch_assoc()) != false)
        {
            $temp = explode('_', $r['id']);
            $tree[$temp[0]][$r['id']]['subcat'] = $r['subcat'];
        }

        $sql = 'SELECT * FROM `subsubcats_569`';
        $res = $this->db->query($sql);

        while(($r = $res->fetch_assoc()) != false)
        {
            $temp = explode('_', $r['id']);
            $tree[$temp[0]][$temp[0].'_'.$temp[1]][$r['id']]['subsubcat'] = $r['subsubcat'];
        }

        /*$sql = 'SELECT * FROM `moduls_569`';
        $res = $this->db->query($sql);

        while(($r = $res->fetch_assoc()) != false)
        {
            $temp = explode('_', $r['id']);
            $tree[$temp[0]][$temp[0].'_'.$temp[1]][$temp[0].'_'.$temp[1].'_'.$temp[2]][$r['id']]['modul'] = $r['modul'];
        }*/

        return $tree;
    }

    public function getCountOfComms($m)
    {
        $this->connectDB();

        $sql = "SELECT COUNT(DISTINCT C.id_comm) ile FROM `commisions_272` C LEFT JOIN `comm_moduls` CM ON C.id_comm=CM.id_comm LEFT JOIN `moduls_569` M ON CM.id_mod=M.id_modul WHERE id LIKE '".$m."_%'";

        $res = $this->db->query($sql);
        if($res) $res = $res->fetch_assoc();
        else $res['ile'] = 0;

        return $res['ile'];
    }
}

?>
