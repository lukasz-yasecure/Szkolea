<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if(file_exists($sysFile) && file_exists($logFile))
{
    require_once($sysFile);
    require_once($logFile);
}
else
{
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir.date('Ymd').'_system_exc.log', date('Y-m-d H:i:s').' SYSTEM/LOG NIEDOSTEPNY!'.PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/***********************[ action = cron ]****************************************************************************
 *
 * 2011-03-05
 *
 ***********************************************************************************************************************/

try
{
    $sys = new System('cron', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $tm = new TemplateManager();
    $m = new Mailer();
    $dbc = new DBC($sys);
    
    
    $res = $dbc->query(Query::getCronComm()); // pobierana lista do zakończonych zleceń
    while($x = $res->fetch_object()) {
        $dbc->query(Query::setCronFinished($x->id_comm)); // zaznaczane zakończone zlecenia
    // wysyłane powiadomienie właścicielowi zlecenia
    $m->infoZakonczoneZlecenieWlasciciel($um->getUser($dbc, $x->id_user));
    // wysyłane powiadomienia dodanym do zlecenia
    $get_group = $dbc->query(Query::getGroupCommUsers($x->id_comm)); // pobierana lista dodanych do zlecenia
        while ($x = $get_group->fetch_object()) {
            $m->infoZakonczoneZlecenieDodane($um->getUser($dbc, $x->id_user));
        }
    $get_ofe = $dbc->query(Query::getOfferForCommAll($x->id_comm)); // pobierana lista wszystkich dostawców, którzy dodali ofertę
        while ($x = $get_ofe->fetch_object()) {
            $m->infoZakonczoneZlecenieOferty($um->getUser($dbc, $x->id_user));
        }

    }

}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>
