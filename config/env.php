<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load(); // defined variables are now available in the $_ENV and $_SERVER super-globals.

$dotenv->required('DB_DRIVER')->notEmpty();
$dotenv->required('DB_HOST')->notEmpty();
$dotenv->required('DB_PORT')->notEmpty();
$dotenv->required('DB_DATABASE')->notEmpty();
$dotenv->required('DB_USERNAME')->notEmpty();
$dotenv->required('TOKEN_SECRET')->notEmpty();
