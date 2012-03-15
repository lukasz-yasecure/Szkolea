<?php

/**
 *  2011-11-08  przenioslem do tej statycznej klasy 2 metody ktore mi nigdzie nie pasowaly
 */
class UF {

    /**
     * dodaje do dlugosci szkolenia "dni" np. dostaje 1 zwraca "1 dzien" dostaje 10 zwraca "10 dni"
     * @param type $l
     * @return string 
     */
    public static function longWithDays($l) {
        if (intval($l) <= 0)
            return '0 dni';

        if ($l == '1')
            return '1 dzień';
        else
            return $l . ' dni';
    }

    public static function date2timestamp($d) {
        if (is_null($d))
            return null;

        $t = explode('-', $d);

        if (count($t) != 3)
            return false;

        foreach ($t as $e) {
            if (!is_numeric($e))
                return false;
        }

        $t[0] = intval($t[0]);
        $t[1] = intval($t[1]);
        $t[2] = intval($t[2]);

        return mktime(0, 0, 0, $t[1], $t[0], $t[2]);
    }

    public static function days2names($d) {
        $days = array('Obojętnie', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
        $t = explode(',', $d);
        $r = '';

        foreach ($t as $s) {
            $r.= '<br/>' . $days[$s];
        }

        return $r;
    }

    public static function getDoKonca($de) {
        $dni = floor(($de - time()) / (24 * 3600));

        if ($dni == 0) {
            $g = floor(($de - time()) / (3600));

            switch ($g) {
                case 0:
                    return 'mniej niż godzina';
                case 1:
                    return '1 godzina';
                case 2:
                case 3:
                case 4:
                case 22:
                case 23:
                    return $g . ' godziny';
                default:
                    return $g . ' godzin';
            }
        } else if ($dni == 1)
            return '1 dzień';
        else {
            return $dni . ' dni';
        }
    }

    public static function nr2wojName($nr) {
        if ($nr > 0 && $nr < 16) {
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

            return $wojs[$nr - 1];
        }else
            return '';
    }

    public static function cena_2name($c) {
        $n = array('bez Vat', 'zwolnione z Vat', 'z vat (23%)');
        return $n[$c - 1];
    }

    public static function basicHttpAuth() {
        // jest jedna proba, pozniej trzeba czyscic dane logowana w FF
        // ctrl+shift+del -> aktywne zalogowania
        $login = 'szkolea';
        $pass = 'testy';

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="basic http auth"');
            header('HTTP/1.0 401 Unauthorized');
            exit();
        } else {
            if ($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $pass) {
                
            } else {
                header('HTTP/1.0 401 Unauthorized');
                exit;
            }
        }
    }

    /**
     * Zamienia timestampa na date jesli damy $time na true to rowniez czas wyswietli
     * @param type $t
     * @param type $time
     * @return type 
     */
    public static function timestamp2date($t, $time = false) {
        if ($time)
            return date('Y-m-d H:i', $t);
        else
            return date('Y-m-d', $t);
    }

    /**
     * przy ofertach i uslugach wystepuje taka opcja, w bazie zapisujemy ja 1 lub 2 lub 3 wiec trzeba to przetlumaczyc
     * @param type $c
     * @return string 
     */
    public static function cenax2name($c) {
        if ($c == '1')
            return 'Bez Vat';
        if ($c == '2')
            return 'Zwolnione z Vat';
        if ($c == '3')
            return 'Z Vat (23%)';
    }

    /**
     * sposob rozliczania - w bazie zapisujemy 1 2 3 wiec trzeba tlumaczyc
     * @param type $c
     * @return string 
     */
    public static function rozl2name($c) {
        if ($c == '1')
            return 'Rachunek';
        if ($c == '2')
            return 'Faktura Vat';
        if ($c == '3')
            return 'Rachunek do umowy o dzieło';
    }

    /**
     * przy ofercie zaznacza sie czy bedzie sala, materialy, lunch i przerwy kawowe, w bazie zapisujemy to w jednym polu w formacie 1;2;3;4 - to oznacza ze wszystko bedzie
     * @param type $i
     * @return string 
     */
    public static function inne2arrayTakNie($i) {
        $t = explode(';', $i);
        $a = array('sala' => 'nie', 'materialy' => 'nie', 'lunch' => 'nie', 'kawa' => 'nie');

        if (is_array($t)) {
            foreach ($t as $v) {
                if ($v == '1')
                    $a['sala'] = 'tak';
                else if ($v == '2')
                    $a['materialy'] = 'tak';
                else if ($v == '3')
                    $a['lunch'] = 'tak';
                else if ($v == '4')
                    $a['kawa'] = 'tak';
            }
        }

        return $a;
    }

}

?>
