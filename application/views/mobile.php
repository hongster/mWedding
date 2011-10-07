<!DOCTYPE html> 
<html> 
	<head> 
	<title>mWedding</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />
	<?php echo HTML::style('css/mobile/style.css'); ?>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
</head> 

<body>
	<div data-role="page" data-dom-cache="false"> 
		<div data-role="header" data-theme="e">
			<?php if (Request::current()->action() != 'index'): ?>
				<?php echo HTML::anchor('mobile/index', 'Home'); ?>
			<?php endif; ?>
			
			<h1><?php echo isset($title) ?  $title: 'mWedding'; ?></h1>
			<?php echo HTML::anchor('mobile/search', 'Search', array('class'=>'ui-btn-right')); ?>
		</div> 
		
		<div data-role="content">
			<?php if (isset($content)) echo $content; ?>
		</div> 
		
		<div data-role="footer" id="footer" data-theme="e">
			<strong>mWedding</strong> by <span><a href="http://twitter.com/hongster" rel="external" data-ajax="false" style="">@hongster</a></span>
		</div> 
	</div>
</body>
</html>
