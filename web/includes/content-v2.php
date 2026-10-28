<?php
require_once __DIR__.'/../../config/config.php';
function acp_content(string $kind): array { global $pdo; try {$s=$pdo->prepare('SELECT * FROM acp_content_items WHERE kind=? AND is_published=1 ORDER BY sort_order,id DESC');$s->execute([$kind]);return $s->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable $e){error_log($e->getMessage());return [];}}
function acp_h($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function acp_media($v):string {if(!$v)return ''; if(preg_match('~^https?://~i',$v))return $v;return '../'.ltrim($v,'/');}
