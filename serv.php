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

/***********************[ action = serv ]*********************************************************************************
 *
 * 2011-11-10   dziala
 *
 *************************************************************************************************************************/

if(isset($_GET['id']))
{
    try
    {
        $sys = new System('serv', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $pm->checkPrivileges($u);
        $ud = new UserData();
        $sm = new ServiceManager();
        $dbc = new DBC($sys);
        $s = $sm->getService($dbc, $ud->getIDFromURL());
        $tm = new TemplateManager();
        $st = $tm->getServTemplate($sys, $s, $u->isLogged());
        $mt = $tm->getMainTemplate($sys, $st->getContent(), BFEC::showAll(), file_get_contents('temp/easy.html'));
        echo $mt->getContent();
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
    catch(NoDefinitionForAction $e) // System, PM
    {
        Log::System($e);
        $sys->getFatalError();
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
    catch(InvalidID $e) // UD
    {
        Log::HackingAttempt($e);
        BFEC::add('', true, $sys->getScriptIndexPath());
    }
    catch(DBConnectException $e) // DBC
    {
        Log::DBConnect($e);
        $sys->getFatalError();
    }
    catch(DBCharsetException $e) // DBC
    {
        Log::DBCharset($e);
        $sys->getFatalError();
    }
    catch(DBQueryException $e) // DBC
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
}

?>