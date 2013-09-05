<?php

class BTDialog {
	protected $_id = 0;

	public function __construct() {
		$this->_id = 'bt_dialog_' . mt_rand(0,20000);
	}

	/**
	 * Places dialog onto page
	 */
	public function dialog() {		
		?>
		<div id="<?php echo $this->_id;?>" class="bt_dialog"></div>
		
		<script type="text/javascript">
			$(document).ready(function() {
				$( "#<?php echo $this->_id;?>" ).dialog({
					autoOpen: false,
					modal: true,
					width: 800,
					open: function(){ $(this).parent().css('overflow', 'visible');}
				});
			});
		</script>
		<?php
	}
	
	/**
	 * Shows the dialog on the screen.
	 * 
	 * @param string Path to the file to load, via an AJAX get request
	 * @param string $title Title of the dialog
	 * @param array $opts Various options for the dialog
	 */
	public function show($view,$title,$opts = array()) {
		$default_opts = array('width'=>800,'height'=>'','onLoad'=>'','params'=>'params','cancelButton'=>'.cancel');
		$opts = array_merge($default_opts,$opts);
	
		?>
		$(document).ready(function() {
			$('#<?php echo $this->_id;?>').dialog('option', 'title', '<?php echo addSlashes($title);?>');
			
			<?php
				if($opts['width']) {
					printf("$('#%s').dialog('option', '%s', %s);",$this->_id,'width',$opts['width']);
				}
				
				if($opts['height']) {
					printf("$('#%s').dialog('option', '%s', %s);",$this->_id,'height',$opts['height']);
				}
				
				if(!$opts['params']) {
					$opts['params'] = '{}';
				}
			?>
			
			$.get("<?php echo $view;?>",<?php echo $opts['params'];?>,function(data) {
				$('#<?php echo $this->_id;?>').html(data);
				<?php if($opts['onLoad']) { ?>
					var func = '<?php echo $opts['onLoad'];?>'
							
					window[func]('<?php echo $this->_id;?>');			
				<?php } ?>
				
				if('<?php echo $opts['cancelButton'];?>') {
					$('#<?php echo $this->_id;?> <?php echo $opts['cancelButton'];?>').click(function() {
						$('#<?php echo $this->_id;?>').dialog('close');
					});
				}
															
				$('#<?php echo $this->_id;?>').dialog('open');
			});
		});
		<?php
	}
	
	/**
	 * Close the dialog. 
	 */
	public function close() {
		?>
		$('#<?php echo $this->_id;?>').dialog('close');
		$('#<?php echo $this->_id;?>').html('');
		<?php
	}
}