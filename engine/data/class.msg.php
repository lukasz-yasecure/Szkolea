<?php

class MSG {

    public static function activationMailFail() {
        return 'Nie udało się wysłać maila aktywującego konto. Prosimy o kontakt z administratorem!';
    }

    public static function activationMailSend() {
        return 'Wysłaliśmy do Ciebie mail z linkiem do aktywacji konta.';
    }

    public static function registerComplete() {
        return 'Dziękujemy za rejestrację!';
    }

    public static function registerErrorEmailIsNotAvailable() {
        return 'Podany przez Ciebie e-mail jest już w użyciu, wybierz inny.';
    }

    public static function sendMailFail() {
        return 'Nie udało się wysłać maila! Spróbuj ponownie później.';
    }

    public static function profilePackageIdError() {
        return 'Wybrałeś niepoprawny pakiet. Spróbuj ponownie.';
    }

    public static function profilePackageAddError() {
        return 'Nie dodano pakietu. Spróbuj ponownie.';
    }

    public static function profilePackageAddErrorInRegister() {
        return 'Konto zostało zarejestrowane, ale wystąpiły błedy. Skontaktuj się z administratorem!';
    }

    public static function profileNoPackages() {
        return 'Nie masz aktywnych pakietów. Skontaktuj się z administratorem.';
    }

    public static function profileAddPackagesSuccess() {
        return 'Dodano pomyślnie pakiet.';
    }

    public static function profileNoServicesAllow() {
        return 'Nie masz aktywnych pakietów pozwalających na dodawanie usług. Aby dodać usługę <a href="' . Pathes::getScriptProfilePackageBuyingPath() . '">kup jeden z pakietów</a>.';
    }

    public static function profileNoOffersAllow() {
        return 'Nie masz aktywnych pakietów pozwalających na dodawanie ofert. Aby złożyć ofertę realizacji zlecenia <a href="' . Pathes::getScriptProfilePackageBuyingPath() . '">kup jeden z pakietów</a>.';
    }

    public static function addServProgramZaDlugi() {
        return 'Program może mieć co najwyżej 1500 znaków!';
    }

    public static function profileNoCardUpdate() {
        return 'Nie udało się zaktualizować wizytówki!';
    }

    public static function addServProgramNiedozwoloneZnaki() {
        return 'Program, który podałeś, zawiera niedozwolone znaki!';
    }

    public static function profileOfferChosen() {
        return 'Oferta została wybrana! Powiadomimy o Twoim wyborze zapisane osoby oraz właściciela wybranej oferty.';
    }

    public static function profileNoOfferChosen() {
        return 'Zrezygnowałeś z wyboru oferty!';
    }

    public static function profileBlednyAdresWWW() {
        return 'Błędny adres strony internetowej!';
    }

    public static function profileOpisZaDlugi() {
        return 'Opis jest zbyt długi!';
    }

    public static function profileCardUpdate() {
        return 'Twoja wizytówka została zaktualizowana!';
    }

    public static function ongoing() {
        return 'Trwa';
    }

    public static function finished() {
        return 'Zakończono';
    }

    public static function waitingForChoice() {
        return 'Czekamy na wybór oferty';
    }

    public static function offerChosen() {
        return 'Oferta wybrana';
    }

    public static function offerUnchosen() {
        return 'Oferta nie została wybrana';
    }

    public static function noOffer() {
        return 'Nie było ofert';
    }

    public static function NoText() {
        return 'Nie wprowadzono tekstu.';
    }

    public static function NoSubject() {
        return 'Nie wprowadzono tematu.';
    }

    public static function NoChoice() {
        return 'Nie dokonano wyboru opcji.';
    }

    public static function NewsletterSent() {
        return 'Newsletter został rozesłany.';
    }

    public static function ServicePromotionSet() {
        return 'Ustawiono promocję usługi.';
    }

    public static function instertError() {
        return 'Dane nie zostały zapisane! Jeśli problem będzie się powtarzał skontaktuj się z administratorem.';
    }

    public static function profileNoPromotionAllow() {
        return 'Nie masz aktywnych pakietów pozwalających na promowanie usług. Aby promować usługę <a href="' . Pathes::getScriptProfilePackageBuyingPath() . '">kup jeden z pakietów</a>.';
    }

    public static function profileNoBanerAllow() {
        return 'Nie masz aktywnych pakietów pozwalających na dodanie banera. Aby promować usługę <a href="' . Pathes::getScriptProfilePackageBuyingPath() . '">kup jeden z pakietów</a>.';
    }

    public static function noServices() {
        return 'Nie masz żadnych aktywnych usług.';
    }

    public static function paymentThankYou() {
        return 'Dziękujemy za dokonanie wpłaty!';
    }

    public static function submitForBaner() {
        return 'Użyj poniższego przycisku, aby wysłać zapytanie o dodanie banera na nasza strone. Osoba odpowiadająca za banery wkrótce się z Tobą skontaktuje.';
    }

    public static function submitedForBaner() {
        return 'Zapytanie o dodanie banera zostało wysłane.';
    }

}

?>
