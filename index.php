<?php

$parse_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch($parse_url)
{
    case '/':
    break;

    default:
        echo "Erro 404";
    break;
}