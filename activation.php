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

/***********************[ action = activation_check ]*********************************************************************
 *
 * 2011-09-26   caly proces dokonczylem, trzeba zrobic ponowne wysylanie aktywacji jak komus sie nie wysle/nie dojdzie
 * 2011-10-10   activation_check - nie trzeba byc niezalogowanym
 * 2011-11-04   wylogowanie usera po aktywacji, zeby mogl sie zalogowac (mogl byc zalogowany na nieaktywnym koncie jesli nie byl to nic sie nie dzieje)
 *
 ***********************[ action = activation_resend ]********************************************************************
 *
 * 2011-10-11   jeszcze nie działa
 *
 *************************************************************************************************************************/

if(isset($_GET['k'], $_GET['c']))
{
    try
    {
        $sys = new System('activation_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $sm->storeQueryString(); // zapamietuje query string
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $ud = new UserData(); // operacje na danych od usera
        $amd = $ud->getActivationFormData();
        $amk = new ActivationMainKey();
        $amk->setMainKeyCode($amd->getMkey());
        $ack = new ActivationControlKey($amk);
        $km = new KeyManager();
        $km->checkActivationControlKey($ack, $amd->getCkey());
        $dbc = new DBC($sys);
        $id_user = $um->activateUser($sys, $dbc, $amk);
        $km->deleteKeyForUser($dbc, $id_user);
        $sm->logoutUser();
        BFEC::addm('konto zostalo aktywowane - mozesz sie zalogowac', 'index.php');
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
    catch(NoKey $e) // UserData
    {
        BFEC::add('zly kod w linku', true, 'remind.php');
    }
    catch(InvalidKey $e) // KeyManager
    {
        BFEC::add('kod jest bledny - konto nie zostalo aktywowane', true, 'index.php');
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
    catch(DBQueryException $e) // UserManager
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
    catch(NoActivationKey $e) // UserManager
    {
        BFEC::add('nie ma takiego kodu - konto nie zostanie aktywowane', true, 'index.php');
    }
    catch(ActivationExpired $e)
    {
        try
        {
            BFEC::add('aktywacja się przedawniła - zarejestruj się jeszcze raz');
            $um->deleteUser($dbc, $e->getMessage());
            $um->deleteKeyForUser($dbc, $e->getMessage());
            BFEC::add('', true, 'index.php');
        }
        catch(DBQueryException $e) // UserManager
        {
            Log::DBQuery($e);
            $sys->getFatalError();
        }
    }
}
else if(isset($_GET['resend']))
{
    try
    {
        $sys = new System('activation_resend', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $tm = new TemplateManager();
        $mt = $tm->getMainTemplate($sys, 'Opcja chwilowo niedostępna.', BFEC::showAll());
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
    catch(NoTemplateFile $e) // TM
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
}


?>
