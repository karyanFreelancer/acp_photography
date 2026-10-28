<?php
$isRegister = basename($_SERVER['SCRIPT_NAME'] ?? '') === 'register.php';
$googleReady = defined('GOOGLE_CLIENT_ID') && GOOGLE_CLIENT_ID !== '' && GOOGLE_CLIENT_ID !== 'YOUR_GOOGLE_CLIENT_ID';
$facebookReady = defined('FACEBOOK_APP_ID') && FACEBOOK_APP_ID !== '' && FACEBOOK_APP_ID !== 'YOUR_FACEBOOK_APP_ID';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#10100f">
<title><?= e($pageTitle) ?> · ACP Photography</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(url('web/assets/css/admin.css')) ?>">
</head>
<body class="auth-page">
<div class="auth-grain" aria-hidden="true"></div>
<header class="auth-top"><a class="brand" href="<?= e(url('web/')) ?>" aria-label="ACP Photography home"><span class="brand-symbol">A<span>·</span>P</span><span class="brand-word">ACP <b>PHOTOGRAPHY</b><small>THE ART OF YOUR STORY</small></span></a><a class="top-back" href="<?= e(url('web/')) ?>">Back to website <span>↗</span></a></header>
<main class="auth-layout">
<section class="auth-story">
<p class="eyebrow"><span></span> YOUR STORY, BEAUTIFULLY TOLD</p>
<h1><?= $isRegister ? 'Make room for your ' : 'Your memories, ' ?><em><?= $isRegister ? 'memories.' : 'all in one place.' ?></em></h1>
<p class="story-copy"><?= $isRegister ? 'Create your private ACP account to keep bookings, event details and photo galleries close at hand.' : 'Sign in to revisit your bookings, follow your project, and open your private event galleries.' ?></p>
<div class="story-image"><div class="image-label"><span>ACP / STORIES</span><span>EST. WITH LOVE</span></div><div class="image-caption">The moments<br><i>that stay.</i></div></div>
<div class="story-foot"><span>PHOTOGRAPHY · CINEMATOGRAPHY · MEMORIES</span><span>01 — 03</span></div>
</section>
<section class="auth-panel">
<div class="panel-kicker"><?= $isRegister ? 'START YOUR JOURNEY' : 'WELCOME BACK' ?></div>
<h2><?= $isRegister ? 'Create your account' : 'Sign in to ACP.' ?></h2>
<p class="panel-sub"><?= $isRegister ? 'A personal space for every chapter.' : 'Your photography journey continues here.' ?></p>
<?php if ($error !== ''): ?><div class="auth-alert" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="auth-form">
<input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
<?php if ($isRegister): ?><label>Full name<div class="input-wrap"><span>✳</span><input name="name" autocomplete="name" required minlength="2" maxlength="120" placeholder="Your full name"></div></label><?php endif; ?>
<label>Email address<div class="input-wrap"><span>@</span><input type="email" name="email" autocomplete="email" required maxlength="190" placeholder="you@example.com"></div></label>
<label>Password<div class="input-wrap"><span>⌑</span><input type="password" name="password" autocomplete="<?= $isRegister ? 'new-password' : 'current-password' ?>" required <?= $isRegister ? 'minlength="10"' : '' ?> placeholder="<?= $isRegister ? 'At least 10 characters' : 'Enter your password' ?>"><button class="password-toggle" type="button" aria-label="Show password">Show</button></div></label>
<?php if (!$isRegister): ?><div class="form-meta"><label class="check-label"><input type="checkbox" name="remember" value="1"><span>Keep me signed in</span></label><span class="meta-note">Secure sign-in</span></div><?php endif; ?>
<button class="submit-btn" type="submit"><span><?= $isRegister ? 'Create account' : 'Sign in to your account' ?></span><b>↗</b></button>
</form>
<div class="separator"><span></span><small>OR CONTINUE WITH</small><span></span></div>
<div class="social-buttons">
<?php if ($googleReady): ?><a class="social-btn" href="<?= e(url('auth/google.php')) ?>"><span class="google-g">G</span> Google</a><?php else: ?><button class="social-btn social-disabled" type="button" title="Configure Google OAuth credentials first"><span class="google-g">G</span> Google <small>Setup needed</small></button><?php endif; ?>
<?php if ($facebookReady): ?><a class="social-btn" href="<?= e(url('auth/facebook.php')) ?>"><span class="facebook-f">f</span> Facebook</a><?php else: ?><button class="social-btn social-disabled" type="button" title="Configure Facebook OAuth credentials first"><span class="facebook-f">f</span> Facebook <small>Setup needed</small></button><?php endif; ?>
</div>
<p class="switch-copy"><?= $isRegister ? 'Already have an account?' : 'New to ACP Photography?' ?> <a href="<?= e(url($isRegister ? 'auth/login.php' : 'auth/register.php')) ?>"><?= $isRegister ? 'Sign in' : 'Create an account' ?></a></p>
<p class="privacy-note">By continuing, you agree to use your account responsibly. Your private galleries should only be shared with people you trust.</p>
</section>
</main>
<footer class="auth-footer"><span>© <?= date('Y') ?> ACP PHOTOGRAPHY</span><span>MADE FOR THE MOMENTS THAT MATTER</span></footer>
<script>
document.querySelectorAll('.password-toggle').forEach(button => {
  button.addEventListener('click', () => {
    const input = button.parentElement.querySelector('input');
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    button.textContent = show ? 'Hide' : 'Show';
    button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
  });
});
</script>
</body>
</html>
