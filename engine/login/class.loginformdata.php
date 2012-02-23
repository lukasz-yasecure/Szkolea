<?php

/**
 * dane z forma do log
 *
 *  2011-10-10  1 podejscie
 *  2011-10-11  + back
 *              - back :P
 */
class LoginFormData
{
    private $email;
    private $pass;

    /**
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     *
     * @param Password $p
     */
    public function setPass(Password $p)
    {
        $this->pass = $p;
    }

    /**
     *
     * @return Password
     */
    public function getPass()
    {
        return $this->pass;
    }
}

?>
