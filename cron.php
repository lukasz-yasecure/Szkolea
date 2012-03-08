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

/* * *********************[ action = cron ]****************************************************************************
 *
 * 2011-03-05
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('cron', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $tm = new TemplateManager();
    $m = new Mailer();
    $dbc = new DBC($sys);

    $maile = 0; // do testowania crona

    $res = $dbc->query(Query::getCronComm()); // pobierana lista do zakończonych zleceń
    if ($dbc->affected_rows > 0) {
        Log::cronTest('====== pobralem zlecen do zakonczenia: ' . $res->num_rows);
        while ($x = $res->fetch_object()) {
            $dbc->query(Query::setCronFinished($x->id_comm)); // zaznaczane zakończone zlecenia
            Log::cronTest('-zakonczylem zlecenie id: ' . $x->id_comm);
            // wysyłane powiadomienie właścicielowi zlecenia
            $m->infoZakonczoneZlecenieWlasciciel($um->getUser($dbc, $x->id_user));
            Log::cronTest('-wyslalem mail do wlasciciela zlecenia id: ' . $x->id_user);
            $maile++;
            // wysyłane powiadomienia dodanym do zlecenia
            $get_group = $dbc->query(Query::getGroupCommUsers($x->id_comm)); // pobierana lista dodanych do zlecenia
            if ($dbc->affected_rows > 0) {
                Log::cronTest('-dodanych do zlecenia jest: ' . $get_group->num_rows);
                while ($y = $get_group->fetch_object()) {
                    $m->infoZakonczoneZlecenieDodane($um->getUser($dbc, $y->id_user));
                    Log::cronTest('-wyslalem mail do dopisanego do zlecenia id: ' . $y->id_user);
                    $maile++;
                }
                $get_ofe = $dbc->query(Query::getOfferForCommAll($x->id_comm)); // pobierana lista wszystkich dostawców, którzy dodali ofertę
                if ($dbc->affected_rows > 0) {
                    Log::cronTest('-ofert do zlecenia jest: ' . $get_ofe->num_rows);
                    while ($z = $get_ofe->fetch_object()) {
                        $m->infoZakonczoneZlecenieOferty($um->getUser($dbc, $z->id_user));
                        Log::cronTest('-wyslalem mail do wlasciciela oferty przy zleceniu id: ' . $z->id_user);
                        $maile++;
                    }
                }
                else
                    Log::cronTest('-ofert do zlecenia: 0');
            }
            else
                Log::cronTest('-dodanych do zlecenia: 0');
            
            Log::cronTest('--- przy tym zleceniu wyslalem maili: ' . $maile);
            $maile = 0;
        }
    }
    else
        Log::cronTest('====== pobralem zlecen do zakonczenia: 0 !');
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>
