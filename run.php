<?php

//eval($_POST["code"]);

require_once __DIR__ . "/classes/Fiddle.php";

if(!isset($_GET["p"]))
    exit;

$project = $_GET["p"];
$path    = $_GET["file"];

$fiddle = new Fiddle();
if(!$fiddle->isProject($project)){
    echo json_encode(["Bad Project"]);
    exit;
}
$runPath = $fiddle->getPath($project, $path);
$opt     = [];
$dir     = pathinfo($runPath)["dirname"];
exec("cd $dir && /php/5.4.8/php -f " . __DIR__ . "/run/run.php $runPath", $opt);

echo implode(" ", $opt);

//echo json_encode([$path]);
//echo $fiddle->getFile($project, $path);
//$file = $_GET["file"];