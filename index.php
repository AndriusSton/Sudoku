

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sudoku</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>
    <body>
        <?php
        echo '<table>';
        for ($i = 0; $i < 9; $i++) {
            echo '<tr id="' . $i . '">';
            for ($j = 0; $j < 9; $j++) {
                echo '<td id="' . $j . '">' . $i . ' ' . $j . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        ?>
    </body>
</html>
