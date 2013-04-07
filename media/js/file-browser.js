$(document).on("click", ".folder a", function(){
    var path = $(this).closest("div").attr("data-name");
    var self = $(this).closest("div");
    $.ajax({
        type: "get",
        dataType: "json",
        url: "./get/dirContents.php?p=" + project + "&path=" + escape(path),
        success: function(data){
            var files = data["files"];
            var folders = data["folders"];
            str = "<div class='dir-content'>";
            for(var i in folders){
                str += "<div class='folder' data-name='" + folders[i] + "'><a href=''>" + i + "</a></div>";
            }
            for(i in files){
                str += "<div class='file' data-name='" + files[i] + "'><a href=''>" + i + "</a></div>";
            }
            str += "</div>";
            if(self.find("div.dir-content").length === 0){
                self.append(str);
            }else{
                self.find("div.dir-content").remove();
                self.append(str);
            }
        }
    });
    return false;
});

$(document).on("click", ".file a", function(){
    var path = $(this).closest("div").attr("data-name");
    curFile = path;
    openFile(path);
    return false;
});

function openFile(path, complete){
    $.ajax({
        type: "get",
        url: "./get/fileContents.php?p=" + project + "&file=" + escape(path),
        success: function(code){
            editor.setValue(code);
            var pos = path.lastIndexOf(".");
            setMode(path.substr(pos + 1));
            if(typeof complete === "function"){
                complete();
            }
        }
    });
}

$("#run").click(function(){
    saveRun();
    return false;
});
$(document).keydown(function(e){
    if(e.keyCode === 13 && e.ctrlKey){
        saveRun();
    }
});

function saveRun(){
    save(function(){
        run();
    });
}

function save(complete){
    $.ajax({
        url: "./save.php",
        type: "post",
        data: {
            p: project,
            file: curFile,
            content: editor.getValue()
        },
        success: function(){
            complete();
        }
    });
}

function run(){
    var code = editor.getValue();
    $.ajax({
        url: "./run.php?p=" + project + "&file=" + escape(curFile),
        type: "get",
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

function setMode(mode){
    switch(mode){
        case "html":
            editor.setOption("mode", "text/html");
            break;
        case "css":
            editor.setOption("mode", "text/css");
            break;
        case "javascript":
            editor.setOption("mode", "text/javascript");
            break;
        case "php":
            editor.setOption("mode", "php");
            break;
        case "plain text":
            editor.setOption("mode", "text/plain");
            break;
        default:
            editor.setOption("mode", "text/plain");
    }
}