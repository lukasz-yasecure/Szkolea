<?php

class Services
{
    private $db;
    private $sc; // ServControl
    private $sid; // ServID (dodany)
    private $mc; // MainControl

    public function __construct()
    {
        $this->mc = new MainControl();
    }

    public function connectDB()
    {
        if(!is_null($this->db)) return true;

        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function addServiceToDB(ServControl $sc)
    {
        $this->connectDB();

        $this->sc = $sc;

        $sql = $sc->getAddQuery();
        $this->db->query($sql);
        $this->sid = $this->db->insert_id;

        $sql = $sc->getAddQueryForModuls($this->sid);
        if($sql)
        {
            $this->db->query($sql);
        }
        //dpr($this->db->error);
    }

    public function getSelectedServsFromDB(ServControl $sc)
    {
        /* bierzemy zapytanie sql
         * jesli select cos zwrocil to podajemy dalej do obrobki
         */

        $this->connectDB();
        $this->sc = $sc;

        $sql = $sc->getSearchQueryFromSource();
        $sr = $this->db->query($sql);

        if(!$sr) return array();

        return $this->mysqliResultToServs($sr, $sc->getWord());
    }

    public function mysqliResultToServs(mysqli_result $res, $word)
    {
        /*
         * dostaje wyniki SELECTa i ewentualnie slowo wg. ktorego nalezy przefiltrowac wyniki
         * najpierw sprowadzamy wyniki do ladnej postaci a potem szukamy slowa
         */

        $s = array();
        $ns = array();

        while($t = $res->fetch_assoc())
        {
            $s[$t['id_serv']]['date_add'] = $t['date_add'];
            $s[$t['id_serv']]['date_end'] = $t['date_end'];
            $s[$t['id_serv']]['cena'] = $t['cena'];
            $s[$t['id_serv']]['gdzie'] = $t['place'];
            if($t['modul'] != null) $s[$t['id_serv']]['moduly'][$t['id']] = $t['modul'];
            $s[$t['id_serv']]['nazwa'] = $t['name'];
            $s[$t['id_serv']]['program'] = $t['program'];
            $s[$t['id_serv']]['kategoria'] = $t['kategoria'];
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

            foreach($s as $id => $v) // jedziemy ze wszystkimi zleceniami po kolei
            {
                $ttt = false;

                if(isset($v['moduly']) && is_array($v['moduly']) && count($v['moduly']) > 0)
                {
                    foreach($v['moduly'] as $mid => $m) // sprawdzamy kazdy modul zlecenia
                    {
                        $m = mb_strtolower($m, 'UTF-8');
                        $find = mb_strpos($m, $word);

                        if($find !== false) // znaleziono w module
                        {
                            $ttt = true;
                            $ns[$id] = $s[$id];
                            break;
                        }
                    }
                }

                if($ttt) continue;
                else // nie ma w modulach trzeba sprawdzic kat obsz i tem; nazwe i program tez
                {

                    $hay = mb_strtolower($this->mc->getNamesForCatEtcFromDB($v['kategoria']), 'UTF-8');
                    $find = mb_strpos($hay, $word);

                    if($find !== false)
                    {
                        $ns[$id] = $s[$id];
                    }
                    else
                    {
                        $hay = mb_strtolower($v['nazwa'].$v['program'], 'UTF-8');
                        $find = mb_strpos($hay, $word);

                        if($find !== false) $ns[$id] = $s[$id];
                    }
                }
            }

            $s = $ns;
        }

        return $s;
    }

    public function addOfferForCommToDB(ServControl $sc)
    {
        $this->connectDB();

        // oferta -> DB
        $sql = $sc->getAddQueryForOffer();

        $this->db->query($sql);

        // czy sa jakies oferty
        $sql = $sc->getCheckQueryIfAreOffers();
        $res = $this->db->query($sql);
        $r = $res->fetch_assoc();

        // nie ma ofert = zmiana waznosci zlecenia
        if($r['oferty'] < 1)
        {
            $sql = $sc->getUpdateQueryForCommAfterOffer();
            $this->db->query($sql);
        }
    }

    public function getAllServsFromDB(ServControl $sc)
    {
        $this->connectDB();

        $sql = $sc->getShowQueryForAllServs();

        $res = $this->db->query($sql);
        $s = array();

        while($t = $res->fetch_assoc())
        {
            $s[$t['id_serv']]['date_add'] = $t['date_add'];
            $s[$t['id_serv']]['date_end'] = $t['date_end'];
            $s[$t['id_serv']]['cena'] = $t['cena'];
            $s[$t['id_serv']]['gdzie'] = $t['place'];
            $s[$t['id_serv']]['moduly'][$t['id']] = $t['modul'];
            $s[$t['id_serv']]['nazwa'] = $t['name'];
            $s[$t['id_serv']]['program'] = $t['program'];
        }

        return $s;
    }

    public function getServFromDB(ServControl $sc, $id)
    {
        $this->connectDB();

        $sql = $sc->getShowQueryForOneServ($id);

        $res = $this->db->query($sql);
        
        if($this->db->affected_rows == 1)
        {
            $s = array();
            $mc = new MainControl();
            $res = $res->fetch_assoc();

            $s['id'] = $res['id_serv'];
            $s['nazwa'] = $res['name'];
            $s['miejsce'] = $res['place'];
            $s['cena'] = $res['cena'];
            $s['cena_'] = $res['cena_'];
            $s['kontakt'] = $res['contact'].':<br/>'.$res['mail'].'<br/>'.$res['phone'];

            if(!empty($res['desc'])) $s['info'] = $res['desc'];
            if(!empty($res['woj'])) $s['woj'] = $res['woj'];
            if(!is_null($res['date_a']) && !is_null($res['date_b']))
            {
                $s['termin']['a'] = $res['date_a'];
                $s['termin']['b'] = $res['date_b'];
            }
            if($res['moduly'] == 1) $s['moduly'] = $this->getModuls(new ServControl(), $s['id']);
            list($s['kategoria'], $s['obszar'], $s['tematyka']) = $mc->getNamesForCatEtcFromDB($res['kategoria'], true);
            $s['koniec'] = $res['date_end'];
            if(!empty($res['program'])) $s['program'] = $res['program'];

            return $s;
        }
        else return false;
    }

    public function getModuls(ServControl $sc, $id)
    {
        $this->connectDB();

        $sql = $sc->getQueryForModulsForServ($id);

        $res = $this->db->query($sql);
        $ret = array();

        while($r = $res->fetch_assoc())
        {
            $ret[] = $r['modul'];
        }

        return $ret;
    }

    public function getServID()
    {
        return $this->sid;
    }
}

?>
