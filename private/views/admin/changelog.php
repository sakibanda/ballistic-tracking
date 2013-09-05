<?php $this->menu();  ?>

<h1 class="grid_12"><span>View Version Changes</span></h1>

<div class="grid_12">
	<h2>13.05.08</h2>
	
	<ul>
		<li>Added changelog under Admin -> What's New.</li>
		<li>Relocated spy view menu item to directly under the overview item.</li>
		<li>Valid form fields will no longer show green checkmark icon. Invalid
		fields will continue to show red icon.</li>
		<li>Switched theme to use OS/Browser-native dropdown menus. This will fix
		several alignment and resize issues throughout the system with long dropdown
		menu labels.</li>
		<li>Added delete offer option under Manage LP campaign.</li>
		<li>Fixed system-wide pagination issue with reporting. Previously, going forward
		one page would only go forward one item.</li>
		<li>Greatly improved export functionality on reports. CSV exports will now
		export the entire report, rather than just the current page.</li>
		<li>Fixed misspelling on edit Ad Account dialog.</li>
		<li>Increased auto-logout timeout to 90 minutes.</li>
		<li>Increased dialog widths.</li>
		<li>Switched Ad Account's "pixel code" field to a multi-line textarea, so users can view the entire pixel code.</li>
	</ul>
</div>

<div class="grid_12">
	<h2>13.05.02</h2>
	
	<ul>
		<li>Improved validation for offer URLs</li>
		<li>Fixed tooltip for redirect pause button, to better reflect changes from 13.04.23. </li>
		<li>Fixed bug on manage links page which caused the system to not properly pre-select the correct offer or LP in the rotation drop down menus. </li>
		<li>Added token support for the iframe pixel code - to allow users to pass back subids, clickids, or amounts.</li>
		<li>Added support to postback pixel to allow ballistic to fire 3rd party postback code during the pixel fire. This also supports the above mentioned tokens.</li>
	</ul>
</div>

<div class="grid_12">
	<h2>13.04.23</h2>
	
	<ul>
		<li>Added colorization into the overview table.</li>
		<li>Added variable passthrough to the manage Direct/LP campaigns pages. This enables users to directly pass specific variables through Ballistic Tracking.</li>
		<li>Increased entropy for randomized variable name generation.</li>
		<li>Pausing an advanced redirect will now cause all traffic to flow to the "Safe URL" page.</li>
		<li>Fixed bug that caused subid variables of differing cases from being correctly tracked, and from throwing incorrect syslog messages.</li>
	</ul>
</div>

<div class="grid_12">
	<h2>13.04.16</h2>
	
	<ul>
		<li>Initial Release</li>
	</ul>
</div>