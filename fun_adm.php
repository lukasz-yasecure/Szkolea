<?php

/* test !!!!! */

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

/* * *********************[ action = XXX ]****************************************************************************
 *
 * 2011-1x-xx
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('fun_adm', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $pm->checkPrivileges($u);
    $dbc = new DBC($sys);




    if ($_GET['what'] == 'comm' && $_GET['action'] == 'delete' && (Valid::isNatural($_GET['ID']))) {
        $id_comm = $_GET['ID'];

        $sql = Query::deleteComm('commisions', $id_comm);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1)
            BFEC::addm('Zlecenie usunięte.');
        else
            BFEC::add("Nie udało się usunąć zlecenia", true, 'profile.php?w=comms');

        $sql = Query::deleteComm('commisions_group', $id_comm);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1)
            BFEC::addm('Usunięto dopisane osoby: ' . $dbc->affected_rows);
        else
            BFEC::add("Nie udało się usunąć dopisanych osób", true, 'profile.php?w=comms');

        $sql = Query::deleteComm('comm_moduls', $id_comm);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1)
            BFEC::addm('Usunięto moduły: ' . $dbc->affected_rows);
        else
            BFEC::add("Nie udało się usunąć modułów", true, 'profile.php?w=comms');

        $sql = Query::deleteComm('commisions_ofe', $id_comm);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1)
            BFEC::addm('Usunięto oferty: ' . $dbc->affected_rows);
        else
            BFEC::addm('Nie usunięto ofert.');

        BFEC::addm('', 'profile.php?w=comms');
    }


    if ($_GET['what'] == 'user' && $_GET['action'] == 'ban' && (Valid::isNatural($_GET['id']))) {
        $id_user = $_GET['id'];

        $sql = Query::setUserBanned($id_user);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1) {
            BFEC::addm('Użytkownik zbanowany', true, 'profile.php?w=user');
            $mailer = new Mailer;

            $mailer->sendMail($um->getUser($dbc, $id_user)->getEmail(), 'szkolea@szkolea.pl', 'Zostałeś zablokowany w serwisie Szkolea.pl', file_get_contents('view/html/mail_user_ban.html'));
        }
        else
            BFEC::add("Nie udało się zbanować użytkownika.", true, 'profile.php?w=user');
    }
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>