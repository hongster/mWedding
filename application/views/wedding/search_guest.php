<section class="grid_12">
	<h2>Guest Search</h2>
</section>

<section class="grid_12" ng:controller="GuestController">
	<?php echo Form::open(URL::site('wedding/search_guest', TRUE)); ?>
		<fieldset>
			<input type="search" name="query" id="query" value="<?php echo HTML::chars($query); ?>" />
			<input type="submit" value="Search" ng:click="search()" />
		</fieldset>
	<?php echo Form::close(); ?>


</section>

<script>
	function GuestController($xhr) {
		self = this;
	}

	GuestController.prototype = {
		save: function() {
			console.log(this.query);
			// TODO prevent form submission and perform AJAX query
		}
	}
</script>