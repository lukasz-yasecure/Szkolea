<?php

/**
 * kontener danych szablonu glownej strony
 *
 *  2011-10-03  setSkrypty
 */
class MainTemplate
{
    private $content;
    private $search = array('{%main%}', '{%bfec%}', '{%skrypty%}', '{%foot%}');
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
    public function setMain($content)
    {
        $this->replace[0] = $content;
    }

    /**
     *
     * @param string $bfec
     */
    public function setBFEC($bfec)
    {
        $this->replace[1] = $bfec;
    }

    /**
     *
     * @param string $skrypty
     */
    public function setSkrypty($skrypty)
    {
        $this->replace[2] = $skrypty;
    }
    
    public function setFooter($footer)
    {
        $this->replace[3] = $footer;
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
