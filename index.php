<?php
require 'C:\xampp\htdocs\Sudoku\table.php';

$table = new table();
$cells = $table->getTable();

$possible_cell_values = $table->getPossible();
$wrong_choices = array();

for ($j = 0; $j < 81; $j++) {
    $possible_cell_values = $table->getPossibleValues($j, $cells);
    //echo 'Cell ' . $j . '<br/>';
    //echo ' Size of choices: ' . sizeof($possible_cell_values) . '<br/>';
    //print_r($possible_cell_values);

    if (empty($possible_cell_values)) {
        do {
            $j--;
            //echo '<br/> ! BACK TO ' . $j . '<br/>';
            $possible_cell_values = $table->getPossibleValues($j, $cells);

            if (array_key_exists($j, $wrong_choices)) {
                array_push($wrong_choices[$j], $cells[$j]);
            } else {
                $wrong_choices[$j] = array($cells[$j]);
            }

            $cells[$j] = 0;
            
            if ($j < max(array_keys($wrong_choices))) {
                foreach (array_keys($wrong_choices) as $key) {
                    if ($key > $j) {
                        unset($wrong_choices[$key]);
                    }
                }
            }

            //print_r($wrong_choices);
            foreach ($wrong_choices[$j] as $choice) {
                $possible_cell_values = $table->removeFromPossible($possible_cell_values, $choice);
            }
        } while (sizeof($possible_cell_values) < 1);

    }

    $cells[$j] = $table->getRandomNumber($possible_cell_values);

}
echo '<pre>';
print_r($cells);
echo '</pre>';
?>

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
