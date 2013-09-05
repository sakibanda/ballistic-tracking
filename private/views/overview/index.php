<h1 class="grid_12"><span>Campaign Overview</span></h1>

<script type="text/javascript">
	$('body').addClass('flex_width');
</script>

<?php
$this->setVar('page','/ajax/overview/viewOverview');
$this->setVar('opts','overview/overview');
$this->loadTemplate('report_options');
?>