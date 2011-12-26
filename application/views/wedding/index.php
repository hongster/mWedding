<section class="grid_12">
	<p>
		Remember to bookmark this URL! <?php echo HTML::anchor(
			Route::url('wedding_login', array('alias'=>Session::instance()->get('alias')), TRUE)
		); ?>
</p>
</section>
<div class="clear"></div>

<section>
	<article class="grid_6" id="table-listing">
		<h2>Tables</h2>

		<ul>
			<?php foreach($tables as $table): ?>
				<li>
					<?php echo HTML::anchor(
						"wedding/table/{$table->id}",
						$table->name.' ('.$table->num_checkins().'/'.$table->num_guests().')'
					); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</article>
	<!-- end #table-listing -->

	<article class="grid_6 omega">
		<h2>Stats</h2>
		<?php $percent = ($wedding->total_guests() == 0) ? '0' : 100 * $wedding->total_checkins() / $wedding->total_guests(); 
			$percent = ((int) $percent).'%';
		?>
		<p><span class="title">Attandance:</span> <?php echo $percent; ?></p>

		<h2>Create New Table</h2>
		<?php echo Form::open('wedding/new_table'); ?>
			<fieldset>
				<label for="table_name">Table name:</label>
				<input type="text" name="table_name" id="table_name" />
				<input type="submit" value="Create" />
			</fieldset>
		<?php echo Form::close(); ?>

		<h2>Guest Search</h2>
		<?php echo Form::open('wedding/search_guest'); ?>
			<fieldset>
				<input type="search" name="query" id="query" />
				<input type="submit" value="Search" />
			</fieldset>
		<?php echo Form::close(); ?>
	</article>
	<div class="clear"></div>
</section>
