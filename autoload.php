<?php

spl_autoload_register(function ($classe) 
{
    $arquivo = dirname(__FILE__, 2) . '/' . $classe . '.php';

    if(file_exists($arquivo))
    {
        include $arquivo;
    }
    else
    {
        exit('Arquivo não encontrado. Arquivo: ' . $arquivo . "<br />");
    }
});
