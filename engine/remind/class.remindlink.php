<?php

/**
 *  link do maila z remindem passa
 *
 *  2011-09-21  ostatni wglad
 */
class RemindLink
{
    private $rk; // RemindKey
    private $rs; // skrypt remindujacy
    private $mail; // mail remindujacego usera

    /**
     *
     * @param RemindKey $rk
     */
    public function setRemindKey(RemindKey $rk)
    {
        $this->rk = $rk;
    }

    /**
     *
     * @param string $rs
     */
    public function setRemindScript($rs)
    {
        $this->rs = $rs;
    }

    /**
     *
     * @param User $u
     */
    public function setEmail(User $u)
    {
        $this->mail = $u->getEmail();
    }

    /**
     *
     * @return string
     */
    public function getLink()
    {
        return $this->rs.'?check&u='.$this->mail.'&k='.$this->rk->getKeyCode();
    }
}

?>
