<?php

class IndexView
{
    public function getLeftMenu()
    {
        $content = file_get_contents('view/html/index_left_menu.html');

        $commsStyle = '';
        $servsStyle = '';
        $what = 'comms';
        $id = '';

        if(isset($_GET['comms']) && !isset($_GET['servs']))
        {
            $commsStyle = 'active';
            $servsStyle = 'disactive';
            $id = $_GET['comms'];
        }
        else if(!isset($_GET['comms']) && isset($_GET['servs']))
        {
            $commsStyle = 'disactive';
            $servsStyle = 'active';
            $id = $_GET['servs'];
            $what = 'servs';
        }
        else
        {
            $commsStyle = 'active';
            $servsStyle = 'disactive';
        }

        $s = array(
            '{%commsStyle%}',
            '{%servsStyle%}'
        );

        $r = array(
            $commsStyle,
            $servsStyle
        );

        $content = str_replace($s, $r, $content);

        /*
         * sprawdzamy id w URL tzn. ile poziomow trzeba czytac w menu
         * comms - lista kategorii
         * comms=1 - rozwinieta kategoria 1
         * comms=1_2 - rozwinieta kategoria 1 i obszar 1_2
         * comms=1_2_3 - rozwienita kategoria 1, obszar 1_2, zaznaczona tematyka 1_2_3
         */
        
        $tid = explode('_', $id);
        $ctid = count($tid);

        if($ctid >= 1 && $ctid <= 3 && Valid::program($ctid-1, $id))
        {
            if($ctid == 1) $content = $this->getListOfCatsAndSubcats($content, $what, $id);
            if($ctid == 2) $content = $this->getListOfCatsAndSubcatsAndSubsubcats($content, $what, $id, $tid[0]);
            if($ctid == 3) $content = $this->getListOfCatsAndSubcatsAndSubsubcats($content, $what, $tid[0].'_'.$tid[1], $tid[0], $id);
        }
        else
        {
            // zle lub brak id - wczytujemy kategorie normalnie
            $id = '';
            $content = $this->getListOfCats($content, $what, $id);
        }

        return $content;
    }

    public function getListOfCatsAndSubcatsAndSubsubcats($c, $w, $id, $cid, $tid = null)
    {
        /*
         * zmienne szablonu:
         * {%what%} kategoria obszar tematyka
         * {%act} active disactive
         * {%name%}
         *
         * $c - szablon
         * $w - comms / servs
         * $id - ID w postaci X_X
         * $cid - ID kategorii
         * $tid - caly ID X_X_X
         */

        if(preg_match('#\{%%(.*)%%\}#s', $c, $m))
        {
            require_once('clsadm/class.admin.php');
            $adm = new Admin();
            $cats = $adm->getAllCatsFromDB();
            $scats = $adm->getAllSubcatsFromDB($cid);
            $sscats = $adm->getAllSubsubcatsFromDB($id);

            $newcon = '';

            /*
             *
             * teraz muszę wyświetlić 1 kategorie (odpowiednia) - z petli chyba
             * i wszystkie jej obszary (1 podswietlic)
             *
             */

            foreach($cats as $cat)
            {
                if($cat['id_cat'] == $cid)
                {
                    $t = str_replace('{%what%}', 'kategoria', $m[1]);
                    $t = str_replace('{%act%}', 'active', $t);
                    $t = str_replace('{%name%}', $cat['cat'], $t);
                    $t = str_replace('{%url%}', $w.'='.$cat['id_cat'], $t);
                    $newcon.= $t;
                }
            }
            
            /*
             * obszarrrrry !
             */
            foreach($scats as $scat)
            {
                if($scat['id'] == $id)
                {
                    $t = str_replace('{%what%}', 'obszar', $m[1]);
                    $t = str_replace('{%act%}', 'active', $t);
                    $t = str_replace('{%name%}', $scat['subcat'], $t);
                    $t = str_replace('{%url%}', $w.'='.$scat['id'], $t);
                    $newcon.= $t;
                }
            }

            /*
             * tematykkkki !
             */
            foreach($sscats as $sscat)
            {
                $t = str_replace('{%what%}', 'tematyka', $m[1]);

                if($tid == $sscat['id']) $t = str_replace('{%act%}', 'active', $t);
                else $t = str_replace('{%act%}', 'disactive', $t);

                $t = str_replace('{%name%}', $sscat['subsubcat'], $t);
                $t = str_replace('{%url%}', $w.'='.$sscat['id'], $t);
                $newcon.= $t;
            }

            $c = str_replace($m[0], $newcon, $c);
        }

        return $c;
    }

    public function getListOfCatsAndSubcats($c, $w, $id)
    {
        /*
         * zmienne szablonu:
         * {%what%} kategoria obszar tematyka
         * {%act} active disactive
         * {%name%}
         *
         * $c - szablon
         * $w - comms / servs
         * $id - caly ID
         */

        if(preg_match('#\{%%(.*)%%\}#s', $c, $m))
        {
            require_once('clsadm/class.admin.php');
            $adm = new Admin();
            $cats = $adm->getAllCatsFromDB();
            $scats = $adm->getAllSubcatsFromDB($id);

            $newcon = '';

            /*
             *
             * teraz muszę wyświetlić 1 kategorie (odpowiednia) - z petli chyba
             * i wszystkie jej obszary (1 podswietlic)
             *
             */

            foreach($cats as $cat)
            {
                if($cat['id_cat'] == $id)
                {
                    $t = str_replace('{%what%}', 'kategoria', $m[1]);
                    $t = str_replace('{%act%}', 'active', $t);
                    $t = str_replace('{%name%}', $cat['cat'], $t);
                    $t = str_replace('{%url%}', $w.'='.$cat['id_cat'], $t);
                    $newcon.= $t;
                }
            }

            /*
             * podkategorie!
             */
            foreach($scats as $scat)
            {
                $t = str_replace('{%what%}', 'obszar', $m[1]);

                if($id == $scat['id']) $t = str_replace('{%act%}', 'active', $t);
                else $t = str_replace('{%act%}', 'disactive', $t);

                $t = str_replace('{%name%}', $scat['subcat'], $t);
                $t = str_replace('{%url%}', $w.'='.$scat['id'], $t);
                $newcon.= $t;
            }

            $c = str_replace($m[0], $newcon, $c);
        }

        return $c;
    }

    public function getListOfCats($c, $w, $id)
    {
        /*
         * zmienne szablonu:
         * {%what%} kategoria obszar tematyka
         * {%act} active disactive
         * {%name%}
         *
         * $c - szablon
         * $w - comms / servs
         * $id - 1 lub 1_1 lub 1_1_1
         */

        if(preg_match('#\{%%(.*)%%\}#s', $c, $m))
        {
            require_once('clsadm/class.admin.php');
            $adm = new Admin();
            $cats = $adm->getAllCatsFromDB();
            
            $newcon = '';

            foreach($cats as $cat)
            {
                $t = str_replace('{%what%}', 'kategoria', $m[1]);

                if($id == $cat['id_cat']) $t = str_replace('{%act%}', 'active', $t);
                else $t = str_replace('{%act%}', 'disactive', $t);

                $t = str_replace('{%name%}', $cat['cat'], $t);
                $t = str_replace('{%url%}', $w.'='.$cat['id_cat'], $t);
                $newcon.= $t;
            }

            $c = str_replace($m[0], $newcon, $c);
        }

        return $c;
    }

    public function getResults()
    {
        $c = array();
        $s = array();
        $com = new Commisions();
        $ser = new Services();
        $mc = new MainControl();

        if(!isset($_GET['search']))
        {
            if(isset($_GET['comms']) && !isset($_GET['servs']) && empty($_GET['comms']))
            {
                $c = $com->getAllCommsFromDB(new CommControl());
            }
            else if(!isset($_GET['comms']) && isset($_GET['servs']) && empty($_GET['servs']))
            {
                $s = $ser->getAllServsFromDB(new ServControl());
            }
            else if(isset($_GET['comms']) && !isset($_GET['servs']) && !empty($_GET['comms']))
            {
                $cc = new CommControl();
                $cc->setSource($_GET);
                $cc->setShowingByLeftMenu($_GET['comms']);
                $c = $com->getSelectedCommsFromDB($cc);
            }
            else if(!isset($_GET['comms']) && isset($_GET['servs']) && !empty($_GET['servs']))
            {
                $sc = new ServControl();
                $sc->setSource($_GET);
                $sc->setShowingByLeftMenu($_GET['servs']);
                $s = $ser->getSelectedServsFromDB($sc);
            }
            else
            {
                $c = $com->getAllCommsFromDB(new CommControl());
            }
        }
        else
        {
            /*
             * SEARCH
             */
            if(isset($_GET['what']) && $_GET['what'] == 'z')
            {
                $cc = new CommControl();
                $cc->setSource($_GET);
                $c = $com->getSelectedCommsFromDB($cc);
            }
            else if(isset($_GET['what']) && $_GET['what'] == 'u')
            {
                $sc = new ServControl();
                $sc->setSource($_GET);
                $s = $ser->getSelectedServsFromDB($sc);
            }
            else
            {
                //$ss->setMessage('error');
                header('Location: index.php');
                exit();
            }
        }

        if(count($c) > 0) // COMMISIONS
        {
            $i = 0;
            $file = file_get_contents('view/html/index_comms_table.html');
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
                    '{%moduly%}'
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
                    $moduly
                );

                $new_content.= str_replace($s, $r, $content);
            }

            $all_content = str_replace($m[0], $new_content, $file);
            return $all_content;
        }
        else if(count($s) > 0) // SERVICES
        {
            $i = 0;
            $file = file_get_contents('view/html/index_servs_table.html');
            $content = '';
            $new_content = '';

            if(preg_match('#\{(.*)%wynik%\}#s', $file, $m))
            {
                $content = $m[1];
            }

            foreach($s as $id => $d)
            {
                if(isset($d['moduly'])) $moduly = implode(', ', $d['moduly']);
                else $moduly = '';

                $i++;

                $s = array(
                    '{%bgcolor1%}',
                    '{%bgcolor2%}',
                    '{%ikona%}',
                    '{%kategoria%}',
                    '{%id%}',
                    '{%nazwa%}',
                    '{%gdzie%}',
                    '{%cena%}',
                    '{%pozostalo%}',
                    '{%moduly%}',
                    '{%program%}'
                );
                $r = array(
                    (($i)%2 == 1) ? '#f9f9f9' : '#eeeeee',
                    (($i)%2 == 1) ? '#ececec' : '#f9f9f9',
                    'img/icons/free-for-job.png',
                    'biznes',
                    $id,
                    $d['nazwa'],
                    $d['gdzie'],
                    $d['cena'],
                    $mc->getDoKonca($d['date_end']),
                    $moduly,
                    $d['program']
                );

                $new_content.= str_replace($s, $r, $content);
            }

            $all_content = str_replace($m[0], $new_content, $file);
            return $all_content;
        }
    }
}

?>