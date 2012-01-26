<?php

/**
 * posrednik w pracy na zmiennych sesji
 * przenoszenie bledow miedzy stronami
 * przenoszenie wartosci z pol formularzy
 */
class SSMan
{
    // licznik bledow
    private $error_counter = 0;
    
    public function __construct()
    {
        if(!isset($_SESSION))
            session_start();

        if(isset($_SESSION['msg']) && !is_array($_SESSION['msg']))
        {
            $_SESSION['msg'] = array();
        }
    }

    public function regenerateSID()
    {
        session_regenerate_id(true);
    }

    public function clearMail()
    {
        if(isset($_SESSION['mail']))
        {
            $_SESSION['mail'] = '';
            unset($_SESSION['mail']);
        }
    }

    public function clearUID()
    {
        if(isset($_SESSION['uid']))
        {
            $_SESSION['uid'] = '';
            unset($_SESSION['uid']);
        }
    }

    public function clearStatus()
    {
        if(isset($_SESSION['status']))
        {
            $_SESSION['status'] = '';
            unset($_SESSION['status']);
        }
    }

    public function clearDateReg()
    {
        if(isset($_SESSION['date_reg']))
        {
            $_SESSION['date_reg'] = '';
            unset($_SESSION['date_reg']);
        }
    }

    public function clearUKind()
    {
        if(isset($_SESSION['ukind']))
        {
            $_SESSION['ukind'] = '';
            unset($_SESSION['ukind']);
        }
    }

    public function setMessage($msg)
    {
        if(!isset($_SESSION['msg'][$msg]))
        {
            $_SESSION['msg'][] = $msg;
            $_SESSION['msg'][$msg] = $msg;
        }
    }

    public function getMessage()
    {
        if(isset($_SESSION['msg']))
        {
            $ile = count($_SESSION['msg']);
            $ile = 0.5 * $ile;

            if($ile >= 1)
            {
                $t = 0;
                
                while(!isset($_SESSION['msg'][$t]))
                {
                    $t++;
                }

                $msg = $_SESSION['msg'][$t];
                unset($_SESSION['msg'][$t]);
                unset($_SESSION['msg'][$msg]);
                return $msg;
            }
            else
            {
                return '';
            }
        }
    }

    public function clearMessage()
    {
        $_SESSION['msg'] = '';
        unset($_SESSION['msg']);
    }

    /**
     *
     * @param string $id zrodlo bledu (np. mail = pole na mail w formularzu)
     * @param string $msg tresc bledu
     */
    public function setError($id, $msg)
    {
        $_SESSION['error_'.$id] = $msg;
        $this->error_counter++;
    }

    /**
     *
     * @param string $id sprawdzamy po id czy jest jakis blad
     * @return string albo tresc bledu albo ''
     */
    public function getError($id)
    {
        if(isset($_SESSION['error_'.$id]))
        {
            $r = $_SESSION['error_'.$id];
            unset($_SESSION['error_'.$id]);
            $this->error_counter--;
            return $r;
        }
        else return '';
    }

    /**
     *
     * @return bool czy byly bledy?
     */
    public function anyErrors()
    {
        if($this->error_counter > 0) return true;
        else return false;
    }

    /**
     *
     * @param string $mail adres mailowy
     */
    public function setEMail($mail)
    {
        $_SESSION['mail'] = $mail;
    }

    /**
     *
     * @return string mail jesli jest przenoszony albo '' jesli nie
     */
    public function getEMail()
    {
        if(isset($_SESSION['mail']))
        {
            $m = $_SESSION['mail'];
            //unset($_SESSION['mail']);
            return $m;
        }
        else return '';
    }

    public function setStatus($s)
    {
        $_SESSION['status'] = $s;
    }

    public function getStatus()
    {
        if(isset($_SESSION['status']))
            return $_SESSION['status'];
        else
            return null;
    }

    public function setUID($uid)
    {
        $_SESSION['uid'] = $uid;
    }

    public function getUID()
    {
        if(isset($_SESSION['uid']))
            return $_SESSION['uid'];
        else
            return 0;
    }

    public function setDateReg($dr)
    {
        $_SESSION['date_reg'] = $dr;
    }

    public function getDateReg()
    {
        if(isset($_SESSION['date_reg']))
            return $_SESSION['date_reg'];
        else
            return 0;
    }

    public function setUKind($k)
    {
        $_SESSION['ukind'] = $k;
    }

    public function getUKind()
    {
        if(isset($_SESSION['ukind']))
            return $_SESSION['ukind'];
        else
            return null;
    }

    public function setRedirection($r)
    {
        $_SESSION['redirection'] = $r;
    }

    public function getRedirection()
    {
        if(isset($_SESSION['redirection']))
        {
            $r = $_SESSION['redirection'];
            unset($_SESSION['redirection']);
            return $r;
        }
        else return 'index.php';
    }

    public function saveInputsForComm($name, $data)
    {
        $_SESSION['comm'][$name] = $data;
    }

    public function saveErrorsForComm($name, $error)
    {
        $_SESSION['comm_errors'][$name] = $error;
    }
}

?>
