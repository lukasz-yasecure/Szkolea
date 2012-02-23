<?php

/**
 * tworzy link aktywacyjny a pozniej calego maila
 *
 *  2011-09-21  ostatni wglad
 *  2011-09-26  poprawilem linka aktywujacego - dwa razy dodawal sie base_url
 */
class ActivationManager
{
    /**
     *
     * @param System $sys
     * @param KeyManager $km
     * @return ActivationLink 
     */
    public function getActivationLink(System $sys, ActivationMainKey $amk, ActivationControlKey $ack)
    {
        $al = new ActivationLink();
        $al->setActivationMainKey($amk);
        $al->setActivationControlKey($ack);
        $al->setActivationScript($sys->getScriptActivationPath());
        return $al;
    }

    /**
     *
     * @param User $u
     * @param ActivationMailTemplate $amt
     * @return ActivationMail 
     */
    public function getActivationMail(User $u, ActivationMailTemplate $amt)
    {
        $am = new ActivationMail();
        $am->setContent($amt->getContent());
        $am->setReceiver($u->getEmail());
        return $am;
    }
}

?>
