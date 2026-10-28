<?php
require_once __DIR__.'/../config/config.php';
if(admin_user()){header('Location: index.php');exit;}
header('Location: ../web/index.php?auth=login&next=admin');exit;
