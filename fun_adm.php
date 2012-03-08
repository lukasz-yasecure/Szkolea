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
    $sys = new System('admin', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $pm->checkPrivileges($u);
    $dbc = new DBC($sys);




    if (isset($_GET['what']) && isset($_GET['action']) && isset($_GET['ID']) && $_GET['what'] == 'comm' && $_GET['action'] == 'delete' && (Valid::isNatural($_GET['ID']))) {
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


    if (isset($_GET['what']) && isset($_GET['action']) && isset($_GET['ID']) && $_GET['what'] == 'user' && $_GET['action'] == 'ban' && (Valid::isNatural($_GET['id']))) {
        $id_user = $_GET['id'];

        $sql = Query::setUserBanned($id_user);
        $dbc->query($sql);
        if ($dbc->affected_rows >= 1) {
            BFEC::addm('Użytkownik zbanowany', 'profile.php?w=user');
            $mailer = new Mailer;

            $mailer->sendMail($um->getUser($dbc, $id_user)->getEmail(), 'szkolea@szkolea.pl', 'Zostałeś zablokowany w serwisie Szkolea.pl', file_get_contents('view/html/mail_user_ban.html'));
        }
        else
            BFEC::add("Nie udało się zbanować użytkownika.", true, 'profile.php?w=user');
    }

    if (isset($_POST) && count($_POST) > 0) {
        /*
         * dodawanie, usuwanie, edycja OBSZAROW TEMATYK MODULOW
         */

        //pre($_POST);
        $adm = new Admin();

        // OBSZARY
        if (isset($_POST['addscat']) && !empty($_POST['addscat']) && isset($_POST['id'])) {
            $lid = $adm->getLastID($_POST['id'], 1);
            $t = explode('_', $lid);
            $t[count($t) - 1] = ($t[count($t) - 1] + 1);
            $id = implode('_', $t);
            $adm->add($id, $_POST['addscat'], 1);
        } else if (isset($_POST['delscat']) && !empty($_POST['delscat']) && strpos($_POST['delscat'], '#') !== false) {
            $t = explode('#', $_POST['delscat']);
            $id = $t[1];
            $adm->del($id, 1);
            $adm->delDown($id, 1);
        } else if (isset($_POST['editscat']) && !empty($_POST['editscat']) && isset($_POST['id'])) {
            $id = $_POST['id'];
            $adm->update($id, $_POST['editscat'], 1);
        }
        // TEMATYKI
        else if (isset($_POST['addsscat']) && !empty($_POST['addsscat']) && isset($_POST['id'])) {
            $lid = $adm->getLastID($_POST['id'], 2);
            $t = explode('_', $lid);
            $t[count($t) - 1] = ($t[count($t) - 1] + 1);
            $id = implode('_', $t);
            $adm->add($id, $_POST['addsscat'], 2);
        } else if (isset($_POST['delsscat']) && !empty($_POST['delsscat']) && strpos($_POST['delsscat'], '#') !== false) {
            $t = explode('#', $_POST['delsscat']);
            $id = $t[1];
            $adm->del($id, 2);
            $adm->delDown($id, 2);
        } else if (isset($_POST['editsscat']) && !empty($_POST['editsscat']) && isset($_POST['id'])) {
            $id = $_POST['id'];
            $adm->update($id, $_POST['editsscat'], 2);
        }
        // MODULY
        else if (isset($_POST['addmoduls']) && !empty($_POST['addmoduls']) && isset($_POST['id'])) {
            $lid = $adm->getLastID($_POST['id'], 3);
            $t = explode('_', $lid);
            $t[count($t) - 1] = ($t[count($t) - 1] + 1);
            $id = implode('_', $t);
            $adm->add($id, $_POST['addmoduls'], 3);
        } else if (isset($_POST['delmoduls']) && !empty($_POST['delmoduls']) && strpos($_POST['delmoduls'], '#') !== false) {
            $t = explode('#', $_POST['delmoduls']);
            $id = $t[1];
            $adm->del($id, 3);
        } else if (isset($_POST['editmoduls']) && !empty($_POST['editmoduls']) && isset($_POST['id'])) {
            $id = $_POST['id'];
            $adm->update($id, $_POST['editmoduls'], 3);
        }

        header('Location: profile.php?w=kategorie#' . $id);
        exit();
    }
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>