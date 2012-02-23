<?php

/**
 * BIG FINE ERROR CONTROLER ;)
 *
 * POPRAWNE UZYCIE BFEC:
 * dodac blad ::add okreslic tresc i czy krytyczny, jesli krytyczny to ustawic przekierowanie
 * dodac news ::addm okreslic tresc i ewentualne przekierowanie
 * odczyt bledow i newsow powinien pojawic sie na kazdej podstronie ::showAll
 * ta metoda zastepuje reczne odczytywanie bledow i newsow (::get ::getm ::clear)
 *
 * !!! EDYCJE
 *  2011-09-08  dodalem metody getm, redirect, addm     pod zwykle wiadomosci wszystko (NEWSY)
 *              zmienilem showAll, clear
 * 
 */
class BFEC
{
    public static function add($message, $critic = false, $redirection = null)
    {
        /*
         * tresc bledu
         * blad moze byc krytyczny - wtedy exitujemy
         * no i moze byc przekierowanie
         */

        $_SESSION['errors'][] = $message;

        if($critic)
        {
            if(!is_null($redirection)) BFEC::redirect($redirection);

            exit();
        }
    }

    public static function addm($message, $redirection = null)
    {
        /*
         * zwykly komunikat, np. o poprawnych zalogowaniu
         */

        $_SESSION['news'][] = $message;

        if(!is_null($redirection)) BFEC::redirect($redirection);
    }

    public static function redirect($redirection)
    {
        /*
         * obsluga przekierowac
         * sa 2 rodzaje urli, bez QUERY_STRING - wtedy trzeba dodac ?e
         *  i z Q_S wtedy &e
         * pozniej exit();
         */

        if(!strpos($redirection, '?')) header('Location: '.$redirection.'?e');
        else header('Location: '.$redirection.'&e');

        exit();
    }

    public static function get()
    {
        /*
         * nie ma e w URL-u wiec nie wyswietlamy bledow
         * nie ma bledow w sesji wiec tez nie ma co wyswietlac
         */

        if(!isset($_GET['e'])) return null;
        if(!isset($_SESSION['errors'])) return null;
        $count = count($_SESSION['errors']);
        if($count === 0) return null;

        $return = $_SESSION['errors'][$count-1];
        unset($_SESSION['errors'][$count-1]);
        return $return;
    }

    public static function getm()
    {
        /*
         * nie ma e w URL-u wiec nie wyswietlamy wiadomosci
         * nie ma wiadomosci w sesji wiec tez nie ma co wyswietlac
         */

        if(!isset($_GET['e'])) return null;
        if(!isset($_SESSION['news'])) return null;
        $count = count($_SESSION['news']);
        if($count === 0) return null;

        $return = $_SESSION['news'][$count-1];
        unset($_SESSION['news'][$count-1]);
        return $return;
    }

    public static function clear()
    {
        if(isset($_SESSION['errors'])) unset($_SESSION['errors']);
        if(isset($_SESSION['news'])) unset($_SESSION['news']);
    }

    public static function showAll()
    {
        while(($error = BFEC::get()) !== null)
        {
            echo 'ERROR: '.$error.'<br/>';
        }

        while(($news = BFEC::getm()) !== null)
        {
            echo 'NEWS: '.$news.'<br/>';
        }

        BFEC::clear();
    }
}

?>