<?php

class MailDidNotSend extends Exception
{
  public function __construct($error, $errno = 0){
    parent::__construct($error, $errno);
  }
}

/**
 * klasa wysyla gotowe maile na podany adres
 *
 *  2011-09-21  sendRemindMail() +
 */
class Mailer
{
    /**
     *
     * @param System $sys
     * @param ActivationMail $am
     * @throws MailDidNotSend jesli nie udalo sie wyslac maila
     */
    public function sendActivationMail(System $sys, ActivationMail $am)
    {
        $naglowki = 'Reply-to: '.$sys->getMailSzkolea().PHP_EOL;
        $naglowki.= 'From: '.$sys->getMailSzkolea().PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0'.PHP_EOL;
        $naglowki.= 'Content-type: text/html; utf-8'.PHP_EOL;

        if(!@mail($am->getReceiver(), 'Aktywacja w serwisie szkolea.pl', $am->getContent(), $naglowki))
                throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: '.$am->getReceiver());
    }

    /**
     *
     * @param System $sys
     * @param RemindMail $rm
     * @throws MailDidNotSend jesli nie udalo sie wyslac maila
     */
    public function sendRemindMail(System $sys, RemindMail $rm)
    {
        $naglowki = 'Reply-to: '.$sys->getMailSzkolea().PHP_EOL;
        $naglowki.= 'From: '.$sys->getMailSzkolea().PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0'.PHP_EOL;
        $naglowki.= 'Content-type: text/html; utf-8'.PHP_EOL;

        if(!@mail($rm->getReceiver(), 'Nowe hasło do serwisu szkolea.pl', $rm->getContent(), $naglowki))
                throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: '.$rm->getReceiver());
    }
}

?>
