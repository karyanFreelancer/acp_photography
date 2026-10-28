<?php
require_once __DIR__.'/../config/config.php';
header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
$map=['color_background'=>'--color-background','color_surface'=>'--color-surface','color_surface_secondary'=>'--color-surface-secondary','color_text'=>'--color-text','color_text_muted'=>'--color-text-muted','color_primary'=>'--color-primary','color_primary_hover'=>'--color-primary-hover','color_border'=>'--color-border','color_card'=>'--color-card','color_overlay'=>'--color-overlay','color_success'=>'--color-success','color_warning'=>'--color-warning','color_danger'=>'--color-danger'];
echo ':root{';
foreach($map as $key=>$var){$v=setting($key,'');if(preg_match('/^#[0-9a-f]{6}$/i',$v))echo $var.':'.$v.';';}
$fonts=['font_primary'=>'--font-primary','font_heading'=>'--font-heading','font_body'=>'--font-body','font_accent'=>'--font-accent'];
$safe=['DM Sans','Manrope','Inter','Plus Jakarta Sans','Cormorant Garamond','Playfair Display','DM Mono','JetBrains Mono','system-ui'];
foreach($fonts as $key=>$var){$v=setting($key,'');if(in_array($v,$safe,true))echo $var.':\''.str_replace("'",'', $v).'\',sans-serif;';}
$size=(int)setting('font_size_base','16');if($size>=12&&$size<=22)echo '--font-size-base:'.$size.'px;';
$scale=(float)setting('heading_scale','1');if($scale>=.8&&$scale<=1.5)echo '--heading-scale:'.$scale.';';
$lh=(float)setting('body_line_height','1.65');if($lh>=1.2&&$lh<=2.2)echo '--body-line-height:'.$lh.';';
$ls=(float)setting('letter_spacing','0');if($ls>=-.05&&$ls<=.2)echo '--letter-spacing:'.$ls.'em;';echo '}';
