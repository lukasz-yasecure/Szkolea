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
 *  2011-09-08  dodalem metody getm, redirect, addm     pod zwykle wiadomosci wszystko (NEWSY)
 *              zmienilem showAll, clear
 *  2011-09-21  showAll() - bierze szablon dla errorow i szablon dla wiadomosci, wrzuca tam tresc i zwraca zamiast wyswietlac (echo) jak wczesniej
 *  2011-09-22  isError() - czy sa bledy
 *              odwracam liste bledow/newsow zeby sie wyswietlaly w kolejnosci dodania (pierwszy jest pierwszy itd)
 *  2011-10-10  tresci bledow w klasie BFEC
 *              newsy nadpisywaly errory przy show
 *              newsy przed errorami
 *  2011-10-11  nowe bledy
 *              wywalilem "e" z URL-a
 *              ?resend do nieaktywowany (link)
 *  2011-10-31  $e + long
 *  2011-11-04  nowe bledy 'UD'
 *  2011-11-08  nowy msg w $m 'add_comm'
 *
 */
class BFEC
{
    static $e = array(
        'PM' => array(
            'UserIsLogged' => 'Nie możesz być zalogowany!',
            'UserIsNotLogged' => 'Musisz się zalogować!',
            'UserIsNotKlient' => 'Musisz posiadać konto typu "klient", żeby dodać zlecenie!',
            'UserIsNotKlient_join' => 'Musisz posiadać konto typu "klient", żeby wziąć udział w szkoleniu!',
            'UserIsNotDostawca_offer' => 'Musisz posiadać konto typu "dostawca", żeby dodać ofertę!',
            'UserIsNotDostawca' => 'Musisz posiadać konto typu "dostawca", żeby dodać uslugę!'
        ),
        'UD' => array(
            'NoEmail' => 'Musisz podać adres e-mail.',
            'NoValidEmail' => 'Musisz podać poprawny adres e-mail.',
            'NoPass' => 'Musisz podać hasło.',
            'NoValidPass' => 'Hasło niepoprawne.',
            'regulamin' => 'Musisz zaakceptować regulamin!',
            'kategoria' => 'Musisz wybrać poprawną kategorię z listy!',
            'obszar' => 'Musisz wybrać poprawny obszar z listy!',
            'tematyka' => 'Musisz wybrać poprawną tematykę z listy.',
            'moduly' => 'Musisz wybrać poprawne moduły z listy.',
            'long' => 'Musisz wybrać długość szkolenia z listy.',
            'days' => 'Musisz zaznaczyć preferowane dni na szkolenie.',
            'days_continuity' => 'Musisz wybrać preferowane dni w jednym ciągu, aby szkolenie odbyło się bez przerw.',
            'date' => 'Musisz podać poprawne daty.',
            'date_long' => 'Musisz podać termin, w którym zmieści się szkolenie podanej długości!',
            'expire' => 'Ważność szkolenia musi być określona liczbą wiekszą od 0!',
            'place' => 'Musisz podać miejsce szkolenia np. cała Polska, Wrocław!',
            'cena' => 'Musisz podać cenę!',
            'cena_' => 'Musisz wybrać opcję VAT.',
            'cena_min' => 'Musisz podać minimalną cenę!',
            'cena_max' => 'Musisz podać maksymalną cenę!',
            'ceny' => 'Cena maksymalna musi być większa od minimalnej!',
            'uczestnik' => 'Imię lub nazwisko jednego z uczestników jest niepoprawne!',
            'liczba_ucz' => 'Musisz zapisać przynajmniej jednego uczestnika!',
            'name' => 'Musisz podać poprawną nazwę dla szkolenia!',
            'program' => 'Musisz podać poprawny program dla szkolenia!',
            'brak_programu' => 'Musisz wybrać moduły albo uzupełnić program!',
            'phone' => 'Musisz podać poprawny numer telefonu.',
            'contact' => 'Musisz podać dane osoby do kontaktu.'
        ),
        'UM' => array(
            'InvalidUserValidation' => 'Nie pamiętasz hasła? <a href="remind.php">Poproś o nowe</a>.<br/>Nie masz konta? <a href="register.php">Rejestracja jest za darmo</a>!',
            'nieaktywowany' => 'Aktywuj swoje konto, aby mieć pełny dostęp do usług w Szkolea.pl! Aby to zrobić kliknij w link podany w mailu otrzymanym po rejestracji.<br/><a href="activation.php?resend">Nie otrzymałem maila</a>.',
            'serv_niezalogowany' => 'Aby zobaczyć dane kontaktowe usługodawcy, musisz się <a href="log.php">zalogować</a>!',
            'zbanowany' => 'Twoje konto zostało zablokowane. Skontaktuj się z administratorem w celu wyjaśnienia.'
        ),
        'contact' => 'Musisz podać treść wiadomości!'
    );

    static $m = array(
        'UM' => array(
            'zalogowany' => 'Witaj! Zostałeś zalogowany!',
            'wylogowany' => 'Zostałeś wylogowany!'
        ),
        'add_comm' => 'Twoje zlecenie zostało dodane!',
        'add_serv' => 'Twoja usługa została dodana!',
        'contact' => 'Twoja wiadomość została wysłana! Dziękujemy!'
    );

    public static function add($message, $critic = false, $redirection = null)
    {
        /*
         * tresc bledu
         * blad moze byc krytyczny - wtedy exitujemy
         * no i moze byc przekierowanie
         */

        if(!empty($message)) $_SESSION['errors'][] = $message;

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

        if(!empty($message)) $_SESSION['news'][] = $message;

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

        if(!strpos($redirection, '?')) header('Location: '.$redirection); //.'?e');
        else header('Location: '.$redirection); //.'&e');

        exit();
    }

    public static function get()
    {
        /*
         * nie ma e w URL-u wiec nie wyswietlamy bledow
         * nie ma bledow w sesji wiec tez nie ma co wyswietlac
         */

        //if(!isset($_GET['e'])) return null;
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

        //if(!isset($_GET['e'])) return null;
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
        $err = '';
        $msg = '';
        $ret = '';

        if(isset($_SESSION['errors'])) $_SESSION['errors'] = array_reverse($_SESSION['errors']);
        if(isset($_SESSION['news'])) $_SESSION['news'] = array_reverse($_SESSION['news']);

        while(($error = BFEC::get()) !== null)
        {
            $err.= $error.'<br/>';
        }

        while(($news = BFEC::getm()) !== null)
        {
            $msg.= $news.'<br/>';
        }

        if(!empty($msg)) $ret.= str_replace(array('{%msg%}'), array($msg), file_get_contents('view/html/message.html'));
        if(!empty($err)) $ret.= str_replace(array('{%err%}'), array($err), file_get_contents('view/html/error.html'));

        BFEC::clear();
        return $ret;
    }

    /**
     *
     * @return bool
     */
    public static function isError()
    {
        return isset($_SESSION['errors']) && (count($_SESSION['errors']) > 0);
    }
}

?>