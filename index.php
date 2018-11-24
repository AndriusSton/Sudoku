<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sudoku</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>
    <body>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-3'>
                    <button type="button" class='btn btn-danger' id='generate'>Give me a Sudoku</button>
                    <button type="submit" class='btn btn-success' id='submit'>Submit</button>
                    <button type="submit" class='btn btn-primary' id='solve'>I give up</button>
                </div>
                <div id='grid' class='col-lg-6'>
                    
                </div>
                <div id='spacer' class='col-lg-3'>
                    <p id="serverResponse"></p>
                </div>
            </div>
        </div>
        <script src="js/main.js"></script>
    </body>
</html>
