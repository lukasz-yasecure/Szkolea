<?php

/**
 * kontener danych szablonu wyszukiwarki z glownej
 *
 *  2011-09-27  ostatni wglad
 */
class SearchTemplate
{
    private $content;
    private $search = array(
        '{%cats%}',
        '{%obszary%}',
        '{%tematyki%}',
        '{%bar%}',
        '{%woj1%}', // 4
        '{%woj2%}',
        '{%woj3%}',
        '{%woj4%}',
        '{%woj5%}',
        '{%woj6%}',
        '{%woj7%}',
        '{%woj8%}',
        '{%woj9%}',
        '{%woj10%}',
        '{%woj11%}',
        '{%woj12%}',
        '{%woj13%}',
        '{%woj14%}',
        '{%woj15%}',
        '{%woj16%}', // 19
        '{%place%}',
        '{%cena_min%}',
        '{%cena_max%}',
        '{%date_a%}',
        '{%date_b%}',
        '{%word%}', // 25
        '{%radio1%}',
        '{%radio2%}' // 27
    );
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->replace = array_fill(0, 28, null);
    }

    public function setWord($w)
    {
        $this->replace[25] = $w;
    }

    public function setWhat($r)
    {
        if($r == 'servs') $this->replace[27] = 'checked="checked"';
        else $this->replace[26] = 'checked="checked"';
    }

    public function setDates($d1, $d2)
    {
        $this->replace[23] = $d1;
        $this->replace[24] = $d2;
    }

    public function setPlace($e)
    {
        if(!is_null($e)) $this->replace[20] = $e;
    }

    public function setWoj($w)
    {
        if(!is_null($w))
        {
            $this->replace[3+$w] = 'selected="selected"';
        }
    }

    public function setCeny($c1, $c2)
    {
        if(!is_null($c1)) $this->replace[21] = $c1;
        if(!is_null($c2)) $this->replace[22] = $c2;
    }

    /**
     *
     * @param string $content
     */
    public function setBar($content)
    {
        $this->replace[3] = $content;
    }

    public function setCategories(Categories $c, $id)
    {
        $o1 = '<option value="id">cat</option>';
        $o2 = '<option value="id" selected>cat</option>';
        $ret = '';

        while(!is_null($k = $c->getK()))
        {
            $kname = $k[1];
            $kid = $k[0];

            if(!is_null($id) && $id == $kid) $ret.= str_replace(array('id', 'cat'), array($kid, $kname), $o2);
            else $ret.= str_replace(array('id', 'cat'), array($kid, $kname), $o1);
        }

        $this->replace[0] = $ret;
    }

    public function setObszary(Categories $c, $id)
    {
        $o1 = '<option value="id">cat</option>';
        $o2 = '<option value="id" selected>cat</option>';
        $ret = '';

        while(!is_null($o = $c->getO()))
        {
            $oname = $o[1];
            $oid = $o[0];

            if(!is_null($id) && $id == $oid) $ret.= str_replace(array('id', 'cat'), array($oid, $oname), $o2);
            else $ret.= str_replace(array('id', 'cat'), array($oid, $oname), $o1);
        }

        $this->replace[1] = $ret;
    }

    public function setTematyki(Categories $c, $id)
    {
        $o1 = '<option value="id">cat</option>';
        $o2 = '<option value="id" selected>cat</option>';
        $ret = '';

        while(!is_null($t = $c->getT()))
        {
            $tname = $t[1];
            $tid = $t[0];

            if(!is_null($id) && $id == $tid) $ret.= str_replace(array('id', 'cat'), array($tid, $tname), $o2);
            else $ret.= str_replace(array('id', 'cat'), array($tid, $tname), $o1);
        }

        $this->replace[2] = $ret;
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
