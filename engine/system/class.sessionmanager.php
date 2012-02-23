<?php

/**
 * klasa do przechowywania danych w sesji:
 *  - obiekt User pomiedzy podstronami
 *
 *  2011-09-21  storeUser() getUser() +
 *              storeQueryString() +
 *              getQueryString() +
 *  2011-10-03  storeCommision
 *              getCommision
 *  2011-11-03  logoutUser
 */
class SessionManager
{
    /**
     * start sesji
     */
    public function __construct($store = true)
    {
        if(!isset($_SESSION))
            session_start();

        $this->storeCurrentURL($store);
    }

    /**
     *  zapisujemy aktualny URL a poprzedni aktualny przesuwamy na pole back, wyjatkiem sa sytuacje gdy store zostanie ustawione na FALSE na przyklad przy logowaniu/sprawdzaniu logowania
     *  wtedy aktualny adres jest czyszczony, a back to strona na ktora trzeba sie przeniesc po zalogowaniu, gdy current jest '' wtedy nie nastepuje zapisanie nowego currenta a poprzedni nie jest przenoszony na pole back
     *
     */
    private function storeCurrentURL($store)
    {
        if(isset($_SESSION['history']['current']) && $_SESSION['history']['current'] != '') $_SESSION['history']['back'] = $_SESSION['history']['current'];
        if($store) $_SESSION['history']['current'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    public function getBackURL()
    {
        return isset($_SESSION['history']['back']) ? $_SESSION['history']['back'] : 'index.php'; // 'http://'.$_SERVER['HTTP_HOST']; - strona glowna
    }

    public static function getBackURL_Static()
    {
        return isset($_SESSION['history']['back']) ? $_SESSION['history']['back'] : 'index.php'; // 'http://'.$_SERVER['HTTP_HOST']; - strona glowna
    }

    /**
     * zapisuje usera w sesji
     *
     * @param User $u
     */
    public function storeUser(User $u)
    {
        $_SESSION['user'] = $u;
    }

    /**
     * wyciaga usera z sesji, jesli nie ma usera to zwraca pustego
     *
     * @return User
     */
    public function getUser()
    {
        if(isset($_SESSION['user']) && is_object($_SESSION['user'])) return $_SESSION['user'];
        else
        {
            $u = new User();
            return $u;
        }
    }

    public function storeQueryString()
    {
        $_SESSION['query_string'] = $_SERVER['QUERY_STRING'];
    }

    /**
     *
     * @return string
     */
    public function getQueryString()
    {
        return isset($_SESSION['query_string']) ? $_SESSION['query_string'] : '';
    }

    /**
     *
     * @param Commision $c
     */
    public function storeCommision(Commision $c)
    {
        $_SESSION['commision'] = $c;
    }

    /**
     *
     * @return Commision 
     */
    public function getCommision()
    {
        if(isset($_SESSION['commision']) && is_object($_SESSION['commision'])) return $_SESSION['commision'];
        else
        {
            return new Commision();
        }
    }

    public function logoutUser()
    {
        if(isset($_SESSION['user'])) unset($_SESSION['user']);
    }
}

?>
