<?php
require_once __DIR__.'/../config/config.php'; require_admin();
$modules=['studio_services'=>['title','description','price','duration','sort_order','is_published'],'studio_partners'=>['name','logo_url','website_url','sort_order','is_published'],'studio_videos'=>['title','video_url','poster_url','sort_order','is_published'],'studio_faqs'=>['question','answer','sort_order','is_published'],'studio_banners'=>['title','subtitle','image_url','button_label','button_url','sort_order','is_published']];
$module=(string)($_GET['module']??'studio_services'); if(!isset($modules[$module]))$module='studio_services';
$fields=$modules[$module];$error='';
try{
 if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_check();$id=(int)($_POST['id']??0);
  if(isset($_POST['delete']) && $id){$stmt=$pdo->prepare("DELETE FROM `$module` WHERE id=?");$stmt->execute([$id]);}
  else {
   $values=[];foreach($fields as $field){$values[]=trim((string)($_POST[$field]??''));}
   if($id){$sets=implode(',',array_map(fn($f)=>"`$f`=?",$fields));$stmt=$pdo->prepare("UPDATE `$module` SET $sets WHERE id=?");$stmt->execute([...$values,$id]);}
   else{$cols=implode(',',array_map(fn($f)=>"`$f`",$fields));$marks=implode(',',array_fill(0,count($fields),'?'));$stmt=$pdo->prepare("INSERT INTO `$module` ($cols) VALUES ($marks)");$stmt->execute($values);}
  }
  header('Location: studio-content.php?module='.urlencode($module));exit;
 }
 $rows=$pdo->query("SELECT * FROM `$module` ORDER BY sort_order,id DESC")->fetchAll();
}catch(Throwable $e){$rows=[];$error='Please import database/ACP_UPGRADE.sql first. '.$e->getMessage();}
$pageTitle='Studio content';$active='settings';require __DIR__.'/_layout.php';
?>
<style>.acp-editor{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px}.acp-editor label{display:grid;gap:6px;font-size:13px}.acp-editor input,.acp-editor textarea{padding:12px;border:1px solid #ccc;border-radius:8px;width:100%;box-sizing:border-box}.acp-entry{border:1px solid #ddd;padding:18px;border-radius:12px;margin:14px 0}.acp-links{display:flex;flex-wrap:wrap;gap:9px;margin:16px 0}.acp-links a{padding:9px 14px;border:1px solid #ddd;border-radius:8px}</style>
<section class="welcome-row"><div><p class="eyebrow">ACP STUDIO / CONTENT MANAGER</p><h1>Manage <?=e(str_replace('_',' ',$module))?></h1><p class="muted">Edit services, pricing, partners, video links, FAQs and banners from here.</p></div></section>
<div class="acp-links"><?php foreach(array_keys($modules) as $key):?><a href="?module=<?=e($key)?>"><?=e(ucwords(str_replace('_',' ',$key)))?></a><?php endforeach;?></div>
<?php if($error):?><p class="alert"><?=e($error)?></p><?php endif;?>
<section class="panel" style="padding:22px"><h2>Add new</h2><form method="post"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><div class="acp-editor"><?php foreach($fields as $field):?><label><?=e(ucwords(str_replace('_',' ',$field)))?><input name="<?=e($field)?>" value="<?=in_array($field,['sort_order','is_published'])?'1':''?>" <?=in_array($field,['title','name','question','video_url'])?'required':''?>></label><?php endforeach;?></div><button class="btn btn-primary" style="margin-top:18px">Add content</button></form></section>
<?php foreach($rows as $row):?><section class="acp-entry"><form method="post"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=(int)$row['id']?>"><div class="acp-editor"><?php foreach($fields as $field):?><label><?=e(ucwords(str_replace('_',' ',$field)))?><input name="<?=e($field)?>" value="<?=e((string)($row[$field]??''))?>"></label><?php endforeach;?></div><div style="display:flex;gap:10px;margin-top:14px"><button class="btn btn-primary">Save</button><button class="btn btn-outline" name="delete" value="1" onclick="return confirm('Delete this item?')">Delete</button></div></form></section><?php endforeach;?>
<?php require __DIR__.'/_footer.php';?>
