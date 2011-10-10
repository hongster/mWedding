<!DOCTYPE HTML>
<html xmlns:ng="http://angularjs.org">
<head>
	<title>mWedding</title>
	
	<?php echo HTML::style('css/reset.css'); ?>
	<?php echo HTML::style('css/text.css'); ?>
	<?php echo HTML::style('css/960.css'); ?>
	<?php echo HTML::style('css/style.css'); ?>
	<?php echo HTML::style('css/jquery-ui-1.8.16.custom.css'); ?>
	
	<script src="http://code.angularjs.org/0.9.15/angular-0.9.15.min.js" ng:autobind></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<?php echo HTML::script('js/jquery-ui-1.8.16.custom.min.js'); ?>
</head>

<body>
	<header class="container_12">
		<section class="grid_12">
			<h1 id="site-title">mWedding</h1>
			<em>Your wedding assistant</em>
		</section>
	</header>
	
	<section id="content" class="container_12">
		<?php if(isset($content)) echo $content; ?>
	</section>
	<!-- #content -->
	
	<footer class="container_12">
		(CC) BY | <a href="http://about.me/hongster">@hongster</a>
	</footer>
</body>
</html>
