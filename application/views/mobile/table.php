<?php // Separate guests in 2 lists
$arrived = array();
$not_yet = array();

foreach ($guests as $guest)
{
	if ($guest->has_arrived())
	{
		$arrived[$guest->id] = $guest->name;
	}
	else
	{
		$not_yet[$guest->id] = $guest->name;
	}
}

asort($arrived);
asort($not_yet);

?>

<div class="content-primary">
	<ul data-role="listview" data-theme="c" data-dividertheme="b" data-split-theme="d">
		<?php if (count($not_yet)): ?>
			<li data-role="list-divider">Waiting</li>
			<?php foreach ($not_yet as $id => $name): ?>
				<li>
					<a href="#"><?php echo $name; ?></a>
					<?php echo HTML::anchor(
						Route::get('mobile_checkin')->uri(array('guest_id'=>$id, 'checkin'=>1)),
						'',
						array('data-icon'=>'check')
					);?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	
		<?php if (count($arrived)): ?>
			<li data-role="list-divider">Arrived</li>
			<?php foreach ($arrived as $id => $name): ?>
				<li>
					<a href="#"><?php echo $name; ?></a>
					<?php echo HTML::anchor(
						Route::get('mobile_checkin')->uri(array('guest_id'=>$id, 'checkin'=>0)),
						'',
						array('data-icon'=>'delete')
					);?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
</div>