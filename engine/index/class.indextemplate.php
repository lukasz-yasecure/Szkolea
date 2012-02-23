<?php

/**
 * kontener danych szablonu dla strony glownej
 *
 *  2011-09-27  ostatni wglad
 */
class IndexTemplate
{
    private $content;
    private $search = array('{%search%}', '{%left_menu%}', '{%results%}');
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
     * @param string $content
     */
    public function setSearch($content)
    {
        $this->replace[0] = $content;
    }

    /**
     *
     * @param string $content
     */
    public function setLeftMenu($content)
    {
        $this->replace[1] = $content;
    }

    /**
     *
     * @param string $content
     */
    public function setResults($content)
    {
        $this->replace[2] = $content;
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
