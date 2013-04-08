$("#run").click(function(){
    saveRun();
    return false;
});

$("#save").click(function(){
    save();
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
        url: "/phpFiddle/save.php",
        type: "post",
        data: {
            p: project,
            file: curFile,
            content: editor.getValue()
        },
        success: function(){
            if(typeof complete === "function"){
                complete();
            }
        }
    });
}

function run(){
    //var code = editor.getValue();
    //$("#results").attr("src", "/phpFiddle/run.php?p=" + project + "&file=" + btoa(curFile));
    $.ajax({
        url: "/phpFiddle/run.php?p=" + project + "&file=" + escape(curFile),
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