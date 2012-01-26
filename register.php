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

/***********************[ action = register_form ]***********************************************************************
 *
 * 2011-09-22
 *
 ***********************[ action = register_check & activation_send ]****************************************************
 *
 * 2011-09-22
 *
 **********************************************]***********************************************************************/

if(!isset($_POST['register']))
{
    try
    {
        $sys = new System('register_form', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys); // nowy manager przywilejow
        $pm->checkPrivileges($u);
        $tm = new TemplateManager(); // nowy manager szablonow
        $rft = $tm->getRegisterFormTemplate($sys);
        $mt = $tm->getMainTemplate($sys, $rft->getContent(), BFEC::showAll());
        RFD::clear('regForm');
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
    catch(NoDefinitionForAction $e) // PrivilegesManager
    {
        Log::System($e);
        $sys->getFatalError();
    }
    catch(UserIsLogged $e)
    {
        BFEC::add('nie mozesz byc zalogowany!', true, 'index.php');
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
}
else if(isset($_POST['register']))
{
    try
    {
        $sys = new System('register_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $dbc = new DBC($sys); // obiekt laczacy sie z baza
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys); // nowy manager przywilejow
        $pm->checkPrivileges($u);
        $ud = new UserData(); // operacje na danych od usera
        $rfd = $ud->getRegisterFormData(); // dane z register_form
        $um->checkIfEmailAvailable($dbc, $rfd->getEmail());
        $um->storeNewUserInDB($dbc, $rfd);
        BFEC::addm('rejestracja przebiegla pomyslnie');

        $sys = new System('activation_send', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $u = $um->getUserByEmail($dbc, $rfd->getEmail());
        $km = new KeyManager(); // nowy manager kluczy
        $km->prepareActivationKeys($u); // klucze aktywacji
        $am = new ActivationManager(); // nowy manager aktywacji
        $tm = new TemplateManager(); // nowy manager szablonow
        $amail = $am->getActivationMail($u, $tm->getActivationMailTemplate($sys, $am->getActivationLink($sys, $km->getActivationMainKey(), $km->getActivationControlKey()))); // link aktywujacy, szablon maila i caly mail
        $m = new Mailer();
        $m->sendActivationMail($sys, $amail);
        $km->storeActivationKey($dbc, $u);

        BFEC::addm('kliknij w link aktywujacy na maili zeby aktywowac konto', 'index.php');
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
    catch(NoDefinitionForAction $e) // PrivilegesManager
    {
        Log::System($e);
        $sys->getFatalError();
    }
    catch(UserIsLogged $e) // PrivilegesManager
    {
        BFEC::add('nie mozesz byc zalogowany!', true, 'index.php');
    }
    catch(ErrorsInRegisterForm $e) // UserData
    {
        BFEC::add('', true, 'register.php');
    }
    catch(EmailIsNotAvailable $e)
    {
        BFEC::add('Podany adres e-mail jest zajety!', true, 'register.php');
    }
    catch(DBQueryException $e) // UserManager, KeyManager
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
    catch(UMNoUser $e) // UserManager
    {
        Log::NoUser($e);
        $sys->getFatalError();
    }
    catch(UMTooManyUsers $e) // UserManager
    {
        Log::TooManyUsers($e);
        $sys->getFatalError();
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
    catch(NoPreparedActivationKeys $e) // KeyManager
    {
        Log::NoPreparedActivationKeys($e);
        $sys->getFatalError();
    }
    catch(MailDidNotSend $e) // Mailer
    {
        Log::MailDidNotSend($e);
        BFEC::add('nie udalo sie wyslac maila aktywujacego, trzeba sie zalogowac pozniej i wyslac jeszcze raz', true, 'index.php');
    }
}

?>
