<!DOCTYPE html> 
<html> 
	<head> 
	<title>Sesame List</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php echo HTML::style('css/mobile/jquery.mobile-1.0.min.css'); ?>
	<?php echo HTML::style('css/mobile/style.css'); ?>
	<?php echo HTML::script('js/jquery-1.7.min.js'); ?>
	<?php echo HTML::script('js/mobile/jquery.mobile-1.0.min.js'); ?>
</head> 

<body>
	<div data-role="page" data-dom-cache="false"> 
		<div data-role="header" data-theme="e">
			<?php if ( ! in_array(Request::current()->action(), array('index', 'main'))): ?>
				<?php echo HTML::anchor('mobile/main', 'Home'); ?>
			<?php endif; ?>
			
			<h1><?php echo isset($title) ?  $title: 'Sesame List'; ?></h1>
			<?php if (Request::current()->action() != 'index'): ?>
				<?php echo HTML::anchor('mobile/search', 'Search', array('class'=>'ui-btn-right')); ?>
			<?php endif; ?>
		</div> 
		
		<div data-role="content">
			<?php if (isset($content)) echo $content; ?>
		</div> 
		
		<div data-role="footer" id="footer" data-theme="e">
			<strong>Seasame List</strong> by 
			<span><a href="http://twitter.com/hongster" rel="external" data-ajax="false" style="">@hongster</a></span>
			<span><a href="http://twitter.com/sanchow" rel="external" data-ajax="false" style="">@sannchow</a></span>
		</div> 
	</div>
</body>
</html>
