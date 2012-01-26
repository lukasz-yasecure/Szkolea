<?php

require_once('config.php');

class Cron
{
    private $db;

    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(DBHOST, DBLOG, DBPASS, DBNAME);
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
