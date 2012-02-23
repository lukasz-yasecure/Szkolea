<?php

/**
 * kontener danych dla userbar
 *
 *  2011-09-27  ostatni wglad
 */
class UserbarTemplate
{
    private $content;
    private $search = array('{%name%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     *
     * @param string $name
     */
    public function setUsername($name)
    {
        $this->replace[0] = $name;
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
