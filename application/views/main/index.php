<p>
	<?php echo Form::open('main/search'); ?>
	<?php echo Form::input('query', ''); ?><?php echo Form::submit('', 'Search'); ?>
	<?php echo Form::close(); ?>
</p>

<p>
	<?php echo HTML::anchor('table/add', 'Add Table'); ?>
</p>

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
				<td style="background-color:<?php echo $guest->has_arrived() ? '#66FF66' : '#EFEFEF';?>">
					<?php echo HTML::anchor('/guest/info/'.$guest->id, $guest->name); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<br />
<?php endforeach; ?>
