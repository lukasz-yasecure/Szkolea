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
    $dbc = new DBC($sys);
    
    if($_SERVER['REMOTE_ADDR'] == '195.150.9.37') { // check IP
            $salt = '9ETs0gaZNS1ATAJx';
            $md5 = md5($salt.':57265:'.$_POST['control'].':'.$_POST['t_id'].':'.$_POST['amount'].'::::::'.$_POST['t_status']);
            if($md5 == $_POST['md5']) { // check MD5
                if($_POST['t_status'] == '2') { // wykonana
                    $dbc->query(Query::logDotPay('1',int($_POST['control']),''));   
                    $dbc->query(Query::updateDotPay(int($_POST['control'])));   
                    echo "OK";
                } else { // 1 - nowa, 3 - odzrucona, 4, 5...
                    $dbc->query(Query::logDotPay('2',int($_POST['control']),''));   
                    echo "OK";
                }
            } else {
                $dbc->query(Query::logDotPay('3',int($_POST['control']),''));   
                exit;
            }
    }
    else {
        $dbc->query(Query::logDotPay('4','',$_SERVER['REMOTE_ADDR'])); // vars: type, urlc, info   
        exit;
    }
/*
    if($check!=1){
        echo "Wiadomość: Dostęp zabroniony!";
        exit;
    }
    if($_POST['t_status']==2 and $_POST['control']!=NULL){ // t_status: 1 - nowa, 2 - wykonana, 3 - odrzucona
        $control=$_POST['control'];
        if(is_numeric($control)==true){ // control = id faktury
        Log::DotPay('PAYMENT ACCEPTED: id=' .$control);
        echo 'OK'; // ma wyświetlić OK, po czym zaprzestaje nadawać potwierdzenia
        }
    }
*/    
}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>
