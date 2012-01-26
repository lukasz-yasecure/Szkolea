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

/***********************[ action = login_check ]*************************************************************************
 *
 * 2011-10-10 podejscie nr 2
 *
 ***********************[ action = login ]*******************************************************************************
 *
 * 2011-10-10 calkowicie nowe logowanie na nowych klasach
 *
 ***********************************************************************************************************************/

if(isset($_POST['logging']))
{
    try
    {
        $sys = new System('login_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager(false);
        $dbc = new DBC($sys);
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);
        $ud = new UserData();
        $lfd = $ud->getLoginFormData();
        $um->verifyUser($dbc, $lfd);
        $u = $um->getUserByEmail($dbc, $lfd->getEmail());
        $u->setLoggedStatus(true);
        $um->storeUserInSession($sm, $u);
        if(!$u->isActivated()) BFEC::add(BFEC::$e['UM']['nieaktywowany']);
        RFD::clear('logForm');
        BFEC::addm(BFEC::$m['UM']['zalogowany'], $sm->getBackURL());
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
    catch(UserIsLogged $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsLogged'], true, $sys->getScriptIndexPath());
    }
    catch(SomeErrors $e)
    {
        BFEC::add('', true, $sys->getScriptLoginPath());
    }
    catch(DBQueryException $e)
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
    catch(InvalidUserValidation $e)
    {
        BFEC::add(BFEC::$e['UM']['InvalidUserValidation'], true, $sys->getScriptLoginPath());
    }
}
else
{
    try
    {
        $sys = new System('login', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager(false);
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);
        $ud = new UserData();
        $tm = new TemplateManager();
        $lft = $tm->getLoginFormTemplate($sys);
        $mt = $tm->getMainTemplate($sys, $lft->getContent(), BFEC::showAll());
        RFD::clear('logForm');
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
    catch(NoDefinitionForAction $e) // System
    {
        Log::System($e);
        $sys->getFatalError();
    }
    catch(UserIsLogged $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsLogged'], true, $sys->getScriptIndexPath());
    }
    catch(NoTemplateFile $e) // TM
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
}

?>