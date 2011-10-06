<p>
	Name: <?php echo HTML::chars($guest->name); ?><br />
	Table: <?php echo HTML::chars($guest->table->id); ?><br />
	Arrival: <?php echo $guest->has_arrived() ? 'Yes': 'Not yet';?>
</p>

<p>
	<?php if ($guest->has_arrived()): ?>
		<?php echo HTML::anchor('/guest/checkout/'.$guest->id, 'Check out'); ?>
	<?php else: ?>
		<?php echo HTML::anchor('/guest/checkin/'.$guest->id, 'Check in'); ?>
	<?php endif; ?>
</p>

<p>
	<?php echo HTML::image('https://chart.googleapis.com/chart?chs=200x200&cht=qr&choe=UTF-8&chl='.urlencode($guest->name), array('alt'=>$guest->name)); ?>
</p>

