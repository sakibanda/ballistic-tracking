<h1 class="grid_12"><span>Time Zones Breakdown</span></h1>

<?php
$this->setVar('page','/ajax/geography/viewtimezones');
$this->setVar('opts','geography/time_zones');
$this->loadTemplate('report_options');
?>