<?php

if(!isset($argv)){
    exit;
}
$file = $argv[1];

$functions = glob(__DIR__."/functions/*.php");

foreach($functions as $func){
    require_once $func;
}