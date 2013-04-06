<?php
require_once "classes/Fiddle.php";
$p = "";
if(isset($_GET["p"])){
    $p = $_GET["p"];
}
$fiddle   = new Fiddle();
$fiddle->create($p);
?><!DOCTYPE HTML>
<html>
    <head>
        <title>PHP Fiddle</title>
        <link rel="stylesheet" href="./media/css/main.css" />
        <link rel="stylesheet" href="./media/css/codemirror.css">

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

        <script src="./media/js/codemirror.js"></script>
        <script src="./media/js/support/matchbrackets.js"></script>
        <script src="./media/js/support/htmlmixed.js"></script>
        <script src="./media/js/support/xml.js"></script>
        <script src="./media/js/support/javascript.js"></script>
        <script src="./media/js/support/css.js"></script>
        <script src="./media/js/support/clike.js"></script>
        <script src="./media/js/support/php.js"></script>
        <script src="./media/js/support/active-line.js"></script>
        <script src="./media/js/support/autoformat.js"></script>
    </head>
    <body>
        <div id="top-nav">
            <div id="links">
                <a href="" id="run" title="Ctrl + Enter">Run</a>
            </div>
        </div>
        <div id="left-nav">
            <div class="nav-section">
                <select id="run-type">
                    <option>Run as Http</option>
                    <option>Run as CLI</option>
                </select>
            </div>
            <div class="nav-section">
                <span class="file-browser">File Browser</span>
                <div id="file-browser"><?php
                    $project  = $fiddle->getProject();
                    $contents = $fiddle->getContents($project);
                    $files    = $contents["files"];
                    $folders  = $contents["folders"];
                    echo "<div style='font-weight: bolder;'>$project</div>";

                    foreach($folders as $base => $folder){
                        echo "<div class='folder' data-name='$folder'>
                                <a href=''>" . $base . "</a>
                              </div>";
                    }
                    foreach($files as $base => $file){
                        echo "<div class='file' data-name='$file'>
                                <a href=''>" . $base . "</a>
                                    </div>";
                    }
                    ?></div>
            </div>
        </div>
        <div id="code" style='-moz-user-select: none;-webkit-user-select: none;' onselectstart='return false;'>
            <textarea id="editor"></textarea>
        </div>
        <div id="output">
            <iframe src="" id="results"></iframe>
        </div>
        <script>
            var project = '<?php echo $project; ?>';
            var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
                autofocus: true,
                lineNumbers: true,
                matchBrackets: true,
                mode: "application/x-httpd-php",
                tabSize: 4,
                indentUnit: 4,
                enterMode: "keep",
                tabMode: "spaces",
                styleActiveLine: true,
                autoEnabled: true,
                lineWrapping: true,
                viewportMargin: Infinity,
                extraKeys: {
                    Tab: function betterTab(cm){
                        if(cm.somethingSelected()){
                            cm.indentSelection("add");
                        }else{
                            cm.replaceSelection(cm.getOption("indentWithTabs") ? "\t" :
                                    Array(cm.getOption("indentUnit") + 1).join(" "), "end", "+input");
                        }
                    },
                    /*
                     * HTML Options
                     */
                    "'>'": function(cm){
                        cm.closeTag(cm, '>');
                    },
                    "'/'": function(cm){
                        cm.closeTag(cm, '/');
                    },
                    /*
                     * Other Language options
                     */
                    "'('": function(cm){
                        cm.autoFormat(cm, '(');
                    },
                    "')'": function(cm){
                        cm.autoFormat(cm, ')');
                    },
                    "'\''": function(cm){
                        cm.autoFormat(cm, '\'');
                    },
                    "'\"'": function(cm){
                        cm.autoFormat(cm, '\"');
                    },
                    "'{'": function(cm){
                        cm.autoFormat(cm, '{');
                    },
                    "'['": function(cm){
                        cm.autoFormat(cm, '[');
                    },
                    "']'": function(cm){
                        cm.autoFormat(cm, ']');
                    },
                    "Enter": function(cm){
                        cm.autoFormat(cm, '13');
                    },
                    "Backspace": function(cm){
                        cm.autoFormat(cm, '12');
                    },
                    "Ctrl-D": function(cm){
                        cm.autoFormat(cm, 'CtrlD');
                    },
                    "Ctrl-Up": function(cm){
                        cm.autoFormat(cm, 'CtrlUp');
                    },
                    "Ctrl-Down": function(cm){
                        cm.autoFormat(cm, 'CtrlDown');
                    },
                    "Shift-Ctrl-Down": function(cm){
                        cm.autoFormat(cm, 'CtrlShiftDown');
                    }
                }
            });
            var width = $("#code").width();
            var pos = $("#code").position();
            var codeSize = width + pos.left + 8;
            $("#output").css({left: codeSize + "px"});
            $("#code").click(function(){
                var pos = editor.getCursor();
                var lines = editor.lineCount();
                var ch = editor.getLine(lines - 1);
                pos = {line: lines, ch: ch};
                editor.setCursor(pos);
                editor.focus();
            });
            function run(){
                var code = editor.getValue();
                $.ajax({
                    url: "./run.php",
                    type: "POST",
                    data: {
                        code: code
                    },
                    success: function(data){
                        var ifrm = document.getElementById('results');
                        ifrm = (ifrm.contentWindow)
                                ? ifrm.contentWindow
                                : (ifrm.contentDocument.document)
                                ? ifrm.contentDocument.document
                                : ifrm.contentDocument;
                        ifrm.document.open();
                        ifrm.document.write(data);
                        ifrm.document.close();
                    }
                });
            }
            $("#run").click(function(){
                run();
                return false;
            });
            $(document).keydown(function(e){
                if(e.keyCode === 13 && e.ctrlKey){
                    run();
                }
            });
        </script>
        <script src="./media/js/file-browser.js"></script>
    </body>
</html>