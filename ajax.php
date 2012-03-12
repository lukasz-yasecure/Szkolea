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

/* * *********************[ action = XXX ]****************************************************************************
 *
 * 2011-1x-xx
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('ajax', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $p = $pm->checkPrivileges($u);

    if (isset($_GET['get'])) {
        /*
         * odpytanie AJAX
         * odsylamy odpowiedz w formacie json
         */

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode(array('error' => '1'));
            exit();
        }

        if ($_GET['get'] == 'scat' && Valid::program(0, $_GET['id'])) {
            $adm = new Admin();
            $t = $adm->getAllSubcatsFromDB($_GET['id']);
            echo json_encode($t);
        } else if ($_GET['get'] == 'sscat' && Valid::program(1, $_GET['id'])) {
            $adm = new Admin();
            $t = $adm->getAllSubsubcatsFromDB($_GET['id']);
            echo json_encode($t);
        } else if ($_GET['get'] == 'moduls' && Valid::program(2, $_GET['id'])) {
            $adm = new Admin();
            $t = $adm->getAllModulsFromDB($_GET['id']);
            echo json_encode($t);
        } else {
            echo json_encode(array('error' => '1'));
        }

        exit();
    }
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>
