<?php $this->loadTemplate('report_links'); ?>

<h1 class="grid_12"><span>Custom Data Report</span></h1>
<script type="text/javascript">
    $('body').addClass('flex_width');
</script>
<?php
$this->setVar('page','/ajax/reports/viewCustomReport');
$this->setVar('opts','reports/reports');
$this->loadTemplate('report_options_reportpage');
?>