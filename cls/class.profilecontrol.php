<?php

class ProfileControl
{
    private $ss;
    private $a;

    public function  __construct()
    {
        $this->ss = new SSMan();
        $this->a = new Auth();
    }

    public function checkPrivileges()
    {
        if(!$this->a->isLogged())
        {
            $this->ss->setRedirection('profile.php');
            header('Location: log.php');
            exit();
        }

        if(!$this->a->isActivated())
        {
            $this->ss->setMessage('aktyw');
            header('Location: index.php');
            exit();
        }
    }

    public function makeMenuGBForK($c, $u)
    {
        /*
         * tworzy gorna belke menu w profilu
         * ta belka wyglada tak ze sa wszystkie opcje i niedostepne trzeba ukryc a nieaktywnym i aktywnej dac odpowiednie style
         * ustawia style
         * mozliwe opcje
         * B - aktyw
         * W - nieaktyw
         *  hider (tak, ze spacja przed) - ukryte
         */
        $s = array(
            '{%gb1%}',
            '{%gb2%}',
            '{%gb3%}',
            '{%gb4%}'
        );
        
        $r = array('W', 'W', 'W', 'W hider');

        if(isset($u['w']) && !empty($u['w']))
        {
            $vv = array('servs', 'comms', 'dane', ''); // valid values

            $index = array_search($u['w'], $vv); // index z tablicy VV lub null

            if($index !== false)
            {
                $r[$index] = 'B';
            }
        }

        return str_replace($s, $r, $c);
    }

    public function makeMenuDBForK($c, $u)
    {
        /*
         * tworzy dolna belke menu w profilu
         * ta belka wyglada tak ze sa wszystkie opcje i niedostepne trzeba ukryc a nieaktywnym i aktywnej dac odpowiednie style
         * ustawia style
         * mozliwe opcje
         *  (puste) - aktyw
         * b - nieaktyw
         *  hider (tak, ze spacja przed) - ukryte
         */

        $s = array(
            '{%db0%}',
            '{%db1%}',
            '{%db2%}',
            '{%db3%}',
            '{%db4%}',
            '{%db5%}',
            '{%db6%}',
            '{%db7%}',
            '{%db8%}',
            '{%what%}',
            '{%here%}'
        );

        $r = array('b hider', 'b hider', 'b hider', 'b hider', 'b hider', 'b hider', 'b hider', 'b hider', 'b hider', '', '');

        if(isset($u['w']) && !empty($u['w']))
        {
            if($u['w'] == 'servs')
            {
                $r[9] = 'servs';
                $r[0] = '';
                $r[10] = $this->getUslugiObserwowane();
            }
            else if($u['w'] == 'comms')
            {
                if(!isset($u['a'])) $u['a'] = 0;
                
                $r[9] = 'comms';
                $vv = array('0', '1', '3', '4');
                $ok = false;

                foreach($vv as $v)
                {
                    if($v == $u['a'])
                    {
                        $r[$v] = '';
                        $ok = $v;
                    }
                    else $r[$v] = 'b';
                }

                if(!$ok || $ok == 0)
                {
                    $r[0] = '';
                    $r[10] = $this->getZleceniaObserwowaneKategorie();
                }
                else if($ok == 1) $r[10] = $this->getZleceniaObserwowaneZlecenia();
                else if($ok == 3) $r[10] = $this->getZleceniaMoje();
                else if($ok == 4) $r[10] = $this->getZleceniaUdzial();

            }
            else if($u['w'] == 'dane')
            {
                $r[9] = 'dane';
                $r[6] = '';
                $r[10] = file_get_contents('view/html/profile_dane_edycja.html');
            }
        }
        else
        {
            $r[10] = file_get_contents('view/html/profile_info.html');
        }

        return str_replace($s, $r, $c);
    }

    public function getUslugiObserwowane()
    {
        return file_get_contents('view/html/profile_us_obs.html');
    }

    public function getZleceniaObserwowaneKategorie()
    {
        return file_get_contents('view/html/profile_zl_obs_kat.html');
    }

    public function getZleceniaObserwowaneZlecenia()
    {
        return file_get_contents('view/html/profile_zl_obs_zl.html');
    }

    public function getZleceniaMoje()
    {
        /*
         *
         */
        $com = new Commisions();
        $mc = new MainControl();
        $c = $com->getMyCommsFromDB($this->ss->getUID());
        $file = file_get_contents('view/html/index_comms_table2.html');

        if(count($c) > 0) // COMMISIONS
        {
            $i = 0;
            $content = '';
            $new_content = '';

            if(preg_match('#\{(.*)%wynik%\}#s', $file, $m))
            {
                $content = $m[1];
            }

            foreach($c as $id => $d)
            {
                $moduly = implode(', ', $d['moduly']);
                $i++;

                if($d['date_end'] == 1) continue;

                $sql = 'SELECT COUNT(*) AS ILE FROM `commisions_ofe` WHERE `id_comm`=\''.$id.'\'';
                $db = Valid::getDBObject();
                $res = $db->query($sql);
                pre($db->error);
                $ile_ofert = $res->fetch_assoc();
                $ile_ofert = $ile_ofert['ILE'];



                $s = array(
                    '{%bgcolor1%}',
                    '{%bgcolor2%}',
                    '{%ikona%}',
                    '{%kategoria%}',
                    '{%id%}',
                    '{%tematyka%}',
                    '{%gdzie%}',
                    '{%zapisanych%}',
                    '{%cena_min%}',
                    '{%cena_max%}',
                    '{%pozostalo%}',
                    '{%moduly%}',
                    '{%ile_ofert%}'
                );
                $r = array(
                    (($i)%2 == 1) ? '#f9f9f9' : '#eeeeee',
                    (($i)%2 == 1) ? '#ececec' : '#f9f9f9',
                    'img/icons/free-for-job.png',
                    'biznes',
                    $id,
                    $d['sscat'],
                    $d['gdzie'],
                    $d['zapis'],
                    $d['cena_min'],
                    $d['cena_max'],
                    $mc->getDoKonca($d['date_end']),
                    $moduly,
                    $ile_ofert
                );

                $new_content.= str_replace($s, $r, $content);
            }

            $all_content = str_replace($m[0], $new_content, $file);
            return $all_content;
        }

        return '';
    }

    public function getZleceniaUdzial()
    {
        return file_get_contents('view/html/profile_zl_udzial.html');
    }
}

?>