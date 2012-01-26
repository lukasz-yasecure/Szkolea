<?php

/**
 * 2011-10-10   1st
 */
class LoginFormTemplate
{
    private $content;
    private $search = array('{%email%}', '{%action%}');
    private $replace = array();

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function setEmail($replace) {
        $this->replace[0] = $replace;
    }

    public function setAction($action)
    {
        $this->replace[1] = $action;
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        return str_replace($this->search, $this->replace, $this->content);
    }
}

?>
