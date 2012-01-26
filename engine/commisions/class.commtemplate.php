<?php

/**
 * kontener danych dla podstrony zlecenia
 *
 *  2011-11-08  zrobione
 */
class CommTemplate
{
    private $content;
    private $search = array(
    '{%id_comm%}',
    '{%date_end%}',
    '{%long%}',
    '{%days%}',
    '{%terminy%}',
    '{%ceny%}',
    '{%oferty%}',
    '{%place%}',
    '{%parts_count%}',
    '{%kategoria_name%}',
    '{%obszar_name%}',
    '{%tematyka_name%}',
    '{%moduly_names%}');
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->replace = array_fill(0, 13, null);
    }

    public function setId_comm($id)
    {
        $this->replace[0] = $id;
    }

    public function setDate_end($d)
    {
        $this->replace[1] = date('Y-m-d H:i', $d);
    }

    public function setLong($l)
    {
        $this->replace[2] = $l;
    }

    public function setDays($e)
    {
        $this->replace[3] = UF::days2names($e);
    }

    public function setTerminy($d1, $d2, $d3 = null, $d4 = null)
    {
        $this->replace[4] = date('Y-m-d', $d1).' - '.date('Y-m-d', $d2);

        if(!is_null($d3) && !is_null($d4)) $this->replace[4].= '<br/>'.date('Y-m-d', $d3).' - '.date('Y-m-d', $d4);
    }

    public function setCeny($c1, $c2)
    {
        $this->replace[5] = $c1.' - '.$c2;
    }

    public function setOferty($e)
    {
        $this->replace[6] = $e;
    }

    public function setPlace($p, $w = null)
    {
        $p.= is_null($w) ? '' : ' ('.UF::nr2wojName($w).')';
        $this->replace[7] = $p;
    }

    public function setParts_count($e)
    {
        $this->replace[8] = $e;
    }

    public function setKategoria_name($e)
    {
        $this->replace[9] = $e;
    }

    public function setObszar_name($e)
    {
        $this->replace[10] = $e;
    }

    public function setTematyka_name($e)
    {
        $this->replace[11] = $e;
    }

    public function setModuly_names($e)
    {
        $this->replace[12] = $e;
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
