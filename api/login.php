<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('./db.php');
$input = json_decode(file_get_contents('php://input'), true);


$email = isset($input['email']) ? trim($input['email']) : null;
$password = isset($input['password']) ? trim($input['password']) : null;
// print($email);
// print($password);

if(json_last_error() !== JSON_ERROR_NONE){
    http_response_code(400);
    echo json_encode(['message' => 'Invalid JSON input']);
    exit();
}

if(!$email || !$password){
    http_response_code(400);
    echo json_encode(['message' => 'Email and password are required']);
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email= ?");
$stmt->execute([$email]);
$fetched_user = $stmt->fetch();

if($fetched_user && password_verify($password, $fetched_user['password'])){
    // create a token
    $token = base64_encode(random_bytes(32));
    // set the token as an HTTP-only cookie
    setcookie("auth_token", $token, [
        'expires' => time() + 86400, // 1 day expiration
        'path' => '/',
        'secure' => true,           // only send over HTTPS
        'httponly' => true,         // Accessible only via HTTP(S)
        'samesite' => 'Strict'      // prevent CSRF attacks
    ]);
    http_response_code(200);
    echo json_encode(['token' => $token, 'user' => ['id' => $fetched_user['id'], 'fullname' => $fetched_user['fullname'], 'email' => $fetched_user['email']]]);
}else{ 
    http_response_code(401);
    echo json_encode(['message' => 'Invalid email or password']);
}