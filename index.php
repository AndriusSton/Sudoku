
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
                <div class='col-lg-3'></div>
                <div class='col-lg-3'>
                    <form>
                        <select id='level' class="form-control">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                        </select>
                    </form>
                </div>
                <div class='col-lg-3'>
                    <button type="button" class='btn btn-danger' id='generate'>Generate</button>
                    <button type="button" class='btn btn-primary' id='solve'>Solution</button>
                    <button type="button" class='btn btn-primary' id='reset'>Reset</button>
                    <button type="button" class='btn btn-primary' id='getpdf'>Get PDF</button>
                </div>
                <div class='col-lg-3'></div>
            </div>

            <div class='row'>
                <div class='col-lg-3'>
                </div>
                <div id='grid' class='col-lg-6'>
                </div>
                <div class='col-lg-3'>
                </div>
            </div>
        </div>
        <script src="js/main.js"></script>
    </body>
</html>
