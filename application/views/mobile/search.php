<?php // Separate guests in 2 lists
$arrived = array();
$not_yet = array();

foreach ($guests as $guest)
{
	if ($guest->has_arrived())
	{
		$arrived[$guest->name] = $guest;
	}
	else
	{
		$not_yet[$guest->name] = $guest;
	}
}

ksort($arrived);
ksort($not_yet);

?>

<div class="content-primary">
	<?php echo Form::open('mobile/search'); ?>
		<?php echo Form::input(
			'query',
			isset($query) ? $query : '',
			array('type'=>'search', 'placeholder'=>'guest name', 'required'=>'required')
		); ?>
		<?php echo Form::submit('', 'Search'); ?>
	<?php echo Form::close(); ?>
	
	<ul data-role="listview" data-divider-theme="b" data-inset="true">		
		<?php if (count($not_yet)): ?>
			<li data-role="list-divider">Waiting</li>
			<?php foreach ($not_yet as $name => $guest): ?>
				<li>
					<?php echo HTML::anchor(
						'mobile/table/'.$guest->table_id,
						$name
					); ?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<?php if (count($arrived)): ?>
			<li data-role="list-divider">Arrived</li>
			<?php foreach ($arrived as $name => $guest): ?>
				<li>
					<?php echo HTML::anchor(
						'mobile/table/'.$guest->table_id,
						$name
					); ?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div>

