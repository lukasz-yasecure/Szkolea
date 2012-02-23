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

/***********************[ action = XXX ]****************************************************************************
 *
 * 2011-1x-xx
 *
 ***********************************************************************************************************************/

if(isset($_GET['what']) && isset($_GET['id']))
{
    $result = array('result' => 0);

    try
    {
        $sys = new System('observe', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);

        $ud = new UserData();
        if(($par = $ud->getObserveParamsFromURL()))
        {
            $om = new ObserveManager();
            if($om->addObserve(new DBC($sys), $u, $par)) $result['result'] = 1;
        }
    }
    catch(Exception $e) {
        //$em = new EXCManager($e);
    }

    echo json_encode($result);
}

exit();

try
{
    $sys = new System('observe', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $p = $pm->checkPrivileges($u);


}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>