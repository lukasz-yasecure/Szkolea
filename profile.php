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

                $t = new Template(Pathes::getPathTemplateProfileObservedServCats());
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
                    $t = new Template(Pathes::getPathTemplateProfileObservedComms());
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
                    $t = new Template(Pathes::getPathTemplateProfileZleceniaMoje());
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
                    $t = new Template(Pathes::getPathTemplateProfilePaticipate());
                    $ud = new UserData();
                    $s = $ud->getSearch(); // pobieramy parametry szukania jesli jakies sa
                    $s->setWhat('comms');
                    $rm = new ResultsManager(); // tworzymi liste wynikow do wyswietlenia
                    $r = $rm->getResults($dbc, $s, 'SELECT * FROM `commisions_group` CG LEFT JOIN commisions C ON CG.id_comm=C.id_comm WHERE CG.id_user=' . $u->getId_user() . ' GROUP BY C.id_comm'); // tutaj tak naprawde dopiero tworzymy liste wynikow na bazie wyszukiwania/wyboru z lewego menu
                    $rlt = $tm->getResultsListTemplateForProfile($sys, $r, 'biore'); // szablon listy z wynikami
                    $rt = $tm->getResultsTemplateForProfile($sys, $rlt, 'biore'); // szablon wynikow
                    $r = $rt->getContent();
                } else if (isset($_GET['a']) && $_GET['a'] == 4)
                    $t = new Template(Pathes::getPathTemplateProfilePaticipateEnd());
                else {
                    /*
                     * KLIENT - OBSERWOWANE KATEGORIE ZLECEN
                     */
                    $t = new Template(Pathes::getPathTemplateProfileObservedCommsCats());
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
                $t = new Template(Pathes::getPathTemplateProfileZleceniaMoje());
                $res = $dbc->query(Query::getOfferForComm($_GET['id'])); // pobierane oferty wg. id zlecenia (tylko statu 1 lub 2)
                $get_group = $dbc->query(Query::getGroupCommUsers($_GET['id'])); // pobierana lista dodanych do zlecenia
                if (isset($_GET['ofe'])) { // wybór oferty przez klienta
                    $dbc->query(Query::getOfferAcceptYes($_GET['ofe'])); // oznacza status oferty jako 2, czyli oferta wybrana (1 - dodana, 2 - wybrana, 3 - rezygnacja)
                    // wysyłane powiadomienie właścicielowi wybranej oferty
                    $gu = $dbc->query(Query::getOfferAccept($_GET['ofe'])); // pobierane dane wybranej oferty
                    $gu_fetch = $gu->fetch_object();
                    $m->infoWybranaOfertaWlasciciel($um->getUser($dbc, $gu_fetch->id_user));

                    // wysyłane powiadomienia właścicielom odrzuconych ofert
                    $res = $dbc->query(Query::getOfferAcceptYesAfter($_GET['id'], $_GET['ofe'])); // pobieramy oferty zlecenia z wyjatkiem wybranej oferty
                    // faktura proforma: tworzymy wpis i wysyłamy powiadomienie
                    $im = new InvoiceManager();
                    $im->createUnpaidInvoiceProwizja($dbc, $gu_fetch);
                    $m->infoUnpaidInvoice($um->getUser($dbc, $gu_fetch->id_user)); // powiadomienie: dostępna faktura pro forma

                    while ($x = $res->fetch_assoc()) {
                        $dbc->query(Query::getOfferAcceptNo($x['id_ofe'])); // oznaczamy oferty jako odrzucone
                        $m->infoOdrzuconaOfertaWlasciciel($um->getUser($dbc, $x['id_user']));
                    }

                    // wysyłane informacje o wybranej ofercie dodanym do zlecenia osobom
                    while ($x = $get_group->fetch_assoc()) {
                        $m->infoWybranaOfertaDodaneDoZlecenia($um->getUser($dbc, $x['id_user']));
                    }
                    BFEC::addm(MSG::profileOfferChosen(), Pathes::getScriptProfileZleceniaMoje());
                } elseif (isset($_GET['resign'])) {
                    while ($x = $res->fetch_assoc()) {
                        $dbc->query(Query::getOfferAcceptNo($x['id_ofe'])); // oznaczamy oferty jako odrzucone
                        // wysyłamy powiadomienie właścicielowi odrzuconej oferty
                        $m->infoOdrzuconaOfertaWlasciciel($um->getUser($dbc, $x['id_user']));
                    }
                    // wysyłane informacje o odrzuconej ofercie dodanym do zlecenia osobom
                    while ($x = $get_group->fetch_assoc()) {
                        $m->infoOdrzuconaOfertaDodaneDoZlecenia($um->getUser($dbc, $x['id_user']));
                    }
                    BFEC::addm(MSG::profileNoOfferChosen(), Pathes::getScriptProfileZleceniaMoje());
                } else {
                    /*
                     * KLIENT - LISTA OFERT
                     */
                    $temp_lo = new Template(Pathes::getPathTemplateProfileOffers());
                    $rezygnacja = '';
                    $oferty = '';
                    $tm = new TemplateManager();

                    $o1 = $res->fetch_assoc();
                    // oferta nr 1 ma status 1 czyli generujemy liste ofert z przyciskiem do akceptacji
                    if ($o1['ofe_status'] === '1' && $res->num_rows > 0) {
                        $temp_r = new Template(Pathes::getPathTemplateProfileOffersRezygnacja());
                        $temp_r->addSearchReplace('id', $_GET['id']);
                        $rezygnacja = $temp_r->getContent();
                        $temp_1o = new Template(Pathes::getPathTemplateProfileOffers1OfferToChoose());
                        $temp_1o = $tm->getOfferTemplate($temp_1o, $o1);
                        $temp_1o->addSearchReplace('id_zl', $_GET['id']);
                        $oferty.= $temp_1o->getContent();
                        $temp_1o->clearSearchReplace();

                        while ($x = $res->fetch_assoc()) {
                            $temp_1o = $tm->getOfferTemplate($temp_1o, $x);
                            $temp_1o->addSearchReplace('id_zl', $_GET['id']);
                            $oferty.= $temp_1o->getContent();
                            $temp_1o->clearSearchReplace();
                        }
                        // oferta nr 1 ma status inny niz 1 czyli byl juz wybor oferty wiec nie wyswietlamy przycisku do akceptacji
                    } else if ($res->num_rows > 0) {
                        $temp_1o = new Template(Pathes::getPathTemplateProfileOffers1Offer());
                        $temp_1o = $tm->getOfferTemplate($temp_1o, $o1);
                        $oferty.= $temp_1o->getContent();
                        $temp_1o->clearSearchReplace();

                        while ($x = $res->fetch_assoc()) {
                            $temp_1o = $tm->getOfferTemplate($temp_1o, $x);
                            $oferty.= $temp_1o->getContent();
                            $temp_1o->clearSearchReplace();
                        }
                    }

                    $temp_lo->addSearchReplace('oferty', $oferty);
                    $r = $rezygnacja . $temp_lo->getContent();
                }
            } else if ($_GET['w'] == 'dane') {
                /*
                 * KLIENT - EDYCJA DANYCH
                 */
                try {
                    if (!isset($_POST['profile_edit_form'])) {
                        $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                        $t = new Template(Pathes::getPathTemplateProfileEdit()); // szablon profilu użytkownika
                        $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                        $r = $pft->getContent();
                    } else if (isset($_POST['profile_edit_form'])) {
                        $ud = new UserData();
                        $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                        $t = new Template(Pathes::getPathTemplateProfileEdit()); // szablon profilu użytkownika
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

            //USŁUGI - DOSTAWCA
            if ($_GET['w'] == 'servs') {
                $sm = new ServiceManager();
                $pm = new PackageManager();

                //moje usługi
                if (isset($_GET['a']) && $_GET['a'] == 1) {
                    $t = new Template(Pathes::getPathTemplateProfileMyObserved());
                }
                //promowana usługa
                elseif (isset($_GET['a']) && $_GET['a'] == 2) {

                    $t = new Template(Pathes::getPathTemplateProfilePromote());

                    //przypadek gdy użytkownik promuje już jakąś usługę, więc wyświetlamu mu ją i jej termin promowania
                    if (count($promoted = $sm->getPromotedServs($dbc, $u->getId_user())) > 0) {
                        $t_prom = new Template(Pathes::getPathTemplatePromotedService());
                        $t_prom->addSearchReplace('serv_name', $promoted[0]->getName());
                        $t_prom->addSearchReplace('data', UF::timestamp2date($promoted[0]->getPromoteDate_end()));
                        $t_prom->addSearchReplace('serv_link', Pathes::getScriptServicePath($promoted[0]->getId_serv()));

                        $r = $t_prom->getContent();

                        //przypadek gdy użytkownik ma odpowiedni pakiet
                    } elseif ($pm->pobierzInformacjePakietow($dbc, $u->getId_user()) && $pm->czyMoznaWlaczycMailing()) {

                        //przypadek gdy użytkownik posiada aktywne usługi
                        if (count($user_services = $sm->getActiveServicesForUser($dbc, $u->getId_user())) > 0) {

                            //obsługa wyboru z radio usługi do promowania
                            if (isset($_POST['promote_serv'])) {
                                if ($sm->insertPromotedService($dbc, $_POST['promote_serv'], $u->getId_user()))
                                    BFEC::addm(MSG::ServicePromotionSet(), Pathes::getScriptProfilePromotedServices());
                                else
                                    BFEC::add(MSG::instertError());
                            }
                            //jeśli nie ma $_POSTa to generujemy formularz
                            else {
                                $t_choose = new Template(Pathes::getPathTemplatePromotedChoose());
                                $t_radio = new Template(Pathes::getPathTemplatePromoted1ServiceForChoose());
                                $radios = '';   //lista pól radio z szablonu na radio
                                //generowanie listy pól radio dla wszystkich usług danego użytkownika
                                for ($i = 0; $i < count($user_services); $i++) {
                                    $t_radio->addSearchReplace('id_serv', $user_services[$i]->getId_serv());
                                    $t_radio->addSearchReplace('name_serv', $user_services[$i]->getName());
                                    $radios .= $t_radio->getContent();
                                    $t_radio->clearSearchReplace();
                                }
                                $t_choose->addSearchReplace('lista', $radios);
                                $r = $t_choose->getContent();
                            }
                        } else
                        //brak aktywnych usług
                            BFEC::add(MSG::noServices(), true, Pathes::getScriptProfileServices());
                    } else {
                        //brak odpowiednich pakietów
                        BFEC::add(MSG::profileNoPromotionAllow(), true, Pathes::getScriptProfilePackageBuyingPath());
                    }


                    //obserwowane kategorie - domyślnie
                } else {
                    /*
                     * DOSTAWCA - OBSERWOWANE KATEGORIE USLUG
                     */
                    $t = new Template(Pathes::getPathTemplateProfileObservedServsCats());
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
                    $t = new Template(Pathes::getPathTemplateProfileObservedCommsForDeveloper());
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
                    $t = new Template(Pathes::getPathTemplateProfileOffersForDeveloper());
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
                    $t = new Template(Pathes::getPathTemplateProfileObservedCommsCatsForDeveloper());
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
                if ((isset($_GET['a']) && $_GET['a'] == 0) || !isset($_GET['a'])) {

                    $t = new Template(Pathes::getPathTemplateProfileCardForDeveloper());
                    $t_wiz = new Template(Pathes::getPathTemplateProfileCard());

                    $pkgm = new PackageManager();
                    $pkgm->pobierzInformacjePakietow($dbc, $u->getId_user());

                    if ((isset($_POST['submit']))) {

                        if (isset($_POST['opis']) && strlen($_POST['opis']) > 0) {
                            $_POST['opis'] = Valid::antyHTML($_POST['opis']);
                            $_POST['opis'] = nl2br($_POST['opis']);

                            //sprawdzenie długości wizytówki czy zgodna z dozwoloną
                            if (strlen($_POST['opis']) <= $pkgm->iIleZnakowWizytowka()) {
                                RFD::add('edycja_wizytowki', 'opis', $_POST['opis']);
                            }else
                                BFEC::add(MSG::profileOpisZaDlugi());
                        }else {
                            $_POST['opis'] = '';
                            RFD::add('edycja_wizytowki', 'opis', $_POST['opis']);
                        }

                        if (isset($_POST['www']) && strlen($_POST['www']) > 0) {
                            $_POST['www'] = Valid::antyHTML($_POST['www']);

                            if (Valid::isValidURL($_POST['www'])) {
                                RFD::add('edycja_wizytowki', 'www', $_POST['www']);
                            } else {
                                BFEC::add(MSG::profileBlednyAdresWWW());
                            }
                        } else {
                            $_POST['www'] = '';
                            RFD::add('edycja_wizytowki', 'www', $_POST['www']);
                        }


                        //zapisywanie poprawnych danych w bazie
                        if ($pkgm->sprawdzWizytowke($dbc, $u->getId_user()) == FALSE && !BFEC::isError()) {
                            //w przypadku gdy nowa pozycja w bazie
                            $sql = Query::setNewCardForUser($u->getId_user(), RFD::get('edycja_wizytowki', 'opis'), RFD::get('edycja_wizytowki', 'www'), 'NULL');
                            $dbc->query($sql);
                            RFD::clear('edycja_wizytowki');
                            if ($dbc->affected_rows != 1) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                throw new NieZaktualizowanoWizytowki;
                        }else if (!BFEC::isError()) {
                            //w przypadku gdy rekord odnośnie wizytówki już istnieje
                            $sql = Query::setCardForUser($u->getId_user(), RFD::get('edycja_wizytowki', 'opis'), RFD::get('edycja_wizytowki', 'www'));
                            $dbc->query($sql);
                            RFD::clear('edycja_wizytowki');
                            if (strlen($dbc->error) > 0) // obsługa błedu gdy ilość zmienionych wierszy inna niż 1
                                throw new NieZaktualizowanoWizytowki;
                        }


                        BFEC::addm(MSG::profileCardUpdate(), Pathes::getScriptProfileCard()); //przekierowanie po obsłużeniu formularza na odświeżony formularz wizytówki
                    }
                    else {

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
                            $t_wiz->addSearchReplace('RFD_opis', RFD::get('edycja_wizytowki', 'opis'));
                            $t_wiz->addSearchReplace('RFD_www', RFD::get('edycja_wizytowki', 'www'));
                        }

                        //gdy użytkownik ma już logo wyświetlamu mu je z przyciskiem USUŃ
                        if (strlen($pkgm->pobierzLogoLink()) > 0 && !($pkgm->pobierzLogoLink() == 'NULL')) {
                            $t_wiz->addSearchReplace('logo', 'loga/' . $pkgm->pobierzLogoLink());
                            $t_wiz_usun = new Template(Pathes::getPathTemplateProfileDeleteLogo());
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

                        $r = $t_wiz->getContent();
                    }
                } else if (isset($_GET['a']) && $_GET['a'] == 1) {
                    /*
                     * DOSTAWCA - EDYCJA DANYCH
                     */
                    try {
                        if (!isset($_POST['profile_edit_form'])) {
                            $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                            $t = new Template(Pathes::getPathTemplateProfileEditForDeveloper()); // szablon profilu użytkownika
                            $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                            $r = $pft->getContent();
                        } else if (isset($_POST['profile_edit_form'])) {
                            $ud = new UserData();
                            $gu = $um->getUser($dbc, $u->getId_user()); // pobieramy dane użytkownika z bazy
                            $t = new Template(Pathes::getPathTemplateProfileEditForDeveloper()); // szablon profilu użytkownika
                            $pft = $tm->getProfileEditFormTemplate($sys, $gu, $u); // szablon z formularzem
                            $rfd = $ud->getProfileEditFormData(); // pobieramy dane z klasy ProfileEditForm
                            $um->updateProfileData($dbc, $rfd, $u); // edycja danych w bazie
                            header('Location:' . $_SERVER['REQUEST_URI']); // przeładowanie strony, kasujemy stary $_POST
                            $r = $pft->getContent();
                        }
                    } catch (ErrorsInprofileEditForm $e) {
                        BFEC::add('', true, 'profile.php?w=dane&a=1');
                    }

                    //DOSTAWCA - OCENY
                } elseif (isset($_GET['a']) && $_GET['a'] == 2) {
                    $t = new Template(Pathes::getPathTemplateProfileRateData());
                }

                //DOSTAWCA - BANER
                elseif (isset($_GET['a']) && $_GET['a'] == 3) {
                    $pkgm = new PackageManager();
                    $pkgm->pobierzInformacjePakietow($dbc, $u->getId_user());

                    if ($pkgm->czyMoznaDodacBaner()) {

                        if (isset($_POST['submit'])) {
                            if ($pkgm->czyMoznaDodacBaner()) {
                                $mailer = new Mailer();
                                $mailer->sendToAdminBanerRequest($u);

                                BFEC::addm(MSG::submitedForBaner(), Pathes::getScriptProfileBaner());
                            }
                            else
                                BFEC::add(MSG::profileNoBanerAllow(), true, Pathes::$script_profile_packages_buying);
                        } else {
                            $t = new Template(Pathes::getPathTemplateProfileBaner());
                            $t->addSearchReplace('text', MSG::submitForBaner());
                        }
                    } else
                        BFEC::add(MSG::profileNoBanerAllow(), true, Pathes::$script_profile_packages_buying);
                }


                //przypadek gdy wybrana opcja nie pasuje do powyższych - przechodzimy wtedy na MojeDane->Wizytówka    
                else
                    BFEC::redirect(Pathes::$script_profile_card);
            }
            //AKTYWNE PAKIETY
            else if ($_GET['w'] == 'pakiety') {
                if (isset($_GET['a']) && $_GET['a'] == 0) {
                    $t = new Template(Pathes::getPathTemplateProfileActivePackages());

                    $pm = new PackageManager();
                    $pakiet = $pm->pobierzAktywnePakiety($dbc, $u->getId_user());

                    $temp_lista = new Template(Pathes::getPathTemplateProfileActivePackagesList());

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
                    $r = $temp;

                    //KUP PAKIET
                } else if ((isset($_GET['a']) && $_GET['a'] == 1) || !isset($_GET['a'])) {
                    $t = new Template(Pathes::getPathTemplateProfileBuyPackage());

                    //dodawanie listy pakietow do zakładki PAKIETY w profilu DOSTAWCY

                    $temp_lista = new Template(Pathes::getPathTemplateProfilePackagesList());

                    $temp = '';

                    //generowanie listy pakietów od 2 do 5 dla DOSTAWCY
                    for ($i = 2; $i <= 5; $i++) {

                        $temp_lista->clearSearchReplace();
                        $temp_lista->addSearchReplace('id', $i);

                        $temp.=$temp_lista->getContent();
                    }
                    $r = $temp;
                }
                //kup_pakiet walidacja + czy z zakresu 2-5
                if (isset($_GET['kup_pakiet'])) {
                    if (Valid::isNatural($_GET['kup_pakiet']) && $_GET['kup_pakiet'] <= 5 && $_GET['kup_pakiet'] > 1) {
                        $pkm = new PackageManager();
                        $pk = $pkm->pobierzPakiet($dbc, $_GET['kup_pakiet']);
                        $im = new InvoiceManager();
                        $im->createUnpaidInvoicePakiet($dbc, $u->getId_user(), $pk); // dodana faktura proforma, pobierany id z mysqli, przekierowanie na formularz opłaty
                    } else {
                        throw new NieprawidloweIdPakietu;
                    }
                }
            }
            //FAKTURY
            else if ($_GET['w'] == 'faktury') {
                if (isset($_GET['a'])) {

                    //opłacone faktury
                    if ($_GET['a'] == 0) {
                        $t = new Template(Pathes::getPathTemplateProfilePaidInvoice());

                        $uil = $dbc->query(Query::getDataProfilePaidInvoiceList($u->getId_user())); // pobierana lista faktur opłaconych / paid invoice list
                        $r = $tm->getTemplateProfilePaidInvoiceList($uil); // paid invoice list template result
                    }
                    //nieopłacone faktury
                    else if ($_GET['a'] == 1) {
                        if (isset($_GET['p'])) { // `p` jak payment
                            $t = new Template(Pathes::getPathTemplateProfilePaymentProwizja());
                            $fr = $dbc->query(Query::getDataProfileInvoice($_GET['p'])); // pobierane dane faktury / form result
                            $r = $tm->getTemplateProfilePaymentFormProwizja($fr, $u); // form template
                        } else if (isset($_GET['m']) AND $_GET['m'] == 'thankyou') {
                            $t = new Template(Pathes::getPathTemplateProfilePaymentThankYouProwizja());
                            $r = MSG::paymentThankYou();
                        } else {
                            $t = new Template(Pathes::getPathTemplateProfileUnpaidInvoice());
                            $uil = $dbc->query(Query::getDataProfileUnpaidInvoiceList($u->getId_user())); // pobierana lista faktur proforma / unpaid invoice list
                            $r = $tm->getTemplateProfileUnpaidInvoiceList($uil); // unpaid invoice list template result
                        }

                        if (isset($_GET['f'])) {
                            $fget = (int) $_GET['f'];
                            $f = $dbc->query(Query::getDataProfileInvoice($fget))->fetch_object(); // pobierane dane faktury wg. id_faktura
                        }
                        if (isset($fget) AND !empty($fget) AND isset($f)) {
                            $sys->loadPdf();
                            $pdf = new Pdf();
                            $pdf->generate($u, $f, 'fpf'); // generowanie pdf faktury pro forma (fpf)
                        }
                    }
                    else
                        BFEC::redirect(Pathes::getScriptProfileUnpaidInvoices());
                }
                else
                    BFEC::redirect(Pathes::getScriptProfileUnpaidInvoices());
            }
            else
                $t = new Template(Pathes::getPathTemplateProfileU());
        }
        else
            $t = new Template(Pathes::getPathTemplateProfileU());
    }
    else if ($u->isAdmin()) {

        //wyświetlenie listy zleceń dla admina
        if ((isset($_GET['w']) && $_GET['w'] == 'comms' && !isset($_GET['a'])) || (isset($_GET['w']) && $_GET['w'] == 'comms' && isset($_GET['a']) && $_GET['a'] == '0')) {

            $t = new Template(Pathes::getPathTemplateProfileCommsForAdmin());
            $r = $tm->getCommsListForAdmin(new DBC($sys));
        } else if (isset($_GET['w']) && $_GET['w'] == 'kategorie' && ((isset($_GET['a']) && $_GET['a'] == '0') || !isset($_GET['a']))) {
            $t = new Template(Pathes::getPathTemplateProfileCatsEditForAdmin());

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

            //LISTA UŻYTKOWNIKÓW
        } else if (isset($_GET['w']) && $_GET['w'] == 'uzytkownicy' && ((isset($_GET['a']) && $_GET['a'] == '0') || !isset($_GET['a']))) {
            $t = new Template(Pathes::getPathTemplateProfileUsersListForAdmin());
            $sql = Query::getAllUsers();
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
            //throw new EmptyList();
                $r = '';
            else {
                $t_user = file_get_contents(Pathes::getPathTemplateProfileUsersSublistForAdmin());

                while ($row = $result->fetch_assoc()) {
                    $user = $um->getUserFromRow($row);  //użytkownicy kolejno przerabiani na obiekty
                    //ustawienie rodzaju użytkownika względem symbolizującej litery
                    if ($user->getKind() == 'A')
                        $kind = 'admin';
                    elseif ($user->getKind() == 'D')
                        $kind = 'usługodawca';
                    elseif ($user->getKind() == 'K')
                        $kind = 'klient';

                    //podmiana w szablonie
                    $r.= str_replace(
                            array('{%id_user%}', '{%nazwa%}', '{%kind%}'), array($user->getId_user(), $user->getFullName(), $kind), $t_user);
                }
            }
            //KONKRETNY UŻYTKOWNIK
        }else if (isset($_GET['w']) && $_GET['w'] == 'uzytkownik' && isset($_GET['u']) && is_numeric($_GET['u'])) {

            $t = new Template(Pathes::getPathTemplateProfileUsersListForAdmin());

            $sql = Query::getUser($_GET['u']);
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $r = '';
            else {
                $t_user = file_get_contents(Pathes::getPathTemplateProfileDetailsForAdmin());

                while ($row = $result->fetch_assoc()) {

                    $user = $um->getUserFromRow($row);  //zamiana danych użytkownika na obiekt
                    //ustawienie rodzaju użytkownika względem symbolizującej litery
                    if ($user->getKind() == 'A')
                        $kind = 'admin';
                    elseif ($user->getKind() == 'D')
                        $kind = 'usługodawca';
                    elseif ($user->getKind() == 'K')
                        $kind = 'klient';

                    //ustawienie statusu użytkownika względem symbolizującej liczby
                    if ($user->getStatus() == 0)
                        $status = 'nieaktywny';
                    elseif ($user->getStatus() == 1)
                        $status = 'aktywny';
                    elseif ($user->getStatus() == 2)
                        $status = 'zbanowany';

                    //podmiana w szablonie
                    $r.= str_replace(array('{%id_user%}', '{%nazwa%}', '{%kind%}', '{%data_rej%}', '{%status%}', '{%email%}', '{%os_name%}', '{%os_surname%}', '{%os_street%}', '{%os_house_number%}', '{%os_postcode%}', '{%os_city%}', '{%os_woj%}', '{%os_phone%}', '{%f_company%}', '{%f_name%}', '{%f_surname%}', '{%f_position%}', '{%f_street%}', '{%f_house_number%}', '{%f_postcode%}', '{%f_city%}', '{%f_woj%}', '{%f_phone%}', '{%f_regon%}', '{%f_nip%}', '{%f_krs%}'), array($user->getId_user(), $user->getFullName(), $kind, UF::timestamp2date($user->getDate_reg()), $status, $user->getEmail(), $user->getOs_name(), $user->getOs_surname(), $user->getOs_street(), $user->getOs_house_number(), $user->getOs_postcode(), $user->getOs_city(), UF::nr2wojName($user->getOs_woj()), $user->getOs_phone(), $user->getF_company(), $user->getF_name(), $user->getF_surname(), $user->getF_position(), $user->getF_street(), $user->getF_house_number(), $user->getF_postcode(), $user->getF_city(), UF::nr2wojName($user->getF_woj()), $user->getF_phone(), $user->getF_regon(), $user->getF_nip(), $user->getF_krs()), $t_user);
                }
            }
        }
        //STATYSTYKI
        //podstawową stroną jest strona ofertami
        elseif ((isset($_GET['w']) && $_GET['w'] == 'statystyki' && !isset($_GET['a'])) || (isset($_GET['w']) && $_GET['w'] == 'statystyki' && isset($_GET['a']) && $_GET['a'] == 'oferty')) {
            $t = new Template(Pathes::getPathTemplateStatsOffers());

            //jeśli mamy ustawiony parametr 'o' to przechodzimy na konkretną ofertę
            if (isset($_GET['o']) && is_numeric($_GET['o'])) {
                $om = new OfferManager();

                $sql = Query::getOffer($_GET['o']); //wczytujemy odpowiednią ofertę z bazy
                $result = $dbc->query($sql);

                if (!$result)
                    throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                if ($result->num_rows <= 0)
                    $r = '';
                else {
                    $temp = file_get_contents(Pathes::getPathTemplateOfferDetails());

                    while ($row = $result->fetch_assoc()) {
                        $offer[] = $om->getOfferFromRow($row);  //zamiana danych oferty na obiekt
                        $inne = UF::inne2arrayTakNie($om->getOfferFromRow($row)->getInne()); //pomocnicza na INNE
                        //podmiana w szablonie
                        $r.= str_replace(array('{%id_ofe%}', '{%id_comm%}', '{%id_user%}', '{%date_add%}', '{%cena%}', '{%cenaX%}', '{%rozl%}', '{%date_a%}', '{%date_b%}', '{%ofe_status%}', '{%sala%}', '{%materialy%}', '{%lunch%}', '{%kawa%}', '{%ile_kaw%}'), array($om->getOfferFromRow($row)->getId_ofe(), $om->getOfferFromRow($row)->getId_comm(), $om->getOfferFromRow($row)->getId_user(), UF::timestamp2date($om->getOfferFromRow($row)->getDate_add(), true),
                            $om->getOfferFromRow($row)->getCena(), UF::cenax2name($om->getOfferFromRow($row)->getCenax()), UF::rozl2name($om->getOfferFromRow($row)->getRozl()), UF::timestamp2date($om->getOfferFromRow($row)->getDate_a(), true), UF::timestamp2date($om->getOfferFromRow($row)->getDate_b(), true), $om->getStatusOffersChoiceForComm($offer, $om->getOfferFromRow($row)->getId_comm()), $inne['sala'], $inne['materialy'], $inne['lunch'], $inne['kawa'], ($om->getOfferFromRow($row)->getIle_kaw() ? $om->getOfferFromRow($row) : '0')), $temp);
                    }
                }
            } else {    //w standardowym przypadku wyświetlamy listę ofert
                //wczytujemy dane posortowane po dacie
                $sql = Query::getAllOffersDescDate();
                $result = $dbc->query($sql);
                if (!$result)
                    throw new DBQueryException($dbc->error, $sql, $dbc->errno);
                if ($result->num_rows <= 0)
                    $r = '';
                else {
                    $temp = file_get_contents(Pathes::getPathTemplateStatsOffersList());

                    while ($row = $result->fetch_assoc()) {
                        $user = $um->getUserFromRow($row);  //zamiana danych użytkownika na obiekt
                        //podmiana w szablonie
                        $r.= str_replace(array('{%data%}', '{%id_ofe%}', '{%id_comm%}', '{%nazwa%}', '{%id_user%}'), array(UF::timestamp2date($row['date_add'], true), $row['id_ofe'], $row['id_comm'], $user->getFullName(), $user->getId_user()), $temp);
                    }
                }
            }
            //statystyki dla zleceń
        } elseif (isset($_GET['w']) && $_GET['w'] == 'statystyki' && isset($_GET['a']) && $_GET['a'] == 'zlecenia') {
            $t = new Template(Pathes::getPathTemplateStatsComms());

            //wczytujemy dane posortowane po dacie
            $sql = Query::getAllCommsDescDate();
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $r = '';
            else {
                $temp = file_get_contents(Pathes::getPathTemplateStatsCommsList());

                while ($row = $result->fetch_assoc()) {
                    $user = $um->getUserFromRow($row);  //zamiana danych użytkownika na obiekt
                    //podmiana w szablonie
                    $r.= str_replace(array('{%data%}', '{%id_comm%}', '{%nazwa%}', '{%id_user%}'), array(UF::timestamp2date($row['date_add'], true), $row['id_comm'], $user->getFullName(), $user->getId_user()), $temp);
                }
            }

            //statystyki dla usług
        } elseif (isset($_GET['w']) && $_GET['w'] == 'statystyki' && isset($_GET['a']) && $_GET['a'] == 'uslugi') {
            $t = new Template(Pathes::getPathTemplateStatsServs());

            //wczytujemy dane posortowane po dacie
            $sql = Query::getAllServsDescDate();
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $r = '';
            else {
                $temp = file_get_contents(Pathes::getPathTemplateStatsServsList());

                while ($row = $result->fetch_assoc()) {
                    $user = $um->getUserFromRow($row);  //zamiana danych użytkownika na obiekt
                    //podmiana w szablonie
                    $r.= str_replace(array('{%data%}', '{%id_serv%}', '{%nazwa%}', '{%id_user%}'), array(UF::timestamp2date($row['date_add'], true), $row['id_serv'], $user->getFullName(), $user->getId_user()), $temp);
                }
            }
            //statystyki dla pakietów
        } elseif (isset($_GET['w']) && $_GET['w'] == 'statystyki' && isset($_GET['a']) && $_GET['a'] == 'pakiety') {
            $t = new Template(Pathes::getPathTemplateStatsPackages());

            //wczytujemy dane posortowane po dacie
            $sql = Query::getAllPackagesDescDate();
            $result = $dbc->query($sql);
            if (!$result)
                throw new DBQueryException($dbc->error, $sql, $dbc->errno);
            if ($result->num_rows <= 0)
                $r = '';
            else {
                $temp = file_get_contents(Pathes::getPathTemplateStatsPackagesList());

                while ($row = $result->fetch_assoc()) {
                    $user = $um->getUserFromRow($row);  //zamiana danych użytkownika na obiekt
                    //podmiana w szablonie
                    $r.= str_replace(array('{%data%}', '{%id_pakietu%}', '{%nazwa_pakietu%}', '{%nazwa%}', '{%id_user%}'), array(UF::timestamp2date($row['date_begin'], true), $row['id_pakietu'], $row['nazwa'], $user->getFullName(), $user->getId_user()), $temp);
                }
            }
        }
        //INNE
        //NEWSLETTER
        elseif (isset($_GET['w']) && $_GET['w'] == 'inne' && !isset($_GET['a']) || (isset($_GET['a']) && $_GET['a'] == 'newsletter')) {
            $t = new Template(Pathes::getPathTemplateNewsletter());
            $ud = new UserData();

            //reakcja na POST
            if ((isset($_POST['submit']))) {
                $sm = new ServiceManager();

                //pobieramy obiekt Newsletter z uzupełnionymi danymi
                $n = $ud->getNewsletter($dbc);

                //uzupełniamy Newsletter o promowane usługi z ich nazwami
                $n->setPromotedServs($sm->getPromotedServs($dbc));

                //wysyłanie Newslettera
                $mailer = new Mailer();
                $mailer->sendNewsletter($n);

                BFEC::addm(MSG::NewsletterSent(), Pathes::getScriptAdminNewsletter());
            } else {
                //dane z RFD do szablonu
                $t->addSearchReplace('RFD_subject', RFD::get('newsletter', 'subject'));
                $t->addSearchReplace('RFD_content', RFD::get('newsletter', 'content'));

                //wybieranie odpowiedniego radio na podstawie RFD
                if (RFD::get('newsletter', 'receivers') == 'klienci') {
                    $t->addSearchReplace('RFD_radio_klienci', 'checked="checked"');
                    $t->addSearchReplace('RFD_radio_uslugodawcy', '');
                    $t->addSearchReplace('RFD_radio_wszyscy', '');
                } elseif (RFD::get('newsletter', 'receivers') == 'uslugodawcy') {
                    $t->addSearchReplace('RFD_radio_uslugodawcy', 'checked="checked"');
                    $t->addSearchReplace('RFD_radio_klienci', '');
                    $t->addSearchReplace('RFD_radio_wszyscy', '');
                } elseif (RFD::get('newsletter', 'receivers') == 'wszyscy') {
                    $t->addSearchReplace('RFD_radio_wszyscy', 'checked="checked"');
                    $t->addSearchReplace('RFD_radio_klienci', '');
                    $t->addSearchReplace('RFD_radio_uslugodawcy', '');
                }

                RFD::clear('newsletter');
            }
        }

        //płatności
        elseif (isset($_GET['w']) && $_GET['w'] == 'inne' && (isset($_GET['a']) && $_GET['a'] == 'platnosci')) {
            $t = new Template(Pathes::getPathTemplatePayment());
        }
        //CZYSTY SZABLON - w przypadku, gdy opcje nie pasują do wszystkich powyższych wyświetla się domyśny szablon baz wybranej zakładki
        else {
            $t = new Template(Pathes::getPathTemplateProfileAdmin());
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
