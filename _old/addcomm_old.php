<?php
require_once('config_old.php');
require_once('view/top.php');

$cc = new CommControl();
//$cc->checkPrivileges();

if(!isset($_POST['add_comm']))
{
    //echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
    //pre($_SESSION);

    $cv = new CommView();
    echo $cv->getAddForm();

    // czyszczenie sesji do wywalenia pozniej
    unset($_SESSION['comm']);
    unset($_SESSION['comm_errors']);
}
else
{
    $cc->setSource($_POST);
    if($cc->getAllProgramFromSource())
    {
        $cc->setUID($ss->getUID());
        $c = new Commisions();
        $c->addComToDB($cc);
        $ss->setMessage('commadd');
        header('Location: comm.php?id='.$c->getCommID());
        exit();

        // powiadamiamy obserwatorow kategorii
        /*
        $o = new Observ();
        if(($c = $v->getCat()) !== false) $o->notifyObserversAboutNewStuffInCat($c);
        if(($c = $v->getSCat()) !== false) $o->notifyObserversAboutNewStuffInCat($c);
        if(($c = $v->getSSCat()) !== false) $o->notifyObserversAboutNewStuffInCat($c);
        if(($c = $v->getModul()) !== false) $o->notifyObserversAboutNewStuffInCat($c);
        */
    }
    else
    {
        $ss->setMessage('comm');
        header('Location: addcomm.php');
        exit();
    }
}

require_once('view/foot.php');
?>
