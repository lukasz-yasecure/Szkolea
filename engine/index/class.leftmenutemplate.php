<?php

/**
 * template dla left menu w index
 *
 *  2011-09-28  zmiana sposobu budowania szablonu
 *              nie bedzie petli w szablonie tylko wydzielilem z szablonu mniejszy szablon do listy
 *              tutaj po prostu wstawie gotowa liste
 */
class LeftMenuTemplate
{
    private $content;
    private $search = array('{%commsStyle%}', '{%servsStyle%}', '{%list%}', '{%wht%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->replace = array_fill(0, count($this->search), null);
    }

    public function setCommsStyle($cs)
    {
        $this->replace[0] = $cs;
    }

    public function setServsStyle($ss)
    {
        $this->replace[1] = $ss;
    }

    public function setList($list)
    {
        $this->replace[2] = $list;
    }

    public function setWht($w)
    {
        $this->replace[3] = $w;
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
