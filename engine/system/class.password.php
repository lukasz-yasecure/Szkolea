<?php

class Password
{
    private $hash;
    
    public function __construct($pass)
    {
        $this->hash = sha1(Salts::getPasswordSalt().$pass);
    }

    public function getHash()
    {
        return $this->hash;
    }
}

?>
