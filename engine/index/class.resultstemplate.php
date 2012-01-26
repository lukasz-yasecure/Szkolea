<?php

/**
 * template dla wynikow na glownej
 *
 *  2011-09-29  last
 */
class ResultsTemplate
{
    private $content;
    private $search = array('{%wyniki%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function setWyniki($wyniki)
    {
        $this->replace[0] = $wyniki;
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
