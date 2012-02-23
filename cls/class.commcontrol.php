<?php

class CommControl
{
    private $ss;
    private $a;
    private $conf;
    private $vw;
    private $db;

    // searching
    private $source;
    private $word;
    // searching koniec

    // dane do bazy
    private $uid;
    private $cmm;
    private $parts = 0; // liczba uczestnikow
    // czy all ok?
    private $save1 = false;
    private $save2 = false;
    // dane do bazy KONIEC
    
    private $cid; // uzyskane po dodaniu comm
    private $leftmenu = false; // czy wyniki sa ustalane na podstawie menu z lewej
    private $leftmenu_id = null; // id z menu z lewej

    private $error = ''; // blad przy formularzu dodawania zlecenia
    
    function __construct()
    {
        $this->ss = new SSMan();
        $this->a = new Auth();
        $this->conf = new Config();
        $this->vw = new View();
    }

    public function connectDB()
    {
        if(!is_null($this->db)) return true;

        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function checkPrivileges()
    {
        if(!$this->a->isLogged())
        {
            $this->ss->setRedirection('addcomm.php');
            header('Location: log.php');
            exit();
        }

        if(!$this->a->isActivated())
        {
            $this->ss->setMessage('aktyw');
            header('Location: index.php');
            exit();
        }

        if($this->ss->getUKind() != 'K')
        {
            $this->ss->setMessage('klient');
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

    private function checkContinuityOfPrefferedDays($d, $l)
    {
        $d = substr($d, 0, -1);

        if($l == 0) return false;
        if($l == 1) return true;

        $l2 = array('1;2', '2;3', '3;4', '4;5', '5;6', '6;7', '1;7');
        $l3 = array('1;2;3', '2;3;4', '3;4;5', '4;5;6', '5;6;7', '1;6;7', '1;2;7',);
        $l4 = array('1;2;3;4', '2;3;4;5', '3;4;5;6', '4;5;6;7', '1;5;6;7', '1;2;6;7', '1;2;3;7',);

        //pre(${'l'.$l});

        foreach(${'l'.$l} as $c)
        {
            //pre($c);

            $i = 0;

            $cc = explode(';', $c);

            foreach($cc as $t)
            {
                if(strpos($d, $t) === false) break;
                else $i++;
            }

            if($i == $l) return true;
        }

        return false;
    }

    public function ogarnij($name, $valid = true, $date = false)
    {
        /*
         * zapisuje podane przez usera dane w sesji
         *
         */

        $errors = array(
            'cat' => 'wybierz kategorię z listy',
            'subcat' => 'wybierz obszar z listy',
            'subsubcat' => 'wybierz tematykę z listy',
            'moduly' => 'wybierz moduł(y) z listy',
            'long' => 'podaj ile dni ma trwać szkolenie',
            'days' => 'wybierz z listy preferowane dni na szkolenie',
            'date_a' => 'wybierz z kalendarza preferowany termin',
            'date_b' => 'wybierz z kalendarza preferowany termin',
            'expire' => 'podaj czas ważności zlecenia',
            'place' => 'podaj miejsce odbywania się szkolenia',
            'cena_min' => 'podaj przedział cenowy',
            'cena_max' => 'podaj przedział cenowy'
        );

        if($valid)
        {
            if($date)
            {
                $this->cmm[$name] = $date;
                $this->ss->saveInputsForComm($name, $date);
            }
            else
            {
                $this->cmm[$name] = $this->source[$name];
                $this->ss->saveInputsForComm($name, $this->source[$name]);
            }
        }
        else
        {
            $this->ss->saveErrorsForComm($name, $errors[$name]);
            BFEC::add($errors[$name]);
        }
    }

    public function getAllProgramFromSource()
    {
        $valid = true;

        /*
         * sprawdzamy jakosc zrodla danych
         * czy w ogole jest zrodlo
         * czy jest pewne pole formularza i czy = 1 (do sfejkowania ofkorz)
         */
        if(is_null($this->source) || !isset($this->source['reg']) || $this->source['reg'] != '1')
        {
            $this->setError('wypełnij formularz według wskazówek');
            return false;
        }

        /*
         * czy podano poprawna kategorie
         */
        if(isset($this->source['cat']) && Valid::program(0, $this->source['cat']))
        {
            $this->ogarnij('cat');
        }
        else
        {
            $valid = false;
            $this->ogarnij('cat', false);
        }

        /*
         * czy podano poprawny obszar
         */
        if(isset($this->source['subcat']) && Valid::program(1, $this->source['subcat']))
        {
            $this->ogarnij('subcat');
        }
        else
        {
            $valid = false;
            $this->ogarnij('subcat', false);
        }

        /*
         * czy podano poprawna tematyke
         */
        if(isset($this->source['subsubcat']) && Valid::program(2, $this->source['subsubcat']))
        {
            $this->ogarnij('subsubcat');
        }
        else
        {
            $valid = false;
            $this->ogarnij('subsubcat', false);
        }

        /*
         * czy podano poprawne moduly
         * jeśli chociaż 1 modul jest niepoprawny - niezapisujemy ani jednego
         * jesli all dobrze - zapisujemy
         */
        if(isset($this->source['moduly']) && is_array($this->source['moduly']))
        {
            $temp = true; // zmienna zeby stwierdzic czy wszystkie moduly sa OK

            foreach($this->source['moduly'] as $m)
            {
                if(Valid::program(3, $m)) $this->cmm['moduly'][] = $m;
                else
                {
                    $valid = false;
                    $this->ogarnij('moduly', false);
                    $temp = false;
                    break;
                }
            }

            if($temp) $this->ogarnij('moduly');
        }
        else
        {
            $valid = false;
            $this->ogarnij('moduly', false);
        }

        /*
         * dlugosc szkolenia
         */
        if(isset($this->source['long']) && is_numeric($this->source['long']) && ($this->source['long'] >= 1 || $this->source['long'] <=4))
        {
            $this->ogarnij('long');
        }
        else
        {
            $valid = false;
            $this->cmm['long'] = 0;
            $this->ogarnij('long', false);
        }

        /*
         * preferowane dni tygodnia
         * sprawdzana jest ciaglosc i czy zgadza sie dlugosc
         */
        $days_error = false;
        if(isset($this->source['days']) && is_array($this->source['days']))
        {
            $this->cmm['days'] = '';
            
            foreach($this->source['days'] as $d)
            {
                if(is_numeric($d))
                {
                    $obojetnie = false;
                    
                    $this->cmm['days'].= $d.';';

                    if($d == 0)
                    {
                        $obojetnie = true;
                        break;
                    }
                }
                else $days_error = true;
            }
            
            if(!$obojetnie && !$this->checkContinuityOfPrefferedDays($this->cmm['days'], $this->cmm['long'])) $days_error = true;
        }
        else $days_error = true;

        if($days_error)
        {
            $valid = false;
            $this->ogarnij('days', false);
        }
        else $this->ogarnij('days');

        /*
         * preferowane terminy (dwa)
         */
        if(isset($this->source['date_a']) && ($dateA = Valid::date2timestamp($this->source['date_a'])) !== false)
        {
            $this->ogarnij('date_a', true, $dateA);
        }
        else
        {
            $valid = false;
            $this->ogarnij('date_a', false);
        }

        if(isset($this->source['date_b']) && ($dateB = Valid::date2timestamp($this->source['date_b'])) !== false)
        {
            $this->ogarnij('date_b', true, $dateA);
        }
        else
        {
            $valid = false;
            $this->ogarnij('date_b', false);
        }

        /*
         * drugi termin (opcjonalny) - jedna data zle - to wypad
         */
        if(isset($this->source['drugi_termin']))
        {
            if(isset($this->source['date_c'])
                && ($dateC = Valid::date2timestamp($this->source['date_c'])) !== false
                && isset($this->source['date_d'])
                && ($dateD = Valid::date2timestamp($this->source['date_d'])) !== false
            )
            {
                $this->cmm['date_c'] = $dateC;
                $this->cmm['date_d'] = $dateD;
            }
        }

        if(isset($this->source['expire']) && is_numeric($this->source['expire']) && $this->source['expire'] > 0)
        {
            $this->ogarnij('expire');
        }
        else
        {
            $valid = false;
            $this->ogarnij('expire', false);
        }

        if(isset($this->source['place']) && Valid::city($this->source['place']))
        {
            $this->ogarnij('place');
        }
        else
        {
            $valid = false;
            $this->ogarnij('place', false);
        }

        if(isset($this->source['woj']) && Valid::woj($this->source['woj'])) $this->ogarnij('woj');

        if(isset($this->source['cena_min']) && Valid::price($this->source['cena_min']))
        {
            $this->ogarnij('cena_min');
        }
        else
        {
            $valid = false;
            $this->ogarnij('cena_min', false);
        }

        if(isset($this->source['cena_max']) && Valid::price($this->source['cena_max']))
        {
            $this->ogarnij('cena_max');
        }
        else
        {
            $valid = false;
            $this->ogarnij('cena_max', false);
        }

        $this->cmm['parts'] = '';

        if(isset($this->source['part0']) && $this->source['part0'] == 1)
        {
            $this->cmm['owner_join'] = 1;
            $this->parts++;
            $this->cmm['parts'].= 'owner';
        }

        for($i=2; $i<=16; $i++)
        {
            if(isset($this->source['part'.$i.'_name']) && isset($this->source['part'.$i.'_surname']) && Valid::name($this->source['part'.$i.'_name']) && Valid::surname($this->source['part'.$i.'_surname']))
            {
                $this->cmm['parts'].= '; '.$this->source['part'.$i.'_name'].' '.$this->source['part'.$i.'_surname'];
                $this->parts++;
            }
        }

        if($this->parts > 15 || $this->parts == 0) $valid = false;

        if($valid)
        {
            $this->save = true;
            return true;
        }
        else
        {
            $this->save = false;
            return false;
        }
    }

    public function getAddQuery()
    {
        if(!$this->save) return false;
        if(is_null($this->uid)) return false;

        $date_add = time();
        $date_end = $date_add + $this->cmm['expire']*24*3600;

        $sql = "INSERT INTO `szkolea`.`commisions` (
            `id_comm` ,
            `id_user` ,
            `date_add` ,
            `date_end` ,
            `long` ,
            `days` ,
            `date_a` ,
            `date_b` ,
            `date_c` ,
            `date_d` ,
            `expire` ,
            `place` ,
            `woj` ,
            `cena_min` ,
            `cena_max` ,
            `parts_count` ,
            `parts`
            )
            VALUES (
                NULL ,
                '".$this->uid."',
                '".$date_add."',
                '".$date_end."',
                '".$this->cmm['long']."',
                '".substr($this->cmm['days'], 0, -1)."',
                '".$this->cmm['date_a']."',
                '".$this->cmm['date_b']."',
                '".(isset($this->cmm['date_c']) ? $this->cmm['date_c'] : "NULL")."' ,
                '".(isset($this->cmm['date_d']) ? $this->cmm['date_d'] : "NULL")."' ,
                '".$this->cmm['expire']."',
                '".$this->cmm['place']."',
                '".(isset($this->cmm['woj']) ? $this->cmm['woj'] : "NULL")."' ,
                '".$this->cmm['cena_min']."',
                '".$this->cmm['cena_max']."',
                '".$this->parts."',
                '".$this->cmm['parts']."'
            )";

        return str_replace("'NULL'", 'NULL', $sql);
    }

    public function getAddQueryForModuls($cid)
    {
        if(!$this->save) return false;
        if(!isset($this->cmm['moduly'])) return false;
        if(!is_array($this->cmm['moduly'])) return false;

        $this->cid = $cid;

        // moduly
        $sql = "INSERT INTO `comm_moduls` (`id_comm`, `id_mod`) VALUES";
        foreach($this->cmm['moduly'] as $m)
        {
            $sql.= " ('".$cid."', '".$this->vw->getDBIdOfModulFromID($m)."'),";
        }
        $sql = substr($sql, 0, -1);

        return $sql;
    }

    public function getAddQueryForParticipants($cid)
    {
        if(!$this->save) return false;
        if(is_null($this->uid)) return false;
        
        $date_add = time();

        $sql = 'INSERT INTO `commisions_group` (`id_comm`, `id_user`, `date_add`) VALUES ';

        $z = $this->parts;

        while(($z--) > 0)
        {
            $sql.= '(\''.$cid.'\', \''.$this->uid.'\', \''.$date_add.'\'), ';
        }

        return substr($sql, 0, -2);
    }

    public function getShowQueryForAllComms()
    {
        $sql = 'SELECT *
            FROM `comm_moduls` CM
            LEFT JOIN `commisions` C ON CM.id_comm=C.id_comm
            LEFT JOIN `moduls_569` M ON CM.id_mod=M.id_modul
            LEFT JOIN
                (SELECT id_comm id_comm2, COUNT(*) zapis
                FROM `commisions_group`
                GROUP BY id_comm) Z ON C.id_comm=Z.id_comm2
            WHERE `date_end`>\''.time().'\'
            ORDER BY `date_end` ASC';
        return $sql;
    }

    public function getWord()
    {
        if(is_null($this->word)) return false;
        else return $this->word;
    }

    public function setShowingByLeftMenu($lmid)
    {
        /*
         * do czego to bylo?
         */
        $this->leftmenu = true;
        $this->leftmenu_id = $lmid;
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
            FROM `comm_moduls` CM
            LEFT JOIN `commisions` C ON CM.id_comm=C.id_comm
            LEFT JOIN `moduls_569` M ON CM.id_mod=M.id_modul
            LEFT JOIN
                (SELECT id_comm id_comm2, COUNT(*) zapis
                FROM `commisions_group`
                GROUP BY id_comm) Z ON C.id_comm=Z.id_comm2
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

            if($mod != '') $sql.= ' id LIKE \''.$mod.'_%\' AND';

            // miejsce
            if(isset($a['place']) && $a['place'] != '') $sql.= ' place LIKE \'%'.$a['place'].'%\' AND';

            // woje
            if(isset($a['woj']) && $a['woj'] != '0') $sql.= ' woj=\''.$a['woj'].'\' AND';

            // minimalna cena
            if(isset($a['cena_min']) && $a['cena_min'] != '') $sql.= ' cena_min >= \''.$a['cena_min'].'\' AND';

            // maksymalna cena
            if(isset($a['cena_max']) && $a['cena_max'] != '') $sql.= ' cena_max <= \''.$a['cena_max'].'\' AND';

            /*
             * przedzial czasowy - jest porycie poniewaz zlecenie moze miec nawet 2 terminy
             * w wyszukiwarce okreslamy przedzial OD kiedy DO kiedy szukamy szkolen
             * chociaz 1 z terminow zlecenia musi sie miescic w podanym przedziale
             */
            if(isset($a['date_a']) && ($dateA = Valid::date2timestamp($a['date_a'])) !== false && isset($a['date_b']) && ($dateB = Valid::date2timestamp($a['date_b'])) !== false)
            {
                $sql.= ' (date_a >= \''.$dateA.'\' AND date_b <= \''.$dateB.'\') OR (date_c >= \''.$dateA.'\' AND date_d <= \''.$dateB.'\') AND';
            }
            else if(isset($a['date_a']) && ($dateA = Valid::date2timestamp($a['date_a'])) !== false && (!isset($a['date_b']) || ($dateB = Valid::date2timestamp($a['date_b'])) == false))
            {
                $sql.= ' date_a >= \''.$dateA.'\' OR date_c >= \''.$dateA.'\' AND';
            }
            else if(isset($a['date_b']) && ($dateB = Valid::date2timestamp($a['date_b'])) !== false && (!isset($a['date_a']) || ($dateA = Valid::date2timestamp($a['date_a'])) == false))
            {
                $sql.= ' date_b <= \''.$dateB.'\' OR date_d <= \''.$dateB.'\' AND';
            }
        }
        else
        {
            $sql.= ' id LIKE \''.$this->leftmenu_id.'_%\' AND';
        }

        return substr($sql, 0, -4).' ORDER BY `date_end` ASC';
    }
}

?>
