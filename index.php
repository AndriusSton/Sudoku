<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sudoku</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>  
    </head>
    <body>
        <div class="container">
            <div id="level-menu">
                <h1>Choose Your level and PLAY!</h1>             
                <ul id="level-list">
                    <li><button type="button" class="level-btn" id="easy" onclick="requestGrid('easy');">Easy</button></li>
                    <li><button type="button" class="level-btn" id="medium" onclick="requestGrid('medium');">Medium</button></li>
                    <li><button type="button" class="level-btn" id="hard" onclick="requestGrid('hard');">Hard</button></li>
                </ul>
            </div>
            <div id="alert">
                <span class="closebtn" onclick="this.parentElement.style.display = 'none';">&times;</span> 
                <strong id="message"></strong>
            </div>
            <div id="main-container" class="hidden">
                <div id="pdf-config" class="hidden">
                    <form>
                        <label>Select Level</label><br/>
                        <select id="level">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select><br/>
                        <label>Select the number of puzzles</label><br/>
                        <input type="number" id="num-of-grids" min="1" max="100" autocomplete="off"/><br/>
                        <button type="button" id="get-pdf-btn" class="grid-control-btn">Get PDF</button>
                    </form>
                </div>
                <div id="grid" class="hidden">
                    <div id="table" ></div>
                    <ul id="controls">
                        <li><button type="button" class="grid-control-btn" id="solution-btn">Solution</button></li>
                        <li><button type="button" class="grid-control-btn" id="reset-btn">Reset</button></li>
                    </ul>
                </div>
            </div>
            <div id="pdf-generator">
                <h3>Click <button type="button" id="pdf-config-btn" onclick='displayPDFConfig();'>HERE</button> for PDF Download</h3>
            </div>
        </div>
        <script src="js/main.js"></script>
    </body>
</html>
