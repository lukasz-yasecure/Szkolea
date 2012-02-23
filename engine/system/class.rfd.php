<?php

/* Remember Form Data
 *  każdy form to nazwa forma i pola
 *  a pole rowniez ma nazwe
 *  wiec mozna identyfikowac pola po nazwie forma i nazwie pola
 *
 * POPRAWNE UZYCIE RFD:
 * przy validowaniu danych (np. w klasie *control) zwalidowane pole dodac przy pomocy ::add
 * przy tworzeniu formularza uzyc skladni:
 *      is_null($email = RFD::get('logForm', 'email')) ? '' : $email
 * do tworzenia tablicy $r dla str_replace przy tworzeniu szablonu
 * po stworzeniu szablonu uzyc clear:
 *      RFD::clear('logForm');
 * PAMIETAC O CZYSZCZENIU - inaczej dane beda dluzej pamietane i sie z dupy pojawia
 *
 * !!! klasa dopracowana 2011-09-08
 * 
 */
class RFD
{
    public static function add($formName, $fieldName, $content)
    {
        $_SESSION['rfd'][$formName][$fieldName] = $content;
    }

    public static function get($formName, $fieldName)
    {
        if(isset($_SESSION['rfd'][$formName][$fieldName]) && !empty($_SESSION['rfd'][$formName][$fieldName])) return $_SESSION['rfd'][$formName][$fieldName];
        else return null;
    }

    public static function clear($formName)
    {
        if(isset($_SESSION['rfd'][$formName])) unset($_SESSION['rfd'][$formName]);
    }
}

?>