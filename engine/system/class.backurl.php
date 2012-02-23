<?php

/**
 *  2011-10-11  1st
 */
class BackURL
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getURL()
    {
        return $this->url;
    }
}

?>
