<?php $this->loadTemplate('report_links'); ?>

<h1 class="grid_12"><span>Breakdown Overview</span></h1>
<script type="text/javascript">
	$('body').addClass('flex_width');
</script>
<?php
$this->setVar('page','/ajax/reports/viewBreakdown');
$this->setVar('opts','reports/breakdown');
$this->loadTemplate('report_options');
?>