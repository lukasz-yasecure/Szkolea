<?php

            $msg = 'http://szkolea.pl/szkolea/';

            $content = file_get_contents('view/html/mail_aktywacja.html');
            $content = str_replace('{%link%}', $msg, $content);

            $headers = 'From: Maria Curie-Sklodowska <maria@curie-sklodowska.pl>'."\r\n".'Reply-To: Rzecznik pani Marii <lukasz@yasecure.pl>'."\r\n";

            $msg = '... ze jakiegos komiksa czy inne diabelstwo mi sie wygralo! Prawda to?'."\n\n\n\n\n";
            $msg.= 'Powyzszy mail to oczywiscie zart sytuacyjny zmyslnie przygotowany w celu zgarniecia komiksu :) a adres maria@curie-sklodowska.pl nie istnieje.'."\n";
            $msg.= 'W razie checi skomentowania mego niecnego wystepku prosze posluzyc sie adresem lukasz@yasecure.pl'."\n\n";
            $msg.= 'Pozdrawiam serdecznie! :)';

            mail('wilq@wilq.pl', 'W tym Waszym Internecie powiadaja ...', $msg, $headers);

?>