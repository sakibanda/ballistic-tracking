<?php $this->loadTemplate('stats_links'); ?>
<h1 class="grid_12"><span>Campaign Stats</span></h1>

<?php
$this->setVar('page','/ajax/stats/viewStats');
$this->setVar('opts','reports/stats');
$this->loadTemplate('report_options');
?>