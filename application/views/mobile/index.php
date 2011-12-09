<div class="content-primary">
	<p>Please enter the wedding code to login.</p>
	<?php echo Form::open('mobile/index'); ?>
		<?php echo Form::input('alias', '', array('id'=>'alias', 'placeholder'=>'wedding code')); ?>
		<?php echo Form::submit('', 'Login'); ?>
	<?php echo Form::close(); ?>
</div>

