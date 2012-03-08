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

/* * *********************[ action = cat ]****************************************************************************
 *
 * 2012-02-17 dzialaja katalogi zlecen i uslug
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('cat', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $tm = new TemplateManager();


    $content = '';
    // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
    // dla cat.php?c zlecenia, w przeciwnym przypadku usługi    
    if (isset($_GET['c'])) {
        $content = $tm->getCatalog('comms', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
        // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
        // domyślnie dla usług
    } elseif (isset($_GET['ud'])) {
        $dbc = new DBC($sys);
        $t = new Template('view/html/cat_ud.html');

        //szablony dla różnych wersji wyświetlania wizytówek wg. schematu: n- nazwa, l-logo, w-adres www, o-opis, all-wszystko
        $t_n = new Template('view/html/cat_ud_lista_n.html');
        $t_n_l = new Template('view/html/cat_ud_lista_n_l.html');
        $t_n_w_l = new Template('view/html/cat_ud_lista_n_w_l.html');
        $t_n_o_w = new Template('view/html/cat_ud_lista_n_o_w.html');
        $t_n_o = new Template('view/html/cat_ud_lista_n_o.html');
        $t_n_w = new Template('view/html/cat_ud_lista_n_w.html');
        $t_n_o_l = new Template('view/html/cat_ud_lista_n_o_l.html');
        $t_all = new Template('view/html/cat_ud_lista_all.html');
        $t_premium = new Template('view/html/cat_ud_premium.html');

        //szablony dla listy literek i cyfr
        $t_menu_litery = new Template('view/html/cat_ud_menu_litery.html');
        $t_menu_cyfry = new Template('view/html/cat_ud_menu_cyfry.html');
        $menu = '';     //zmienna pomocnicza przy generowaniu menu z literkami i cyframi
        $premium = '';  //zmienna pomocnicza dla premiowanych usługodawców
        //zbiory znaków
        $alfabet = Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $cyfry = Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        //generowanie listy literek w szablonie
        for ($i = 0; $i < count($alfabet); $i++) {
            $t_menu_litery->addSearchReplace('literka', $alfabet[$i]);
            $menu .= $t_menu_litery->getContent();
            $t_menu_litery->clearSearchReplace();
        }
        //generowanie listy cyferek w szablonie
        for ($i = 0; $i < count($cyfry); $i++) {
            $t_menu_cyfry->addSearchReplace('cyferka', $cyfry[$i]);
            $menu .= $t_menu_cyfry->getContent();
            $t_menu_cyfry->clearSearchReplace();
        }
        $t->addSearchReplace('menu', $menu);


        //Wyświetlanie na domyślnej stronie Katalogu usługodawców listy wszystkich wyróżnionych usługodawców
        if (strlen($_GET['ud']) == 0) {

            $sql = Query::getProfilePremiumCardsForCatalog('');
            $r = $dbc->query($sql);

            //przejście przez to co otrzymaliśmy z bazy z dopasowanie dla konretnego szablonu wyświetlania wizytówki względem tego co użytkownik uzupełnił
            while ($set = $r->fetch_assoc()) {
                if (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                    $t_n->addSearchReplace('nazwa', $set['nazwa']);
                    $premium .= $t_n->getContent();
                    $t_n->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                    $t_n_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_l->addSearchReplace('logo', $set['logo']);
                    $premium .= $t_n_l->getContent();
                    $t_n_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                    $t_n_w_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_w_l->addSearchReplace('logo', $set['logo']);
                    $t_n_w_l->addSearchReplace('www', $set['www']);
                    $premium .= $t_n_w_l->getContent();
                    $t_n_w_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                    $t_n_o_w->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o_w->addSearchReplace('www', $set['www']);
                    $t_n_o_w->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o_w->getContent();
                    $t_n_o_w->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                    $t_n_o->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o->getContent();
                    $t_n_o->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                    $t_n_w->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_w->addSearchReplace('www', $set['www']);
                    $premium .= $t_n_w->getContent();
                    $t_n_w->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                    $t_n_o_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o_l->addSearchReplace('logo', $set['logo']);
                    $t_n_o_l->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o_l->getContent();
                    $t_n_o_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                    $t_all->addSearchReplace('nazwa', $set['nazwa']);
                    $t_all->addSearchReplace('logo', $set['logo']);
                    $t_all->addSearchReplace('www', $set['www']);
                    $t_all->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_all->getContent();
                    $t_all->clearSearchReplace();
                }
            }


            $t_premium->addSearchReplace('lista', $premium);
            $t->addSearchReplace('lista_premium', $t_premium->getContent());
            $t->addSearchReplace('lista', '');
        } elseif (strlen($_GET['ud']) == 1) {
            //Wyświetlanie na stronie Katalogu usługodawców dla konkretnej litery listy wyróżnionych usługodawców na daną literę

            $sql = Query::getProfilePremiumCardsForCatalog($_GET['ud']);
            $r = $dbc->query($sql);

            //przejście przez to co otrzymaliśmy z bazy z dopasowanie dla konretnego szablonu wyświetlania wizytówki względem tego co użytkownik uzupełnił
            while ($set = $r->fetch_assoc()) {
                if (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                    $t_n->addSearchReplace('nazwa', $set['nazwa']);
                    $premium .= $t_n->getContent();
                    $t_n->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                    $t_n_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_l->addSearchReplace('logo', $set['logo']);
                    $premium .= $t_n_l->getContent();
                    $t_n_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                    $t_n_w_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_w_l->addSearchReplace('logo', $set['logo']);
                    $t_n_w_l->addSearchReplace('www', $set['www']);
                    $premium .= $t_n_w_l->getContent();
                    $t_n_w_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                    $t_n_o_w->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o_w->addSearchReplace('www', $set['www']);
                    $t_n_o_w->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o_w->getContent();
                    $t_n_o_w->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                    $t_n_o->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o->getContent();
                    $t_n_o->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                    $t_n_w->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_w->addSearchReplace('www', $set['www']);
                    $premium .= $t_n_w->getContent();
                    $t_n_w->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                    $t_n_o_l->addSearchReplace('nazwa', $set['nazwa']);
                    $t_n_o_l->addSearchReplace('logo', $set['logo']);
                    $t_n_o_l->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_n_o_l->getContent();
                    $t_n_o_l->clearSearchReplace();
                } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                    $t_all->addSearchReplace('nazwa', $set['nazwa']);
                    $t_all->addSearchReplace('logo', $set['logo']);
                    $t_all->addSearchReplace('www', $set['www']);
                    $t_all->addSearchReplace('opis', $set['opis']);
                    $premium .= $t_all->getContent();
                    $t_all->clearSearchReplace();
                }
            }
            $t_premium->addSearchReplace('lista', $premium);
            $t->addSearchReplace('lista_premium', $t_premium->getContent());


            //Wyświetlanie na stronie Katalogu usługodawców dla konkretnej litery listy usługodawców (niewyróznionych)
            $sql = Query::getProfileCardsForCatalog($_GET['ud']);
            $r = $dbc->query($sql);

            //przypadek braku usługodawców na daną literę
            if ($dbc->affected_rows == 0) {
                $t->addSearchReplace('menu', $menu);
                $t->addSearchReplace('lista', '<h1>Brak dostawców na wskazaną literę.</h1>');
            } else {

                //przejście przez to co otrzymaliśmy z bazy z dopasowanie dla konretnego szablonu wyświetlania wizytówki względem tego co użytkownik uzupełnił
                while ($set = $r->fetch_assoc()) {
                    if ($set['premium'] == '') { //sprawdzenie czy użytkownik został oznaczony jako premium (ma aktywny 5 pakiet) - bierzemy tych bez premium, aby nie dublować wyróżnionych wcześniej
                        if (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                            $t_n->addSearchReplace('nazwa', $set['nazwa']);
                            $content .= $t_n->getContent();
                            $t_n->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) == 0) {

                            $t_n_l->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_l->addSearchReplace('logo', $set['logo']);
                            $content .= $t_n_l->getContent();
                            $t_n_l->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                            $t_n_w_l->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_w_l->addSearchReplace('logo', $set['logo']);
                            $t_n_w_l->addSearchReplace('www', $set['www']);
                            $content .= $t_n_w_l->getContent();
                            $t_n_w_l->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                            $t_n_o_w->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_o_w->addSearchReplace('www', $set['www']);
                            $t_n_o_w->addSearchReplace('opis', $set['opis']);
                            $content .= $t_n_o_w->getContent();
                            $t_n_o_w->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                            $t_n_o->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_o->addSearchReplace('opis', $set['opis']);
                            $content .= $t_n_o->getContent();
                            $t_n_o->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) == 0 && strlen($set['www']) > 0 && strlen($set['opis']) == 0) {

                            $t_n_w->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_w->addSearchReplace('www', $set['www']);
                            $content .= $t_n_w->getContent();
                            $t_n_w->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) == 0 && strlen($set['opis']) > 0) {

                            $t_n_o_l->addSearchReplace('nazwa', $set['nazwa']);
                            $t_n_o_l->addSearchReplace('logo', $set['logo']);
                            $t_n_o_l->addSearchReplace('opis', $set['opis']);
                            $content .= $t_n_o_l->getContent();
                            $t_n_o_l->clearSearchReplace();
                        } elseif (strlen($set['nazwa']) > 0 && strlen($set['logo']) > 0 && strlen($set['www']) > 0 && strlen($set['opis']) > 0) {

                            $t_all->addSearchReplace('nazwa', $set['nazwa']);
                            $t_all->addSearchReplace('logo', $set['logo']);
                            $t_all->addSearchReplace('www', $set['www']);
                            $t_all->addSearchReplace('opis', $set['opis']);
                            $content .= $t_all->getContent();
                            $t_all->clearSearchReplace();
                        }
                    }
                }

                $t->addSearchReplace('lista', $content);
            }
        }
    } else {
        $content = $tm->getCatalog('servs', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
    }

    $skrypty = '';
    $mt = $tm->getMainTemplate($sys, $t->getContent(), BFEC::showAll(), $skrypty);
    echo $mt->getContent();
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>