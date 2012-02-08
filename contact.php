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

/* * *********************[ action = XXX ]****************************************************************************
 *
 * 2011-1x-xx
 *
 * ********************************************************************************************************************* */

try {
    $sys = new System('contact', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    //$pm = new PrivilegesManager($sys);
    //$p = $pm->checkPrivileges($u);
    
    
    $skrypty = file_get_contents('temp/contact.html');
    
    $tm = new TemplateManager();
    $mt = $tm->getMainTemplate($sys, file_get_contents('view/html/contact.html'), BFEC::showAll(), $skrypty);
    echo $mt->getContent();

    if ((isset($_POST['submit']))) {
        $m = new Mailer();
        $v = new Valid();
        if ((mb_strlen($_POST['comment'])) > 0) {
            $email = false;

            if ((mb_strlen($_POST['email'])) > 0 && $v->email($_POST['email'])) {
                // email ok - wysylamy i zapisujemy w bazie
                $email = true;
            } else if ((mb_strlen($_POST['email'])) > 0 && !$v->email($_POST['email'])) {
                // email niepoprawny
                $_POST['email'] = 'nieodpisuj@szkolea.pl';
            } else {
                // nie ma email - wysylamy i zapisujemy w bazie
                $_POST['email'] = 'nieodpisuj@szkolea.pl';
            }

            $m->sendMail('podlewski.lukasz@gmail.com', $_POST['email'], 'Mail z portalu Szkolea.pl',$_POST['comment']);
            $dbc = new DBC($sys);
            $sql = 'INSERT INTO messages (emails, comments) VALUES (NULL, "' . $_POST["comment"] . '")';
            if ($email)
                $sql = str_replace('NULL', '"' . $_POST['email'] . '"', $sql);
            $dbc->query($sql);
            BFEC::addm(BFEC::$m['contact'], 'index.php');
        } else {
            BFEC::add(BFEC::$e['contact'], true, 'index.php');
        }
    }
} catch (Exception $e) {
    $em = new EXCManager($e);
}
?>