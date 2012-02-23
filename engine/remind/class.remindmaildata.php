<?php

/**
 * dane z maila do reminda
 *
 *  2011-09-21  ostatni wglad
 */
class RemindMailData
{
    private $email;
    private $key;

    /**
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     *
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
    }

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
