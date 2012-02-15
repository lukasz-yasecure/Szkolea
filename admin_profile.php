<?php
require_once('config_old.php');
require_once('clsadm/class.admin.php');
require_once('engine/class.sc.php');

if(isset($_POST) && count($_POST) > 0)
{
    /*
     * dodawanie, usuwanie, edycja OBSZAROW TEMATYK MODULOW
     */

    //pre($_POST);
    $adm = new Admin();

    // OBSZARY
    if(isset($_POST['addscat']) && !empty($_POST['addscat']) && isset($_POST['id']))
    {
        $lid = $adm->getLastID($_POST['id'], 1);
        $t = explode('_', $lid);
        $t[count($t)-1] = ($t[count($t)-1]+1);
        $id = implode('_', $t);
        $adm->add($id, $_POST['addscat'], 1);
    }
    else if(isset($_POST['delscat']) && !empty($_POST['delscat']) && strpos($_POST['delscat'], '#') !== false)
    {
        $t = explode('#', $_POST['delscat']);
        $id = $t[1];
        $adm->del($id, 1);
        $adm->delDown($id, 1);
    }
    else if(isset($_POST['editscat']) && !empty($_POST['editscat']) && isset($_POST['id']))
    {
        $id = $_POST['id'];
        $adm->update($id, $_POST['editscat'], 1);
    }
    // TEMATYKI
    else if(isset($_POST['addsscat']) && !empty($_POST['addsscat']) && isset($_POST['id']))
    {
        $lid = $adm->getLastID($_POST['id'], 2);
        $t = explode('_', $lid);
        $t[count($t)-1] = ($t[count($t)-1]+1);
        $id = implode('_', $t);
        $adm->add($id, $_POST['addsscat'], 2);
    }
    else if(isset($_POST['delsscat']) && !empty($_POST['delsscat']) && strpos($_POST['delsscat'], '#') !== false)
    {
        $t = explode('#', $_POST['delsscat']);
        $id = $t[1];
        $adm->del($id, 2);
        $adm->delDown($id, 2);
    }
    else if(isset($_POST['editsscat']) && !empty($_POST['editsscat']) && isset($_POST['id']))
    {
        $id = $_POST['id'];
        $adm->update($id, $_POST['editsscat'], 2);
    }
    // MODULY
    else if(isset($_POST['addmoduls']) && !empty($_POST['addmoduls']) && isset($_POST['id']))
    {
        $lid = $adm->getLastID($_POST['id'], 3);
        $t = explode('_', $lid);
        $t[count($t)-1] = ($t[count($t)-1]+1);
        $id = implode('_', $t);
        $adm->add($id, $_POST['addmoduls'], 3);
    }
    else if(isset($_POST['delmoduls']) && !empty($_POST['delmoduls']) && strpos($_POST['delmoduls'], '#') !== false)
    {
        $t = explode('#', $_POST['delmoduls']);
        $id = $t[1];
        $adm->del($id, 3);
    }
    else if(isset($_POST['editmoduls']) && !empty($_POST['editmoduls']) && isset($_POST['id']))
    {
        $id = $_POST['id'];
        $adm->update($id, $_POST['editmoduls'], 3);
    }

    header('Location: profile.php?w=kategorie#'.$id);
    exit();
}

?>
