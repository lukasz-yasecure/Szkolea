<?php

class Register
{
    private $db;
    private $ac;

    private $user_id;
    private $user_status;
    private $user_date_reg;
    private $email;

    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
            $db->set_charset('utf8');
            $this->db = $db;
        }
    }

    /*
     * null - blad
     * false - nie ma w bazie
     * true - jest w bazie
     */
    public function checkIfEMailInDB(RegControl $rc)
    {
        $this->connectDB();

        $sql = $rc->getCheckQueryIfEMailInDB();

        if(!$sql) return null;

        $res = $this->db->query($sql);

        if($res->num_rows > 0)
        {
            $res = $res->fetch_assoc();
            $this->user_id = $res['id_user'];
            $this->user_status = $res['status'];
            $this->user_date_reg = $res['date_reg'];
            return true;
        }
        else
        {
            //$this->new_mail = true;
            return false;
        }
    }

    public function checkIfActivated()
    {
        if($this->user_status > 0) return true;
        else return false;
    }

    public function checkIfActivationExpired()
    {
        $time = time() - $this->user_date_reg; // czas od rejestracji
        $h = $time/3600;

        if($h > TIMEFORACT)
        {
            //$this->new_mail = true;
            return true;
        }
        else
            return false;
    }

    /**
     * zaczyna aktywacje
     */
    public function setActivation()
    {
        if(is_null($this->ac))
        {
            $this->ac = new Activation();
            $this->ac->setUID($this->user_id);
            $this->ac->setDateReg($this->user_date_reg);
        }
    }

    /**
     * usuwa stare wpisy aktywacyjne z bazy
     */
    public function clearOldActivation()
    {
        $this->setActivation();
        $this->ac->deleteNotActivatedEMail();
        $this->ac->deleteActivationKey();
    }

    /**
     * tworzy nowa aktywacje (nowe klucze)
     * i inicjuje wyslanie maila
     */
    public function newActivation()
    {
        $this->setActivation();
        $this->ac->setEMail($this->email);
        $this->ac->calcActivateKeys();
        $this->ac->insertKeyInDB();
        $this->ac->sendActivateMail();
    }

    public function registerUser(RegControl $rc)
    {
        $this->connectDB();

        $this->user_date_reg = $rc->getDateReg();
        $this->email = $rc->getEMail();

        $sql = $rc->getRegisterQuery();

        //dpr($sql);

        $res = $this->db->query($sql);

        //dpr($this->db->error);

        if($this->db->insert_id > 0)
        {
            $this->user_id = $this->db->insert_id;
            $this->user_status = 0;
            return true;
        }
        else return false;
    }
}

?>
