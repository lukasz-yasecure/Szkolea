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
        return 'Nie masz aktywnych pakietów pozwalających na dodawanie usług.';
    }

    public static function profileNoOffersAllow() {
        return 'Nie masz aktywnych pakietów pozwalających na dodawanie ofert.';
    }

    public static function addServProgramZaDlugi() {
        return 'Program może mieć co najwyżej 1500 znaków!';
    }
    
    public static function profileNoCardUpdate() {
        return 'Nie udało się zaktualizować wizytówki!';
    }
    
    
}

?>
