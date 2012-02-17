<?php

/**
 *  2011-11-08  przenioslem do tej statycznej klasy 2 metody ktore mi nigdzie nie pasowaly
 */
class UF
{
    /**
     * dodaje do dlugosci szkolenia "dni" np. dostaje 1 zwraca "1 dzien" dostaje 10 zwraca "10 dni"
     * @param type $l
     * @return string 
     */
    public static function longWithDays($l)
    {
        if(intval($l) <= 0) return '0 dni';
        
        if($l == '1') return '1 dzień';
        else return $l.' dni';
    }
    
    public static function date2timestamp($d)
    {
        if(is_null($d)) return null;

        $t = explode('-', $d);

        if(count($t) != 3) return false;

        foreach($t as $e)
        {
            if(!is_numeric($e)) return false;
        }

        $t[0] = intval($t[0]);
        $t[1] = intval($t[1]);
        $t[2] = intval($t[2]);

        return mktime(0, 0, 0, $t[1], $t[0], $t[2]);
    }

    public static function days2names($d)
    {
        $days = array('Obojętnie', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
        $t = explode(',', $d);
        $r = '';

        foreach($t as $s)
        {
            $r.= '<br/>'.$days[$s];
        }

        return $r;
    }

    public static function getDoKonca($de)
    {
        $dni = floor(($de-time())/(24*3600));

        if($dni == 0)
        {
            $g = floor(($de-time())/(3600));

            switch($g)
            {
                case 0:
                    return 'mniej niż godzina';
                case 1:
                    return '1 godzina';
                case 2:
                case 3:
                case 4:
                case 22:
                case 23:
                    return $g.' godziny';
                default:
                    return $g.' godzin';
            }
        }
        else if($dni == 1) return '1 dzień';
        else
        {
            return $dni.' dni';
        }
    }

    public static function nr2wojName($nr)
    {
        $wojs = array(
            'dolnośląskie',
            'kujawsko-pomorskie',
            'lubelskie',
            'lubuskie',
            'łódzkie',
            'małopolskie',
            'mazowieckie',
            'opolskie',
            'podkarpackie',
            'podlaskie',
            'pomorskie',
            'śląskie',
            'świętokrzyskie',
            'warmińsko-mazurskie',
            'wielkopolskie',
            'zachodniopomorskie');

        return $wojs[$nr-1];
    }

    public static function cena_2name($c)
    {
        $n = array('bez Vat', 'zwolnione z Vat', 'z vat (23%)');
        return $n[$c-1];
    }

    public static function basicHttpAuth()
    {
        // jest jedna proba, pozniej trzeba czyscic dane logowana w FF
        // ctrl+shift+del -> aktywne zalogowania
        $login = 'test';
        $pass = 'szkolea';

        if(!isset($_SERVER['PHP_AUTH_USER']))
        {
            header('WWW-Authenticate: Basic realm="basic http auth"');
            header('HTTP/1.0 401 Unauthorized');
            exit();
        }
        else
        {
            if($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $pass)
            {

            }
            else
            {
                header('HTTP/1.0 401 Unauthorized');
                exit;
            }
        }
    }
}

?>
