<?php

$protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$nomePastaAplicacao = basename(dirname(__DIR__));
$base_url = "$protocol://$host/$nomePastaAplicacao";


return [
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'igreja'
    ],
    'app' => [
        'app_name' => 'Igreja Digital',
        'app_folder' => $base_url
    ]
];
