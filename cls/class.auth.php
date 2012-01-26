<?php

if(!defined('INSIDE')) header('Location: ../index.php');

/**
 * ogarnia logowanie
 *
 * BLEDY MySQL
 */
class Auth
{
    private $mail = null;
    private $pass = null;
    private $hPass = null;

    private $db = null;

    private $ss = null;

    private $uid = null;
    private $status = null;
    private $date_reg = null;
    private $ukind = null;

    /**
     * rozpoczyna sesje
     * jesli status > 0 (czyli ktos jest zalogowany) to regenerujemy sida
     */
    public function __construct()
    {
        $this->ss = new SSMan();

        if($this->isLogged())
                $this->ss->regenerateSID();
    }

    public function isLogged()
    {
        if($this->ss->getUID() != 0)
        {
            if(!is_null($this->ss->getStatus()))
                return true;
            else
                return false;
        }
        else
            return false;
    }

    public function isActivated()
    {
        if(is_null($this->ss->getStatus()) || $this->ss->getStatus() == 0)
            return false;
        else
            return true;
    }

    public function isAdmin()
    {
        if($this->ss->getStatus() == 9)
            return true;
        else
            return false;
    }

    public function basicHttpAuth()
    {
        // jest jedna proba, pozniej trzeba czyscic dane logowana w FF
        // ctrl+shift+del -> aktywne zalogowania
        $login = 'test';
        $pass = 'szkolea';
        
        if(!isset($_SERVER['PHP_AUTH_USER']))
        {
            header('WWW-Authenticate: Basic realm="basic http auth"');
            header('HTTP/1.0 401 Unauthorized');
            exit();
        }
        else
        {
            if($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $pass)
            {
                
            }
            else
            {
                header('HTTP/1.0 401 Unauthorized');
                exit;
            }
        }
    }

    /**
     * polaczenie z baza wg stalych z configa
     */
    public function connectDB()
    {
        if(is_null($this->db))
        {
            $db = new mysqli();
            $db->real_connect(DBHOST, DBLOG, DBPASS, DBNAME);
            $db->set_charset('utf8');
            $this->db = $db;
        }
    }

    public function setEMail($m)
    {
        $this->mail = $m;
    }

    public function getEMail()
    {
        return $this->mail;
    }

    public function getUID()
    {
        return $this->uid;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDateReg()
    {
        return $this->date_reg;
    }

    public function setPass($p)
    {
        $this->pass = $p;
    }

    public function hashPass()
    {
        if(!is_null($this->pass)) $this->hPass = sha1($this->pass);
    }

    /**
     *
     * @return bool czy poprawne dane?
     */
    public function check(LogControl $lc)
    {
        $this->connectDB();

        $sql = $lc->getLogQuery();

        $r = $this->db->query($sql);
                
        if($r->num_rows == 1)
        {
            $r = $r->fetch_assoc();
            $this->uid = $r['id_user'];
            $this->status = $r['status'];
            $this->date_reg = $r['date_reg'];
            $this->ukind = $r['kind'];
            $this->mail = $lc->getEmail();
            return true;
        }
        else
            return false;
    }

    /**
     * spr czy podany mail jest w bazie
     *
     * @return bool czy mail jest w bazie
     */
    public function checkEMailExistance(LogControl $lc)
    {
        $this->connectDB();

        $sql = $lc->getQueryForEMailExistanceInDB();

        $res = $this->db->query($sql);

        if($res->num_rows == 1)
        {
            $res = $res->fetch_assoc();
            $this->uid = $res['id_user'];
            $this->date_reg = $res['date_reg'];
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * ustawia w sesji dane wazne z punktu widzenia autoryzacji
     */
    public function logIn()
    {
        $this->ss->setUID($this->uid);
        $this->ss->setStatus($this->status);
        $this->ss->setDateReg($this->date_reg);
        $this->ss->setUKind($this->ukind);
        $this->ss->setEMail($this->mail);
    }

    public function logOut()
    {
        $this->ss->clearMail();
        $this->ss->clearUID();
        $this->ss->clearDateReg();
        $this->ss->clearStatus();
        $this->ss->clearUKind();
        $this->ss->clearMessage();
    }
}

?>