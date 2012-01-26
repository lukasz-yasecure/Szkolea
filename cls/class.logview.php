<?php

class LogView
{
    private $vw;

    public function __construct()
    {
        $this->vw = new View();
    }

    public function getLogForm()
    {
        $content = file_get_contents('view/html/log.html');

        $s = array(
            '{%email%}'
        );
        $r = array(
            is_null($email = RFD::get('logForm', 'email')) ? '' : $email
        );
        $content = str_replace($s, $r, $content);
        RFD::clear('logForm');

        return $content;
    }

    public function getRemindForm()
    {
        $content = file_get_contents('view/html/log_remind.html');
        return $content;
    }
}

?>
