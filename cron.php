<?php

require_once('config.php');
require_once('engine/class.sc.php');

class Cron
{
    private $db;

    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
            $db->set_charset('utf8');
            $this->db = $db;
        }
    }

    public function set()
    {
        $this->connectDB();

        $sql = "INSERT INTO `cron` (`id`, `kiedy`) VALUES (NULL, NOW())";

        $this->db->query($sql);

        $error = $this->db->error;

        if(strlen($error) > 0)
        {
            echo $error;
        }
    }
}

$cr = new Cron();
$cr->set();

?>
