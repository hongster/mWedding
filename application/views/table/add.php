<?php echo Form::open(Request::current()->uri()); ?>
	<div>
		<?php echo Form::label('table_name', 'Table name:'); ?>
		<?php echo Form::input('table_name', $table_name); ?>
		<?php echo Form::submit('', 'Add'); ?>
	</div>
<?php echo Form::close(); ?>