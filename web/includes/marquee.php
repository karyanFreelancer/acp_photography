<?php
/* Edit this list in code; no admin upload or URL is required. */
$acpMarqueeWords = ['ACP PHOTOGRAPHY', 'WEDDING STORIES', 'CINEMATIC FILMS', 'PRE-WEDDING', 'PORTRAITS', 'TIMELESS MOMENTS'];
?>
<section class="acp-code-marquee" aria-label="ACP Photography creative specialties">
 <div class="acp-code-marquee__window"><div class="acp-code-marquee__track">
 <?php foreach ([1,2] as $copy): ?><div class="acp-code-marquee__group" <?= $copy===2?'aria-hidden="true"':'' ?>>
 <?php foreach ($acpMarqueeWords as $word): ?><span><?=htmlspecialchars($word,ENT_QUOTES,'UTF-8')?></span><i aria-hidden="true">✳</i><?php endforeach; ?>
 </div><?php endforeach; ?>
 </div></div>
</section>
