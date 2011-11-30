<section class="grid_12">
	<h2 id="table_name"><?php echo HTML::chars($table->name); ?></h2>
	<span>[<?php echo HTML::anchor('wedding/delete_table/'.$table->id, 'Delete table', array('id'=>'delete_table')); ?>]</span>
	
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
				<span id="guest_<?php echo $guest_id; ?>" class="guest_name"><?php echo View_Helper::tagalizer($name, URL::site('wedding/tag/:tag', TRUE)); ?></span>
				<?php echo '['.HTML::anchor('wedding/guest_checkin/'.$guest_id, 'Checkin').']'; ?>
				<?php echo '['.HTML::anchor('wedding/delete_guest/'.$guest_id, 'Delete', array('class'=>'delete_guest')).']'; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	
	<h3>Arrived</h3>
	<ul class="guest-list">
		<?php foreach($guests['arrived'] as $name => $guest_id): ?>
			<li>
				<span id="guest_<?php echo $guest_id; ?>" class="guest_name"><?php echo View_Helper::tagalizer($name, URL::site('wedding/tag/:tag', TRUE)); ?></span>
				<?php echo '['.HTML::anchor('wedding/guest_checkout/'.$guest_id, 'Checkout').']'; ?>
				<?php echo '['.HTML::anchor('wedding/delete_guest/'.$guest_id, 'Delete', array('class'=>'delete_guest')).']'; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
<div class="clear"></div>

<div id="confirm_delete_guest" title="Delete Guest?">
	<p>Are you sure you want to remove this guest from the list?</p>
</div>

<div id="confirm_delete_table" title="Delete Table?">
	<p>Are you sure you want to remove this table from wedding list?</p>
</div>

<?php echo HTML::script('js/jquery.editable-1.3.3.min.js'); ?>
<script>	
$(document).ready(function(){
	$('#table_name').editable({
		onSubmit: function(content) {
			// Simple validation
			if ($.trim(content.current) == '') {
				$(this).text(content.previous);
				return;
			}
			else if (content.current != content.previous) {
				// Submit to server
				$.post(
					'<?php echo URL::Site('wedding/ajax_update_table_name/'.$table->id, TRUE); ?>',
					{table_name: content.current},
					function (data, status) {$(this).text(data)}
				);
			}
		}
	});
	
	$('.guest_name').editable({
		onEdit: function(content) {
			txt = $('<span>'+content.previous+'</span>').text();
			$('input', this).val(txt);
		},
		onSubmit: function(content) {
			id = $(this).attr('id');
			id = id.substr(id.indexOf('_') + 1);
			previous = $('<span>'+content.previous+'</span>').text();
			// Simple validation
			if ($.trim(content.current) == '') {
				$(this).text(content.previous);
				return;
			}
			else if (content.current == previous) {
				$(this).html(content.previous);
				return;
			}
			else if (content.current != content.previous) {
				// Submit to server
				guest_name = this;
				$.post(
					'<?php echo URL::Site('wedding/ajax_update_guest_name', TRUE); ?>/' + id,
					{guest_name: content.current},
					function (data, status) {
						$(guest_name).html(data);
					}
				);
			}
		}
	});
	
	$('#confirm_delete_table').dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Cancel: function() {
				$(this).dialog('close');
			},
			Yes: function() {
				location.href = $('#delete_table').attr('href');
			}
		}
	});
	
	$('#delete_table').click(function(event) {
		event.preventDefault();
		$('#confirm_delete_table')
			.dialog('open');
	});
	
	$('#confirm_delete_guest').dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Cancel: function() {
				$(this).dialog('close');
			},
			Yes: function() {
				location.href = $(this).data('delete_url');
			}
		}
	});
	
	$('.delete_guest').click(function(event) {
		event.preventDefault();
		$('#confirm_delete_guest')
			.data('delete_url', $(this).attr('href'))
			.dialog('open');
	});
});
</script>
