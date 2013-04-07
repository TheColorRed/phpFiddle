<?php

//eval($_POST["code"]);

require_once __DIR__ . "/classes/Fiddle.php";

if(!isset($_POST["p"]))
    exit;

$project = $_POST["p"];
$path    = $_POST["file"];

$fiddle = new Fiddle();
if(!$fiddle->isProject($project)){
    echo json_encode(["Bad Project"]);
    exit;
}
$runPath = $fiddle->getPath($project, $path);

file_put_contents($runPath, $_POST["content"]);

//echo json_encode([$path]);
//echo $fiddle->getFile($project, $path);
//$file = $_GET["file"];