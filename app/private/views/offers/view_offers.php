<?php
if(count($offers)<1){
	echo <<<ENDHTML
    <tr>
		<td colspan="5" class="center">You have not added any offers.</td>
	</tr>
ENDHTML;
}else{
	foreach($offers as $offer) {
	    echo <<<ENDHTML
        <tr>
            <td class="center">{$offer->offer_id}</td>
            <td class="center">{$offer->network->name}</td>
            <td>{$offer->name}</td>
            <td class="center">{$offer->payout}</td>
            <td class="center">
                <a class="button small grey tooltip" target="_blank" href="{$offer->url}"><i class="icon-external-link"></i></a>
            </td>
            <td>
                <a href="/offers/edit?id={$offer->offer_id}" class="button small grey tooltip" title="Edit"><i class="icon-pencil"></i> Edit</a>
                <a rel="{$offer->offer_id}" class="button small grey tooltip delete_offer" title="Delete" href="#"><i class="icon-remove"></i> Delete</a>
            </td>
        </tr>
ENDHTML;
	}
}