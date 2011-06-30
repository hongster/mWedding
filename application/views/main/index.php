<?php echo HTML::anchor('table/add', 'Add Table'); ?>

<?php foreach ($tables as $table): ?>
	<table border="1">
		<tr>
			<th>
				<?php echo HTML::anchor('/table/update/'.$table->id, $table->name); ?>
				[<?php echo HTML::anchor('/table/delete/'.$table->id, 'Delete'); ?>]
			</th>
		</tr>
		
		<?php foreach ($table->all_guests() as $guest): ?>
			<tr>
				<td><?php echo HTML::chars($guest->name); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<br />
<?php endforeach; ?>