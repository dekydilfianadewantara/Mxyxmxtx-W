<?php
class Nexmo {
	
	public static function SendSMS($idUser,$from,$to,$price,$idRequest,$title,$weight,$distance,$time)
	{
		$UDM = UserModel::find($idUser);
		if($UDM->is_active==1){

			$phone = $UDM->phone;
			//from
			$address_from     = explode(',', $from);
			$street_from      = $address_from[0];
			$street_from_city = ($address_from[1])?$address_from[1]:'';

			//to
			$address_to     = explode(',', $to);
			$street_to      = $address_to[0];
			$street_to_city = ($address_to[1])?$address_to[1]:'';
			GammuSms::sendText($phone, 'JOB: Deliver '.$title.' '.$weight.' kgs, from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.'. Distance is '.$distance.' and the maximum rate is $'.$price.'. reply with BID#'.$idRequest.'#price. Bidding ends at '.date('Y-m-d H:i',strtotime($time)));
			// Nulinexmo::sendText($phone, '46769436047', 'JOB: Deliver '.$title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.' and maximum rate is $'.$price.'. reply with BID#'.$idRequest.'#price to 46769436047');

		}

	}

	public static function SendWinner($idUser,$idRequest)
	{
		$RM    = RequestModel::find($idRequest);
		$UDM   = UserModel::find($idUser);
	
		$phone = $UDM->phone;

		//from
		$address_from     = explode(',', $RM->desc_from);
		$street_from      = $address_from[0];
		$street_from_city = ($address_from[1])?$address_from[1]:'';

		//to
		$address_to     = explode(',', $RM->desc_to);
		$street_to      = $address_to[0];
		$street_to_city = ($address_to[1])?$address_to[1]:'';

		GammuSms::sendText($phone, 'You got a job to deliver '.$RM->title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.', reply with REQUEST#'.$idRequest.'#YES');
		// Nulinexmo::sendText($phone, '46769436047', 'You got a job to deliver '.$RM->title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.', reply with REQUEST#'.$idRequest.'#YES to receive this job to 46769436047');

	}

	public static function SendNoResponse($idRequest)
	{
		$RM    = RequestModel::find($idRequest);

		// get weight

		$RIM = RequestItemModel::where('id_request',$idRequest)->get();
		if(count($RIM)>0){
			foreach ($RIM as $key => $value) {
				$berat[$key] = $value->weight;
			}
		}else{
			$berat[] = 0;
		}
		$weight = array_sum($berat);
		//from
		$address_from     = explode(',', $RM->desc_from);
		$street_from      = $address_from[0];
		$street_from_city = ($address_from[1])?$address_from[1]:'';

		//to
		$address_to     = explode(',', $RM->desc_to);
		$street_to      = $address_to[0];
		$street_to_city = ($address_to[1])?$address_to[1]:'';
		GammuSms::sendText('263773182743', 'There is no winner on job to Deliver '.$RM->title.' '.$weight.' kgs, from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.'. Distance is '.$RM->distance.' and the maximum rate is '.$RM->open_price.'. Make a follow up as soon as possible.');
		// Nulinexmo::sendText('263773182743', '46769436047', 'There is no winner on job to Deliver '.$RM->title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.'. Please follow up soon.');
	}

	public static function SendNotifToOther($idUser,$from,$to,$price,$title,$idRequest)
	{
		$UDM = UserModel::find($idUser);
		if($UDM->is_active==1){

			$phone = $UDM->phone;
			//from
			$address_from     = explode(',', $from);
			$street_from      = $address_from[0];
			$street_from_city = ($address_from[1])?$address_from[1]:'';

			//to
			$address_to     = explode(',', $to);
			$street_to      = $address_to[0];
			$street_to_city = ($address_to[1])?$address_to[1]:'';
			GammuSms::sendText($phone, 'Someone has made a bid to deliver '.$title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.' at $'.$price.'. You can update your bid with UPDATE#'.$idRequest.'#price');
			// Nulinexmo::sendText($phone, '46769436047', 'Someone has made a ​bid to deliver '.$title.' from '.$street_from.', '.$street_from_city.' to '.$street_to.', '.$street_to_city.' at $'.$price.'. You can update your bid with UPDATE#'.$idRequest.'#price to 46769436047');

		}

	}
}