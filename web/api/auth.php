<?php
require_once __DIR__.'/../../config/config.php';header('Content-Type: application/json');
function respond($ok,$message){echo json_encode(['ok'=>$ok,'message'=>$message]);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST')respond(false,'Method not allowed');
try{csrf_check();}catch(Throwable $e){respond(false,'Session expired. Refresh and retry.');}
$mode=$_POST['mode']??'login';$email=strtolower(trim($_POST['email']??''));$password=$_POST['password']??'';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<10)respond(false,'Enter a valid email and password (10+ characters).');
if($mode==='register'){$name=trim($_POST['name']??'');if(mb_strlen($name)<2||mb_strlen($name)>120)respond(false,'Enter your full name.');try{$s=$pdo->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)');$s->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),'user']);$id=(int)$pdo->lastInsertId();}catch(PDOException $e){respond(false,'Account already exists or could not be created.');}}
else{$s=$pdo->prepare('SELECT id,password_hash,role FROM users WHERE email=? LIMIT 1');$s->execute([$email]);$u=$s->fetch();if(!$u||!password_verify($password,$u['password_hash']??''))respond(false,'Incorrect email or password.');$id=(int)$u['id'];}
session_regenerate_id(true);$_SESSION['user_id']=$id;respond(true,'Signed in');
