<?php

require_once('config.php');
require_once('view/top.php');

$rc = new RegControl();
$rc->checkPrivileges();

if(!isset($_POST['register']))
{
    header('Location: log.php');
    exit();
}
else
{
    $rc->setSource($_POST);
    //pre($_POST);
    if($rc->getAllDataFromSource())
    {
        $r = new Register();
        $wynik = $r->checkIfEMailInDB($rc);

        if(is_null($wynik))
        {
            dpr('error1');
        }
        else if($wynik)
        {
            $wynik = $r->checkIfActivated();

            if($wynik)
            {
                dpr('aktywowany - error');
            }
            else
            {
                $wynik = $r->checkIfActivationExpired();

                if($wynik)
                {
                    //dpr('przedawniony');
                    $r->clearOldActivation();
                }
                else
                {
                    dpr('do aktywacji - error');
                }
            }
        }
        else
        {
            //dpr('nie w bazie');
        }

        if($r->registerUser($rc))
        {
            $r->newActivation();
            //echo $_SESSION['alink'];
            $ss->setMessage('registered');
            header('Location: index.php');
            exit();
        }
        else dpr('error2');
    }
    else
    {
        dpr('formularz - error');
    }
}

require_once('view/foot.php');

?>