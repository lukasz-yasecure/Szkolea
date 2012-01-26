<?php

/**
 * kontrolny klucz aktywacyjny (kontener)
 *
 *  2011-09-21  ostatni wglad
 */
class ActivationControlKey
{
    private $controlKeyCode;
    private $generateTime;

    /**
     *
     * @param ActivationMainKey $amk
     */
    public function __construct(ActivationMainKey $amk)
    {
        $this->generateTime = time();
        $this->controlKeyCode = substr(md5(Salts::getFrontSalt().$amk->getMainKeyCode().Salts::getBackSalt()), -10);
    }

    /**
     *
     * @param string $code
     */
    public function setControlKeyCode($code)
    {
        $this->controlKeyCode = $code;
    }

    /**
     *
     * @return string
     */
    public function getControlKeyCode()
    {
        return $this->controlKeyCode;
    }
}

?>
