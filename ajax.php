<?php

require_once('config_old.php');
require_once('clsadm/class.admin.php');
require_once('engine/class.sc.php');

if(isset($_GET['get']))
{
    /*
     * odpytanie AJAX
     * odsylamy odpowiedz w formacie json
     */

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');

    if(!isset($_GET['id']) || empty($_GET['id']))
    {
        echo json_encode(array('error' => '1'));
        exit();
    }

    if($_GET['get'] == 'scat' && Valid::program(0, $_GET['id']))
    {
        $adm = new Admin();
        $t = $adm->getAllSubcatsFromDB($_GET['id']);
        echo json_encode($t);
    }
    else if($_GET['get'] == 'sscat' && Valid::program(1, $_GET['id']))
    {
        $adm = new Admin();
        $t = $adm->getAllSubsubcatsFromDB($_GET['id']);
        echo json_encode($t);
    }
    else if($_GET['get'] == 'moduls' && Valid::program(2, $_GET['id']))
    {
        $adm = new Admin();
        $t = $adm->getAllModulsFromDB($_GET['id']);
        echo json_encode($t);
    }
    else
    {
        echo json_encode(array('error' => '1'));
    }
    
    exit();
}

?>
