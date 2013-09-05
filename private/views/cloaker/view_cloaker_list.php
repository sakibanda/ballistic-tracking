<?php

foreach($cloakers as $cloaker) {
	echo <<<ENDHTML
	<tr>
		<td>
			{$cloaker->name}
		</td>
		<td>
			{$cloaker->url}
		</td>
		<td>
			<a href="/cloaker/edit?id={$cloaker->id()}" class="button small grey tooltip" title="Edit"><i class="icon-pencil"></i> Edit</a>
			<a rel="{$cloaker->id()}" class="button small grey tooltip copy_cloaker" title="Clone" href="#"><i class="icon-copy"></i> Clone</a>
			<a rel="{$cloaker->id()}" class="button small grey tooltip delete_cloaker" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
		</td>
	</tr>
ENDHTML;
}