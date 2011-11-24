<section class="grid_12">
	<h2 id="table_name"><?php echo HTML::chars($table->name); ?></h2>

	<?php echo Form::open(URL::site("wedding/add_guest/".$table->id, TRUE)); ?>
		<label for="guest_name">Guest name:</label>
		<input type="text" name="guest_name" id="guest_name" />
		<input type="submit" value="Add Guest" />
	<?php echo Form::close(); ?>

	<p>
		<span class="title">Attandence:</span> <?php echo $table->num_checkins().'/'.$table->num_guests(); ?>
	</p>

	<?php
		$guests = array('waiting'=>array(), 'arrived'=>array());
		foreach ($table->all_guests() as $guest)
		{
			if ($guest->has_arrived())
			{
				$guests['arrived'][$guest->name] = $guest->id;
			}
			else 
			{
				$guests['waiting'][$guest->name] = $guest->id;
			}
		}
		
		ksort($guests['waiting']);
		ksort($guests['arrived']);
	?>

	<h3>Waiting</h3>
	<ul class="guest-list">
		<?php foreach($guests['waiting'] as $name => $guest_id): ?>
			<li>
				<?php echo View_Helper::tagalizer($name, URL::site('wedding/tag/:tag', TRUE)); ?>
				<?php echo '['.HTML::anchor('wedding/guest_checkin/'.$guest_id, 'Checkin').']'; ?>
				<?php echo '['.HTML::anchor('wedding/delete_guest/'.$guest_id, 'Delete').']'; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	
	<h3>Arrived</h3>
	<ul class="guest-list">
		<?php foreach($guests['arrived'] as $name => $guest_id): ?>
			<li>
				<?php echo View_Helper::tagalizer($name, URL::site('wedding/tag/:tag', TRUE)); ?>
				<?php echo '['.HTML::anchor('wedding/guest_checkout/'.$guest_id, 'Checkout').']'; ?>
				<?php echo '['.HTML::anchor('wedding/delete_guest/'.$guest_id, 'Delete').']'; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
<div class="clear"></div>
