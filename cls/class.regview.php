<?php

class RegView
{
    private $vw;

    public function __construct()
    {
        $this->vw = new View();
    }

    public function getRegForm()
    {
        $content = file_get_contents('view/html/reg.html');
        $s = array(
            '{%wojs%}'
        );
        $r = array(
            $this->vw->getOptionsWithWojewodztwa()
        );
        $content = str_replace($s, $r, $content);
        return $content;
    }
}

?>
