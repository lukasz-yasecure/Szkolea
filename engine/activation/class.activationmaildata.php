<?php

/**
 * dane z maila do aktywacji
 *
 *  2011-09-24  ostatni wglad
 */
class ActivationMailData
{
    private $mkey;
    private $ckey;

    /**
     *
     * @return string
     */
    public function getMkey() {
        return $this->mkey;
    }

    /**
     *
     * @param string $mkey
     */
    public function setMkey($mkey) {
        $this->mkey = $mkey;
    }

    /**
     *
     * @return string
     */
    public function getCkey() {
        return $this->ckey;
    }

    /**
     *
     * @param string $ckey
     */
    public function setCkey($ckey) {
        $this->ckey = $ckey;
    }


}

?>
