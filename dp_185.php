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
            $post = 'Control: '.$_POST['control'].', opis: '.$_POST['description'].', email: '.$_POST['email'].', status: '.$_POST['t_status'].', id: '.$_POST['t_id'].', md5: '.$_POST['md5'];
            $md5 = md5($salt.':57265:'.$_POST['control'].':'.$_POST['t_id'].':'.$_POST['amount'].':'.$_POST['email'].':::::'.$_POST['t_status']);
            if($md5 == $_POST['md5']) { // check MD5
                if($_POST['t_status'] == '2') { // wykonana
                    $dbc->query(Query::logDotPay('1',$_POST['control'],$post));   
                    $dbc->query(Query::updateDotPay($_POST['control']));   
                    echo "OK";
                } else { // 1 - nowa, 3 - odzrucona, 4, 5...
                    $dbc->query(Query::logDotPay('2',$_POST['control']),$post);   
                    echo "OK";
                }
            } else {
                $dbc->query(Query::logDotPay('3',$_POST['control'],$post));   
                exit;
            }
    }
    else {
        $dbc->query(Query::logDotPay('4','',$_SERVER['REMOTE_ADDR'])); // vars: type, urlc, info   
        exit;
    }
}
catch(Exception $e)
{
    $em = new EXCManager($e);
}

?>
