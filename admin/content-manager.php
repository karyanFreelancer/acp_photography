<?php
require_once __DIR__.'/../config/config.php';require_admin();
$types=['gallery'=>'Photo gallery','film'=>'Films','service'=>'Services','brand'=>'Worked with / brands','testimonial'=>'Client review marquee','banner'=>'Banners','pricing'=>'Pricing cards'];
$kind=$_GET['kind']??'gallery';if(!isset($types[$kind]))$kind='gallery';
$editId=max(0,(int)($_GET['edit']??0));$token=$_SESSION['acp_cms_csrf']??=bin2hex(random_bytes(24));$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!hash_equals($token,$_POST['csrf']??'')){$error='Invalid form token.';}else{
 try{
  if(isset($_POST['delete'])){$s=$pdo->prepare('DELETE FROM acp_content_items WHERE id=? AND kind=?');$s->execute([(int)$_POST['delete'],$kind]);}
  else {
   $title=trim($_POST['title']??'');if($title==='')throw new RuntimeException('Title required');
   $media=trim($_POST['media_path']??'');
   if(!empty($_FILES['media']['name'])){
    $file=$_FILES['media'];if($file['error']!==UPLOAD_ERR_OK)throw new RuntimeException('Upload failed');
    if($file['size']>($kind==='film'?200:40)*1024*1024)throw new RuntimeException('File exceeds the upload limit');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','video/mp4'=>'mp4','video/webm'=>'webm'][$mime]??null;
    if(!$ext)throw new RuntimeException('Only JPG, PNG, WEBP, MP4 or WEBM supported');
    $dir=__DIR__.'/../uploads/cms';if(!is_dir($dir))mkdir($dir,0755,true);
    $name=bin2hex(random_bytes(16)).'.'.$ext;
    if(!move_uploaded_file($file['tmp_name'],$dir.'/'.$name))throw new RuntimeException('Unable to save file');
    $media='uploads/cms/'.$name;
   }
   $url=$kind==='film' ? '' : trim($_POST['link_url']??'');if($url && !filter_var($url,FILTER_VALIDATE_URL))throw new RuntimeException('Enter valid URL');
   $id=(int)($_POST['id']??0);$category=$kind==='gallery' ? ($_POST['category']??'weddings') : '';if($kind==='gallery'&&!in_array($category,['weddings','prewedding','portraits','events','fashion'],true))throw new RuntimeException('Invalid gallery category');
   $values=[$title,trim($_POST['subtitle']??''),$media,$url,(int)($_POST['sort_order']??0),isset($_POST['is_published'])?1:0,$category];
   if($id){$s=$pdo->prepare('UPDATE acp_content_items SET title=?,subtitle=?,media_path=?,link_url=?,sort_order=?,is_published=?,category=? WHERE id=? AND kind=?');$s->execute([...$values,$id,$kind]);}
   else{$s=$pdo->prepare('INSERT INTO acp_content_items (title,subtitle,media_path,link_url,sort_order,is_published,category,kind) VALUES (?,?,?,?,?,?,?,?)');$s->execute([...$values,$kind]);}
  }
  header('Location: content-manager.php?kind='.urlencode($kind));exit;
 }catch(Throwable $e){$error=$e->getMessage();}
 }}
try{$s=$pdo->prepare('SELECT * FROM acp_content_items WHERE kind=? ORDER BY sort_order,id DESC');$s->execute([$kind]);$items=$s->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable $e){$items=[];$error='Import database/ACP_CONTENT_V2.sql first. '.$e->getMessage();}
$editing=null;foreach($items as $entry){if((int)$entry['id']===$editId){$editing=$entry;break;}}$pageTitle='Content Manager';$active='content-manager';require __DIR__.'/_layout.php';
?>
<style>.cms-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,320px),1fr));gap:20px}.cms-box{background:#fff;color:#17241e;padding:22px;border:1px solid #dce5df;border-radius:16px}.cms-box input,.cms-box textarea,.cms-box select{width:100%;padding:11px;margin:6px 0 14px;border:1px solid #cdd7d0;border-radius:8px;color:#15271f;background:#fff}.cms-box label{display:block;font-weight:600}.cms-box button{background:#183d32;color:white;border:0;border-radius:8px;padding:12px 18px;cursor:pointer}.cms-tabs{display:flex;flex-wrap:wrap;gap:8px;margin:15px 0 25px}.cms-tabs a{padding:9px 13px;border:1px solid #bfcfc5;border-radius:8px;background:#fff;color:#17372e;text-decoration:none}.cms-preview{max-width:140px;max-height:100px;object-fit:cover}</style>
<h1>Content Manager · <?=e($types[$kind])?></h1><p>Upload gallery photos, films, banners, services, prices, brands and client reviews.</p>
<?php if($error):?><p role="alert" style="color:#a11"><?=htmlspecialchars($error)?></p><?php endif;?>
<div class="cms-tabs"><?php foreach($types as $k=>$label):?><a href="?kind=<?=urlencode($k)?>"><?=htmlspecialchars($label)?></a><?php endforeach;?></div>
<div class="cms-grid"><section class="cms-box"><h2><?= $editing ? "Edit" : "Add" ?> <?=htmlspecialchars($types[$kind])?></h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=htmlspecialchars($token)?>"><input type="hidden" name="id" value="<?=(int)($editing['id']??0)?>"><label>Title<input name="title" required maxlength="200" value="<?=e($editing['title']??'')?>"></label><?php if($kind==='gallery'):?><label>Photo category<select name="category" required><?php foreach(['weddings'=>'Weddings','prewedding'=>'Pre-Weddings','portraits'=>'Portraits','events'=>'Events','fashion'=>'Fashion'] as $value=>$label):?><option value="<?=e($value)?>" <?=($editing['category']??'weddings')===$value?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></label><?php endif;?><label>Description / review / package features<textarea name="subtitle" rows="4"><?=e($editing['subtitle']??'')?></textarea></label><label><?= $kind==='film' ? 'Upload film from your device (MP4 / WEBM)' : 'Upload file from your device' ?><input type="file" name="media" accept="<?= $kind==='film' ? 'video/mp4,video/webm' : 'image/jpeg,image/png,image/webp,video/mp4,video/webm' ?>" <?= !$editing && $kind==='film' ? 'required' : '' ?>></label><?php if($kind==='film'):?><p style="font-size:12px;color:#667">No URL needed. Maximum 200 MB; check PHP upload_max_filesize and post_max_size for large videos.</p><?php endif;?><input type="hidden" name="media_path" value="<?=e($editing['media_path']??'')?>"><?php if($kind!=='film'):?><label>External link (optional, only for external services)<input type="url" name="link_url" value="<?=e($editing['link_url']??'')?>"></label><?php endif;?><label>Sort order<input type="number" name="sort_order" value="<?=(int)($editing['sort_order']??0)?>"></label><label><input type="checkbox" name="is_published" <?=!$editing || !empty($editing['is_published'])?'checked':''?> style="width:auto"> Published</label><button type="submit"><?= $editing ? "Update record" : "Save content" ?></button><?php if($editing):?><a href="?kind=<?=e($kind)?>">Cancel edit</a><?php endif;?></form></section>
<section class="cms-box"><h2>Existing items (<?=count($items)?>)</h2><?php foreach($items as $item):?><article style="border-bottom:1px solid #ddd;padding:14px 0"><strong><?=htmlspecialchars($item['title'])?></strong><?php if($kind==='gallery'):?><span style="display:inline-block;margin-left:8px;background:#edf3ef;padding:4px 8px;border-radius:6px"><?=e($item['category']??'Uncategorized')?></span><?php endif;?><p><?=htmlspecialchars($item['subtitle']??'')?></p><?php if($item['media_path'] && preg_match('~\.(jpg|png|webp|jpeg)$~i',$item['media_path'])):?><img class="cms-preview" src="<?=htmlspecialchars(str_starts_with($item['media_path'],'uploads/')?'../'.$item['media_path']:$item['media_path'])?>" alt=""><?php endif;?><a style="display:inline-block;margin:8px 14px 8px 0" href="?kind=<?=e($kind)?>&edit=<?=(int)$item['id']?>">Edit record ↗</a><form method="post" onsubmit="return confirm('Delete this item?')"><input type="hidden" name="csrf" value="<?=htmlspecialchars($token)?>"><button name="delete" value="<?=(int)$item['id']?>">Delete</button></form></article><?php endforeach;?></section></div>
<?php require __DIR__.'/_footer.php';?>
