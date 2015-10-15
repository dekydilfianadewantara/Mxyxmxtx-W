<?php
class GammuSms {
	// GammuOutBoxMultipartModel
	public static function sendText($phone, $text)
	{
		$jumlahText = strlen($text);
		$contentSms = 153;
		if($jumlahText > $contentSms){
			$jmlhPesan = ceil(strlen($text)/$contentSms);
			$pecah     = str_split($text,$contentSms);
			$Ainc = DB::connection('mysql2')->select("SHOW TABLE STATUS LIKE 'outbox'");
			$iD = $Ainc[0]->Auto_increment;
			// $iD = DB::select("SHOW TABLE STATUS LIKE 'outbox'");
			for ($i=1; $i <= $jmlhPesan; $i++) { 
				$udh = "050003A7".sprintf("%02s", $jmlhPesan).sprintf("%02s", $i);
				$pesan = $pecah[$i-1];
				if($i==1){
					$out                    = new GammuOutBoxModel;
					$out->DestinationNumber = $phone;
					$out->UDH               = $udh;
					$out->TextDecoded       = $pesan;
					$out->ID                = $iD;
					$out->MultiPart         = 'true';
					$out->CreatorID         = 'Gammu';
					$out->save();
					// $iD =& $out->ID;
				}else{
					$mutiOut = new GammuOutBoxMultipartModel;
					$mutiOut->UDH              = $udh;
					$mutiOut->TextDecoded      = $pesan;
					$mutiOut->ID               = $iD;
					$mutiOut->SequencePosition = $i;
					$mutiOut->save();
				}
			}
		}else{
			$out = new GammuOutBoxModel;
			$out->DestinationNumber = $phone;
			$out->TextDecoded       = $text;
			$out->CreatorID         = 'Gammu';
			$out->save();
		}
	}
}