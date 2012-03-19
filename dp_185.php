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

/***********************[ action = dp_185 ]****************************************************************************
 *
 * 2011-03-19 ogólny  
 *
 ***********************************************************************************************************************/

try
{
    $sys = new System('dp_185', true); // nowy kontener ustawien aplikacji, laduje moduly (klasy)
    $sm = new SessionManager();
    $um = new UserManager();
    $u = $um->getUserFromSession($sm);
    
    
    // Sprawdzany adres IP. Dozwolone tylko IP dotpay
    $check=0;
    $ip=$_SERVER['REMOTE_ADDR'];
    if($ip=='195.150.9.37'){
        $check=1;
    }
    if($check!=1){
        echo "Wiadomość: Dostęp zabroniony!";
        Log::DotPay('BAD IP: ' . $_SERVER['REMOTE_ADDR']);
        exit;
    }
    if($_POST['t_status']==2 and $_POST['control']!=NULL){ // t_status: 1 - nowa, 2 - wykonana, 3 - odrzucona
        $control=$_POST['control'];
        if(is_numeric($control)==true){ // control = id faktury
        Log::DotPay('PAYMENT ACCEPTED: id=' .$control);
        echo 'OK'; // ma wyświetlić OK, po czym zaprzestaje nadawać potwierdzenia
        }
    }
    
}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>
