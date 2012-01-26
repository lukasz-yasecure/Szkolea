<?php

/**
 * template forma do reminda
 *
 *  2011-09-21  ostatni wglad
 */
class PasswordChangeFormTemplate
{
    private $content;

    /**
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

?>
