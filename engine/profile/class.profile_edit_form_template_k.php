<?php

/**
 * template form do register
 *
 *  2011-09-22  search, replace i sety
 */
class ProfileEditFormTemplateK
{
    private $content;
    private $search = array(
        '{%os_name%}',
        '{%os_surname%}',
        '{%os_street%}',
        '{%os_house_number%}',
        '{%os_postcode%}',
        '{%os_city%}',
        '{%os_phone%}',
        '{%f_name%}',
        '{%f_surname%}',
        '{%f_position%}',
        '{%f_company%}',
        '{%f_street%}',
        '{%f_house_number%}',
        '{%f_postcode%}',
        '{%f_city%}',
        '{%f_phone%}',
        '{%f_nip%}',
        '{%f_regon%}',
        '{%f_krs%}',
        '{%os_woj1%}',
        '{%os_woj2%}',
        '{%os_woj3%}',
        '{%os_woj4%}',
        '{%os_woj5%}',
        '{%os_woj6%}',
        '{%os_woj7%}',
        '{%os_woj8%}',
        '{%os_woj9%}',
        '{%os_woj10%}',
        '{%os_woj11%}',
        '{%os_woj12%}',
        '{%os_woj13%}',
        '{%os_woj14%}',
        '{%os_woj15%}',
        '{%os_woj16%}',
        '{%f_woj1%}',
        '{%f_woj2%}',
        '{%f_woj3%}',
        '{%f_woj4%}',
        '{%f_woj5%}',
        '{%f_woj6%}',
        '{%f_woj7%}',
        '{%f_woj8%}',
        '{%f_woj9%}',
        '{%f_woj10%}',
        '{%f_woj11%}',
        '{%f_woj12%}',
        '{%f_woj13%}',
        '{%f_woj14%}',
        '{%f_woj15%}',
        '{%f_woj16%}'
    );
    private $replace = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
    

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function setOs_name($os_name) {
        $this->replace[0] = $os_name;
    }

    public function setOs_surname($os_surname) {
        $this->replace[1] = $os_surname;
    }

    public function setOs_street($os_street) {
        $this->replace[2] = $os_street;
    }

    public function setOs_house_number($os_house_number) {
        $this->replace[3] = $os_house_number;
    }

    public function setOs_postcode($os_postcode) {
        $this->replace[4] = $os_postcode;
    }

    public function setOs_city($os_city) {
        $this->replace[5] = $os_city;
    }

    public function setOs_phone($os_phone) {
        $this->replace[6] = $os_phone;
    }

    public function setF_name($f_name) {
        $this->replace[7] = $f_name;
    }

    public function setF_surname($f_surname) {
        $this->replace[8] = $f_surname;
    }

    public function setF_position($f_position) {
        $this->replace[9] = $f_position;
    }

    public function setF_company($f_company) {
        $this->replace[10] = $f_company;
    }

    public function setF_street($f_street) {
        $this->replace[11] = $f_street;
    }

    public function setF_house_number($f_house_number) {
        $this->replace[12] = $f_house_number;
    }

    public function setF_postcode($f_postcode) {
        $this->replace[13] = $f_postcode;
    }

    public function setF_city($f_city) {
        $this->replace[14] = $f_city;
    }

    public function setF_phone($f_phone) {
        $this->replace[15] = $f_phone;
    }

    public function setF_nip($f_nip) {
        $this->replace[16] = $f_nip;
    }

    public function setF_regon($f_regon) {
        $this->replace[17] = $f_regon;
    }

    public function setF_krs($f_krs) {
        $this->replace[18] = $f_krs;
    }

    public function setOs_woj($w)
    {
        if(!is_null($w)) $this->replace[(19+$w)] = 'selected="selected"';
    }

    public function setF_woj($w)
    {
        if(!is_null($w)) $this->replace[(34+$w)] = 'selected="selected"';
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        return str_replace($this->search, $this->replace, $this->content);
    }
}

?>
