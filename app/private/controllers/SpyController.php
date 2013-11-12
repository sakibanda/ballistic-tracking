<?php

class SpyController extends BTUserController {
	public function indexAction() {
		$this->setVar("title","Click Spy");
		$this->render("spy/spy");
	}
	
	public function dataAction() {		
		$this->loadModel("ClickModel");
		$model = new ClickModel();

        $colSearchs = array('click.click_id','camp.name','ip_address','referer_domain');
        $like = "";
        if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
            for ( $i=0 ; $i<count($colSearchs) ; $i++ ){
                if ($colSearchs[$i]=="click.click_id"){
                    $like .= $colSearchs[$i]." = '".BTHtml::decode(base_convert($_GET['sSearch'],36,10))."' OR ";
                }else{
                    $like .= $colSearchs[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch'])."%' OR ";
                }
            }
            $like = substr_replace( $like, "", -3 );
        }

		$data = $model->clickSpy(getUserId(),$_GET['iDisplayStart'],$_GET['iDisplayLength'],$like);
		$cnt = $data['count'];
		$click_rows = $data['click_rows'];
		
		$sEcho = $_GET['sEcho'];

		$data = array('sEcho'=>(int)$sEcho,
			'iTotalRecords'=>$cnt,
			'iTotalDisplayRecords'=>$cnt,
			'aaData'=>array());
		
		foreach($click_rows as $click_row) {														
				$html['referer'] = BTHtml::encode($click_row['referer_url_address']);   
				$html['referer_host'] = BTHtml::encode($click_row['referer_domain_host']);   
								
				$html['redirect'] = BTHtml::encode($click_row['redirect_url_address']);   
				
				$html['click_pid'] = BTHtml::encode(base_convert($click_row['click_id'],10,36));
				$html['time'] = date('m/d/y g:ia',$click_row['time']); 
				$html['ts.name'] = BTHtml::encode($click_row['ts.name']);
				$html['ip_address'] = BTHtml::encode($click_row['ip_address']);
				$html['keyword'] = BTHtml::encode($click_row['keyword']);
				$html['lead'] = BTHtml::encode($click_row['lead']);
				$html['filtered'] = BTHtml::encode($click_row['filtered']);   
				$html['landing'] = BTHtml::encode($click_row['landing']);
				$html['name'] = BTHtml::encode($click_row['name']); 
				
				$html['browser_id'] = '';
				if ($click_row['browser_id']) {
					$html['browser_id'] = '<img width="16" height="16" class="tooltip" title="'.Browser::getBrowserName($click_row['browser_id']).'" src="/theme/img/icons/browsers/'.$click_row['browser_id'].'.png"/>';			    
				}
				
				$html['platform_id'] = '';
				if ($click_row['platform_id']) {
					$html['platform_id'] = '<img width="16" height="16" class="tooltip" title="'.Browser::getPlatformName($click_row['platform_id']).'" src="/theme/img/icons/platforms/'.$click_row['platform_id'].'.png"/>';    
				}
					 
			$data_array = array();
			$data_array[] = strtoupper($html['click_pid']);
			
			$admin_image = '';
			// if a click happened from the admin use user.png
			if($click_row['filtered'] == 1) {	// check if logged in user clicked
				$admin_image = '<img width="16" height="16" class="tooltip" src="/theme/img/icons/packs/iconsweets2/16x16/user.png" alt="Affiliate Click" title="Affiliate Click"/>';
			}
			
			$filt = '';
			$landingpage_image = '';
			$offer_image = '';
			$repeated_user = '';
				
			if ($click_row['lead'] == '1') {
				$alt = 'Converted Click';
			
				$filt = '<img width="16" height="16" class="tooltip" src="/theme/img/icons/16x16/money_dollar.png" alt="' . $alt . '" title="' . $alt . '" width="16px" height="16px"/>';
			}
			
			// if a link is going to an offer directly use the bended arrow right.png
			$offer_image = (($html['redirect'])) ? sprintf('<a href="%s" target="_new"><img width="16" height="16" class="tooltip" src="/theme/img/icons/packs/iconsweets2/16x16/bended-arrow-right.png" alt="Offer Click" title="Offer Click"/></a>',$html['redirect']) : '';
			
			if(!$offer_image) {
				// if the click happens on a landing page, they icon should be the document.png
				$landingpage_image = ($html['landing']) ? sprintf('<a href="%s" target="_new"><img width="16" height="16" class="tooltip" src="/theme/img/icons/packs/iconsweets2/16x16/document.png" alt="Landing"  title="Landing Page"/></a>',$html['landing']) : '';
			}
			
			// if a click happened from a repeated user use footprint.png
			if($click_row['filtered'] == 2)	{	// check if repeated user
				$repeated_user = '<img width="16" height="16" class="tooltip" src="/theme/img/icons/packs/iconsweets2/16x16/footprints.png" alt="Repeated User Clicks" title="Repeated User click"/>';
			}
			
			$cloaked_icon = '';
			if($click_row['cloaked']) {
				$cloaked_icon = '<img width="16" height="16" class="tooltip" src="/theme/img/icons/packs/iconsweets2/16x16/bulls-eye.png" alt="Safe Page" title="Visitor Was Filtered By An Advanced Redirect."/>';
			}
			
			if($filt) {
				$data_array[] = $filt . $admin_image . $repeated_user . $cloaked_icon;
			}
			else {
				$data_array[] = $filt . $landingpage_image .  $offer_image . $admin_image . $repeated_user . $cloaked_icon;
			}
			
			$data_array[] = $html['time'];	
			$data_array[] = '<span title="' .  $html['keyword'] . '">' .  $html['keyword'] . '</span>';	
			$data_array[] = $html['name'];
			$data_array[] = sprintf('<a href="%s" target="_new" title="Referer" class="tablelink">%s</a> ',$html['referer'],$html['referer_host']);	
			$data_array[] = sprintf('<a target="_new"  href="http://whois.arin.net/rest/ip/%s" class="tablelink">%s</a>',$html['ip_address'],$html['ip_address']);
			$data_array[] = $html['browser_id'] . ' ' . $html['platform_id'];		
			
			$data['aaData'][] = $data_array;		
		}
		
		echo json_encode($data);
	}
}