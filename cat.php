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
    $dbc = new DBC($sys);

    $content = '';
    // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
    // dla cat.php?c zlecenia, w przeciwnym przypadku usługi    
    if (isset($_GET['c'])) {
        $content = $tm->getCatalog('comms', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
        // wyświetlanie listy K,O,T dla zleceń z liczebnością w postaci: Kategoria(ilość), Obszar(ilość), Tematyka(ilość)
        // domyślnie dla usług
    } elseif (isset($_GET['ud'])) {

        $t = new Template('view/html/cat_ud.html');
        $t_lista = new Template('view/html/cat_ud_lista.html');

        if (strlen($_GET['ud']) == 0) {

            $sql = Query::getProfilePremiumCardsForCatalog();
            $r = $dbc->query($sql);

            while ($set = $r->fetch_assoc()) {
                STD::pre($set);
                if (strlen($set['nazwa']) > 0)
                    $t_lista->addSearchReplace('nazwa', $set['nazwa']);
                if (strlen($set['logo']) > 0)
                    $t_lista->addSearchReplace('logo', $set['logo']);
                if (strlen($set['www']) > 0)
                    $t_lista->addSearchReplace('www', $set['www']);
                if (strlen($set['opis']) > 0)
                    $t_lista->addSearchReplace('opis', $set['opis']);
            }
            $t->addSearchReplace('lista', $t_lista->getContent());
        } elseif (strlen($_GET['ud']) > 0) {

            $sql = Query::getProfileNamesForCatalog($_GET['ud']);
            $r = $dbc->query($sql);

            if ($dbc->affected_rows == 0) {
                $t->addSearchReplace('lista', '<h1>Brak dostawców na wskazaną literę.</h1>');
            } else {

                while ($set = $r->fetch_assoc()) {
                    if (strlen($set['nazwa']) > 0 && !is_null($set['nazwa']))
                        $t_lista->addSearchReplace('nazwa', $set['nazwa']);
                    else
                        $t_lista->addSearchReplace('nazwa', 'brak nazwy');

                    if (strlen($set['logo']) > 0 && !is_null($set['logo']))
                        $t_lista->addSearchReplace('logo', $set['logo']);
                    else
                        $t_lista->addSearchReplace('logo', 'default.png');

                    if (strlen($set['www']) > 0 && !is_null($set['www']))
                        $t_lista->addSearchReplace('www', $set['www']);
                    else
                        $t_lista->addSearchReplace('www', '');

                    if (strlen($set['opis']) > 0 && !is_null($set['opis']))
                        $t_lista->addSearchReplace('opis', $set['opis']);
                    else
                        $t_lista->addSearchReplace('opis', 'brak opisu');
                }
                $t->addSearchReplace('lista', $t_lista->getContent());
            }
        }




        $content = $t->getContent();
    } else {
        $content = $tm->getCatalog('servs', new DBC($sys), new CategoryManager(), new CommisionManager(), new ServiceManager());
    }

    $skrypty = '';
    $mt = $tm->getMainTemplate($sys, $content, BFEC::showAll(), $skrypty);
    echo $mt->getContent();
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>