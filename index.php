<?php
// require class
require_once 'class/config.php';
Config::WakingUp();
// Set get
$get = General::processing_get($_GET);
// Set post
$post = General::processing_post($_POST);
// Work
switch ($get['part']) {

	case 'convert':
        $login = General::checkLogin();
		$content = Convert::doing($post['filename']);
		break;

	case 'finish':
        $login = General::checkLogin();
		$content = Convert::convertMessage();
		break;

	case 'doLogin':
		$content = General::doLogin($post);
		break;

	case 'login':
		$content = General::loginForm();
		break;

	case 'logout':
        General::doLogout();
		break;
	
	case 'index':
	default:
        $login = General::checkLogin();
        $content = Convert::convertForm();
		break;
}
// Set alert
if (!empty($get['message'])) {
	$message = General::setMessage($get['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<title><?php echo Language::getText('headTitle'); ?></title>
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<meta name="author" content="<?php echo Language::getText('headAuthor'); ?>">
    	<meta name="generator" content="<?php echo Language::getText('headGenerator'); ?>">
    	<meta name="keywords" content="<?php echo Language::getText('headKeywords'); ?>">
    	<meta name="description" content="<?php echo Language::getText('headDescription'); ?>">
    	<link href="./css/bootstrap.min.css" rel="stylesheet">
    	<link href="./css/style.css" rel="stylesheet">
	</head>
	<body>
		<header id="mainHeader">
			<div class="container">
				<div class="row clearfix">
					<div class="col-md-10">
						<h1><?php echo Language::getText('pageTitle'); ?></h1>
					</div>
                    <?php if (isset($login) && $login == 1) { ?>
					<div class="col-md-2">
						<a href="<?php echo sprintf('%s/index.php?part=logout', Config::getHost('mainUrl')) ?>" class="btn btn-danger"><?php echo Language::getText('pageLogout'); ?></a>
					</div>
					<?php } ?>
				</div>
			</div>
		</header>
		<section id="mainSection">
			<div class="container">
				<div class="row">
					<?php if (isset($message) && !empty($message)) { ?>
					<div class="col-md-12">
						<?php echo $message; ?>
					</div>
					<?php } ?>
					<div class="col-md-12">
						<?php echo $content; ?>
					</div>
				</div>
			</div>
		</section>
		<footer id="mainFooter">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php echo Language::getText('pageCopyright'); ?>	
					</div>
				</div>
			</div>
		</footer>
		<script src="./js/jquery.min.js"></script>
    	<script src="./js/bootstrap.min.js"></script>
	</body>
</html>
<br />
<pre><?php print_r($_SESSION); ?></pre>
<pre><?php print_r($get); ?></pre>
<pre><?php print_r($post); ?></pre>