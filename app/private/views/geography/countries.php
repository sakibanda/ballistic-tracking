<h1 class="grid_12"><span>Country Breakdown</span></h1>

<?php
$this->setVar('page','/ajax/geography/viewcountries');
$this->setVar('opts','geography/countries');
$this->loadTemplate('report_options');
?>