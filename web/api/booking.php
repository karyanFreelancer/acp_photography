<?php
require_once __DIR__.'/../../config/config.php';header('Content-Type: application/json');
function respond($ok,$message){echo json_encode(['ok'=>$ok,'message'=>$message]);exit;}
if($_SERVER['REQUEST_METHOD']!=='POST')respond(false,'Method not allowed');
try{csrf_check();}catch(Throwable $e){respond(false,'Session expired. Refresh and retry.');}
$u=current_user();if(!$u)respond(false,'Please sign in first.');
$service=trim($_POST['service']??'');$allowed=['Wedding Photography','Cinematic Wedding Film','Pre-Wedding Shoot','Portrait Photography','Event Photography'];if(!in_array($service,$allowed,true))respond(false,'Choose a valid service.');
$date=trim($_POST['event_date']??'');if($date!==''&&(!DateTime::createFromFormat('!Y-m-d',$date)||$date<date('Y-m-d')))respond(false,'Enter a future event date.');
$location=mb_substr(trim($_POST['event_location']??''),0,255);$notes=mb_substr(trim($_POST['notes']??''),0,3000);$budget=$_POST['budget']??'';if($budget!==''&&(!is_numeric($budget)||$budget<0))respond(false,'Invalid budget.');
$client=$pdo->prepare('SELECT id FROM clients WHERE email=? LIMIT 1');$client->execute([$u['email']]);$cid=$client->fetchColumn();if(!$cid){$add=$pdo->prepare('INSERT INTO clients(name,email) VALUES(?,?)');$add->execute([$u['name'],$u['email']]);$cid=$pdo->lastInsertId();}
$s=$pdo->prepare('INSERT INTO bookings(client_id,name,email,service,event_date,event_location,budget,status,notes) VALUES(?,?,?,?,?,?,?,"new",?)');$s->execute([$cid,$u['name'],$u['email'],$service,$date?:null,$location,$budget===''?null:$budget,$notes]);respond(true,'Booking request received');
