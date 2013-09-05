<?php $this->loadTemplate('report_links'); ?>

<h1 class="grid_12"><span>Date Parting</span></h1>

<script type="text/javascript">
	$('body').addClass('flex_width');
</script>

<?php
$this->setVar('page','/ajax/dateparting/view');
$this->setVar('opts','dateparting/date');
$this->loadTemplate('report_options');
?>