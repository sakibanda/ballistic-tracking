<?php

$defaults = array('error'=>'','warning'=>'','success'=>'');

extract(array_merge($defaults,$this->_templateVars));

?>
		
<div class="grid_12">	
	<div class="alert error" style="display: <?php echo ($error) ? 'block' : 'none'; ?>;">
		<span class="icon"></span>
		<strong>Error:</strong>
		<span class="message">
			<?php echo (is_array($error)) ? join('<br>',$error) : $errror; ?>
		</span>
	</div>
	
	<div class="alert warning" style="display: <?php echo ($warning) ? 'block' : 'none'; ?>;">
		<span class="icon"></span>
		<strong>Warning:</strong>
		<span class="message">
			<?php echo (is_array($warning)) ? join('<br>',$warning) : $warning; ?>
		</span>
	</div>
	
	<div class="alert success" style="display: <?php echo ($success) ? 'block' : 'none'; ?>;">
		<span class="icon"></span>
		<strong>Success:</strong>
		<span class="message">
			<?php echo (is_array($success)) ? join('<br>',$success) : $success; ?>
		</span>
	</div>
</div>