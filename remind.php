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

/***********************[ action = remind_send ]***********************************************************************
 *
 * 2011-09-21 obecna wersja
 *
 ***********************[ action = remind_check ]***********************************************************************
 *
 * 2011-09-21 obecna wersja
 *
 ***********************[ action = remind_pass_change ]***********************************************************************
 *
 * 2011-09-21 obecna wersja
 *
 ***********************[ action = remind_form ]***********************************************************************
 *
 * 2011-09-21 obecna wersja
 *
 **********************************************]***********************************************************************/

if(isset($_POST['remind']))
{
    try
    {
        $sys = new System('remind_send', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $dbc = new DBC($sys); // obiekt laczacy sie z baza
        $sm = new SessionManager(); // operacje na sesji
        $ud = new UserData(); // operacje na danych od usera
        $rfd = $ud->getRemindFormData(); // dane z formularza
        $um = new UserManager(); // nowy manager userow
        $u = $um->getUserByEmail($dbc, $rfd->getEmail()); // pobieramy usera
        $um->storeUserInSession($sm, $u);
        $km = new KeyManager(); // manager kluczy
        $rm = new RemindManager(); // manager reminda - link, mail
        $tm = new TemplateManager(); // manager szablonow
        $rmail = $rm->getRemindMail($u, $tm->getRemindMailTemplate($sys, $rm->getRemindLink($sys, $u, $km->getRemindKey($u)))); // remind mail, szablon z linkiem i kluczem
        $m = new Mailer();
        $m->sendRemindMail($sys, $rmail);
        BFEC::addm('wyslane', 'log.php');
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
    catch(NoEmail $e) // UserData
    {
        BFEC::add('nie podales maila', true, 'remind.php');
    }
    catch(InvalidEmail $e) // UserData
    {
        BFEC::add('niepoprawny mail', true, 'remind.php');
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
    catch(UMNoUser $e) // UserManager
    {
        BFEC::add('brak maila w bazie!', true, 'remind.php');
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
    catch(MailDidNotSend $e) // Mailer
    {
        Log::MailDidNotSend($e);
        BFEC::add('blad wewnetrzny! sprobuj pozniej!', true, 'remind.php');
    }
}
else if(isset($_GET['check']))
{
    try
    {
        $sys = new System('remind_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $sm->storeQueryString(); // zapamietuje query string
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys); // nowy manager przywilejow
        $pm->checkPrivileges($u);
        $ud = new UserData(); // operacje na danych od usera
        $rmd = $ud->getRemindMailData(); // dane z formularza
        $u->setEmail($rmd->getEmail()); // ustawiam aktualnemu userowi maila z linka (CHYBA OK)
        $km = new KeyManager(); // manager kluczy
        $km->checkRemindKey($rmd, $u);
        $tm = new TemplateManager(); // nowy manager szablonow
        $pcft = $tm->getPasswordChangeFormTemplate($sys); // nowy form do zmiany passa
        $mt = $tm->getMainTemplate($sys, $pcft->getContent(), BFEC::showAll());
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
    catch(UserIsLogged $e) // PrivilegesManager
    {
        BFEC::add('nie mozesz byc zalogowany!', true, 'index.php');
    }
    catch(NoEmail $e) // UserData
    {
        BFEC::add('zly mail w linku', true, 'remind.php');
    }
    catch(InvalidEmail $e) // UserData
    {
        BFEC::add('zly mail w linku', true, 'remind.php');
    }
    catch(NoKey $e) // UserData
    {
        BFEC::add('zly kod w linku', true, 'remind.php');
    }
    catch(WrongRemindKey $e) // KeyManager
    {
        BFEC::add('kod jest bledny', true, 'remind.php');
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
}
else if(isset($_POST['remind_pass_change']))
{
    try
    {
        $sys = new System('remind_pass_change', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $dbc = new DBC($sys); // obiekt laczacy sie z baza
        $sm = new SessionManager(); // operacje na sesji
        $ud = new UserData(); // operacje na danych od usera
        $pcfd = $ud->getPasswordChangeFormData(); // dane z forma
        $um = new UserManager(); // nowy manager userow
        $u = $um->getUserFromSession($sm);
        $u = $um->getUserByEmail($dbc, $u->getEmail());
        $um->storeUserInSession($sm, $u);
        $um->updatePasswordInDB($dbc, $u, $pcfd);
        BFEC::addm('haslo zostalo zmienione', 'log.php');
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
    catch(InsecurePassword $e) // UserData
    {
        BFEC::add('hasło nie spełnia norm', true, 'remind.php?'.$sm->getQueryString());
    }
    catch(PasswordsDontMatch $e) // UserData
    {
        BFEC::add('hasła nie pasuja', true, 'remind.php?'.$sm->getQueryString());
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
    catch(DBQueryException $e) // UserManager
    {
        Log::DBQuery($e);
        $sys->getFatalError();
    }
}
else
{
    try
    {
        $sys = new System('remind_form', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $sm = new SessionManager();
        $um = new UserManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys); // nowy manager przywilejow
        $pm->checkPrivileges($u);
        $tm = new TemplateManager(); // nowy manager szablonow
        $rft = $tm->getRemindFormTemplate($sys); // nowy szablon remind form
        $mt = $tm->getMainTemplate($sys, $rft->getContent(), BFEC::showAll());
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
    catch(UserIsNotLogged $e)
    {
        BFEC::add('musisz byc zalogowany!', true, 'log.php');
    }
    catch(UserIsNotDostawca $e)
    {
        BFEC::add('musisz byc dostawca', true, 'index.php');
    }
    catch(UserIsNotKlient $e)
    {
        BFEC::add('musisz byc klientem', true, 'index.php');
    }
    catch(UserIsNotAdmin $e)
    {
        BFEC::add('dostep tylko dla admina', true, 'index.php');
    }
    catch(UserIsNotActivated $e)
    {
        BFEC::add('musisz miec aktywowane konto', true, 'index.php');
    }
    catch(UserIsActivated $e)
    {
        BFEC::add('twoje konto nie moze byc aktywowane', true, 'index.php');
    }
    catch(NoTemplateFile $e) // TemplateManager
    {
        Log::NoTemplateFile($e);
        $sys->getFatalError();
    }
}

?>
