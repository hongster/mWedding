<div class="content-primary">
	<ul data-role="listview" data-role="collapsible-set" data-divider-theme="b">
		<li data-role="list-divider"><?php echo "Arrival: {$total_arrived}/{$total_guests}"; ?></li>
		
		<?php foreach ($tables as $table): ?>
			<li>
				<?php echo HTML::anchor(
					'mobile/table/'.$table->id,
					$table->name.' <span class="ui-li-count">'.$table->num_checkins().'/'.$table->num_guests().'</span>'
				); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
