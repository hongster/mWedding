<?php echo Form::open('table/update_name/'.$table->id); ?>
	<div>
		<?php echo Form::label('table_name', 'Table name:'); ?>
		<?php echo Form::input('table_name', $table->name); ?>
		<?php echo Form::submit('', 'Save table name'); ?>
	</div>
<?php echo Form::close(); ?>

<ul>
	<?php foreach ($guests as $guest): ?>
		<li>
			<?php echo HTML::chars($guest->name); ?>
			<?php echo HTML::anchor('table/delete_guest/'.$guest->id, '[Delete]'); ?>
		</li>
	<?php endforeach; ?>
</ul>

<?php echo Form::open('table/add_guest/'.$table->id); ?>
	<div>
		<?php echo Form::label('guest_name', 'Add guest:'); ?>
		<?php echo Form::input('guest_name'); ?>
		<?php echo Form::submit('', 'Add'); ?>
	</div>
<?php echo Form::close(); ?>