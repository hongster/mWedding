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
			<h1 id="site-title"><?php echo HTML::anchor('/wedding/index', 'mWedding'); ?></h1>
			<em>Your wedding assistant</em>
		</section>
	</header>

	<section id="content" class="container_12">

		<?php if (isset($err_msg)): ?>
			<p class="grid_12 error flash"><?php echo HTML::chars($err_msg); ?></p>
			<div class="clear"></div>
		<?php elseif (isset($info_msg)): ?>
			<p class="grid_12 info flash"><?php echo HTML::chars($info_msg); ?>
			<div class="clear"></div>
		<?php endif; ?>

		<?php if(isset($content)) echo $content; ?>
	</section>
	<!-- #content -->

	<footer class="container_12">
		(CC) BY | <a href="http://about.me/hongster">@hongster</a>
		Powered by: <a href="http://kohanaframework.org/">Kohana</a>
		| <a href="http://angularjs.org/">AngularJS</a>
		| <a href="http://jquery.com/">jQuery</a>
	</footer>

	<script>
	$(document).ready(function() {
		$(".flash").animate(
			{"background-color": "#FFFF00"},
			3000,
			function() {
				$(this).animate({"background-color": "#FFFFbb"}, 2000);
			}
		);
	});
	</script>
</body>
</html>
