<?php

// NIE KASOWAC - potrzebne do wczytywania kategorii przy dodawaniu COMMS/SERVS

ob_start(); // flush w View::getHTMLFoot

ini_set('display_errors', 1);

header('content-Type: text/html; charset=utf8');

define('INSIDE', 'OK');
define('DBHOST', 'localhost');
define('DBLOG', 'www.szkolea');
define('DBPASS', 'Gh96$hjOtm');
define('DBNAME', 'szkolea');
define('TIMEFORACT', 1);

date_default_timezone_set('Europe/Warsaw');

require_once('cls/class.ssman.php');
require_once('cls/class.view.php');
require_once('cls/class.observ.php');
require_once('cls/class.maincontrol.php');
require_once('cls/class.valid.php');

require_once('cls/class.indexview.php');

require_once('cls/class.auth.php');
require_once('cls/class.logcontrol.php');
require_once('cls/class.logview.php');

require_once('cls/class.activcontrol.php');
require_once('cls/class.activation.php');

require_once('cls/class.register.php');
require_once('cls/class.regcontrol.php');
require_once('cls/class.regview.php');

require_once('cls/class.services.php');
require_once('cls/class.servcontrol.php');
require_once('cls/class.servview.php');

require_once('cls/class.commisions.php');
require_once('cls/class.commview.php');
require_once('cls/class.commcontrol.php');

require_once('cls/class.profilecontrol.php');
require_once('cls/class.bfec.php');
require_once('cls/class.rfd.php');

function pre($a)
{
    if(is_array($a)) var_dump($a);
    else
    {
        echo '<pre>';
        print_r($a);
        echo '</pre>';
    }
}

function dpr($msg)
{
    echo '<br/><b>UWAGA:</b> <i>'.$msg.'</i><br/>';
    var_dump($msg);
    exit();
}

$msg = '';

$ss = new SSMan();
$vw = new View();
$a = new Auth();

class Config
{
    private $serv_time;
    private $comm_time;
    private $db;

    public function connectDB()
    {
        if(!is_null($this->db)) return true;

        $db = new mysqli();
        $db->real_connect(DBHOST, DBLOG, DBPASS, DBNAME);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    public function getServTime()
    {
        $this->setServTime();
        return $this->serv_time;
    }

    public function getCommTime()
    {
        $this->setCommTime();
        return $this->comm_time;
    }

    private function setServTime()
    {
        $this->connectDB();
        if(!is_null($this->serv_time)) return false;

        $sql = 'SELECT `set` FROM `sets` WHERE `name`=\'serv_time\'';

        $res = $this->db->query($sql);
        $res = $res->fetch_assoc();
        $this->serv_time = $res['set'];

        return true;
    }

    private function setCommTime()
    {
        $this->connectDB();
        if(!is_null($this->comm_time)) return false;

        $sql = 'SELECT `set` FROM `sets` WHERE `name`=\'comm_time\'';

        $res = $this->db->query($sql);
        $res = $res->fetch_assoc();
        $this->comm_time = $res['set'];

        return true;
    }
}

/*
 * PASS
 */
$a->basicHttpAuth();

?>
