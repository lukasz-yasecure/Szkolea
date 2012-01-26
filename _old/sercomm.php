<?php

require_once('config.php');

$ss = new SSMan();

$vw = new View();
$vw->debug();
$vw->getHTMLTop();
$vw->showMessages();
$vw->getHTMLMenu();

if(!isset($_POST['ser_comm']))
{
    echo $vw->commGetSearchForm();
}
else
{
    // jesli kliknie sie szukaj bez zmieniania pol to wyswietla sie wszystko = false

    $v = new Valid();

    $sql = $v->searchGetAll($_POST);

    if($sql !== false)
    {
        //dpr($sql);
        $com = new Commisions();
        $com->getSelectedCommsFromDB($sql);
    }
    else
    {
        // wyswietl wszystko
        $com = new Commisions();
        $com->getAllCommsFromDB();
    }
}

$vw->getHTMLFoot();

?>