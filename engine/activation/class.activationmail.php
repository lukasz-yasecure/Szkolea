<?php

/**
 * mail aktywacyjny (kontener)
 *
 *  2011-09-21  ostatni wglad
 */
class ActivationMail
{
    private $content;
    private $receiver;

    /**
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     *
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     *
     * @return string
     */
    public function getReceiver() {
        return $this->receiver;
    }

    /**
     *
     * @param string $receiver
     */
    public function setReceiver($receiver) {
        $this->receiver = $receiver;
    }
}

?>
