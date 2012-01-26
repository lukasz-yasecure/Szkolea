<?php

class ServControl
{
    private $ss;
    private $a;
    private $conf;
    private $vw;

    // searching
    private $source;
    private $word;
    // searching koniec

    // dane do bazy
    private $uid;
    private $serv = array();
    // czy all ok?
    private $save = false;
    // dane do bazy KONIEC

    private $fieldsOffer = array('id', 'cena', 'cenax', 'rozl', 'date_a', 'date_b', 'reg');
    private $offer = array();

    private $leftmenu = false; // czy wyniki sa ustalane na podstawie menu z lewej
    private $leftmenu_id = null; // id z menu z lewej

    function __construct()
    {
        $this->ss = new SSMan();
        $this->a = new Auth();
        $this->conf = new Config();
        $this->vw = new View();
    }

    public function checkPrivileges($red = 'addserv.php')
    {
        if(!$this->a->isLogged())
        {
            $this->ss->setRedirection($red);
            header('Location: log.php');
            exit();
        }

        if(!$this->a->isActivated())
        {
            $this->ss->setMessage('aktyw');
            header('Location: index.php');
            exit();
        }

        if($this->ss->getUKind() != 'D')
        {
            $this->ss->setMessage('dostawca');
            header('Location: index.php');
            exit();
        }
    }

    public function setSource($s)
    {
        if(is_array($s)) $this->source = $s;
    }

    public function setUID($id)
    {
        $this->uid = $id;
    }

    public function getAllProgramFromSource()
    {
        if(is_null($this->source)) return false;

        if(isset($this->source['name']) && Valid::serv_name($this->source['name'])) $this->serv['name'] = $this->source['name'];
        else return false;

        if(isset($this->source['cat']) && Valid::program(0, $this->source['cat'])) $this->serv['cat'] = $this->source['cat'];
        else return false;

        if(isset($this->source['subcat']) && Valid::program(1, $this->source['subcat'])) $this->serv['subcat'] = $this->source['subcat'];
        else return false;

        if(isset($this->source['subsubcat']) && Valid::program(2, $this->source['subsubcat'])) $this->serv['subsubcat'] = $this->source['subsubcat'];
        else return false;

        if(isset($this->source['date_uzg']) && $this->source['date_uzg'] == '1')
        {
            $this->serv['date'] = 1;
        }
        else
        {
            if(isset($this->source['date_a']) && ($dateA = Valid::date2timestamp($this->source['date_a'])) !== false) $this->serv['date_a'] = $dateA;
            else return false;

            if(isset($this->source['date_b']) && ($dateB = Valid::date2timestamp($this->source['date_b'])) !== false) $this->serv['date_b'] = $dateB;
            else return false;
        }

        if(isset($this->source['place']) && Valid::city($this->source['place'])) $this->serv['place'] = $this->source['place'];
        else return false;

        if(isset($this->source['cena']) && Valid::price($this->source['cena'])) $this->serv['cena'] = $this->source['cena'];
        else return false;

        if(isset($this->source['cena_']) && is_numeric($this->source['cena_']) && ($this->source['cena_'] >= 1 || $this->source['cena_'] <=3)) $this->serv['cena_'] = $this->source['cena_'];
        else return false;

        if(isset($this->source['mail']) && Valid::email($this->source['mail'])) $this->serv['mail'] = $this->source['mail'];
        else return false;

        if(isset($this->source['phone']) && Valid::phone($this->source['phone'])) $this->serv['phone'] = $this->source['phone'];
        else return false;

        if(isset($this->source['contact']) && Valid::position($this->source['contact'])) $this->serv['contact'] = $this->source['contact'];
        else return false;

        // jedyne pola nieobowiazkowe
        if(isset($this->source['desc']) && Valid::text($this->source['desc'])) $this->serv['desc'] = $this->source['desc'];

        if(isset($this->source['woj']) && Valid::woj($this->source['woj'])) $this->serv['woj'] = $this->source['woj'];
        
        $prog = 0;

        if(isset($this->source['moduly']) && is_array($this->source['moduly']))
        {
            foreach($this->source['moduly'] as $m)
            {
                if(Valid::program(3, $m)) $this->serv['moduly'][] = $m;
                else
                {
                    $prog++;
                    break;
                }
            }
        }
        else $prog++;

        if(isset($this->source['program']) && Valid::program(4, $this->source['program']) && Valid::text($this->source['program'])) $this->serv['program'] = $this->source['program'];
        else $prog++;

        // prog = 0 - jest program i moduly
        // prog = 1 - to albo to
        // prog = 2 - nic (error)

        $this->save = true;

        return true;
    }

    public function getAddQuery()
    {
        if(!$this->save) return false;
        if(is_null($this->uid)) return false;

        $date_add = time();
        $date_end = $date_add + $this->conf->getServTime();

        $sql = "INSERT INTO `services` (
                `id_serv` ,
                `id_user` ,
                `date_add` ,
                `date_end` ,
                `name` ,
                `program` ,
                `moduly` ,
                `date_a` ,
                `date_b` ,
                `place` ,
                `woj` ,
                `cena` ,
                `cena_` ,
                `desc` ,
                `mail` ,
                `phone` ,
                `contact`,
                `kategoria`
                )
                VALUES (
                NULL,
                '".$this->uid."',
                '".$date_add."',
                '".$date_end."',
                '".$this->serv['name']."',
                '".(isset($this->serv['program']) ? $this->serv['program'] : "NULL")."' ,
                '".(isset($this->serv['moduly']) && (count($this->serv['moduly'])>0) ? '1' : '0')."' ,
                '".(isset($this->serv['date_a']) ? $this->serv['date_a'] : "NULL")."' ,
                '".(isset($this->serv['date_b']) ? $this->serv['date_b'] : "NULL")."' ,
                '".$this->serv['place']."',
                '".(isset($this->serv['woj']) ? $this->serv['woj'] : "NULL")."' ,
                '".$this->serv['cena']."',
                '".$this->serv['cena_']."',
                '".(isset($this->serv['desc']) ? $this->serv['desc'] : "NULL")."' ,
                '".$this->serv['mail']."',
                '".$this->serv['phone']."',
                '".$this->serv['contact']."',
                '".$this->serv['subsubcat']."'
                )";
        
        return str_replace("'NULL'", 'NULL', $sql);
    }

    public function getAddQueryForModuls($sid)
    {
        if(!$this->save) return false;
        if(!isset($this->serv['moduly'])) return false;
        if(!is_array($this->serv['moduly'])) return false;

        // moduly
        $sql = "INSERT INTO `serv_moduls` (`id_serv`, `id_mod`) VALUES";
        foreach($this->serv['moduly'] as $m)
        {
            $sql.= " ('".$sid."', '".$this->vw->getDBIdOfModulFromID($m)."'),";
        }
        $sql = substr($sql, 0, -1);

        return $sql;
    }

    public function getWord()
    {
        if(is_null($this->word)) return false;
        else return $this->word;
    }

    public function getSearchQueryFromSource()
    {
        /*
         * budujemy zapytanie do wyszukiwania
         * najpierw kategorie itp
         * pozniej pozostale pola formularza
         * zwracamy zapytanie obciete o ostatnie 4 znaki (spacja i AND)
         */

        $a = $this->source;

        if(isset($a['word']) && $a['word'] != '') $this->word = $a['word'];

        $sql = 'SELECT *
            FROM `services` S
            LEFT JOIN (SELECT id_serv id_serv3, id_mod FROM `serv_moduls`) SM ON S.id_serv=SM.id_serv3
            LEFT JOIN `moduls_569` M ON SM.id_mod=M.id_modul
            WHERE `date_end`>\''.time()."' AND";

        $mod = '';

        /*
         * kategoria ma postac 1
         * obszar 1_1
         * tematyka 1_1_1
         * wiec jesli jest podana tematyka (subsubcat) to bierzemy ja
         * jesli jest obszar to obszar itd.
         */

        if(!$this->leftmenu)
        {
            if(isset($a['subsubcat']) && $a['subsubcat'] != '' && $a['subsubcat'] != '0')
            {
                $mod = $a['subsubcat'];
            }
            else if(isset($a['subcat']) && $a['subcat'] != '' && $a['subcat'] != '0')
            {
                $mod = $a['subcat'];
            }
            else if(isset($a['cat']) && $a['cat'] != '' && $a['cat'] != '0')
            {
                $mod = $a['cat'];
            }

            if($mod != '') $sql.= ' kategoria LIKE \''.$mod.'_%\' AND';

            // miejsce
            if(isset($a['place']) && $a['place'] != '') $sql.= ' place LIKE \'%'.$a['place'].'%\' AND';

            // woje
            if(isset($a['woj']) && $a['woj'] != '0') $sql.= ' woj=\''.$a['woj'].'\' AND';

            // minimalna cena
            if(isset($a['cena_min']) && $a['cena_min'] != '') $sql.= ' cena >= \''.$a['cena_min'].'\' AND';

            // maksymalna cena
            if(isset($a['cena_max']) && $a['cena_max'] != '') $sql.= ' cena <= \''.$a['cena_max'].'\' AND';

            /*
             * przedzial czasowy
             * w wyszukiwarce okreslamy przedzial OD kiedy DO kiedy szukamy szkolen
             * termin uslugi musi sie miescic w podanym przedziale
             */
            if(isset($a['date_a']) && ($dateA = Valid::date2timestamp($a['date_a'])) !== false && isset($a['date_b']) && ($dateB = Valid::date2timestamp($a['date_b'])) !== false)
            {
                $sql.= ' (date_a >= \''.$dateA.'\' AND date_b <= \''.$dateB.'\') AND';
            }
            else if(isset($a['date_a']) && ($dateA = Valid::date2timestamp($a['date_a'])) !== false && (!isset($a['date_b']) || ($dateB = Valid::date2timestamp($a['date_b'])) == false))
            {
                $sql.= ' date_a >= \''.$dateA.'\' AND';
            }
            else if(isset($a['date_b']) && ($dateB = Valid::date2timestamp($a['date_b'])) !== false && (!isset($a['date_a']) || ($dateA = Valid::date2timestamp($a['date_a'])) == false))
            {
                $sql.= ' date_b <= \''.$dateB.'\' AND';
            }
        }
        else
        {
            $sql.= ' kategoria LIKE \''.$this->leftmenu_id.'%\' AND';
        }

        return substr($sql, 0, -4).' ORDER BY `date_end` ASC';
    }

    public function getAllOfferFromSource($src)
    {
        if(!$this->checkSource($src)) return false;

        if(Valid::price($src['id'])) $this->offer['id'] = $src['id'];
        else return false;

        if(Valid::price($src['cena'])) $this->offer['cena'] = $src['cena'];
        else return false;

        if($src['cenax'] < 1 || $src['cenax'] > 3) return false;
        else $this->offer['cenax'] = $src['cenax'];

        if($src['rozl'] < 1 || $src['rozl'] > 3) return false;
        else $this->offer['rozl'] = $src['rozl'];

        if(isset($src['inne']) && is_array($src['inne']))
        {
            $inne = implode(';', $src['inne']);
            $this->offer['inne'] = $inne;

            if($inne[strlen($inne)-1] == '3')
            {
                if(isset($src['ile_kaw']) && Valid::price($src['ile_kaw'])) $this->offer['ile_kaw'] = $src['ile_kaw'];
                else return false;
            }
        }

        if(($dateA = Valid::date2timestamp($src['date_a'])) !== false) $this->offer['date_a'] = $dateA;
        else return false;

        if(($dateB = Valid::date2timestamp($src['date_b'])) !== false) $this->offer['date_b'] = $dateB;
        else return false;

        return true;
    }

    public function getAddQueryForOffer()
    {
        $sql = "INSERT INTO `commisions_ofe` (
                    `id_ofe` ,
                    `id_comm` ,
                    `id_user` ,
                    `date_add` ,
                    `cena` ,
                    `cenax` ,
                    `rozl` ,
                    `inne` ,
                    `ile_kaw` ,
                    `date_a` ,
                    `date_b`
                )
                VALUES (
                    NULL , 
                    '".$this->offer['id']."',
                    '".$this->uid."',
                    '".time()."',
                    '".$this->offer['cena']."',
                    '".$this->offer['cenax']."',
                    '".$this->offer['rozl']."',
                    '".(isset($this->offer['inne']) ? $this->offer['inne'] : "NULL")."',
                    '".(isset($this->offer['ile_kaw']) ? $this->offer['ile_kaw'] : "NULL")."',
                    '".$this->offer['date_a']."',
                    '".$this->offer['date_b']."'
                )";

        return str_replace("'NULL'", 'NULL', $sql);
    }

    private function checkSource($source)
    {
        if(!is_array($source)) return false;
        if(count($source) < 1) return false;;

        foreach($this->fieldsOffer as $f)
            if(!isset($source[$f]) || empty($source[$f])) return false;

        return true;
    }

    public function getUpdateQueryForCommAfterOffer()
    {
        $newdate = 48*60*60 + time();
        $sql = "UPDATE `commisions` SET `date_end` = '".$newdate."' WHERE `commisions`.`id_comm` = '".$this->offer['id']."'";

        return $sql;
    }

    public function getCheckQueryIfAreOffers()
    {
        $sql = "SELECT count(*) oferty
                FROM `commisions_ofe`
                WHERE id_comm = '".$this->offer['id']."'";

        return $sql;
    }

    public function getShowQueryForAllServs()
    {
        $sql = "SELECT S.id_serv, date_add, date_end, cena, place, modul, id, name, program
                FROM `services` S
                LEFT JOIN `serv_moduls` SM ON S.id_serv = SM.id_serv
                LEFT JOIN `moduls_569` M ON SM.id_mod = M.id_modul
                WHERE `date_end` > ".time()."
                ORDER BY `date_end` ASC";

        return $sql;
    }

    public function getShowQueryForOneServ($id)
    {
        $sql = "SELECT * FROM `services` WHERE `id_serv`=".$id;

        return $sql;
    }

    public function getQueryForModulsForServ($id)
    {
        $sql = "SELECT * FROM `serv_moduls` SM LEFT JOIN `moduls_569` M ON SM.id_mod=M.id_modul WHERE `id_serv`=".$id;

        return $sql;
    }

    public function setShowingByLeftMenu($lmid)
    {
        $this->leftmenu = true;
        $this->leftmenu_id = $lmid;
    }
}

?>
