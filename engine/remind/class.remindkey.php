<?php

/**
 * klucz do reminda
 *
 *  2011-09-21  inne generowanie remind keya (bez laczenia z baza)
 * 
 */
class RemindKey
{
    private $keyCode;
    private $generateTime;

    /**
     *
     * @param User $u
     */
    public function __construct(User $u)
    {
        $this->generateTime = time();
        $this->keyCode = md5(Salts::getRemindSalt().md5($u->getEmail().Salts::getRemindSalt()));
    }

    /**
     *
     * @param string $code
     */
    public function setKeyCode($code)
    {
        $this->keyCode = $code;
    }

    /**
     *
     * @return string
     */
    public function getKeyCode()
    {
        return $this->keyCode;
    }
}

?>
