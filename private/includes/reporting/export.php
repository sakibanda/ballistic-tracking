<?php

function doReportExport($name,$callback,$type,$column_labels) {
	if($type != 'csv') {
		return false;
	}
	
	$file_name = BT_ROOT . '/bt-config/cache/' . $name . '_' . getUserID() . '.' . $type;
	$fh = fopen($file_name,'w+');
	
	$page_cnt = 1000;
	
	$_GET['iDisplayStart'] = 0;
	$_GET['iDisplayLength'] = $page_cnt;
	
	extract(call_user_func($callback));
		
	fputcsv($fh,explode(',',$column_labels));
	
	if($data) {
		do {
			switch($type) {
				case 'csv':
					foreach($data as $row) {
						$real_row = array();
						foreach($cols as $col) {
							$value = getArrayVar($row,$col);
							
							//if it has spaces, for example mobile breakdown
							$value = str_replace('&nbsp;',' ',$value);
							
							$real_row[] = formatColumnValue($col,$value);
						}
						
						fputcsv($fh,$real_row);
					}
			}
			
			$_GET['iDisplayStart'] += $page_cnt;
			
			extract(call_user_func($callback));
		}while($data);
	}
	
	fclose($fh);
					
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$name . '.'  .$type);
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');

	$content = file_get_contents($file_name);
		
	echo $content;
}