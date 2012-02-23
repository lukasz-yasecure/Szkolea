<?php

$sysFile = 'engine/class.system.php';
$logFile = 'engine/class.log.php';
$logDir = 'logs/';

if (file_exists($sysFile) && file_exists($logFile)) {
    require_once($sysFile);
    require_once($logFile);
} else {
    date_default_timezone_set('Europe/Warsaw');
    file_put_contents($logDir . date('Ymd') . '_system_exc.log', date('Y-m-d H:i:s') . ' SYSTEM/LOG NIEDOSTEPNY!' . PHP_EOL, FILE_APPEND);
    exit('Strona niedostepna! Prosze sprobowac pozniej oraz skontaktowac sie z administratorem: admin@szkolea.pl !');
}

/* * *********************[ action = register_form ]***********************************************************************
 *
 * 2011-09-22
 *
 * **********************[ action = register_check & activation_send ]****************************************************
 *
 * 2011-09-22
 *
 * *********************************************]********************************************************************** */

if (!isset($_POST['register'])) {
    try {
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
    } catch (Exception $e) {
        $em = new EXCManager($e);
    }
} else if (isset($_POST['register'])) {
    try {
        $sys = new System('register_check', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
        $dbc = new DBC($sys); // obiekt laczacy sie z baza
        $sm = new SessionManager();
        $um = new UserManager();
        $pmgr = new PackageManager();
        $u = $um->getUserFromSession($sm);
        $pm = new PrivilegesManager($sys); // nowy manager przywilejow
        $pm->checkPrivileges($u);
        $ud = new UserData(); // operacje na danych od usera
        $rfd = $ud->getRegisterFormData(); // dane z register_form
        $um->checkIfEmailAvailable($dbc, $rfd->getEmail()); // sprawdzamy czy email dostepny
        RFD::clear('regForm'); // po udanej weryfikacji czyscimy RFD
        $um->storeNewUserInDB($dbc, $rfd);

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

        //jeśli użytkownik jest dostawcą dostaje automatycznie pierwszy pakiet podstawowy. jeśli wysypie się wysyłanie maila lub przyznawanie podstawowego pakietu wyjątek o złoszeniu się do admina
        if ($u->isDostawca()) {
            $pakiet = $pmgr->pobierzPakiet($dbc, 1);
            $pmgr->dodajPakietUzytkownikowi($dbc, $u->getId_user(), $pakiet);
        }
        
        BFEC::addm(MSG::registerComplete());
        BFEC::addm(MSG::activationMailSend(), Pathes::getScriptIndexPath());
    } catch (Exception $e) {
        $em = new EXCManager($e, 'register');
    }
}
?>
