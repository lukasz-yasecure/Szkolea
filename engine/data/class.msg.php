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
}

?>
