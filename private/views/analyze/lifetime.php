<h1 class="grid_12"><span>Analyze Click Lifetime</span></h1>
 
<div class="grid_12">
	<p>Click Lifetime is the amount of time between users' clicks and conversions.</p>
</div>                                    

<?php
$this->setVar('page','/ajax/analyze/viewlifetime');
$this->setVar('opts','analyze/lifetime');
$this->loadTemplate('report_options');
?>