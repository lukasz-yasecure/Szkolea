<?php

class MailDidNotSend extends Exception {

    public function __construct($error, $errno = 0) {
        parent::__construct($error, $errno);
    }

}

/**
 * klasa wysyla gotowe maile na podany adres
 *
 *  2011-09-21  sendRemindMail() +
 */
class Mailer {

    /**
     *
     * @param System $sys
     * @param ActivationMail $am
     * @throws MailDidNotSend jesli nie udalo sie wyslac maila
     */
    public function sendActivationMail(System $sys, ActivationMail $am) {
        $naglowki = 'Reply-to: ' . $sys->getMailSzkolea() . PHP_EOL;
        $naglowki.= 'From: ' . $sys->getMailSzkolea() . PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0' . PHP_EOL;
        $naglowki.= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;

        if (!@mail($am->getReceiver(), 'Aktywacja w serwisie szkolea.pl', $am->getContent(), $naglowki))
            throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: ' . $am->getReceiver());
    }

    /**
     *
     * @param System $sys
     * @param RemindMail $rm
     * @throws MailDidNotSend jesli nie udalo sie wyslac maila
     */
    public function sendRemindMail(System $sys, RemindMail $rm) {
        $naglowki = 'Reply-to: ' . $sys->getMailSzkolea() . PHP_EOL;
        $naglowki.= 'From: ' . $sys->getMailSzkolea() . PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0' . PHP_EOL;
        $naglowki.= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;

        if (!@mail($rm->getReceiver(), 'Nowe hasło do serwisu szkolea.pl', $rm->getContent(), $naglowki))
            throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: ' . $rm->getReceiver());
    }

    public function sendMail($do, $od, $temat, $tresc) {
        $naglowki = 'Reply-to: ' . $od . PHP_EOL;
        $naglowki.= 'From: ' . $od . PHP_EOL;
        $naglowki.= 'MIME-Version: 1.0' . PHP_EOL;
        $naglowki.= 'Content-type: text/html; charset=UTF-8' . PHP_EOL;

        if (!@mail($do, $temat, $tresc, $naglowki))
            throw new MailDidNotSend('Wiadomosc nie zostala wyslana na adres: ' . $do);
    }

    public function infoWybranaOfertaWlasciciel(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoWybranaOfertaWlasciciel());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Twoja oferta została wybrana', $tm->getContent());
    }

    public function infoOdrzuconaOfertaWlasciciel(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoOdrzuconaOfertaWlasciciel());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Twoja oferta została odrzucona', $tm->getContent());
    }

    public function infoNowaOfertaWlascicielZlecenia(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoNowaOfertaWlascicielZlecenia());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Nowa oferta w Twoim zleceniu', $tm->getContent());
    }

    public function infoNowaOfertaObserwujacyZlecenie(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoNowaOfertaObserwujacyZlecenie());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Nowa oferta w zleceniu, które obserwujesz', $tm->getContent());
    }

    public function infoWybranaOfertaDodaneDoZlecenia(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoWybranaOfertaDodaneDoZlecenia());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Oferta zlecenia do którego jesteś dodany została wybrana', $tm->getContent());
    }

    public function infoOdrzuconaOfertaDodaneDoZlecenia(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoOdrzuconaOfertaDodaneDoZlecenia());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Oferta zlecenia do którego jesteś dodany została odrzucona', $tm->getContent());
    }

    public function infoZakonczoneZlecenieWlasciciel(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoZakonczoneZlecenieWlasciciel());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Twoje zlecenie właśnie się zakończyło', $tm->getContent());
    }

    public function infoZakonczoneZlecenieDodane(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoZakonczoneZlecenieDodane());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Zlecenie, do którego się dopisałeś właśnie się zakończyło', $tm->getContent());
    }

    public function infoZakonczoneZlecenieOferty(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoZakonczoneZlecenieOferty());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Zlecenie, do którego dodałeś ofertę właśnie się zakończyło', $tm->getContent());
    }

    //rozsyłanie Newslettera do użytkowników zgodnie z wyborem opcji (klienci, usługodawcy, wszyscy). Mail idzie zawsze także do Adminów
    public function sendNewsletter(Newsletter $n) {

        //szablony od maila
        $t_mail = new Template(Pathes::getPathTemplateMailNewsletter());
        $t_mail_list = new Template(Pathes::getPathTemplateMailNewsletterList());

        //wrzucamy do szablony od maila temat i treść
        $t_mail->addSearchReplace('subject', $n->getSubject());
        $t_mail->addSearchReplace('content', $n->getContent());

        $promoted_list = '';

        //pobieramy kolejno usług z Newsletter
        while (!is_null($promoted = $n->getService())) {
            //do szablonu wrzcamy kolejno link do SERVICE i NAZWĘ do wyświetlenia

            $t_mail_list->addSearchReplace('serv_link', Pathes::getScriptServicePath($promoted->getId_serv()));
            $t_mail_list->addSearchReplace('name_serv', $promoted->getName());

            //dołączamy kolejne promowane usługi do całej listy
            $promoted_list .= $t_mail_list->getContent();
            $t_mail_list->clearSearchReplace();
        }



        //wklejamy do szablonu listę promowanych
        $t_mail->addSearchReplace('promoted', $promoted_list);

        //rozsyłamy maile do wszystkich z grupy docelowej pobierając po kolei odbiorców z Newsletter
        while (!is_null($receiver = $n->getReceiver())) {
            $this->sendMail($receiver, 'noreply@szkolea.pl', $n->getSubject(), $t_mail->getContent());
        }
    }

    public function infoUnpaidInvoice(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoUnpaidInvoice());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Dostępna faktura pro forma', $tm->getContent());
    }

    public function infoPaidInvoice(User $adresat) {
        $tm = new Template(Pathes::getPathTemplateMailInfoPaidInvoice());
        $this->sendMail($adresat->getEmail(), 'noreply@szkolea.pl', 'Dostępna faktura vat', $tm->getContent());
    }

    //wysłanie maila do Szkolea o prośbie użytkownika o baner
    public function sendToAdminBanerRequest(User $u) {
        $t_mail = new Template(Pathes::getPathTemplateMailBanerRequest());

        //wrzucamy do szablony od maila ID użytkownika i adres email
        $t_mail->addSearchReplace('id', $u->getId_user());
        $t_mail->addSearchReplace('email', $u->getEmail());

        $this->sendMail('biuro@szkolea.pl', 'noreply@szkolea.pl', 'Zapytanie o baner (użytkownik ID:' . $u->getId_user() . ')', $t_mail->getContent());
    }

}

?>
