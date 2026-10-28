<?php
require_once __DIR__.'/../../config/config.php';
$acpPage=basename($_SERVER['PHP_SELF']??'index.php');
?>
<header class="nav acp-unified-nav" id="acpMainNav">
 <a class="brand" href="index.php" aria-label="ACP Photography home"><img src="assets/reference/acp-horizontal.png" alt="ACP Photography" class="acp-brand-logo"></a>
 <nav class="links" id="acpPrimaryLinks" aria-label="Primary navigation">
 <?php foreach(['index.php'=>'Home','about.php'=>'About','gallery.php'=>'Gallery','films.php'=>'Films','services.php'=>'Services','pricing.php'=>'Pricing','contact.php'=>'Contact'] as $href=>$name):?><a href="<?=htmlspecialchars($href,ENT_QUOTES)?>" <?= $acpPage===$href?'aria-current="page"':''?>><?=htmlspecialchars($name)?></a><?php endforeach;?>
 </nav>
 <div class="acp-nav-actions"><div id="acpAccountSlot"></div><button class="menu acp-nav-toggle" type="button" aria-label="Toggle navigation" aria-controls="acpPrimaryLinks" aria-expanded="false">☰</button></div>
</header>
<link rel="stylesheet" href="assets/reference/unified-shell.css">
<script>document.addEventListener('DOMContentLoaded',function(){const nav=document.getElementById('acpMainNav'),account=document.getElementById('acpAccountLinks'),slot=document.getElementById('acpAccountSlot'),btn=nav?.querySelector('.acp-nav-toggle');if(account&&slot)slot.appendChild(account);btn?.addEventListener('click',function(){let open=nav.classList.toggle('acp-nav-open');btn.setAttribute('aria-expanded',String(open));});});</script>
