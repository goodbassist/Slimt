<?php

/**
* subscription class 
*@author Badejo Oluwatobi
*/
class Subscription {

					public function planDet($planId){
						$DbClass 						= new DbClass;
						$result							= $DbClass->db->select("Plans", "Id = '$planId'");
						return $result;
					}

					public function subdetails($Id){
							$submit_url = "http://api.shobbutest.com/1.0/stores/".$Id."/subscription?results=100&page=1";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $submit_url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result = curl_exec($ch);
							curl_close ($ch);
							$data = json_decode($result, true);
							return $data;
					}
					public function currentsubDets($streID){
							$submain            =   $this->subdetails($streID);
	                        $subdetmain         =   $submain['subscriptions'];
	                        $lastdate           =   array_values($subdetmain)[0];
							return 				$lastdate['stop-date'];
					}
					public function subcounter($streID){
							$submain            =   $this->subdetails($streID);
	                        $subdetmain         =   $submain['subscriptions'];
	                        $lastdate           =   array_values($subdetmain)[0];
	                        $value1 			=  	date('Y-m-d', $lastdate['stop-date']);
	                        $value2 			=  	date('Y-m-d', time());
	                        $timeleft			= 	$this->dateDiff($value1,$value2);
	                        $ret ='<div  style="margin:0;height:30px;line-height:0;margin-left:40px;" class="alert alert-warning alert-dismissable">'.$timeleft.'
              						<b>Left!</b>
						              <i class="fa fa-exclamation-circle"></i><a href="user_payment.php"></a>
						         </div>';
						      return $ret;

					}
					
					function dateDiff($time1, $time2, $precision = 6) {
					    // If not numeric then convert texts to unix timestamps
					    if (!is_int($time1)) {
					      $time1 = strtotime($time1);
					    }
					    if (!is_int($time2)) {
					      $time2 = strtotime($time2);
					    }
					 
					    // If time1 is bigger than time2
					    // Then swap time1 and time2
					    if ($time1 > $time2) {
					      $ttime = $time1;
					      $time1 = $time2;
					      $time2 = $ttime;
					    }
					 
					    // Set up intervals and diffs arrays
					    $intervals = array('year','month','day','hour','minute','second');
					    $diffs = array();
					 
					    // Loop thru all intervals
					    foreach ($intervals as $interval) {
					      // Create temp time from time1 and interval
					      $ttime = strtotime('+1 ' . $interval, $time1);
					      // Set initial values
					      $add = 1;
					      $looped = 0;
					      // Loop until temp time is smaller than time2
					      while ($time2 >= $ttime) {
					        // Create new temp time from time1 and interval
					        $add++;
					        $ttime = strtotime("+" . $add . " " . $interval, $time1);
					        $looped++;
					      }
					 
					      $time1 = strtotime("+" . $looped . " " . $interval, $time1);
					      $diffs[$interval] = $looped;
					    }
					 
					    $count = 0;
					    $times = array();
					    // Loop thru all diffs
					    foreach ($diffs as $interval => $value) {
					      // Break if we have needed precission
					      if ($count >= $precision) {
						break;
					      }
					      // Add value and interval 
					      // if value is bigger than 0
					      if ($value > 0) {
						// Add s if value is not 1
						if ($value != 1) {
						  $interval .= "s";
						}
						// Add value and interval to times array
						$times[] = $value . " " . $interval;
						$count++;
					      }
					    }
					 
					    // Return string with times
					    return implode(", ", $times);
					  }	
	
	
}



?>