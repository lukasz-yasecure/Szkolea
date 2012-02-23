<?php

    try
    {
        $pdo = new PDO('mysql:host=localhost;dbname=test_pdo', 'root', 'root');
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // INSERT UPDATE robi sie exec() zwraca liczbe zmodyfikowanych rekordow
        $stmt = $pdo -> query('SELECT * FROM test');
        echo '<ul>';
        foreach($stmt as $row)
        {
            echo '<li>'.$row['id'].': '.$row['name'].'</li>';
        }
        $stmt -> closeCursor();
        echo '</ul>';

        $stmt = $pdo -> prepare('INSERT INTO `test` (`id`, `name`) VALUES(
                :id,
                :name)');     // 1

        $stmt -> bindValue(':id', time(), PDO::PARAM_INT); // 2
        $stmt -> bindValue(':name', 'hehe', PDO::PARAM_STR);

        $ilosc = $stmt -> execute(); // 3

        if($ilosc > 0)
        {
                echo 'Dodano: '.$ilosc.' rekordow';
        }
        else
        {
                echo 'Wystapil blad podczas dodawania rekordow!';
        }
    }
    catch(PDOException $e)
    {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    }

?>