<?php

/**
 * dane z forma do zmiany passa
 *
 *  2011-09-21  ostatni wglad
 *  2011-10-19  setPass <- Password
 */
class PasswordChangeFormData
{
    private $pass;

    /**
     *
     * @return Password
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     *
     * @param Password $pass
     */
    public function setPass(Password $pass) {
        $this->pass = $pass;
    }
}

?>
