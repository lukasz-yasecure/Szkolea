<?php

/**
 * kontener danych dla loginbar
 *
 *  2011-09-27  ostatni wglad
 */
class LoginbarTemplate
{
    private $content;
    private $search = array();
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
     * @return string
     */
    public function getContent()
    {
        return str_replace($this->search, $this->replace, $this->content);
    }
}

?>
