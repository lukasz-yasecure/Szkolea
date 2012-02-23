<?php

/**
 *  2011-11-04  all
 */
class AddServFormTemplate
{
    private $content;
    private $search = array(
        '{%cats%}',
        '{%obszary%}',
        '{%tematyki%}',
        '{%moduly%}', // 3
        '{%name%}',
        '{%program%}', // 5
        '{%date_uzg%}',
        '{%date_a%}',
        '{%date_b%}',
        '{%cena%}',
        '{%cena_bez_vat%}', // 10
        '{%cena_zw%}',
        '{%cena_vat%}',
        '{%desc%}',
        '{%mail%}',
        '{%contact%}',
        '{%woj1%}', // 16
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
        '{%woj16%}', // 31
        '{%phone%}',
        '{%place%}'
    );
    private $replace = array();

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->replace = array_fill(0, 71, '');
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

    public function setModuly(Categories $c, $ids)
    {
        $o1 = '<option value="id">cat</option>';
        $o2 = '<option value="id" selected>cat</option>';
        $ret = '';

        while(!is_null($m = $c->getM()))
        {
            $mname = $m[1];
            $mid = $m[0];

            if(!is_null($ids) && in_array($mid, $ids)) $ret.= str_replace(array('id', 'cat'), array($mid, $mname), $o2);
            else $ret.= str_replace(array('id', 'cat'), array($mid, $mname), $o1);
        }

        $this->replace[3] = $ret;
    }

    public function setName($e)
    {
        $this->replace[4] = $e;
    }

    public function setWoj($w)
    {
        if(!is_null($w))
        {
            $this->replace[15+$w] = 'selected="selected"';
        }
    }

    public function setProgram($e)
    {
        $this->replace[5] = $e;
    }

    public function setCena($e)
    {
        $this->replace[9] = $e;
    }

    public function setDesc($e)
    {
        $this->replace[13] = $e;
    }

    public function setPhone($e)
    {
        $this->replace[32] = $e;
    }

    public function setMail($e)
    {
        $this->replace[14] = $e;
    }

    public function setContact($e)
    {
        $this->replace[15] = $e;
    }

    public function setCena_($e)
    {
        if(!is_null($e))
        {
            $this->replace[9+$e] = 'checked="checked"';
        }
    }

    public function setDates($uzg, $d1, $d2)
    {
        if(!is_null($d1) && !is_null($d2))
        {
            $this->replace[7] = $d1;
            $this->replace[8] = $d2;
        }
        else if($uzg)
        {
            $this->replace[6] = 'checked="checked"';
        }
    }

    public function setPlace($e)
    {
        $this->replace[33] = $e;
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
