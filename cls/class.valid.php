<?php

/*
 * name         a-z pl
 * surname      a-z pl -(max 1)
 * email
 * phone        9 cyfr
 * street       a-z pl - spacja
 * house_number np. a12a   a12   12   12a   12/12   12/12a
 * postcode     cc-ccc c to cyfra
 * city         a-z pl - spacja
 * woj          liczba od 1 do 16 (wlacznie)
 * company      a-z pl - spacja cyfry
 * nip          wg algorytmu
 * regon        liczba
 * krs          liczba
 * position     a-z pl - spacja
 * serv_name    a-z pl
 * program      poprawnosc kategorii i programu (0,1,2,3,4)
 * text         normalny tekst w textarea
 * price        liczba > 0
 */

class Valid
{
    /**
     *
     * @return mysqli 
     */
    static function getDBObject()
    {
        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        return $db;
    }

    static function serv_name($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż \-\!\?\"\'\,\.\;\d]+$/iu", $s)) return true;
        else return false;
    }

    static function program($l, $s)
    {
        if($l == 0)
        {
            if(!is_numeric($s)) return false;
        }
        else if($l == 1)
        {
            $t = explode('_', $s);
            if(!is_numeric($t[0]) || !is_numeric($t[1])) return false;
        }
        else if($l == 2)
        {
            $t = explode('_', $s);
            if(!is_numeric($t[0]) || !is_numeric($t[1]) || !is_numeric($t[2])) return false;
        }
        else if($l == 3)
        {
            $t = explode('_', $s);
            if(!is_numeric($t[0]) || !is_numeric($t[1]) || !is_numeric($t[2]) || !is_numeric($t[3])) return false;
            else
            {
                // trzeba sprawdzic, czy taki modul wystepuje w bazie
                $db = Valid::getDBObject();

                $sql = 'SELECT * FROM `moduls_569` WHERE id=\''.$s.'\'';

                $ret = $db->query($sql);

                if($ret->num_rows !== 1) return false;
            }
        }
        else if($l == 4)
        {
            if(strlen($s) < 1) return false;
        }

        return true;
    }

    static function text($s)
    {
        $s = mb_strtolower($s, 'UTF-8');
        
        if(preg_match("/^[a-ząęóćłńśźż \-\!\?\"\'\,\.\;\:\d]+$/ium", $s)) return true;
        else return false;
    }

    static function date2timestamp($d)
    {
        $t = explode('/', $d);

        if(count($t) != 3) return false;

        foreach($t as $e)
        {
            if(!is_numeric($e)) return false;
        }

        $t[0] = intval($t[0]);
        $t[1] = intval($t[1]);
        $t[2] = intval($t[2]);

        return mktime(0, 0, 0, $t[0], $t[1], $t[2]);
    }

    static function name($s)
    {
        $s = mb_strtolower($s, 'UTF-8');
        
        if(preg_match("/^[a-ząęóćłńśźż]+$/iu", $s)) return true;
        else return false;
    }

    static function surname($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż]+\-{0,1}[a-ząęóćłńśźż]*$/iu", $s)) return true;
        else return false;
    }

    static function email($s)
    {
        if(filter_var($s, FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }

    static function phone($s)
    {
        if(strlen($s) != 9) return false;
        if(!is_numeric($s)) return false;

        return true;
    }

    static function street($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż \-]+$/iu", $s)) return true;
        else return false;
    }

    static function house_number($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        // akceptuje
        // a12a   a12   12   12a   12/12   12/12a

        if(preg_match("/^[a-z]*\d+[a-z]*(|\/\d+[a-z]*)$/iu", $s)) return true;
        else return false;
    }

    static function postcode($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^\d{2}\-\d{3}$/iu", $s)) return true;
        else return false;
    }

    static function city($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż \-]+$/iu", $s)) return true;
        else return false;
    }

    static function woj($s)
    {
        if(!is_numeric($s)) return false;
        if($s < 1 || $s > 16) return false;

        return true;
    }

    static function company($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż \-\d]+$/iu", $s)) return true;
        else return false;
    }

    static function nip($s)
    {
        $nip = preg_replace('/[^\d]/', '', $s);

        if(strlen($nip) != 10) return false;

        $weights = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $sum = 0;

        for($i = 0; $i < 9; $i++)
            $sum += $nip[$i] * $weights[$i];

        if(($sum % 11) == $nip[9]) return $nip;
        else return false;
    }

    static function krs($s)
    {
        if(!is_numeric($s)) return false;
        else return true;
    }

    static function regon($s)
    {
        if(!is_numeric($s)) return false;
        else return true;
    }

    static function position($s)
    {
        $s = mb_strtolower($s, 'UTF-8');

        if(preg_match("/^[a-ząęóćłńśźż \-]+$/iu", $s)) return true;
        else return false;
    }

    static function price($s)
    {
        if(is_numeric($s) && $s > 0) return true;
        else return false;
    }
}

?>
