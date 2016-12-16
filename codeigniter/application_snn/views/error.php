<?php echo validation_errors(); ?>

	<?php 
		if($flashMessage != ''){
			echo "<div class='flashMsg'>$flashMessage</div>";
		}
	?>