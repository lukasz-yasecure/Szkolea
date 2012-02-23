<?php

/**
 *  2011-10-11  1st - trzeba wykorzystac categories + categorymanager, wzorowac sie na RegisterFT
 *  2011-10-31  uzupelniam liste modulow danymi wybranymi przez usera
 *              + setLong()
 *              + $this->replace = array_fill zeby niewykorzystane zmienne z szablonu byly podmieniane na ''
 *              + setDays
 */
class AddCommFormTemplate
{
    private $content;
    private $search = array(
        '{%cats%}',
        '{%obszary%}',
        '{%tematyki%}',
        '{%moduly%}',
        '{%long1%}', // 4
        '{%long2%}',
        '{%long3%}',
        '{%long4%}',
        '{%days0%}', // 8
        '{%days1%}',
        '{%days2%}',
        '{%days3%}',
        '{%days4%}',
        '{%days5%}',
        '{%days6%}',
        '{%days7%}',
        '{%date_a%}', // 16
        '{%date_b%}',
        '{%date_c%}',
        '{%date_d%}',
        '{%expire%}',
        '{%place%}',
        '{%cena_min%}',
        '{%cena_max%}',
        '{%part0%}',
        '{%woj1%}', // 25
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
        '{%woj16%}', // 40
        '{%drugi_termin%}', // 41
        '{%part2_name%}',
        '{%part2_surname%}',
        '{%part3_name%}',
        '{%part3_surname%}',
        '{%part4_name%}',
        '{%part4_surname%}',
        '{%part5_name%}',
        '{%part5_surname%}',
        '{%part6_name%}',
        '{%part6_surname%}',
        '{%part7_name%}',
        '{%part7_surname%}',
        '{%part8_name%}',
        '{%part8_surname%}',
        '{%part9_name%}',
        '{%part9_surname%}',
        '{%part10_name%}',
        '{%part10_surname%}',
        '{%part11_name%}',
        '{%part11_surname%}',
        '{%part12_name%}',
        '{%part12_surname%}',
        '{%part13_name%}',
        '{%part13_surname%}',
        '{%part14_name%}',
        '{%part14_surname%}',
        '{%part15_name%}',
        '{%part15_surname%}',
        '{%part16_name%}',
        '{%part16_surname%}' // 71
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

    public function setLong($id)
    {
        if(!is_null($id))
        {
            $set = 'selected="selected"';
            $this->replace[(3+$id)] = $set;
        }
    }

    public function setDays($days)
    {
        if(!is_null($days))
        {
            $ch = 'checked="checked"';

            foreach($days as $d)
            {
                $this->replace[8+$d] = $ch;
            }
        }
    }

    public function setDates($d1, $d2, $d3, $d4)
    {
        $this->replace[16] = $d1;
        $this->replace[17] = $d2;
        if(!is_null($d3) && !is_null($d4))
        {
            $this->replace[18] = $d3;
            $this->replace[19] = $d4;
            $this->replace[41] = 'checked="checked"';
        }
    }

    public function setExpire($e)
    {
        if(!is_null($e)) $this->replace[20] = $e;
    }

    public function setPlace($e)
    {
        if(!is_null($e)) $this->replace[21] = $e;
    }

    public function setWoj($w)
    {
        if(!is_null($w))
        {
            $this->replace[24+$w] = 'selected="selected"';
        }
    }

    public function setCeny($c1, $c2)
    {
        if(!is_null($c1)) $this->replace[22] = $c1;
        if(!is_null($c2)) $this->replace[23] = $c2;
    }

    public function setPart0($p)
    {
        if(!is_null($p)) $this->replace[24] = 'checked="checked"';
    }

    public function setParticipants($parts)
    {
        if(!is_null($parts))
        {
            $i = 0;

            foreach($parts as $p)
            {
                $this->replace[42+$i] = $p['name'];
                $this->replace[43+$i] = $p['surname'];
                $i+=2;
            }
        }
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
