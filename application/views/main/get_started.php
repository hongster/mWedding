<section class="grid_12">
	<h2>First Time</h2>
	<p>No registration is required. Specify a <em>wedding code</em>. An 
	unqiue URL will be generated using this wedding code for login. Spaces 
	are not allowed, only alphabets, digits, hyphens, and underscores.
	</p>
	
	<?php echo Form::open(Request::current()); ?>
		<div>
			<?php echo Form::label('alias1', 'Wedding Code: '); ?>
			<input type="text" id="alias1" name="alias" placeholder="e.g. alice-john" ng:non-bindable />
			<?php echo Form::submit('', 'Create guest list'); ?>
		</div>
	<?php echo Form::close(); ?>
</section>
<div class="clear"></div>

<br />
<hr />

<section class="grid_12">
	<h2>Login</h2>
	<p>If you have already created a guest list, enter the wedding code
	to login.
	</p>

	<?php echo Form::open('/wedding/login'); ?>
		<div>
			<?php echo Form::label('alias2', 'Wedding Code: '); ?>
			<input type="text" id="alias2" name="alias" ng:non-bindable />
			<?php echo Form::submit('', 'Login'); ?>
		</div>
	<?php echo Form::close(); ?>
</section>
<div class="clear"></div>
