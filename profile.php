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


//w przypadku adresu typu profile.php?w=pakiety&action=kup_pakiet&pakiet=X dodawany jest dostawcy odpowiedni pakiet (X) od 2 do 5, w przeciwny wypadku wyjątek
    if ($u->isDostawca() && isset($_GET['action']) && $_GET['action'] == 'kup_pakiet' && isset($_GET['pakiet'])) {
        $pm = new PackageManager();
        if (Valid::isNatural($_GET['pakiet']) && $_GET['pakiet'] <= 5 && $_GET['pakiet'] > 1) {     //sprawdzenie czy liczba oraz z czy z zakresu 2-5
            $pakiet = $pm->pobierzPakiet($dbc, $_GET['pakiet']);
            $pm->dodajPakietUzytkownikowi($dbc, $u->getId_user(), $pakiet);
            BFEC::addm(MSG::profileAddPackagesSuccess(), Pathes::$script_profile_packages);   // komunikat o pomyślnym dodaniu pakietu i przekierowanie na aktywne profile
        }else
            throw new NieprawidloweIdPakietu;
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
                    $r = $rm->getResults($dbc, $s, 'SELECT c.*,o.count_offers FROM `commisions` c LEFT JOIN (SELECT `id_comm`,COUNT(*) count_offers FROM `commisions_ofe` GROUP BY `id_comm`) as o ON (o.id_comm = c.id_comm) WHERE c.id_user=' . $u->getId_user()); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
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
                $um = new UserManager();
                $m = new Mailer();
                $t = new Template(Pathes::getPathTemplateProfileOffers());
                $res = $dbc->query(Query::getOfferForComm($_GET['id'])); // pobieramy oferty wg. id zlecenia
                if (isset($_GET['ofe'])) { // wybór oferty przez klienta
                    $dbc->query(Query::getOfferAcceptYes($_GET['ofe'])); // oznacza status oferty jako 2, czyli oferta wybrana (1 - dodana, 2 - wybrana, 3 - rezygnacja)
                    // wysyłamy powiadomienie właścicielowi wybranej oferty
                    $gu = $dbc->query(Query::getOfferAccept($_GET['ofe'])); // pobierane dane wybranej oferty
                    $m->infoWybranaOfertaWlasciciel($um->getUser($dbc, $gu->fetch_object()->id_user));

                    $res = $dbc->query(Query::getOfferAcceptYesAfter($_GET['id'], $_GET['ofe'])); // pobieramy oferty zlecenia z wyjatkiem wybranej oferty
                    while ($x = $res->fetch_assoc()) {
                        $dbc->query(Query::getOfferAcceptNo($x['id_ofe'])); // oznaczamy oferty jako odrzucone
                        // wysyłamy powiadomienie właścicielowi odrzuconej oferty
                        $m->infoOdrzuconaOfertaWlasciciel($um->getUser($dbc, $x['id_user']));
                    }
                    header('Location: profile.php?w=offers&id=' . $_GET['id']);
                } elseif (isset($_GET['resign'])) {

                    while ($x = $res->fetch_assoc()) {
                        $dbc->query(Query::getOfferAcceptNo($x['id_ofe'])); // oznaczamy oferty jako odrzucone
                        // wysyłamy powiadomienie właścicielowi odrzuconej oferty
                        $m->infoOdrzuconaOfertaWlasciciel($um->getUser($dbc, $x['id_user']));
                    }
                    header('Location: profile.php?w=comms&a=2');
                } else {
                    // lista ofert
                    $resign_all = "";
                    $is_aviable = "";
                    $rc = "0"; // row count
                    while ($x = $res->fetch_assoc()) {
                        $rc++;
                        if ($rc = '1')
                            $is_aviable = $x['status']; // sprawdza status pierwszej oferty
                        $r .= '<li>oferta #' . $x['id_ofe'] . '</li>';
                        if ($x['status'] === '1')
                            $r .= '<a href="profile.php?w=offers&id=' . $_GET['id'] . '&ofe=' . $x['id_ofe'] . '"> akceptacja</a>';
                    }
                    if ($is_aviable === '1')
                        $resign_all = "<p class=\"resign_all\"><a href=\"profile.php?w=offers&id=" . $_GET['id'] . "&resign=all\">Odrzuć wszystko</a></p>";
                    $t->addSearchReplace('resign_all', $resign_all);
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

                //DOSTAWCA - EDYCJA WIZYTÓWKI
                if (isset($_GET['a'])) {
                    if ($_GET['a'] == 0) {
                        $t = new Template('view/html/profile_dostawca_wizytowka.html');
                        $t_wiz = new Template('view/html/wizytowka.html');

                        $pkgm = new PackageManager();
                        $pkgm->pobierzInformacjePakietow($dbc, $u->getId_user());

                        $t_wiz->addSearchReplace('ilosc_znakow', $pkgm->iIleZnakowWizytowka());     //podmieniamy w szablonie ilość znaków wizytówki na pobraną z bazy dla odpowiedniego użytkownika


                        if ($pkgm->czyMoznaDodacWWW()) {    // sprawdzamy czy użytkownik może dodawać www i blokujemu mu tą opcje lub nie
                            $t_wiz->addSearchReplace('www_disabled', '');
                        } else {
                            $t_wiz->addSearchReplace('www_disabled', 'disabled="disabled"');
                        }
                        if ($pkgm->czyMoznaDodacLogo()) {   // sprawdzamy czy użytkownik może dodawać logo i blokujemu mu tą opcje lub nie
                            $t_wiz->addSearchReplace('logo_disabled', '');
                        } else {
                            $t_wiz->addSearchReplace('logo_disabled', 'disabled="disabled"');
                        }

                        //pobieramy informacje o wizytowce w bazie, gdyz musimy wiedziec czy generowac nowy rekord odnosnie wizytówki czy updateować istniejący już
                        if ($pkgm->sprawdzWizytowke($dbc, $u->getId_user())) {
                            $pkgm->pobierzWizytowke($dbc, $u->getId_user());


                            //pobieramy opis z bazy, lub w przypadku jego braku ładujemy z RFD
                            if (strlen($pkgm->pobierzOpis()) > 0) {
                                $t_wiz->addSearchReplace('RFD_opis', $pkgm->pobierzOpis());
                            } else {
                                $t_wiz->addSearchReplace('RFD_opis', RFD::get('edycja_wizytowki', 'opis'));
                            }

                            //pobieramy URL z bazy, lub w przypadku jego braku ładujemy z RFD
                            if (strlen($pkgm->pobierzURL()) > 0) {
                                $t_wiz->addSearchReplace('RFD_www', $pkgm->pobierzURL());
                            } else {
                                $t_wiz->addSearchReplace('RFD_www', RFD::get('edycja_wizytowki', 'www'));
                            }
                        } else {    //gdy nie ma wizytówki w bazie ładujemy dane od razu z RFD
                            $t_wiz->addSearchReplace('RFD_opis', $pkgm->pobierzOpis());
                            $t_wiz->addSearchReplace('RFD_www', RFD::get('edycja_wizytowki', 'www'));
                        }

                        //gdy użytkownik ma już logo wyświetlamu mu je z przyciskiem USUŃ
                        if (strlen($pkgm->pobierzLogoLink()) > 0 && !is_null($pkgm->pobierzLogoLink())) {
                            $t_wiz->addSearchReplace('logo', 'loga/' . $pkgm->pobierzLogoLink());
                            $t_wiz_usun = new Template('view/html/wizytowka_usun_logo.html');
                            $t_wiz->addSearchReplace('usun', $t_wiz_usun->getContent());

                            //jeśli użytkownik nie ma jeszcze loga łądujemu mu obrazek domyślny bez przycisku USUŃ
                        } else {
                            $t_wiz->addSearchReplace('logo', 'loga/default.png');
                            $t_wiz->addSearchReplace('usun', '');
                        }

                        //usuwanie loga z przycisku USUŃ
                        if (isset($_GET['usun_logo']) && $_GET['usun_logo'] == 1) {

                            unlink('loga/' . $pkgm->pobierzLogoLink());
                            $sql = Query::setLogoForUser($u->getId_user(), '');
                            $dbc->query($sql);

                            BFEC::redirect(Pathes::getScriptProfileCard()); //przekierowanie po usunięciu na odświeżony formularz wizytówki
                        }

                        $t->addSearchReplace('here', $t_wiz->getContent());

                        //reakcja na zapisanie formularza
                        if ((isset($_POST['submit']))) {

                            $_POST['opis'] = Valid::antyHTML($_POST['opis']);
                            $_POST['opis'] = nl2br($_POST['opis']);

                            $_POST['www'] = Valid::antyHTML($_POST['www']);

                            //sprawdzenie poprawności adresu WWW
                            if (Valid::isValidURL($_POST['www'])) {
                                RFD::add('edycja_wizytowki', 'www', $_POST['www']);
                            } else {
                                BFEC::add(MSG::profileBlednyAdresWWW());
                            }

                            //sprawdzenie długości wizytówki czy zgodna z dozwoloną
                            if (strlen($_POST['opis']) < $pkgm->iIleZnakowWizytowka()) {
                                RFD::add('edycja_wizytowki', 'opis', $_POST['opis']);
                            }else
                                BFEC::add(MSG::profileOpisZaDlugi());


                            //zapisywanie poprawnych danych w bazie
                            if ($pkgm->sprawdzWizytowke($dbc, $u->getId_user()) == FALSE && !is_null(RFD::get('edycja_wizytowki', 'opis')) && !is_null(RFD::get('edycja_wizytowki', 'www'))) {
                                //w przypadku gdy nowa pozycja w bazie
                                $sql = Query::setNewCardForUser($u->getId_user(), RFD::get('edycja_wizytowki', 'opis'), RFD::get('edycja_wizytowki', 'www'), 'NULL');
                                $dbc->query($sql);
                                RFD::clear('edycja_wizytowki');
                                if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                    throw new NieZaktualizowanoWizytowki;
                            }else if (!is_null(RFD::get('edycja_wizytowki', 'opis')) && !is_null(RFD::get('edycja_wizytowki', 'www'))) {
                                //w przypadku gdy rekord odnośnie wizytówki już istnieje
                                $sql = Query::setCardForUser($u->getId_user(), RFD::get('edycja_wizytowki', 'opis'), RFD::get('edycja_wizytowki', 'www'));
                                $dbc->query($sql);
                                RFD::clear('edycja_wizytowki');
                                if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                    throw new NieZaktualizowanoWizytowki;
                            }

                            BFEC::isError();
                        }
                    } else if ($_GET['a'] == 1) {
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
                        $t = new Template('view/html/profile_dostawca_wizytowka.html');
                }
                else
                    $t = new Template('view/html/profile_dostawca_wizytowka.html');
            }
            //AKTYWNE PAKIETY
            else if ($_GET['w'] == 'pakiety') {
                if (isset($_GET['a']) && $_GET['a'] == 0) {
                    $t = new Template('view/html/profile_dostawca_aktywne_pakiety.html');

                    $pm = new PackageManager();
                    $pakiet = $pm->pobierzAktywnePakiety($dbc, $u->getId_user());

                    $temp_lista = new Template('view/html/profile_dostawca_lista_aktywnych_pakietow.html');

                    $temp = '';

                    //generowanie listy aktywnych pakietów dla DOSTAWCY
                    foreach ($pakiet as $temporary) {  //każdy pakiet (po kolei jako temporary) dodawany do szablonu
                        $temp_lista->clearSearchReplace();
                        $temp_lista->addSearchReplace('id', $temporary['id_pakietu']);
                        $temp_lista->addSearchReplace('date_begin', date("d-m-Y H:i", $temporary['date_begin']));


                        //w przypadku pierwszewgo pakietu zamiast daty końcowej wyświetlamy napis o jej braku - pakiet podstawowy jest dożywotni
                        if ($temporary['id_pakietu'] == 1)
                            $temp_lista->addSearchReplace('date_end', 'bez daty końcowej');
                        else
                            $temp_lista->addSearchReplace('date_end', date("d-m-Y H:i", $temporary['date_end']));

                        $temp.=$temp_lista->getContent();  //dołączenie do całości
                    }
                    $t->addSearchReplace('here', $temp);

                    //KUP PAKIET
                } else if ((isset($_GET['a']) && $_GET['a'] == 1) || !isset($_GET['a'])) {
                    $t = new Template('view/html/profile_dostawca_kup.html');

                    //dodawanie listy pakietow do zakładki PAKIETY w profilu DOSTAWCY

                    $temp_lista = new Template('view/html/profile_dostawca_lista_pakietow.html');

                    $temp = '';

                    //generowanie listy pakietów od 2 do 5 dla DOSTAWCY
                    for ($i = 2; $i <= 5; $i++) {

                        $temp_lista->clearSearchReplace();
                        $temp_lista->addSearchReplace('id', $i);

                        $temp.=$temp_lista->getContent();
                    }

                    $t->addSearchReplace('here', $temp);
                }
            } else if ($_GET['w'] == 'faktury') {
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
