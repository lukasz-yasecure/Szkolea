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

/* * *********************[ action = add_serv ]***************************************************************************
 *
 * 2011-11-09   dziala
 *
 * **********************[ action = add_serv_check ]*********************************************************************
 *
 * 2011-11-09 dziala
 *
 * ********************************************************************************************************************* */

if (!isset($_POST['add_serv'])) {
    try {
        $sys = new System('add_serv', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);
        $dbc = new DBC($sys);

                //sprawdzenie przed formularzem czy dostawca może dodawać usługi = ma wystarczającą ilość usług
        $pkgm = new PackageManager();
        $pkgm->pobierzInformacjePakietow($dbc, $u->getId_user());
        $pkgm->czyMoznaDodacUslugi();

        // blokada do uruchomienia platnosci
        //if($u->getId_user() != '87') BFEC::add('Dodawanie usług będzie dostępne, gdy zintegrujemy Szkolea.pl z płatnościami online! Prosimy o cierpliwość.', true, 'index.php');

        $tm = new TemplateManager();
        $cm = new CategoryManager();
        $c = $cm->getListOfAllKOTM($dbc, 'addServForm');
        $asft = $tm->getAddServFormTemplate($sys, $c);

        // to musi byc rozpisane na klase ladnie
        $skrypty = file_get_contents('temp/addserv.html');

        $mt = $tm->getMainTemplate($sys, $asft->getContent(), BFEC::showAll(), $skrypty);
        RFD::clear('addServForm');
        echo $mt->getContent();
    } catch (Exception $e) {
        $em = new EXCManager($e);
    }
} else {
    try {
        $sys = new System('add_serv_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $dbc = new DBC($sys);
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);

        // blokada do uruchomienia platnosci
        //if($u->getId_user() != '87') BFEC::add('Dodawanie usług będzie dostępne, gdy zintegrujemy Szkolea.pl z płatnościami online! Prosimy o cierpliwość.', true, 'index.php');

        $ud = new UserData();
        $sm = new ServiceManager();
        $s = $ud->getService();
        $s->setId_user($u->getId_user());
        $s = $sm->completeData($s, $dbc, new CategoryManager());

        //sprawdzenie po formularzu czy dostawca może dodawać usługi = ma wystarczającą ilość usług
        $pkgm = new PackageManager();
        $pkgm->pobierzInformacjePakietow($dbc, $u->getId_user());
        $pkgm->czyMoznaDodacUslugi();
        
        
        $s = $sm->saveServiceInDB($dbc, $s);
        BFEC::addm(BFEC::$m['add_serv'], $sys->getScriptServicePath($s->getId_serv()));
    } catch (Exception $e) {
        $em = new EXCManager($e);
    }
}
?>