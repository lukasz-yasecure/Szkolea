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
        $naglowki.= 'Content-type: text/html; UTF-8'.PHP_EOL;

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
        $naglowki.= 'Content-type: text/html; UTF-8'.PHP_EOL;

        if(!@mail($rm->getReceiver(), 'Nowe hasło do serwisu szkolea.pl', $rm->getContent(), $naglowki))
                throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: '.$rm->getReceiver());
    }
    
    public function sendMail($do, $od, $temat, $tresc)
    {
        $naglowki = 'Reply-to: '.$od.PHP_EOL;
        $naglowki.= 'From: '.$od.PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0'.PHP_EOL;
        $naglowki.= 'Content-type: text/html; UTF-8'.PHP_EOL;

        if(!@mail($do, $temat, $tresc, $naglowki))
                throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: '.$do);
    }
    
    public function infoWybranaOfertaWlasciciel(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoWybranaOfertaWlasciciel());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Twoja oferta została wybrana', $tm->getContent());
    }
    
    public function infoOdrzuconaOfertaWlasciciel(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoOdrzuconaOfertaWlasciciel());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Twoja oferta została odrzucona', $tm->getContent());
    }

    public function infoNowaOfertaWlascicielZlecenia(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoNowaOfertaWlascicielZlecenia());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Nowa oferta w Twoim zleceniu', $tm->getContent());
    }

    public function infoNowaOfertaObserwujacyZlecenie(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoNowaOfertaObserwujacyZlecenie());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Nowa oferta w zleceniu, które obserwujesz', $tm->getContent());
    }
    
    public function infoWybranaOfertaDodaneDoZlecenia(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoWybranaOfertaDodaneDoZlecenia());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Oferta zlecenia do którego jesteś dodany została wybrana', $tm->getContent());
    }

    public function infoOdrzuconaOfertaDodaneDoZlecenia(User $adresat)
    {
        $tm = new Template(Pathes::getPathTemplateMailInfoOdrzuconaOfertaDodaneDoZlecenia());
        $this->sendMail($adresat->getEmail(),'noreply@szkolea.pl', 'Oferta zlecenia do którego jesteś dodany została odrzucona', $tm->getContent());
    }
}

?>
