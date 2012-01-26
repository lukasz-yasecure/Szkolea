<?php

/**
 * template form do grupowanie dopisania sie
 *
 *  2011-10-03  setGrupa
 */
class CommGroupJoinFormTemplate
{
    private $content;
    private $search = array('{%grupa%}', '{%miejsca%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function setGrupa($grupa)
    {
        $this->replace[0] = $grupa;
        $this->replace[1] = 16-$grupa;
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
