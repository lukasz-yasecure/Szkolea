<?php

/**
 * kontener dostepow
 *
 *  2011-09-21  ostatni wglad
 */
class Privileges
{
    private $a; // 0 - wszyscy 1 - zalogowaniu 2 - niezalogowani
    private $b; // 0 - wszyscy 1 - dostawcy 2 - klienci 3 - admin
    private $c; // 0 - wszyscy 1 - aktywowani 2 - nieaktywowani

    /**
     *
     * @param int $a
     * @param int $b
     * @param int $c
     */
    public function __construct($a, $b, $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    /**
     *
     * @return int 0/1/2
     */
    public function getLogPriv()
    {
        return $this->a;
    }

    /**
     *
     * @return int 0/1/2/3
     */
    public function getStatusPriv()
    {
        return $this->b;
    }

    /**
     *
     * @return int 0/1/2
     */
    public function getActivPriv()
    {
        return $this->c;
    }
}

?>
