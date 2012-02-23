<?php

/**
 *  2011-11-08  stworzylem metode do latwego zarzadzania wynikami do wyswietlenie (Commisions / Services)
 */
class Results
{
    private $c;
    private $s;
    private $commisions = array();
    private $commisions_index = 0; // iterator
    private $services = array();
    private $services_index = 0; // iterator

    public function  __construct()
    {
        $this->c = false;
        $this->s = false;
    }

    public function setCommisions()
    {
        $this->c = true;
    }

    public function setServices()
    {
        $this->s = true;
    }

    public function areCommisionsSet()
    {
        return $this->c;
    }

    public function addComm(Commision $c)
    {
        $this->commisions[] = $c;
    }

    /**
     *
     * @return Commision
     */
    public function getComm()
    {
        if($this->commisions_index == count($this->commisions)) return null;
        else
        {
            $this->commisions_index++;
            return $this->commisions[$this->commisions_index-1];
        }
    }

    public function addServ(Service $s)
    {
        $this->services[] = $s;
    }

    /**
     *
     * @return Service
     */
    public function getServ()
    {
        if($this->services_index == count($this->services)) return null;
        else
        {
            $this->services_index++;
            return $this->services[$this->services_index-1];
        }
    }
}

?>
