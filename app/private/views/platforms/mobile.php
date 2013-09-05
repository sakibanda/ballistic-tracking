<?php $this->loadTemplate('report_links'); ?>

<h1 class="grid_12"><span>Mobile Breakdown</span></h1>

<script type="text/javascript">
	$('body').addClass('flex_width');
</script>

<?php
$this->setVar('page','/ajax/platforms/viewmobile');
$this->setVar('opts','platforms/mobile');
$this->loadTemplate('report_options');
?>