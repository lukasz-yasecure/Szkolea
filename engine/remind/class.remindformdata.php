<?php

/**
 * dane z forma do reminda
 *
 *  2011-09-21  ostatni wglad
 */
class RemindFormData
{
    private $email;

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
}

?>
