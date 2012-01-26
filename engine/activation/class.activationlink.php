<?php

/**
 * link aktywacyjny (kontener)
 *
 *  2011-09-21  ostatni wglad
 */
class ActivationLink
{
    private $amk; // ActivationMainKey
    private $ack; // ActivationControlKey
    private $as; // skrypt aktywujacy

    /**
     *
     * @param ActivationMainKey $amk
     */
    public function setActivationMainKey(ActivationMainKey $amk)
    {
        $this->amk = $amk;
    }

    /**
     *
     * @param string $as
     */
    public function setActivationScript($as)
    {
        $this->as = $as;
    }

    /**
     *
     * @param ActivationControlKey $ack
     */
    public function setActivationControlKey(ActivationControlKey $ack)
    {
        $this->ack = $ack;
    }

    /**
     *
     * @return string
     */
    public function getLink()
    {
        return $this->as.'?k='.$this->amk->getMainKeyCode().'&c='.$this->ack->getControlKeyCode();
    }
}

?>
