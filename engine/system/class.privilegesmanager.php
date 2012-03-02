<?php

/**
 * AUTORYZACJA - baza przywilejow dla akcji w serwisie
 *
 *  2011-09-21  checkPrivileges() +
 *              setPrivilesForRemindForm() -> setPrivilesForNotLogged()
 *  2011-09-22  + register_form
 *              + register_check
 *  2011-10-10  + login, login_check
 *              - activation_check - nie trzeba byc niezalogowanym
 *  2011-10-11  + add_comm
 *              + add_comm_check
 *  2011-11-04  add_serv , add_serv_check
 *              setPrivilegesForAddServ
 *
 */
class PrivilegesManager
{
    private $p;

    /**
     *
     * @param System $sys
     * @throws NoDefinitionForAction
     */
    public function __construct(System $sys)
    {
        switch($sys->getAction())
        {
            case 'remind_form':
            case 'remind_check':
            case 'register_form':
            case 'register_check':
            case 'login':
            case 'login_check':
                $this->setPrivilegesForNotLogged();
                break;
            case 'add_comm':
            case 'add_comm_check':
            case 'comm_join':
                $this->setPrivilegesForAddComm();
                break;
            case 'add_serv':
            case 'add_serv_check':
            case 'comm_offer':
            case 'comm_offer_check':
            case 'image_uploader':    
                $this->setPrivilegesForAddServ();
                break;
            case 'comm':
            case 'serv':
            case 'jtd':
                $this->allAllowed();
                break;
            case 'profile':
                $this->setPrivilegesForProfile();
                break;
            case 'observe':
                $this->setPrivilegesForObserve();
                break;
            case 'fun_adm':
                $this->setPrivilegesForAdmin();
                break;
            default:
                throw new NoDefinitionForAction('Brak przywilejow dla tej akcji!', 0);
        }
    }

    /**
     * user musi byc niezalogowany
     */
    private function setPrivilegesForNotLogged()
    {
        $this->p = new Privileges(2, 0, 0);
    }

    private function setPrivilegesForAddComm()
    {
        $this->p = new Privileges(1, 2, 1);
    }

    private function setPrivilegesForAddServ()
    {
        $this->p = new Privileges(1, 1, 1);
    }

    private function setPrivilegesForProfile()
    {
        $this->p = new Privileges(1, 0, 1);
    }

    private function setPrivilegesForObserve()
    {
        $this->p = new Privileges(1, 0, 1);
    }
    
    private function setPrivilegesForAdmin()
    {
        $this->p = new Privileges(1, 3, 1);
    }

    private function allAllowed()
    {
        $this->p = new Privileges(0, 0, 0);
    }

    /**
     *
     * @param User $u
     * @throws UserIsNotLogged
     * @throws UserIsLogged
     * @throws UserIsNotDostawca
     * @throws UserIsNotKlient
     * @throws UserIsNotAdmin
     * @throws UserIsNotActivated
     *
     */
    public function checkPrivileges(User $u)
    {
        $p = $this->p;

        if(!$u->isAdmin()) // admin moze wszystko
        {
            if($p->getLogPriv() == 1 && $u->isLogged() !== true) throw new UserIsNotLogged('User musi byc zalogowany!');
            if($p->getLogPriv() == 2 && $u->isLogged() !== false) throw new UserIsLogged('User musi byc niezalogowany!');
            if($p->getStatusPriv() == 1 && $u->isDostawca() !== true) throw new UserIsNotDostawca('User musi byc dostawca!');
            if($p->getStatusPriv() == 2 && $u->isKlient() !== true) throw new UserIsNotKlient('User musi byc klientem!');
            if($p->getStatusPriv() == 3 && $u->isAdmin() !== true) throw new UserIsNotAdmin('User musi byc adminem!');
            if($p->getActivPriv() == 1 && $u->isActivated() !== true) throw new UserIsNotActivated('User musi byc aktywowany!');
            if($p->getActivPriv() == 2 && $u->isActivated() !== false) throw new UserIsActivated('User musi byc nieaktywowany!');
        }
    }
}

?>
