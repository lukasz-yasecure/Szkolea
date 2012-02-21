<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if (file_exists($sysFile) && file_exists($logFile)) {
    require_once($sysFile);
    require_once($logFile);
} else {
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir . date('Ymd') . '_system_exc.log', date('Y-m-d H:i:s') . ' SYSTEM/LOG NIEDOSTEPNY!' . PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/* * *********************[ action = profile ]****************************************************************************
 *
 * 2011-11-29
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('profile', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $p = $pm->checkPrivileges($u);
    $tm = new TemplateManager();
    $dbc = new DBC($sys);

    $r = '';
    $cm = new CategoryManager();

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'd_kasuj' || $_POST['action'] == 'z_kasuj' || $_POST['action'] == 'zl_kasuj') {
            if (isset($_POST['id']) && Valid::id($_POST['id'])) {
                $id = $_POST['id'];
                $sql = '';
                if ($_POST['action'] == 'd_kasuj')
                    $sql = 'DELETE FROM `observe_servs_kot` WHERE `id_user` = ' . $u->getId_user() . ' AND `observe_servs_kot`.`id_obs` = \'' . $id . '\'';
                else if ($_POST['action'] == 'z_kasuj')
                    $sql = 'DELETE FROM `observe_comms_kot` WHERE `id_user` = ' . $u->getId_user() . ' AND `observe_comms_kot`.`id_obs` = \'' . $id . '\'';
                else if ($_POST['action'] == 'zl_kasuj')
                    $sql = 'DELETE FROM `observe_comms` WHERE `id_user` = ' . $u->getId_user() . ' AND `id_obs` = \'' . $id . '\'';
                $dbc->query($sql);
                BFEC::addm('Usunięto z obserwowanych!', SessionManager::getBackURL_Static());
            }
        }
    }

    $dodatkowe_js = ''; // dla admina dochodza dodatkowe JS wiec wprowadzilem taka zmienna zeby mozna bylo dolaczyc tylko gdy sa potrzebne te pliki JS

    if ($u->isKlient()) {
        if (isset($_GET['w'])) {
            if ($_GET['w'] == 'servs') {
                /*
                 * KLIENT - OBSERWOWANE KATEGORIE USLUG
                 */
                $t = new Template('view/html/profile_k_u_obs_k.html');
                $sql = 'SELECT * FROM observe_servs_kot WHERE id_user=\'' . $u->getId_user() . '\'';
                $res = $dbc->query($sql);
                while ($x = $res->fetch_assoc()) {
                    $a = $cm->getNamesOf($dbc, $x['id_obs']);
                    $c1 = $c2 = $c3 = '';
                    ${'c' . $a[3]} = ' class="btable"';
                    $r.= '<tr><td' . $c1 . '>' . $a[0] . '</td><td' . $c2 . '>' . $a[1] . '</td><td' . $c3 . '>' . $a[2] . '</td><td><button id="kasuj" name="id" value="' . $x['id_obs'] . '">Kasuj</button></td></tr>';
                }
            } else if ($_GET['w'] == 'comms') {
                if (isset($_GET['a']) && $_GET['a'] == 1) {
                    /*
                     * KLIENT - OBSERWOWANE ZLECENIA
                     */
                    $t = new Template('view/html/profile_k_zl_obs_zl.html');
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `observe_comms` OC LEFT JOIN commisions C ON OC.id_obs=C.id_comm WHERE OC.id_user=' . $u->getId_user()); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt); // szablon wynikow
                    $r = $rt->getContent();
                } else if (isset($_GET['a']) && $_GET['a'] == 2) {
                    /*
                     * KLIENT - MOJE
                     */
                    $t = new Template('view/html/profile_k_zl_moje.html');
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `commisions` WHERE id_user=' . $u->getId_user()); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r, 'moje'); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt, 'moje'); // szablon wynikow
                    $r = $rt->getContent();
                } else if (isset($_GET['a']) && $_GET['a'] == 3) {
                    /*
                     * KLIENT - BIORE UDZIAL
                     */
                    $t = new Template('view/html/profile_k_zl_udzial.html');
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `commisions_group` CG LEFT JOIN commisions C ON CG.id_comm=C.id_comm WHERE CG.id_user=' . $u->getId_user() . ' GROUP BY C.id_comm'); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r, 'biore'); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt, 'biore'); // szablon wynikow
                    $r = $rt->getContent();
                } else if (isset($_GET['a']) && $_GET['a'] == 4)
                    $t = new Template('view/html/profile_k_zl_koniec.html');
                else {
                    /*
                     * DOSTAWCA - OBSERWOWANE KATEGORIE ZLECEN
                     */
                    $t = new Template('view/html/profile_k_zl_obs_k.html');
                    $sql = 'SELECT * FROM observe_comms_kot WHERE id_user=\'' . $u->getId_user() . '\'';
                    $res = $dbc->query($sql);
                    while ($x = $res->fetch_assoc()) {
                        $a = $cm->getNamesOf($dbc, $x['id_obs']);
                        $c1 = $c2 = $c3 = '';
                        ${'c' . $a[3]} = ' class="btable"';
                        $r.= '<tr><td' . $c1 . '>' . $a[0] . '</td><td' . $c2 . '>' . $a[1] . '</td><td' . $c3 . '>' . $a[2] . '</td><td><button id="kasuj" name="id" value="' . $x['id_obs'] . '">Kasuj</button></td></tr>';
                    }
                }
            } else if ($_GET['w'] == 'offers') {
                /*
                 * KLIENT - OFERTY
                 */
                try {
                    $t = new Template(Pathes::getPathTemplateProfileOffers());
                    $res = $dbc->query(Query::getOfferForComm($_GET['id']));
                    if(isset($_GET['ofe']) AND isset($_GET['accept']))
                    {
                        if($_GET['accept'] == 'no')
                        {
                            $dbc->query(Query::getOfferAcceptNo($_GET['ofe']));
                            // wysyłamy powiadomienie
                            header('Location: profile.php?w=offers&id='.$_GET['id']);                           
                        }
                        if($_GET['accept'] == 'yes')
                        {
                            $dbc->query(Query::getOfferAcceptYes($_GET['ofe']));
                            $res = $dbc->query(Query::getOfferAcceptYesAfter($_GET['id'],$_GET['ofe']));
                            while ($x = $res->fetch_assoc()) {
                                $dbc->query(Query::getOfferAcceptNo($x['id_ofe']));
                                // wysyłamy powiadomienie
                            }
                            header('Location: profile.php?w=offers&id='.$_GET['id']);                           
                        }
                    } else {
                        while ($x = $res->fetch_assoc()) {
                            $r .= '<li>oferta #'.$x['id_ofe'].'</li>';
                            $r .= '<a href="profile.php?w=offers&id='.$_GET['id'].'&ofe='.$x['id_ofe'].'&accept=no"> rezygnacja</a>';
                            $r .= '<a href="profile.php?w=offers&id='.$_GET['id'].'&ofe='.$x['id_ofe'].'&accept=yes"> akceptacja</a>';
                        }
                    }
                } catch (ErrorsInprofileEditForm $e) { // to zmienić
                    BFEC::add('', true, 'profile.php?w=offers');
                }

            } else if ($_GET['w'] == 'dane') {
                /*
                 * KLIENT - EDYCJA DANYCH
                 */
                try {
                    if (!isset($_POST['profile_edit_form'])) {
                        $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                        $t = new Template('view/html/profile_k_dane_edycja.html'); // szablon profilu użytkownika
                        $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                        $r = $pft->getContent();
                    } else if (isset($_POST['profile_edit_form'])) {
                        $ud = new UserData();
                        $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                        $t = new Template('view/html/profile_k_dane_edycja.html'); // szablon profilu użytkownika
                        $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                        $rfd = $ud->getProfileEditFormData(); // pobieramy dane z klasy ProfileEditForm
                        $um->updateProfileData($dbc, $rfd, $u); // edycja danych w bazie
                        header('Location:' . $_SERVER['REQUEST_URI']); // przeładowanie strony, kasujemy stary $_POST
                        $r = $pft->getContent();
                    }
                } catch (ErrorsInprofileEditForm $e) {
                    BFEC::add('', true, 'profile.php?w=dane');
                }
            }
            else
                $t = new Template(Pathes::getPathTemplateProfileK());
        }
        else
            $t = new Template(Pathes::getPathTemplateProfileK());
    }
    else if ($u->isDostawca()) {
        if (isset($_GET['w'])) {
            if ($_GET['w'] == 'servs') {
                if (isset($_GET['a']) && $_GET['a'] == 1)
                    $t = new Template('view/html/profile_u_u_obs_moje.html');
                else {
                    /*
                     * DOSTAWCA - OBSERWOWANE KATEGORIE USLUG
                     */
                    $t = new Template('view/html/profile_u_u_obs_k.html');
                    $sql = 'SELECT * FROM observe_servs_kot WHERE id_user=\'' . $u->getId_user() . '\'';
                    $res = $dbc->query($sql);
                    while ($x = $res->fetch_assoc()) {
                        $a = $cm->getNamesOf($dbc, $x['id_obs']);
                        $c1 = $c2 = $c3 = '';
                        ${'c' . $a[3]} = ' class="btable"';
                        $r.= '<tr><td' . $c1 . '>' . $a[0] . '</td><td' . $c2 . '>' . $a[1] . '</td><td' . $c3 . '>' . $a[2] . '</td><td><button id="kasuj" name="id" value="' . $x['id_obs'] . '">Kasuj</button></td></tr>';
                    }
                }
            } else if ($_GET['w'] == 'comms') {
                if (isset($_GET['a']) && $_GET['a'] == 1) {
                    /*
                     * DOSTAWCA - OBSERWOWANE ZLECENIA
                     */
                    $t = new Template('view/html/profile_u_zl_obs_zl.html');
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `observe_comms` OC LEFT JOIN commisions C ON OC.id_obs=C.id_comm WHERE OC.id_user=' . $u->getId_user()); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt); // szablon wynikow
                    $r = $rt->getContent();
                } else if (isset($_GET['a']) && $_GET['a'] == 2) {
                    /*
                     * DOSTAWCA - OFERTY
                     */
                    $t = new Template('view/html/profile_u_zl_oferty.html');
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `commisions_ofe` CO LEFT JOIN commisions C ON CO.id_comm=C.id_comm WHERE CO.id_user=' . $u->getId_user()); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r, 'offer'); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt, 'offer'); // szablon wynikow
                    $r = $rt->getContent();
                } else {
                    /*
                     * DOSTAWCA - OBSERWOWANE KATEGORIE ZLECEN
                     */
                    $t = new Template('view/html/profile_u_zl_obs_k.html');
                    $sql = 'SELECT * FROM observe_comms_kot WHERE id_user=\'' . $u->getId_user() . '\'';
                    $res = $dbc->query($sql);
                    while ($x = $res->fetch_assoc()) {
                        $a = $cm->getNamesOf($dbc, $x['id_obs']);
                        $c1 = $c2 = $c3 = '';
                        ${'c' . $a[3]} = ' class="btable"';
                        $r.= '<tr><td' . $c1 . '>' . $a[0] . '</td><td' . $c2 . '>' . $a[1] . '</td><td' . $c3 . '>' . $a[2] . '</td><td><button id="kasuj" name="id" value="' . $x['id_obs'] . '">Kasuj</button></td></tr>';
                    }
                }
            } else if ($_GET['w'] == 'dane') {
                if (isset($_GET['a'])) {
                    if ($_GET['a'] == 0)
                        $t = new Template('view/html/profile_u_dane_wiz.html');

                    else if ($_GET['a'] == 1) {
                        /*
                         * DOSTAWCA - EDYCJA DANYCH
                         */
                        try {
                            if (!isset($_POST['profile_edit_form'])) {
                                $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                                $t = new Template('view/html/profile_u_dane_edycja.html'); // szablon profilu użytkownika
                                $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                                $r = $pft->getContent();
                            } else if (isset($_POST['profile_edit_form'])) {
                                $ud = new UserData();
                                $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                                $t = new Template('view/html/profile_u_dane_edycja.html'); // szablon profilu użytkownika
                                $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                                $rfd = $ud->getProfileEditFormData(); // pobieramy dane z klasy ProfileEditForm
                                $um->updateProfileData($dbc, $rfd, $u); // edycja danych w bazie
                                header('Location:' . $_SERVER['REQUEST_URI']); // przeładowanie strony, kasujemy stary $_POST
                                $r = $pft->getContent();
                            }
                        } catch (ErrorsInprofileEditForm $e) {
                            BFEC::add('', true, 'profile.php?w=dane&a=1');
                        }
                    } else if ($_GET['a'] == 2)
                        $t = new Template('view/html/profile_u_dane_oceny.html');
                    else
                        $t = new Template('view/html/profile_u_dane_wiz.html');
                }
                else
                    $t = new Template('view/html/profile_u_dane_wiz.html');
            }
            else if ($_GET['w'] == 'pakiety') {
                if (isset($_GET['a'])) {
                    if ($_GET['a'] == 0)
                        $t = new Template('view/html/profile_u_pakiety_akt.html');
                    else if ($_GET['a'] == 1)
                        $t = new Template('view/html/profile_u_pakiety_kup.html');
                    else
                        $t = new Template('view/html/profile_u_pakiety_akt.html');
                }
                else
                    $t = new Template('view/html/profile_u_pakiety_akt.html');
            }
            else if ($_GET['w'] == 'faktury') {
                if (isset($_GET['a'])) {
                    if ($_GET['a'] == 0)
                        $t = new Template('view/html/profile_u_faktury_op.html');
                    else if ($_GET['a'] == 1)
                        $t = new Template('view/html/profile_u_faktury_dop.html');
                    else
                        $t = new Template('view/html/profile_u_faktury_op.html');
                }
                else
                    $t = new Template('view/html/profile_u_faktury_op.html');
            }
            else
                $t = new Template(Pathes::getPathTemplateProfileU());
        }
        else
            $t = new Template(Pathes::getPathTemplateProfileU());
    }
    else if ($u->isAdmin()) {
        
        
        if ((isset($_GET['w']) && $_GET['w'] == 'comms' && !isset($_GET['a'])) || (isset($_GET['w']) && $_GET['w'] == 'comms' && isset($_GET['a']) && $_GET['a'] == '0')) {
        
            $t = new Template('view/html/admin_comms.html');

            //wyświetlenie listy zleceń dla admina
            $r = $tm->getCommsListForAdmin(new DBC($sys));
            
        } else if (isset($_GET['w']) && $_GET['w'] == 'kategorie' && ((isset($_GET['a']) && $_GET['a'] == '0') || !isset($_GET['a']))) {
            $t = new Template('view/html/admin_kategorie_edycja.html');

            $cm = new CategoryManager();
            $c = $cm->getCategories($dbc, null);
            $o = '<option value="id">cat</option>';
            $ret = '';

            while ($cc = $c->getK()) {
                $ret.= str_replace(array('id', 'cat'), array($cc['1'], $cc['0']), $o);
            }

            $t->addSearchReplace('cats', $ret);
            $t->addSearchReplace('subcats', '');
            $t->addSearchReplace('subsubcats', '');
            $t->addSearchReplace('moduls', '');

            $dodatkowe_js = file_get_contents('temp/admin.html');
        } else if (isset($_GET['w']) && $_GET['w'] == 'uzytkownicy' && ((isset($_GET['a']) && $_GET['a'] == '0') || !isset($_GET['a']))) {
            $t = new Template('view/html/admin_uzytkownicy_lista.html');

            $sql = "SELECT * FROM `users_324`";
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
            //throw new EmptyList();
                $r = '';
            else {
                $user = file_get_contents('view/html/admin_uzytkownicy_lista_1_user.html');

                while ($row = $result->fetch_assoc()) {
                    $r.= str_replace(
                            array('{%id_user%}', '{%email%}', '{%kind%}', '{%os_name%}', '{%os_surname%}', '{%os_city%}', '{%f_name%}', '{%f_surname%}', '{%f_company%}'), array($row['id_user'], $row['email'], $row['kind'], $row['os_name'], $row['os_surname'], $row['os_city'], $row['f_name'], $row['f_surname'], $row['f_company']), $user);
                }
            }
        } else {
            $t = new Template('view/html/admin_profile_main.html');
            $r = '';
        }
    }

    $t->addSearchReplace('here', $r);
    $t->addSearchReplace('name', $u->getEmail());
    $t->addSearchReplace('action_url', $_SERVER['REQUEST_URI']); // dodaje action w formie edycji danych profilu
    $mt = $tm->getMainTemplate($sys, $t->getContent(), BFEC::showAll(), file_get_contents('temp/profile.html') . $dodatkowe_js);
    echo $mt->getContent();
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>
