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

/***********************[ action = add_comm ]***************************************************************************
 *
 * 2011-10-11   podejscie nr 1
 * 2011-10-19   podejsce 2
 * 2011-11-08   juz prawie OK, jeszcze musze dolozyc dynamiczne dodawanie odpowiednich js/css w TemplateManagerze
 *
 ***********************[ action = add_comm_check ]*********************************************************************
 *
 * 2011-10-11   podejscie nr 1
 * 2011-10-19   podejscie 2
 * 2011-10-20   podejscie 3
 * 2011-11-08   dziala, zrobione na nowych klasach bez uzywania starych metod
 *
 ***********************************************************************************************************************/

if(!isset($_POST['add_comm']))
{
    try
    {
        $sys = new System('add_comm', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);
        $tm = new TemplateManager();
        $cm = new CategoryManager();
        $dbc = new DBC($sys);
        $c = $cm->getListOfAllKOTM($dbc, 'addCommForm');
        $acft = $tm->getAddCommFormTemplate($sys, $c);

        // to musi byc rozpisane na klase ladnie
        $skrypty = file_get_contents('temp/addcomm.html');

        $mt = $tm->getMainTemplate($sys, $acft->getContent(), BFEC::showAll(), $skrypty);
        RFD::clear('addCommForm');
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
    catch(UserIsNotLogged $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsNotLogged'], true, $sys->getScriptLoginPath());
    }
    catch(UserIsNotKlient $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsNotKlient'], true, $sys->getScriptIndexPath());
    }
    catch(UserIsNotActivated $e) // PM
    {
        BFEC::add(BFEC::$e['UM']['nieaktywowany'], true, $sys->getScriptIndexPath());
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
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
    catch(DBQueryException $e) // CM
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
}
else
{
    try
    {
        $sys = new System('add_comm_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $dbc = new DBC($sys);
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys);
        $p = $pm->checkPrivileges($u);
        $ud = new UserData();
        $cm = new CommisionManager();
        $c = $ud->getCommision();
        $c->setId_user($u->getId_user());
        $c = $cm->completeData($c, $dbc, new CategoryManager());
        $c = $cm->saveCommisionInDB($dbc, $c);
        BFEC::addm(BFEC::$m['add_comm'], $sys->getScriptCommisionPath($c->getId_comm()));
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
    catch(UserIsNotLogged $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsNotLogged'], true, $sys->getScriptLoginPath($sys->getScriptAddCommPath()));
    }
    catch(UserIsNotKlient $e) // PM
    {
        BFEC::add(BFEC::$e['PM']['UserIsNotKlient'], true, $sys->getScriptIndexPath());
    }
    catch(UserIsNotActivated $e) // PM
    {
        BFEC::add(BFEC::$e['UM']['nieaktywowany'], true, $sys->getScriptIndexPath());
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
    catch(ErrorsInAddCommForm $e)
    {
        BFEC::add('', true, $sys->getScriptAddCommPath());
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