<h1 class="grid_12"><span>City Breakdown</span></h1>

<?php
$this->setVar('page','/ajax/geography/viewcities');
$this->setVar('opts','geography/cities');
$this->loadTemplate('report_options');
?>