<?php if(!defined('__ROOTACCESS__')){die('Restricted access!');} ?>
<!DOCTYPE html>
<html lang="ru" prefix="og: https://ogp.me/ns/website#">
<head>
	<meta charset="utf-8">

	<meta name="robots" content="all">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo @$data['title']; ?></title>

	<meta name="keywords" content="<?php echo @$data['meta_keywords']; ?>">
	<meta name="description" content="<?php echo @$data['meta_description']; ?>">

	<meta property="og:type" content="website">
	<meta property="og:locale" content="<?php echo (isset($uri->uri[1]) && $uri->uri[1]==='en') ? 'en_US' : ((isset($uri->uri[1]) && $uri->uri[1]==='am') ? 'hy_AM' : 'ru_RU'); ?>">
	<meta property="og:site_name" content="Valencia IT Solutions">
	<meta property="og:url" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/images/og_logo.jpg">
	<!-- <meta property="og:image:alt" content="Valencia IT Solutions, logo.">
	<meta name="twitter:image:alt" content="Valencia IT Solutions, logo."> -->
	<meta property="og:title" content="<?php echo @$data['title']; ?>">
	<meta property="og:description" content="<?php echo @$data['meta_description']; ?>">
	<meta property="og:updated_time" content="<?php echo @$data['updated']; ?>">

	<meta name="dcterms.rightsHolder" content="Valencia IT Solutions">
	<meta name="generator" content="MediaOS">

	<?php
	if($uri->args_count()>0)
	{
		$url=explode('?',$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$url=$url[0];
		echo '<link rel="canonical" href="'.$url.'">';
	}
	?>
	
	<link rel="icon" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/favicon.svg" type="image/svg+xml">
	<link rel="shortcut icon" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/favicon.png">
	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/css/style.css">
	<link rel="stylesheet" href="//<?php echo $_SERVER['HTTP_HOST']; ?>/css/pages.css">

	<!--[if lt IE 9]>
	<script nonce="<?php echo $nonce; ?>" src="//<?php echo $_SERVER['HTTP_HOST']; ?>/js/html5.min.js" defer></script>
	<script nonce="<?php echo $nonce; ?>" src="//<?php echo $_SERVER['HTTP_HOST']; ?>/js/respond.min.js" defer></script>
	<![endif]-->
</head>
<body>
<div id="bar"></div>

<!-- NAV -->
<nav>
	<div class="nav-row">
		<a href="/" class="nav-logo">
		<img src="//<?php echo $_SERVER['HTTP_HOST']; ?>/images/logo.svg" alt="Valencia IT"/>
		<span class="nav-logo-t">Valencia IT</span>
		</a>
		<div class="nav-links">
			<?php
			if(isset($data['nav']))
			{
				foreach($data['nav'] as $nav)
				{
					if($nav['seo_url']=='index'){continue;}
					echo $nav['seo_url']==$uri->uri[1] ? '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$nav['seo_url'].'/" class="active">'.$nav['menu'].'</a>' : '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$nav['seo_url'].'/">'.$nav['menu'].'</a>';
				}
			}
			?>
		</div>
		<div class="nav-actions">
		<div class="lang-sw">
			<?php
			if(isset($uri->uri[1]))
			{
				if($uri->uri[1]!='index')
				{
					$link=$uri->uri[1];
				}
				else
				{
					$link='';
				}
			}
			?>
			<a href="/<?php echo @$link; ?>" class="active">RU</a>
			<span class="lang-sep">·</span>
			<a href="/en/<?php echo @$link; ?>">EN</a>
			<span class="lang-sep">·</span>
			<a href="/am/<?php echo @$link; ?>">AM</a>
		</div>
		<a href="#contact" class="nav-cta">Начать проект</a>
		<button class="nav-burger" id="burger" aria-label="Меню">
			<span></span><span></span><span></span>
		</button>
		</div>
	</div>
	<div class="nav-mobile" id="navMobile">
		<?php
		if(isset($data['nav']))
		{
			foreach($data['nav'] as $nav)
			{
				if($nav['seo_url']=='index'){continue;}
				echo $nav['seo_url']==$uri->uri[1] ? '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$nav['seo_url'].'/" class="active">'.$nav['menu'].'</a>' : '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$nav['seo_url'].'/">'.$nav['menu'].'</a>';
			}
		}
		?>
		<div class="lang-sw">
			<a href="/<?php echo @$link; ?>" class="active">RU</a>
			<span class="lang-sep">·</span>
			<a href="/en/<?php echo @$link; ?>">EN</a>
			<span class="lang-sep">·</span>
			<a href="/am/<?php echo @$link; ?>">AM</a>
		</div>
		<a href="#contact" class="nav-cta">Начать проект</a>
	</div>
</nav>

<!-- Breadcrumbs -->
<?php
if(isset($breadcrumbs))
{
	echo '<div id="breadcrumbs" class="sp-hero">';
		echo '<div class="sp-breadcrumb">';
			foreach($breadcrumbs as $breadcrumb)
			{
				echo '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$breadcrumb['seo_url'].'/">'.$breadcrumb['menu'].'</a>'
					.'<span class="sp-breadcrumb-sep">›</span>';
			}
		echo '</div>';
		echo '<h1 class="sp-page-title" id="page-title">'.$data['header'].'</h1>';
	echo '</div>';
	echo '<hr class="sp-divider"/>';
}
?>
<div class="sp-layout<?php echo (isset($data['submenu']) && !empty($data['submenu'])) ? ' has-sidebar' : ''; ?>">
	<!-- SIDEBAR -->
	<?php if(isset($data['submenu']) && !empty($data['submenu'])): ?>
	<aside class="sp-sidebar">
		<div class="sp-sidebar-label"><?php echo $data['menu']; ?></div>
		<nav class="sp-nav" id="sp-nav">
			<?php
			foreach($data['submenu'] as $submenu_item)
			{
				$cur = isset($uri->uri[2]) ? $uri->uri[2] : '';
				$is_active = $submenu_item['seo_url']===$cur;
				echo '<a href="//'.$_SERVER['HTTP_HOST'].'/'.$submenu_item['seo_url'].'/" class="sp-sub'.($is_active ? ' active' : '').'">'.$submenu_item['header'].'</a>';
			}
			?>
		</nav>
	</aside>
	<?php endif; ?>
	<!-- CONTENT -->
	<main>
		<?php echo $data['content']; ?>
	</main>
</div>

<!-- CONTACT -->
<section id="contact">
	<div class="w">
		<div class="contact-grid">
			<div class="r">
				<span class="eyebrow">Контакты</span>
				<h2 style="font-size:clamp(38px,4.5vw,64px);font-weight:800;letter-spacing:-.025em;line-height:1.08;margin-bottom:18px">Начнём разговор</h2>
				<p style="font-size:17px;color:var(--mid);line-height:1.8;margin-bottom:40px;font-weight:300">Расскажите, что нужно — ответим в течение одного рабочего дня.</p>
				<div class="ci-list">
				<div class="ci">
					<div class="ci-ico"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.07 11a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3 .18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 7.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 14.92z"/></svg></div>
					<div><div class="ci-k">Телефон</div><div class="ci-v"><a href="tel:+37443865247">+374 43 865247</a></div></div>
				</div>
				<div class="ci">
					<div class="ci-ico"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
					<div><div class="ci-k">Email</div><div class="ci-v"><a href="mailto:info@valencia-it.am">info@valencia-it.am</a></div></div>
				</div>
				<div class="ci">
					<div class="ci-ico"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10A15.3 15.3 0 0 1 8 12a15.3 15.3 0 0 1 4-10z"/></svg></div>
					<div><div class="ci-k">Сайт</div><div class="ci-v"><a href="https://www.valencia-it.am" target="_blank">valencia-it.am</a></div></div>
				</div>
				</div>
			</div>
			<div class="form-box r" style="transition-delay:.14s">
			<form action="/contact/" method="post" enctype="application/x-www-form-urlencoded">
				<div class="form-t">Отправить сообщение:</div>
				<div class="f2">
				<div class="ff"><label>Имя</label><input type="text" name="name" placeholder="Имя"/></div>
				<div class="ff"><label>Фамилия</label><input type="text" name="surname" placeholder="Фамилия"/></div>
				</div>
				<div class="ff"><label>E-mail</label><input type="email" name="email" placeholder="box@domain.tld"/></div>
				<div class="ff"><label>Услуга</label>
				<select name="service">
					<option value="">Выберите…</option>
					<option>Инфраструктура и бэкап</option>
					<option>Базы данных</option>
					<option>DevOps и контейнеризация</option>
					<option>Облачные решения</option>
					<option>Информационная безопасность</option>
					<option>Сетевые решения</option>
					<option>Большие данные / Аналитика</option>
					<option>Обучение</option>
					<option>Другое</option>
				</select>
				</div>
				<div class="ff"><label>Сообщение</label><textarea name="message" placeholder="Опишите задачу…"></textarea></div>
				<div class="consent-wrap">
					<input type="checkbox" id="consent-pdn" name="consent-pdn" required/>
					<label for="consent-pdn">Я согласен на обработку персональных данных согласно <a href="/privacy/">политике конфиденциальности</a></label>
				</div>
				<button class="f-sub" id="sub" name="send">Отправить сообщение</button>
			</form>
			</div>
		</div>
	</div>
</section>

<!-- FOOTER -->
<footer>
	<div class="footer-main">
		<div class="fl"><img src="/images/logo.svg" alt="Valencia IT"/><span>Valencia IT Solutions</span></div>
			<p class="fc">&copy; "Valencia IT Solutions" <?php echo date('Y'); ?></p>
			<div class="flinks">
				<a href="/services/">Услуги</a>
				<a href="#training">Обучение</a>
				<a href="mailto:info@valencia-it.am">Email</a>
			</div>
		</div>
		<div class="footer-legal">
			<a href="/agreement/">Пользовательское соглашение</a>
			<a href="/privacy/">Политика конфиденциальности</a>
			<a href="/cookie/">Использование cookie</a>
		</div>
	</div>
</footer>

<script nonce="<?php echo $nonce; ?>" src="/js/script.js"></script>
</body>
</html>