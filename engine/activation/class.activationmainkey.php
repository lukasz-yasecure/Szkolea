<?php

/**
 * glowny klucz aktywacji (kontener)
 *
 *  2011-09-21  ostatni wglad
 *  2011-09-24  zmienilem konstruktor zeby mozna bylo stworzyc obiekt bez Usera (do sprawdzania aktywacji)
 */
class ActivationMainKey
{
    private $mainKeyCode;
    private $generateTime;

    /**
     *
     * @param User $u
     */
    public function __construct(User $u = null)
    {
        $this->generateTime = time();
        
        if(!is_null($u))
        {
            $this->mainKeyCode = md5($u->getDate_reg().$u->getId_user());
        }
    }

    /**
     *
     * @param string $code
     */
    public function setMainKeyCode($code)
    {
        $this->mainKeyCode = $code;
    }

    /**
     *
     * @return string
     */
    public function getMainKeyCode()
    {
        return $this->mainKeyCode;
    }
}

?>
