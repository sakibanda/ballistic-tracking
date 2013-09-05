<?php

function rotateLPCampaign($campaign) {
	$lps = $campaign->landing_pages;
	
	if(!$lps) {
		return 0;
	}
	
	$lp_ids = array();
	
	foreach($lps as $lp) {
		for($i = 0;$i < $lp->weight;$i++) {
			$lp_ids[] = $lp->id();
		}
	}
	
	$rot = $campaign->rotate;
	
	if($rot >= count($lp_ids)) {
		$rot = 0;
	}
	
	$lp_id = $lp_ids[$rot];
		
	$rot++;
	
	$campaign->rotate = $rot;
	$campaign->useRuleSet('rotation');
	$campaign->save();
	
	foreach($lps as $lp) {
		if($lp->id() == $lp_id) {
			return $lp;
		}
	}
	
	return null;
}

function rotateDirectCampaign($campaign) {
	$offers = $campaign->offers;
	
	if(!$offers) {
		return 0;
	}
	
	$offer_ids = array();
	
	foreach($offers as $offer) {
		for($i = 0;$i < $offer->weight;$i++) {
			$offer_ids[] = $offer->id();
		}
	}
	
	$rot = $campaign->rotate;
	
	if($rot >= count($offer_ids)) {
		$rot = 0;
	}
	
	$offer_id = $offer_ids[$rot];
		
	$rot++;
	
	$campaign->rotate = $rot;
	$campaign->useRuleSet('rotation');
	$campaign->save();
	
	foreach($offers as $offer) {
		if($offer->id() == $offer_id) {
			return $offer;
		}
	}
	
	return null;
}