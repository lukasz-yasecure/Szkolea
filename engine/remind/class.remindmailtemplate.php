<?php

/**
 * szablonik maila reminda
 *
 *  2011-09-21  ostatni wglad
 */
class RemindMailTemplate
{
    private $content;
    private $search = array('{%link%}');
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
     * @param string $link
     */
    public function setLink($link)
    {
        $this->replace[0] = $link;
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
