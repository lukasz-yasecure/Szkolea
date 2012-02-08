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

try
{
    $sys = new System('jtd', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $pm = new PrivilegesManager($sys);
    $p = $pm->checkPrivileges($u);
    
    $skrypty = file_get_contents('temp/jtd.html');
    
    $tm = new TemplateManager();
    $mt = $tm->getMainTemplate($sys, file_get_contents('view/html/jtd.html'), BFEC::showAll(), $skrypty);
    echo $mt->getContent();
}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>