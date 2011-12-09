<div class="content-primary">
	<ul data-role="listview" data-role="collapsible-set" data-divider-theme="b">
		<li data-role="list-divider">Attandence: <?php echo ($total_guests == 0) ? '0%' : (int)($total_checkins / $total_guests * 100).'%'; ?></li>
		
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
