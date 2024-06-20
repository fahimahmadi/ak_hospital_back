<?php
// index.php

// errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$method = $_SERVER['REQUEST_METHOD'];

// Handle OPTIONS request
if ($method == 'OPTIONS') {
    // CORS preflight response
    header("HTTP/1.1 200 OK");
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = explode('/', trim($uri, '/'));

if(count($route) < 2){
    http_response_code(404);
    echo json_encode(['message' => 'Route not found!']);
}

$resource = $route[2];
$action = $route[3];

switch($resource){
    case 'user':
        switch($action){
            case 'login':
                include('api/login.php');
                break;

            case 'logout':
                include('api/logout.php');
                break;

            case 'create':
                break;

            case 'edit':
                break;

            case 'delete':
                break;
        }
        break;
    
    case 'doctors':    
        switch($action){
            case 'login':
                break;

            case 'create':
                break;

            case 'edit':
                break;

            case 'delete':
                break;
        }
        break;


    default:

        break;
}