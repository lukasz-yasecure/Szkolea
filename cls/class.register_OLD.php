<?php

if(!defined('INSIDE')) header('Location: ../index.php');

/**
 * ogarnia rejestracje nowego usera
 *
 * BRAK OBSLUGI BLEDOW BAZY
 * BRAK spr poprawnosci maila
 * BRAK spr poprawnosci hasla
 * SQLI
 * XSS
 */
class Register
{
    private $db = null;
    private $ac = null;
    private $mail = null;
    private $pass = null;
    private $new_mail = false;
    private $ukind = null;

    private $user_id = null;
    private $user_status = null;
    private $user_date_reg = null;

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        $db = new mysqli();
        $db->real_connect(SC::$db_host, SC::$db_login, SC::$db_pass, SC::$db_dbname);
        $db->set_charset('utf8');
        $this->db = $db;
    }

    /**
     *
     * @return string adres mail lub '' jesli nie zostal okreslony
     */
    public function getEMail()
    {
        if(!is_null($this->mail)) return $this->mail;
        else return '';
    }

    public function setEMail($m)
    {
        $this->mail = $m;
    }

    public function setPass($p)
    {
        $this->pass = $p;
    }

    public function getUID()
    {
        return $this->user_id;
    }

    public function getDateReg()
    {
        return $this->user_date_reg;
    }

    public function setUKind($k)
    {
        $this->ukind = $k;
    }

    public function getUKind()
    {
        return $this->ukind;
    }

    public function setUID($u)
    {
        $this->user_id = $u;
    }

    /**
     * spr czy podany mail jest w bazie, jesli tak to pobiera skojarzone z nim dane
     *
     * @return bool czy mail jest w bazie
     */
    public function checkEMailExistance()
    {
        if(is_null($this->db)) $this->connectDB();

        if(!is_null($this->mail))
        {
            $sql = 'SELECT id_user, status, date_reg FROM users_324 WHERE email=\''.$this->mail.'\'';

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
                $this->new_mail = true;
                return false;
            }
        }

        return false;
    }

    /**
     * okresla czy rejestrowac jesli email w bazie. Sa 3 sytuacje:
     * 1) status != 0 (czyli aktywny)
     * 2) czas aktywacji < limit
     * 3) czas aktywacji > limit
     * W 1 lub 2 pytamy usera czy zapomnial hasla
     * W 3 zachowanie jakby maila nie bylo w bazie (bo przekroczony czas rejestracji)
     */
    public function regIfEMailInDB()
    {
        if($this->user_status == 0)
        {
            $time = time() - $this->user_date_reg; // czas od rejestracji
            $h = $time/3600;

            if($h > TIMEFORACT)
            {
                $this->new_mail = true;
                return true;
            }
            else
                return false;
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
        $this->ac->setEMail($this->mail);
        $this->ac->calcActivateKeys();
        $this->ac->insertKeyInDB();
        $this->ac->sendActivateMail();
    }

    /**
     *
     * @return bool/int false jesli nie udalo sie dodac usera/id nowego usera
     */
    public function registerUserInDB()
    {
        if(is_null($this->db)) $this->connectDB();

        if($this->new_mail)
        {
            $this->user_date_reg = time();

            $sql = 'INSERT INTO users_324 (`id_user`, `email`, `pass`, `status`, `date_reg`, `kind`) ';
            $sql.= 'VALUES (NULL, \''.$this->mail.'\', SHA1(\''.$this->pass.'\'), \'0\', \''.$this->user_date_reg.'\', \''.$this->ukind.'\')';

            $res = $this->db->query($sql);
            $this->user_id = $this->db->insert_id;
            $this->user_status = 0;

            return $this->user_id;
        }
        else
            return false;
    }

    public function saveNewPassInDB()
    {
        if(is_null($this->db)) $this->connectDB();

        $sql = "UPDATE `users_324` SET `pass` = SHA1('".$this->pass."') WHERE `id_user` = ".$this->user_id;

        $res = $this->db->query($sql);

        exit($sql.$this->db->error);
    }

    /**
     * logowanie po rejestracji
     */
    public function logInAfterReg()
    {
        $a = new Auth();
        $a->setEMail($this->mail);
        $a->setPass($this->pass);

        if($a->check())
        {
            $a->logIn();
        }
    }
}

?>
