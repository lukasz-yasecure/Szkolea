<?php

class Commisions
{
    private $db;
    private $cc; // CommControl
    private $cid; // CommID (added)
    private $conf;
    private $mc; // MainControl

    private $all = array();
    private $uid = null;

    private $cat = null;
    private $subcat = null;
    private $dur1 = null;
    private $dur2 = null;
    private $part1 = null;
    private $part2 = null;
    private $desc = null;
    private $id = null;

    public function __construct()
    {
        $this->conf = new Config();
        $this->mc = new MainControl();
    }

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        if(!is_null($this->db)) return true;
        
        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function getCommID()
    {
        return $this->cid;
    }

    public function addComToDB(CommControl $cc)
    {
        $this->connectDB();

        $this->cc = $cc;

        $sql = $cc->getAddQuery();
        $this->db->query($sql);
        $this->cid = $this->db->insert_id;

        $sql = $cc->getAddQueryForModuls($this->cid);
        if($sql)
        {
            $this->db->query($sql);
        }

        $sql = $cc->getAddQueryForParticipants($this->cid);
        if($sql)
        {
            $this->db->query($sql);
        }
    }

    public function addKToComm($cid, $uid)
    {
        $this->connectDB();

        $date = time();

        $sql = 'INSERT INTO `commisions_group` (`id_comm`, `id_user`, `date_add`) VALUES (\''.$cid.'\', \''.$uid.'\', \''.$date.'\')';

        $this->db->query($sql);
    }

    public function getAllCommsFromDB(CommControl $cc)
    {
        $this->connectDB();
        $this->cc = $cc;

        $sql = $cc->getShowQueryForAllComms();
        $cm = $this->db->query($sql);

        return $this->mysqliResultToComms($cm, $cc->getWord());
    }

    public function getSelectedCommsFromDB(CommControl $cc)
    {
        /*
         * bierze SQL Query
         * i przekazuje do obrobki
         */

        $this->connectDB();
        $this->cc = $cc;

        $sql = $cc->getSearchQueryFromSource();
        $cm = $this->db->query($sql);

        if(!$cm) return array();

        return $this->mysqliResultToComms($cm, $cc->getWord());
    }

    public function mysqliResultToComms(mysqli_result $res, $word)
    {
        /*
         * dostaje wyniki SELECTa i ewentualnie slowo wg. ktorego nalezy przefiltrowac wyniki
         * najpierw sprowadzamy wyniki do ladnej postaci a potem szukamy slowa
         */

        $comms = array();
        $ncomms = array(); // pod $word

        while($t = $res->fetch_assoc())
        {
            /*
             * ladna postac tablicowa
             */

            $comms[$t['id_comm']]['date_add'] = $t['date_add'];
            $comms[$t['id_comm']]['date_end'] = $t['date_end'];
            $comms[$t['id_comm']]['cena_min'] = $t['cena_min'];
            $comms[$t['id_comm']]['cena_max'] = $t['cena_max'];
            $comms[$t['id_comm']]['gdzie'] = $t['place'];
            $comms[$t['id_comm']]['zapis'] = $t['zapis'];
            $comms[$t['id_comm']]['moduly'][$t['id']] = $t['modul'];
            $comms[$t['id_comm']]['sscat'] = $this->mc->getNameForSubsubcat($t['id']);
        }

        if($word)
        {
            /*
             * jest slowo wiec trzeba filtrowac
             * bierzemy kazde zlecenie po kolei
             * wyciagamy moduly i w kazdym sprawdzamy czy wystepuje slowo
             * jest tak to dodajemy zlecenie do nowej tablicy a jak nie to sprawdzamy w kategorii obszarze i tematyce
             * zwracamy nowa tablice
             */

            $word = mb_strtolower($word, 'UTF-8');

            foreach($comms as $id => $v) // jedziemy ze wszystkimi zleceniami po kolei
            {
                if(is_array($v['moduly']))
                {
                    $ttt = false;

                    foreach($v['moduly'] as $mid => $m) // sprawdzamy kazdy modul zlecenia
                    {
                        $m = mb_strtolower($m, 'UTF-8');
                        $find = mb_strpos($m, $word);

                        if($find !== false) // znaleziono w module
                        {
                            $ttt = true;
                            $ncomms[$id] = $comms[$id];
                            break;
                        }
                    }

                    if($ttt) continue;
                    else // nie ma w modulach trzeba sprawdzic kat obsz i tem
                    {
                        $hay = mb_strtolower($this->mc->getNamesForCatEtcFromDB($mid), 'UTF-8');
                        $find = mb_strpos($hay, $word);

                        if($find !== false) $ncomms[$id] = $comms[$id];
                    }
                }
            }

            $comms = $ncomms;
        }

        return $comms;
    }

    public function getCommFromDB($id)
    {
        $this->connectDB();

        $sql = "SELECT *
                FROM `comm_moduls` CM
                LEFT JOIN `commisions` C ON CM.id_comm = C.id_comm
                LEFT JOIN `moduls_569` M ON CM.id_mod = M.id_modul
                LEFT JOIN (

                SELECT id_comm id_comm2, COUNT( * ) zapis
                FROM `commisions_group`
                GROUP BY id_comm
                )Z ON C.id_comm = Z.id_comm2
                LEFT JOIN wojewodztwa W ON C.woj = W.id_woj
                WHERE CM.id_comm = '".$id."'";

        $res = $this->db->query($sql);
        $t = array();

        while(($r = $res->fetch_assoc()))
        {
            $t['id_modul'] = $r['id'];
            $t['id'] = $r['id_comm'];
            $t['zapisanych'] = $r['zapis'];
            $t['koniec'] = $r['date_end'];
            $t['dlugosc'] = $r['long'];
            $t['dni'] = $r['days'];
            $t['terminy']['a'] = $r['date_a'];
            $t['terminy']['b'] = $r['date_b'];
            if(!empty($r['date_c'])) $t['terminy']['c'] = $r['date_c'];
            if(!empty($r['date_d'])) $t['terminy']['d'] = $r['date_d'];
            $t['miejsce']['place'] = $r['place'];
            if(!empty($r['woj'])) $t['miejsce']['woj'] = $r['woj'];
            $t['cena']['min'] = $r['cena_min'];
            $t['cena']['max'] = $r['cena_max'];
            $t['modul'][] = $r['modul'];
        }

        $sql = "SELECT COUNT( * ) ofert
                FROM `commisions_ofe`
                WHERE id_comm='".$id."'";

        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();
        $t['ofert'] = $r['ofert'];

        return $t;
    }

    public function getModulsForComm($mid)
    {
        $sql = "SELECT modul FROM `comm_moduls` CM LEFT JOIN `moduls_569` M ON CM.id_mod=M.id_modul WHERE id_comm='".$mid."'";
        $res = $this->db->query($sql);

        $ret = '';

        while(($r = $res->fetch_assoc()) != false)
        {
            $ret.= $r['modul'].', ';
        }

        return substr($ret, 0, -2);
    }

    public function getMyCommsFromDB($uid)
    {
        $this->connectDB();

        $sql = "SELECT *
                FROM `comm_moduls` CM
                LEFT JOIN `commisions` C ON CM.id_comm = C.id_comm
                LEFT JOIN `moduls_569` M ON CM.id_mod = M.id_modul
                LEFT JOIN (

                SELECT id_comm id_comm2, COUNT( * ) zapis
                FROM `commisions_group`
                GROUP BY id_comm
                )Z ON C.id_comm = Z.id_comm2
                LEFT JOIN wojewodztwa W ON C.woj = W.id_woj
                WHERE C.id_user = '".$uid."'";

        $res = $this->db->query($sql);

        return $this->mysqliResultToComms($res, false);
    }
}

?>
