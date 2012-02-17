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

/***********************[ action = logout ]***********************************************************************
 *
 * 2011-09-30
 * 2011-10-10   poprawiony BFEC
 *
 **********************************************]***********************************************************************/

try
{
    $sys = new System('logout', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $dbc = new DBC($sys);
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    $u->setLoggedStatus(false);
    $um->storeUserInSession($sm, $u);
    $sm->logoutUser();
    BFEC::addm(BFEC::$m['UM']['wylogowany'], $sys->getScriptIndexPath());
}
catch(BasicModuleDoesNotExist $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}
catch(ModuleDoesNotExist $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}
catch(NoDefinitionForAction $e) // System
{
    Log::System($e);
    $sys->getFatalError();
}

?>