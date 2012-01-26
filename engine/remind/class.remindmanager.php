<?php

/**
 * manager reminda
 *
 *  2011-09-21  getRemindLink() getRemindMail() +
 * 
 */
class RemindManager
{
    /**
     *
     * @param System $sys
     * @param User $u
     * @param RemindKey $rk
     * @return RemindLink
     */
    public function getRemindLink(System $sys, User $u, RemindKey $rk)
    {
        $rl = new RemindLink();
        $rl->setRemindKey($rk);
        $rl->setRemindScript($sys->getScriptRemindPath());
        $rl->setEmail($u);
        return $rl;
    }

    /**
     *
     * @param User $u
     * @param RemindMailTemplate $rmt
     * @return RemindMail 
     */
    public function getRemindMail(User $u, RemindMailTemplate $rmt)
    {
        $rm = new RemindMail();
        $rm->setContent($rmt->getContent());
        $rm->setReceiver($u->getEmail());
        return $rm;
    }
}

?>
