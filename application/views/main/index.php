<section id="frontpage-banner" class="grid_12">
	<?php echo HTML::image('img/banner.jpg', array('alt'=>'banner')); ?>
</section>
<div class="clear"></div>

<section>
	<article class="grid_6">
		<h2>What is mWedding?</h2>
		
		<p>This is a wedding guests attendance marking system. It is deisgned
		to solve some of the problems related to organizing a wedding 
		dinner. The wedding couples needs to know the number of guests 
		who have turned up, so that dinner commences when majority
		of the invited guests have arrived. When a guest arrived, there is a need
		to efficiently determine where he/she sits, and mark attendance. The
		banquet manager needs to be informed on the number of guests
		with special diet requirements on a timely manner. Keeping track 
		of such information has never been an easy task.
		</p>
		
		<p>mWedding solves these problems be allow wedding organizer to
		allocate guests to tables. With a built-in search function, information
		such as dietary needs, seating assignment can be easily retireved.
		Attandence can be marked this information is made available realtime.
		</p>
	</article>
	<!-- what is mWedding -->
	
	<article class="grid_6">
		<h2>How does it works?</h2>
		
		<h3>Pre Dinner Day</h3>
		<ol>
			<li>Define tables and assign name/numbering to them.</li>
			<li>Add guests to each table.</li>
			<li>Optional, tag each guest with <em>#hashtag</em>.</li>
		</ol>
		
		<h3>Dinner Day</h3>
		<ol>
			<li>When guest arrived, search by name.</li>
			<li>Inform guest the assigned table.</li>
			<li>Checkin the arrived guest.</li>
			<li>Update banquet manager number of guests with special diet needs.</li>
			<li>Start dinner when enough guests have arrived.</li>
		</ol>
		
		<p id="get-started"><a href="#" class="button">Get Started</a></p>
	</article>
	<!-- howto -->	
	<div class="clear"></div>
</section>

<!-- get started form -->
<div id="get-started-form" title="Get Started">
	<p>Indicate the number of tables in the wedding dinner. Don't worry, you can change it later.</p>
	
	<?php echo Form::open('wedding/ajax_new'); ?>
		<fieldset>
			<p>
				<label for="num_tables">Number of tables:</label>
				<input type="number" min="1" max="100" required="required" value="10" name="num_tables" id="num_tables" />
			</p>
			
			<p>
				
			</p>
		</fieldset>
	<?php echo Form::close(); ?>
</div>
<!-- end get started form -->

<script>
$(document).ready(function() {
	$("#get-started-form").dialog({
		autoOpen: false,
		modal: true,
		minWidth: 500,
		buttons: {
			"Cancel": function() {
				$(this).dialog("close");
			},
			"Next": function() {
				$.post($("#get-started-form form:first").attr("action"), $("#get-started-form form:first").serialize(),
					function(data){
						if (data.status == "ERROR") {
							alert(data.err_msg);
							return;
						}
						else {
							location.href = data.redirect;
						}
					}
					, 'json')
				.error(function(){
					alert("Unable to connect to server. Please try again later.");
				});
			}
		}
	});
	
	$("#get-started").click(function(event) {
		event.preventDefault();
		$("#get-started-form").dialog("open");
	});
});
</script>