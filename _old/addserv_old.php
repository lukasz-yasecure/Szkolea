<?php

require_once('config.php');
require_once('view/top.php');

$sc = new ServControl();
//$sc->checkPrivileges();

if(!isset($_POST['add_serv']))
{
    $sv = new ServView();
    echo $sv->getAddForm();
}
else
{
    $sc->setSource($_POST);
    if($sc->getAllProgramFromSource())
    {
        $sc->setUID($ss->getUID());
        $s = new Services();
        $s->addServiceToDB($sc);
        $ss->setMessage('servadd');
        header('Location: serv.php?id='.$s->getServID());
        exit();
    }
    else
    {
        $ss->setMessage('serv');
        header('Location: addserv.php');
        exit();
    }

    // OBSERVERS
}

require_once('view/foot.php');

?>