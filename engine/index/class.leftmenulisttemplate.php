<?php

/**
 * template dla left menu list
 *
 *  2011-09-28  szablon z petla
 */
class LeftMenuListTemplate
{
    private $content;
    private $search = array('{%what%}', '{%act%}', '{%url%}', '{%name%}', '{%id%}');
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
     * @param string $w
     * @param string $a
     * @param string $url
     * @param string $name
     */
    public function addPosition($w, $a, $url, $name, $id)
    {
        $this->replace[] = array($w, $a, $url, $name, $id);
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        $content = '';

        foreach($this->replace as $l)
        {
            $content.= str_replace($this->search, $l, $this->content);
        }

        return $content;
    }
}

?>
