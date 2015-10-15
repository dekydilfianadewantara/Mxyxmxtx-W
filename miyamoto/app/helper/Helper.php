<?php
class Helper {
// Mhayamoto

	public static function UsersNotif()
	{
		$um = UserModel::where('id','!=',1)->where('is_active',0)->count();
		if(!empty($um)){
			return '<small class="badge pull-right bg-yellow newusers" data-toggle="tooltip" data-html="true" data-placement="top" title="New Users">'.$um.'</small>';
		}
	}
	
	public static function GetIdBid($idUser)
	{
		$bm = BidModel::where('id_user',$idUser)->where('status',0)->get();
		foreach ($bm as $key => $value) {
			# code...
		}
	}
	public static function TotalOfBid($id)
	{
		return BidModel::where('id_request',$id)->count();
	}

	public static function Encrypt($id)
	{
		return Crypt::encrypt($id);
	}

	public static function Decrypt($id)
	{
		return Crypt::decrypt($id);
	}
	public static function CheckNotif($idUser)
	{
		$rm = RequestModel::where('id_user',$idUser)->get();
		if(count($rm) > 0){
			foreach ($rm as $i => $r) {
				$req[] = $r->id;
			}
			$BM = BidModel::whereIn('id_request',$req)->where('bid_notif',0)->orderBy('id','desc')->count();
		}
		$Bid = BidModel::where('id_user',$idUser)->where('status',1)->where('notif',0)->count();
		return (isset($BM))?$BM+$Bid:$Bid;
	}
	public static function GetNotif($idUser)
	{
		$limit = (self::CheckNotif($idUser))?self::CheckNotif($idUser):8;
		$rm = RequestModel::where('id_user',$idUser)->get();
		if(count($rm) > 0){
			foreach ($rm as $i => $r) {
				$req[] = $r->id;
			}
			$BM = BidModel::whereIn('id_request',$req)->orderBy('id','desc')->take($limit)->get();
			if(count($BM) > 0){
				foreach ($BM as $q => $val) {
					if($val->bid_notif == '0'){
						// echo '<li>
		    //                 <a href='.URL::to("admin/request/detail-bid/".Helper::Encrypt($val->id_request)).'>
		    //                     '.$val->about->name.' bid '.$val->request->title.'
		    //                     <small class="pull-right"><small class="new-notif">new</small><i class="fa fa-clock-o"></i> 5 mins</small>
		    //                 </a>
		    //             </li>';
		                echo '<li class="new-notif">
			                    <a href='.URL::to("admin/request/detail-bid/".Helper::Encrypt($val->id_request)).'>
			                        '.$val->about->name.' bid '.$val->request->title.'
			                        <small class="pull-right"><i class="fa fa-clock-o"></i> '.self::get_timeago(strtotime($val->created_at)).'</small>
			                    </a>
			                </li>';
					}else{
						echo '<li>
			                    <a href='.URL::to("admin/request/detail-bid/".Helper::Encrypt($val->id_request)).'>
			                        '.$val->about->name.' bid '.$val->request->title.'
			                        <small class="pull-right"><i class="fa fa-clock-o"></i> '.self::get_timeago(strtotime($val->created_at)).'</small>
			                    </a>
			                </li>';
		            }
				}
			}
		}
		$BM = BidModel::where('id_user',$idUser)->where('status',1)->orderBy('id','desc')->take($limit)->get();
		if(!empty($BM)){
			foreach ($BM as $key => $value) {
				echo '<li>
	                    <a href='.URL::to("admin/find-work?status=current").'>
	                        Your bid about '.$value->request->title.'  was accepted
	                    </a>
	                </li>';
			}
		}

	}

	public static function get_timeago($ptime)
	{
	    $estimate_time = time() - $ptime;

	    if( $estimate_time < 1 )
	    {
	        return 'less than 1 second ago';
	    }

	    $condition = array( 
	                12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	    );

	    foreach( $condition as $secs => $str )
	    {
	        $d = $estimate_time / $secs;

	        if( $d >= 1 )
	        {
	            $r = round( $d );
	            return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
	        }
	    }
	}

	public static function GetStreet($String)
	{
		$E = explode(',', $String);
		return $E[0];
	}

	public static function GetStreetCity($String)
	{
		$E = explode(',', $String);
		if(!empty($E[0]) && !empty($E[1])){
			return $E[0].', '.$E[1];
		}elseif(!empty($E[0]) && empty($E[1])){
			return $E[0];
		}
	}

	public static function GetDistance($lat1,$lng1,$lat2,$lng2)
	{
		$a = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?origin=".$lat1.",".$lng1."&destination=".$lat2.",".$lng2."&key=AIzaSyA8HshEMMYjzaBhabEOoWmW2-Ix2lN8T8k");
		$EncodeJson = json_decode($a,TRUE);
		if($EncodeJson['status']=='OK'){
			return $EncodeJson['routes'][0]['legs'][0];
		}elseif($EncodeJson['status']=='ZERO_RESULTS'){
			return self::ManualGetDistance($lat1,$lng1,$lat2,$lng2);
		}
	}

	public static function GetAddress($lat,$lng)
	{
		$a = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$lat.",".$lng."&sensor=false&key=AIzaSyA8HshEMMYjzaBhabEOoWmW2-Ix2lN8T8k");
		$EncodeJson = json_decode($a,TRUE);
		if($EncodeJson['status']=='OK'){
			return $EncodeJson['results'][0]['formatted_address'];
		}elseif($EncodeJson['status']=='ZERO_RESULTS'){
			return 'Unknown Address';
		}
	}

	public static function ManualGetDistance($lat1,$lng1,$lat2,$lng2)
	{
		$start    = array($lat1,$lng1);
		$finish   = array($lat2,$lng2);
		$theta    = $start[1]-$finish[1];
		$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		$km       = round($distance, 2);
		$result   = round($km*1.609344,2);
		return array('distance'=>array('text'=>$result),'duration'=>array('text'=>''));
	}

	public static function Jarak($lat1,$lng1,$lat2,$lng2)
	{
		$start    = array($lat1,$lng1);
		$finish   = array($lat2,$lng2);
		$theta    = $start[1]-$finish[1];
		$distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		$km       = round($distance, 2);
		return $result   = round($km*1.609344,2);
		// return array('distance'=>array('text'=>$result),'duration'=>array('text'=>''));
	}

	public static function GetWeight($idR)
	{
		$RI = RequestItemModel::where('id_request',$idR)->count();
		if($RI){
			$RIM = RequestItemModel::where('id_request',$idR)->get();
			foreach ($RIM as $key => $value) {
				$total[] = $value->weight;
			}
			return $amount = array_sum($total);
		}else{
			return $amount = 0; 
		}
	}

	public static function GetName($idUser)
	{
		$UDM = UserModel::find($idUser);
		return $UDM->name;
	}

	public static function GetPhone($idUser)
	{
		$UDM = UserModel::find($idUser);
		return $UDM->phone;
	}

	public static function MessageSent($IdBid)
	{
		// DB::table('message_users')
		// 	->insert(array(
		// 		'id_bid'     => $IdBid,
		// 		'id_request' => $idRequest
		// 	));
		$now    = strtotime(date('Y-m-d H:i'));
		$finish = date('Y-m-d H:i',strtotime("+2 minutes",$now));
		$BM = BidModel::find($IdBid);
		$BM->sent        = 1;
		$BM->send_time   = date('Y-m-d H:i').':00';
		$BM->finish_time = $finish.':00';
		$BM->save();
	}

	public static function StopSend($idRequest)
	{
		return BidModel::where('sent',1)->where('reply',1)->where('id_request',$idRequest)->count();
	}

	public static function ExpireRequest($idRequest)
	{
		$requestMod = RequestModel::find($idRequest);
		$now = Date('Y-m-d H:i').':00';
		$B = BidModel::where('id_request',$idRequest)->count();
		if(empty($B)){
			if(($requestMod->repeat_times <= 2) && ($requestMod->close_date < $now)){
				self::RepeatAgain($idRequest,$requestMod->repeat_times);
			}else{
				$rm = RequestModel::find($idRequest);
				$rm->expire = 1;
				$rm->save();
				Nexmo::SendNoResponse($idRequest);
			}
		}else{
			$BM = BidModel::where('sent',0)->where('reply',0)->where('ignore',0)->where('id_request',$idRequest)->count();
			// $BM = BidModel::where('sent',0)->where('reply',0)->where('id_request',$idRequest)->first();
			if(!empty($BM)){
				$SBM = BidModel::where('sent',1)->where('reply',1)->where('id_request',$idRequest)->count();
				if(!empty($SBM)){
					if(($requestMod->repeat_times <= 2) && ($requestMod->close_date < $now)){
						self::RepeatAgain($idRequest,$requestMod->repeat_times);
					}else{	
						$rm = RequestModel::find($idRequest);
						$rm->expire = 1;
						$rm->save();
					}
				}
			}else{
				$BidSent = BidModel::where('sent',1)->where('reply',0)->where('ignore',0)->where('id_request',$idRequest)->count();
				// return 'ada';
				if(empty($BidSent)){
					$BidMod = BidModel::where('sent',1)->where('ignore',1)->where('id_request',$idRequest)->count();
					if(!empty($BidMod)){
						if(($requestMod->repeat_times <= 2) && ($requestMod->close_date < $now)){
							self::RepeatAgain($idRequest,$requestMod->repeat_times);
						}else{	
							$rm = RequestModel::find($idRequest);
							$rm->expire = 1;
							$rm->save();
							Nexmo::SendNoResponse($idRequest);
						}
					}
				}
			}
		}
	}

	public static function RepeatAgain($idRequest,$rTimes)
	{
		$now = Date('Y-m-d H:i').':00';
		$RM   = RequestModel::find($idRequest);
		$closeD = $RM->close_date;
		// $menit = self::minutesDiff($RM->repeat_from);
		// if($menit>=2){
			$bidMod = BidModel::where('id_request',$idRequest)->get();
			if(count($bidMod)>0){
				// $bids = BidModel::where('id_request',$idRequest)->get();
				$ignore = self::countIgnore($idRequest);
				if(count($bidMod) == $ignore){
					BidModel::where('id_request',$idRequest)->delete();
					// $RM->repeat_from = self::newCloseDate($closeD,60);
					// $RM->repeat_from = $now;
					$RM->repeat_times = 1 + $rTimes;
					$RM->close_date = self::newCloseDate($closeD,30);
					$RM->save();
					$RIM = RequestItemModel::where('id_request',$idRequest)->get();
					foreach ($RIM as $i => $val) {
						$Berat[] = $val->weight;
					}
					$DataAgent  = DB::table('user_detail')
									->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
									->where('vehicles_size.size','>',array_sum($Berat))->get();
					$lat1      = $RM->latitude_from;
					$lng1      = $RM->longitude_from;
					$from      = $RM->desc_from;
					$to        = $RM->desc_to;
					$price     = $RM->open_price;
					$distance  = $RM->distance;
					$closeDate = $RM->close_date;
					foreach ($DataAgent as $key => $value) {
						$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
						if($jarak <= 10){
							Nexmo::SendSMS($value->id_user,$from,$to,$price,$RM->id,$RM->title,array_sum($Berat),$distance,$closeDate);
						}
					}
				}
			}else{
				// $RM->repeat_from = self::newCloseDate($closeD,60);
				// $RM->repeat_from = $now;
				$RM->repeat_times = 1 + $rTimes;
				$RM->close_date = self::newCloseDate($closeD,30);
				$RM->save();
				$RIM = RequestItemModel::where('id_request',$idRequest)->get();
				foreach ($RIM as $i => $val) {
					$Berat[] = $val->weight;
				}
				$DataAgent  = DB::table('user_detail')
								->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
								->where('vehicles_size.size','>',array_sum($Berat))->get();
				$lat1      = $RM->latitude_from;
				$lng1      = $RM->longitude_from;
				$from      = $RM->desc_from;
				$to        = $RM->desc_to;
				$price     = $RM->open_price;
				$distance  = $RM->distance;
				$closeDate = $RM->close_date;
				foreach ($DataAgent as $key => $value) {
					$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
					if($jarak <= 10){
						Nexmo::SendSMS($value->id_user,$from,$to,$price,$RM->id,$RM->title,array_sum($Berat),$distance,$closeDate);
					}
				}		
			}
		// }
	}

	public static function newCloseDate($closeDate,$n)
	{
		$cDate = explode(' ', $closeDate);
		$tgl   = explode('-', $cDate[0]);
		$jam   = explode(':', $cDate[1]);

		$nextN = mktime($jam[0], $jam[1] + $n, 0, $tgl[1], $tgl[2], $tgl[0]);
		return date('Y-m-d H:i:s',$nextN);
	}

	public static function countIgnore($idRequest)
	{
		return BidModel::where('id_request',$idRequest)->where('ignore',1)->count();
	}

	public static function minutesDiff($time)
	{
		$to_time=strtotime($time);
		$from_time=strtotime(date('Y-m-d H:i').':00');
		return round(abs($to_time - $from_time) / 60,2);
	}

	public static function GroupTotal($idGroup)
	{
		return UserModel::where('level',$idGroup)->count();
	}

	public static function CronJob()
	{
// 		$VM             = new VisitorModel;
// $VM->ip         = '212';
// $VM->id_article = 1;
// $VM->type       = 1;
// $VM->save();
// return;
    	$RM = RequestModel::where('expire',0)
			->where('close_date','<',date('Y-m-d H:i').':00')
			->where('status','0')
			->get();
			foreach ($RM as $a => $b) {
					$ID_REQUEST[] = $b->id;
			}
		$now = Date('Y-m-d H:i').':00';
		if(!empty($ID_REQUEST)){
			foreach ($ID_REQUEST as $s => $rec) {

						$Bid = BidModel::orderBy('price','asc')
								->orderBy('created_at','asc')
								->where('id_request',$rec)
								->where('status',0)
								->where('sent',1)
								->where('reply',0)
								->where('ignore',0)
								->first();
						if(!empty($Bid)){
							if(($Bid->finish_time < $now) && ($Bid->reply == 0) && $Bid->ignore == 0){
							
								DB::table('bid')->where('id',$Bid->id)->update(array('ignore' => 1));
								$Bid = BidModel::orderBy('price','asc')
										->orderBy('created_at','asc')
										->where('id_request',$rec)
										->where('status',0)
										->where('sent',0)
										->where('reply',0)
										->first();
								if(!empty($Bid)){
									Nexmo::SendWinner($Bid->id_user,$rec);
									Helper::MessageSent($Bid->id);
								}
							}
						}else{
							$Bid = BidModel::orderBy('price','asc')
									->orderBy('created_at','asc')
									->where('id_request',$rec)
									->where('status',0)
									->where('sent',0)
									->where('reply',0)
									->first();
							if(!empty($Bid)){

								Nexmo::SendWinner($Bid->id_user,$rec);
								Helper::MessageSent($Bid->id);			
							}
						}
				Helper::ExpireRequest($rec);
			}
		}
	}

	public static function CallbackGammu($phone,$text)
	{
		// work with get or post
		// $request = array_merge($_GET, $_POST);
		// $to               = $request['to'];
		// $msisdn           = $request['msisdn'];
		// $text             = $request['text'];
		// $type             = $request['type'];
		// $messagetimestamp = $request['message-timestamp'];
		// $messageId        = $request['messageId'];
		// check that request is inbound message
		if(!isset($phone) OR !isset($text)){
		    error_log('not inbound message');
		    return;
		}

		// $rec = new ReceiveMessageModel;
		// $rec->msisdn           = $phone;
		// $rec->text             = $text;
		// $rec->save();

		$um = UserModel::where('phone',$phone)->first();
		if(!empty($um)){
			$SMS = explode("#", $text);
			if(count($SMS)==3){
				$dateNow    = date('Y-m-d H:i').':00';
				//BID#21#10000 (BID#idrequest#price)
				if($SMS[0]=='BID' && is_numeric($SMS[1]) && is_numeric($SMS[2])){
					$ID_REQUEST = $SMS[1];
					$PRICE      = $SMS[2];
					$rm = RequestModel::find($ID_REQUEST);
					if(!empty($rm)){
						if($dateNow <= $rm->close_date){
							$bCheck = BidModel::where('id_user',$um->id)->where('id_request',$ID_REQUEST)->count();
							if(empty($bCheck)){
								if($PRICE <= $rm->open_price){
									$bm = new BidModel;
									$bm->id_user    = $um->id;
									$bm->id_request = $ID_REQUEST;
									$bm->price      = $PRICE;
									$bm->save();

									//Send SMS
									$RIM = RequestItemModel::where('id_request',$ID_REQUEST)->get();
									foreach ($RIM as $key => $value) {
										$Berat[] = $value->weight;
									}
									if(!empty($Berat)){
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('vehicles_size.size','>',array_sum($Berat))
													->where('user_detail.id_user','!=',$um->id)->get();
									}else{
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('user_detail.id_user','!=',$um->id)
													->get();
									}
									$lat1  = $rm->latitude_from;
									$lng1  = $rm->longitude_from;
									$from  = $rm->desc_from;
									$to    = $rm->desc_to;
									$title = $rm->title;
									foreach ($DataAgent as $key => $value) {
										$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
										if($jarak <= 10){
											Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$ID_REQUEST);
										}
									}
								}
							}
						}
					}
				}
				//REQUEST#21#YES (request#idrequest#yes)
				if ($SMS[0]=='REQUEST' && is_numeric($SMS[1]) && $SMS[2]=='YES') {
					$IDREQUEST = $SMS[1];
					$RM = RequestModel::find($IDREQUEST);
					if(!empty($RM)){
						if($dateNow > $RM->close_date && $RM->expire == 0){
							$CheckStatus = BidModel::where('id_user',$um->id)
											->where('id_request',$IDREQUEST)
											->where('sent',1)
											->where('reply',0)
											->where('ignore',0)
											->where('finish_time','>=',$dateNow)
											->count();
											if(!empty($CheckStatus)){
												// $CheckBid = BidModel::where('id_user',$um->id)
												// 			->where('id_request',$IDREQUEST)
												// 			->where('reply',1)->count();
												// 			if(empty($CheckBid)){
												DB::table('bid')
													->where('id_user',$um->id)
													->where('id_request',$IDREQUEST)
													->update(array('status' => 1, 'reply' => 1, 'notif' => 0));
															// }
													$r = RequestModel::find($IDREQUEST);
													$r->status = 1;
													$r->expire = 1;
													$r->save();
													GammuSms::sendText($phone, 'Please come to ('.$RM->desc_from.') to get your job');
													$bidMod = BidModel::where('id_request',$IDREQUEST)->where('id_user','!=',$um->id)->get();
													if(count($bidMod)>0){
														foreach ($bidMod as $in => $vals) {
															GammuSms::sendText($vals->about->phone, 'We regret to inform you that your bid for BID#'.$IDREQUEST.' has not been successful. Thank you for participating');
														}
													}
											}
						}
					}
				}
				if ($SMS[0]=='UPDATE' && is_numeric($SMS[1]) && is_numeric($SMS[2])) {
					$ID_REQUEST = $SMS[1];
					$PRICE      = $SMS[2];
					$rm = RequestModel::find($ID_REQUEST);
					if(!empty($rm)){
						if($dateNow <= $rm->close_date){
							$bCheck = BidModel::where('id_user',$um->id)->where('id_request',$ID_REQUEST)->count();
							if(!empty($bCheck)){
								if($PRICE <= $rm->open_price){
									DB::table('bid')
										->where('id_user',$um->id)
										->where('id_request',$ID_REQUEST)
										->update(array('price'=>$PRICE));

									//Send SMS
									$RIM = RequestItemModel::where('id_request',$ID_REQUEST)->get();
									foreach ($RIM as $key => $value) {
										$Berat[] = $value->weight;
									}
									if(!empty($Berat)){
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('vehicles_size.size','>',array_sum($Berat))
													->where('user_detail.id_user','!=',$um->id)->get();
									}else{
									$DataAgent  = DB::table('user_detail')
													->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
													->where('user_detail.id_user','!=',$um->id)
													->get();
									}
									$lat1  = $rm->latitude_from;
									$lng1  = $rm->longitude_from;
									$from  = $rm->desc_from;
									$to    = $rm->desc_to;
									$title = $rm->title;
									foreach ($DataAgent as $key => $value) {
										$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
										if($jarak <= 10){
											Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$ID_REQUEST);
										}
									}
								}
							}
						}
					}
				}
			}
		}

	}
// Mhayamoto

}