<?php

class Requests extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function getIndex()
	{
		View::share('title','Request');
		View::share('path','Index');
		$data['limit'] = $this->limit;
		$cari          = Input::get('search');
		if($this->admin['level'] == 1)
		{
			$qr	= RequestModel::orderBy('id','desc');
			if($cari){
				$qr = $qr->where('title','LIKE',"%$cari%");
			}
			$qr = $qr->paginate($this->limit);		
		}else{
			$qr	= RequestModel::where('id_user',$this->admin['id']);
			if($cari){
				$qr = $qr->where('title','LIKE',"%$cari%");
			}
			$qr = $qr->orderBy('id','desc')->paginate($this->limit);
		}
		$data['request'] = $qr;
		return View::make('backend.request.index',$data);
	}
	public function getAvailableAgent($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Find Work');
		View::share('path','Detail');
		if($this->admin['level'] == 1){
			$data['detail'] = RequestModel::find($id);
			$data['item']   = RequestItemModel::where('id_request',$id)->orderBy('id','desc')->get();
			$data['agent']  = DB::table('user_detail')
							->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
							->where('vehicles_size.size','>',Helper::GetWeight($id))->get();
			$lat1 = $data['detail']->latitude_from;
			$lng1 = $data['detail']->longitude_from;
			$agent = array();
			foreach ($data['agent'] as $key => $value) {
				$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
				if($jarak <= 10){
					$agent[] = array('lat'=>$value->latitude,'lng'=>$value->longitude,'user'=>Helper::GetName($value->id_user));
				}
			}
			$data['arround'] = $agent;
		}else{
			$data['detail'] = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
			$data['item']   = RequestItemModel::where('id_request',$id)->orderBy('id','desc')->get();
			$data['agent']  = DB::table('user_detail')
							->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
							->where('vehicles_size.size','>',Helper::GetWeight($id))->get();
			$lat1 = $data['detail']->latitude_from;
			$lng1 = $data['detail']->longitude_from;
			$agent = array();
			foreach ($data['agent'] as $key => $value) {
				$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
				if($jarak <= 10){
					$agent[] = array('lat'=>$value->latitude,'lng'=>$value->longitude,'user'=>Helper::GetName($value->id_user));
				}
			}
			// foreach ($agent as $key => $value) {
			// 	echo $value['lat'].'<br>';
			// }
			// print_r($agent);
			$data['arround'] = $agent;
			// echo count($agent);
			// if(!empty($data['arround'])){
			// 	echo "ada";
			// }else{
			// 	echo "t";
			// }
			// echo "<pre>";
			// print_r($agent);
			// echo "</pre>";
			// return '';
		}
		return View::make('backend.request.nearby',$data);
	}

	public function getCreate()
	{
		View::share('title','Request');
		View::share('path','Index');
		return View::make('backend.request.create');
	}

	public function postCreate()
	{
		$rules = array(
			'title'          => 'required',
			'open_date'      => 'required',
			'close_date'     => 'required',
			'longitude_from' => 'required',
			'latitude_from'  => 'required',
			'longitude_to'   => 'required',
			'latitude_to'    => 'required',
			'standard_price'    => 'required|numeric'
			);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/request/create')->withErrors($valid)->withInput();
		}else{
			

			$lat_from = Input::get('latitude_from');
			$lng_from = Input::get('longitude_from');
			$lat_to   = Input::get('latitude_to');
			$lng_to   = Input::get('longitude_to');

			$direction_json = Helper::GetDistance($lat_from,$lng_from,$lat_to,$lng_to);
			
			$from     = Helper::GetAddress($lat_from,$lng_from);
			$to       = Helper::GetAddress($lat_to,$lng_to);
			$distance = (isset($direction_json['distance']['text'])?$direction_json['distance']['text']:'');
			$duration = (isset($direction_json['duration']['text'])?$direction_json['duration']['text']:'');
			$dateNow  = date('Y-m-d H:i:s');

			$price = Input::get('standard_price');
			$closeDate = Input::get('close_date');
			$openDate = Input::get('open_date');
			$RM = new RequestModel;
			$RM->id_user        = $this->admin['id'];
			$RM->title          = Input::get('title');
			$RM->open_price     = $price;
			$RM->open_date      = $openDate;
			$RM->close_date     = $closeDate;
			$RM->desc_from      = $from;
			$RM->longitude_from = $lng_from;
			$RM->latitude_from  = $lat_from;
			$RM->longitude_to   = $lng_to;
			$RM->latitude_to    = $lat_to;
			$RM->desc_to        = $to;
			$RM->distance       = $distance;
			$RM->duration       = $duration;
			// $RM->repeat_from    = $closeDate;
			$RM->repeat_times   = 1;
			$RM->save();

			$name = Input::get('name');
			$weight = Input::get('weight');
			$description = Input::get('description');
			if(!empty($name)){
				foreach ($name as $key => $value) {
					$RIM = new RequestItemModel;
					$RIM->id_request  = $RM->id;
					$RIM->name        = $name[$key];
					$RIM->weight      = ($weight[$key]?$weight[$key]:'0');
					$RIM->description = $description[$key];
					if(is_uploaded_file($_FILES['picture']['tmp_name'][$key]))
					{
						$gambar 	 = pathinfo(basename($_FILES["picture"]["name"][$key]),PATHINFO_EXTENSION);
						// $gambar      =	Input::file('picture[$key]');
						$nama_gambar =	Str::random(10).'.'.$gambar;
						$nm 	     =	RequestItemModel::where('image',$nama_gambar)->count();
						while ($nm > 0) {
							$nama_gambar =	Str::random(10).'.'.$gambar;
							$nm          =	RequestItemModel::where('image',$nama_gambar)->count();
						}
						$size        = $_FILES['picture']['size'][$key];
						$tmp_name    = $_FILES['picture']['tmp_name'][$key];
						$type        = $_FILES['picture']['type'][$key];
						ImageManipulation::uploadimage($nama_gambar,$size,$tmp_name,$type,'request');

						$RIM->image	=	$nama_gambar;
					}
					$RIM->save();

					// if(is_uploaded_file($_FILES['picture']['tmp_name'][$key])){
						// $imgData =addslashes(file_get_contents($_FILES['picture']['tmp_name'][$key]));
						// DB::insert("insert into request_item (id_request, name, description, weight, image, created_at, updated_at) values ('$RM->id', '$name[$key]', '$description[$key]', '$weight[$key]', '$imgData', '$dateNow', '$dateNow')");
					// }else{
						// DB::insert('insert into request_item (id_request, name, description, weight, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', array($RM->id, $name[$key], $description[$key], $weight[$key], $dateNow, $dateNow));
					// }
					
					$Berat[] = ($weight[$key]?$weight[$key]:'0');
					// $RIM = new RequestItemModel;
					// $RIM->id_request  = $RM->id;
					// $RIM->name        = $name[$key];
					// $RIM->weight      = $weight[$key];
					// $RIM->description = $description[$key];
					// $RIM->save();
				}

			}
			//Send SMS
			$DataAgent  = DB::table('user_detail')
							->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
							->where('vehicles_size.size','>',array_sum($Berat))->get();
			$lat1  = $lat_from;
			$lng1  = $lng_from;
			$agent = array();
			foreach ($DataAgent as $key => $value) {
				$jarak = Helper::Jarak($lat1,$lng1,$value->latitude,$value->longitude);
				if($jarak <= 10){
					Nexmo::SendSMS($value->id_user,$from,$to,$price,$RM->id,$RM->title,array_sum($Berat),$distance,$closeDate);
				}
			}
			// $data['arround'] = $agent;
			//Send SMS
			return Redirect::to('admin/request')->with('request','Data has been added');
		}
	}

	public function getEdit($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Request');
		View::share('path','Edit');
		if($this->admin['level'] == 1){
			$RM              = RequestModel::find($id);
			$RIM             = RequestItemModel::where('id_request',$id)->get();
		}else{
			$RM              = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
			$RIM             = RequestItemModel::where('id_request',$id)->get();
		}
		$data['request'] = $RM;
		$data['item']    = $RIM;
		return View::make('backend.request.edit',$data);
	}

	public function postUpdate($idR)
	{
		$id = Helper::Decrypt($idR);
		$rules = array(
			'title'          => 'required',
			'open_date'      => 'required',
			'close_date'     => 'required',
			'longitude_from' => 'required',
			'latitude_from'  => 'required',
			'longitude_to'   => 'required',
			'latitude_to'    => 'required',
			'standard_price'     => 'required|numeric'
		);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/request/edit/'.$idR)->withErrors($valid)->withInput();
		}else{
			$lat_from = Input::get('latitude_from');
			$lng_from = Input::get('longitude_from');
			$lat_to   = Input::get('latitude_to');
			$lng_to   = Input::get('longitude_to');

			$from    = Helper::GetAddress($lat_from,$lng_from);
			$to      = Helper::GetAddress($lat_to,$lng_to);

			$RM                 = RequestModel::find($id);
			
			if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
			{
				$dateNow = date('Y-m-d H:i:s');
				$closeDate = Input::get('close_date');
				$RM->title          = Input::get('title');
				$RM->open_price     = Input::get('standard_price');
				$RM->open_date      = Input::get('open_date');
				$RM->close_date     = $closeDate;
				$RM->desc_from      = $from;
				$RM->longitude_from = $lng_from;
				$RM->latitude_from  = $lat_from;
				$RM->desc_to        = $to;
				$RM->longitude_to   = $lng_to;
				$RM->latitude_to    = $lat_to;
				// $RM->repeat_from    = $closeDate;
				// $RM->picture        = ($picture?$picture:'');
				$RM->save();

				$NewItem    = Input::get('new_item');
				
				$IdItem     = Input::get('id_item');
				$ItemName   = Input::get('name');
				$ItemWeight = Input::get('weight');
				// $ItemPict   = $_FILES['picture']['tmp_name'];
				$ItemDesc   = Input::get('description');

				$ItemName1   = Input::get('name1');
				$ItemWeight1 = Input::get('weight1');
				// $ItemPict1 = $_FILES['picture']['tmp_name'];
				// $ItemPict1   = Input::get('picture1');
				$ItemDesc1   = Input::get('description1');
				if(!empty($IdItem)){
					foreach ($IdItem as $key => $value) {

						// if(is_uploaded_file($_FILES['picture']['tmp_name'][$key])){
						// 	$imgData =addslashes(file_get_contents($_FILES['picture']['tmp_name'][$key]));
						// 	DB::update("UPDATE request_item set name='$ItemName[$key]', description='$ItemDesc[$key]', weight='$ItemWeight[$key]', image = '$imgData', updated_at='$dateNow' where id=?",array($value));
						// }else{
						// 	DB::update("UPDATE request_item set name='$ItemName[$key]', description='$ItemDesc[$key]', weight='$ItemWeight[$key]', updated_at='$dateNow' where id=?",array($value));
						// }

						// mysql_query($sql);
						// DB::table('request_item')->where('id',$value)->update(array('image'=>$imgData));
						// $RIM = RequestItemModel::find($value);
						// $RIM->id_request  = $id;
						// $RIM->name        = $ItemName[$key];
						// $RIM->description = $ItemDesc[$key];
						// $RIM->weight      = $ItemWeight[$key];
						// // $RIM->imageType   = getimageSize($imageProperties)['mime'];
						// $RIM->image       = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
						// $RIM->save();

						$RIM = RequestItemModel::find($value);
						$RIM->id_request  = $RM->id;
						$RIM->name        = $ItemName[$key];
						$RIM->weight      = $ItemWeight[$key];
						$RIM->description = $ItemDesc[$key];
						if(is_uploaded_file($_FILES['picture']['tmp_name'][$key]))
						{
							$path       = public_path('assets/store/request/'.$RIM->image);
							// $path_thumb = public_path('assets/news/thumb_'.$cm->picture);
							if( is_file($path) ){
								unlink('assets/store/request/'.$RIM->image);
								// unlink('assets/news/thumb_'.$cm->picture);
							}

							// $gambar      =	Input::file('picture[$key]');
							$gambar 	 = pathinfo(basename($_FILES["picture"]["name"][$key]),PATHINFO_EXTENSION);
							$nama_gambar =	Str::random(10).'.'.$gambar;
							$nm 	     =	RequestItemModel::where('image',$nama_gambar)->count();
							while ($nm > 0) {
								$nama_gambar =	Str::random(10).'.'.$gambar;
								$nm          =	RequestItemModel::where('image',$nama_gambar)->count();
							}
							$size        = $_FILES['picture']['size'][$key];
							$tmp_name    = $_FILES['picture']['tmp_name'][$key];
							$type        = $_FILES['picture']['type'][$key];
							ImageManipulation::uploadimage($nama_gambar,$size,$tmp_name,$type,'request');

							$RIM->image	=	$nama_gambar;
						}
						$RIM->save();
					}
				}
				if(!empty($NewItem)){
					foreach ($NewItem as $key1 => $value1) {
						$RIM = new RequestItemModel;
						$RIM->id_request  = $RM->id;
						$RIM->name        = $ItemName1[$key1];
						$RIM->weight      = $ItemWeight1[$key1];
						$RIM->description = $ItemDesc1[$key1];
						if(is_uploaded_file($_FILES['picture1']['tmp_name'][$key1]))
						{
							$gambar 	 = pathinfo(basename($_FILES["picture1"]["name"][$key1]),PATHINFO_EXTENSION);
							// $gambar      =	Input::file('picture1[$key1]');
							$nama_gambar =	Str::random(10).'.'.$gambar;
							$nm 	     =	RequestItemModel::where('image',$nama_gambar)->count();
							while ($nm > 0) {
								$nama_gambar =	Str::random(10).'.'.$gambar;
								$nm          =	RequestItemModel::where('image',$nama_gambar)->count();
							}
							$size        = $_FILES['picture1']['size'][$key1];
							$tmp_name    = $_FILES['picture1']['tmp_name'][$key1];
							$type        = $_FILES['picture1']['type'][$key1];
							ImageManipulation::uploadimage($nama_gambar,$size,$tmp_name,$type,'request');

							$RIM->image	=	$nama_gambar;
						}
						$RIM->save();

						// if(is_uploaded_file($_FILES['picture1']['tmp_name'][$key1])){
						// 	$imgData1 =addslashes(file_get_contents($_FILES['picture1']['tmp_name'][$key1]));
						// 	DB::insert("insert into request_item (id_request, name, description, weight, image, created_at, updated_at) values ('$id', '$ItemName1[$key1]', '$ItemDesc1[$key1]', '$ItemWeight1[$key1]', '$imgData1', '$dateNow', '$dateNow')");
						// }else{
						// 	DB::insert('insert into request_item (id_request, name, description, weight, created_at, updated_at) values (?, ?, ?, ?. ?, ?)', array($id, $ItemName1[$key1], $ItemDesc1[$key1], $ItemWeight1[$key1], $dateNow, $dateNow));
						// }
						// $RIM1 = new RequestItemModel;
						// $RIM1->id_request  = $id;
						// $RIM1->name        = $ItemName1[$key1];
						// $RIM1->description = $ItemDesc1[$key1];
						// $RIM1->weight      = $ItemWeight1[$key1];
						// $RIM1->image       = addslashes(file_get_contents($_FILES['picture']['tmp_name']));
						// $RIM1->save();
					} 
				}
				return Redirect::to('admin/request/edit/'.$idR)->with('request','Data has been updated');
			}
		}
	}
	public function getImage()
	{
		$id = Input::get('id');
		$RIM1 = RequestItemModel::find($id);
		// header("Content-type: " . $RIM1->imageType);
		echo $RIM1->image;

	}
	public function getDetailRequest($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Request');
		View::share('path','Detail');
		if($this->admin['level'] == 1){
			$RM              = RequestModel::find($id);
			$RIM             = RequestItemModel::where('id_request',$id)->get();
		}else{
			$RM              = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
			$RIM             = RequestItemModel::where('id_request',$id)->get();
		}
		$data['request'] = $RM;
		$data['item']    = $RIM;
		return View::make('backend.request.detail',$data);
	}
	public function getDeleteItem($idRequestE,$idItemE)
	{
		$idRequest = Helper::Decrypt($idRequestE);
		$idItem    = Helper::Decrypt($idItemE);
		$RM = RequestModel::find($idRequest);
		if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
		{
			if($RM->status==0){
				$RIM = RequestItemModel::find($idItem);
				$path       = public_path('assets/store/request/'.$RIM->image);
				if( is_file($path) ){
					unlink('assets/store/request/'.$RIM->image);
				}
				RequestItemModel::where('id_request',$idRequest)->where('id',$idItem)->delete();
				return Redirect::to('admin/request/edit/'.$idRequestE)->with('can_delete','The item has been deleted');
			}else{
				return Redirect::to('admin/request/edit/'.$idRequestE)->with('cannot_delete','The item can not be deleted');
			}
		}
	}

	public function getDeleteRequest($idRequestE)
	{
		$idRequest = Helper::Decrypt($idRequestE);
		$RM = RequestModel::find($idRequest);
		if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
		{
			if($RM->status==0){
				$RIM = RequestItemModel::where('id_request',$idRequest)->get();
				foreach ($RIM as $key => $value) {
					$path       = public_path('assets/store/request/'.$value->image);
					if( is_file($path) ){
						unlink('assets/store/request/'.$value->image);
					}
				}
				RequestItemModel::where('id_request',$idRequest)->delete();
				$RM->delete();
				return Redirect::to('admin/request')->with('request','Data has been deleted');
			}else{
				return Redirect::to('admin/request')->with('request_cant_delete','Data can not be deleted');
			}
		}
	}

	public function getDetailBid($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Request');
		View::share('path','Detail');
		$data['limit'] = $this->limit;
		if($this->admin['level'] == 1){
			$RM = RequestModel::find($id);
			$BM = BidModel::where('id_request',$id)->paginate($this->limit);		
		}else{
			$RM = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
			$BM = BidModel::where('id_request',$RM->id)->orderBy('price','asc')->paginate($this->limit);
		}
		$data['request'] = $RM;
		$data['bid']     = $BM;
		return View::make('backend.request.bid_user',$data);
	}

	public function getAcceptBid($idRequestE,$idBidE)
	{
		$idRequest = Helper::Decrypt($idRequestE);
		$idBid     = Helper::Decrypt($idBidE);
		if($this->admin['level'] == 1){
			// $RM = RequestModel::find($id);
			$BM = BidModel::find($idBid);
			$BM->status = 1;
			$BM->notif = 0;
			$BM->save();
			return Redirect::to('admin/request/detail-bid/'.$idRequestE)->with('request','Proposal has been accepted');	
		}else{
			$RM = RequestModel::where('id_user',$this->admin['id'])->where('id',$idRequest)->first();
			$BM = DB::table('bid')->where('id_request',$RM->id)->where('id',$idBid)->update(array('status'=>1,'notif'=>0));
			return Redirect::to('admin/request/detail-bid/'.$idRequestE)->with('request','Proposal has been accepted');	
		}
	}

	public function getWinner()
	{
		// $Sent = MessageSentModel::orderBy('id','asc')->get();
		// foreach ($Sent as $s => $r) {
		// 	$idSent[] = $r->id_request;
		// }
		// if(!empty($idSent)){
			$RM = RequestModel::where('expire',0)
				->where('close_date','<',date('Y-m-d H:i').':00')
				->where('status','0')
				->get();
				foreach ($RM as $a => $b) {
					// if(!Helper::StopSend($b->id)){
						$ID_REQUEST[] = $b->id;
					// }
				}
		// }else{
		// 	$RM = RequestModel::where('close_date','<=',date('Y-m-d H:i').':00')
		// 		->where('status','0')
		// 		->get();
		// 		foreach ($RM as $key => $value) {
		// 			$ID_REQUEST[] = $value->id;
		// 		}
		// }
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
								// ->where('finish_time','<=',$now)
						if(!empty($Bid)){
							if(($Bid->finish_time < $now) && ($Bid->reply == 0) && $Bid->ignore == 0){
							// if(($Bid->finish_time <= $now) && ($Bid->reply == 0) && $Bid->ignore == 0){
							// return 'ada';
								DB::table('bid')->where('id',$Bid->id)->update(array('ignore' => 1));
								$Bid = BidModel::orderBy('price','asc')
										->orderBy('created_at','asc')
										->where('id_request',$rec)
										->where('status',0)
										->where('sent',0)
										->where('reply',0)
										->first();
								if(!empty($Bid)){
									// Nexmo::SendWinner($Bid->id_user,$rec);
									Helper::MessageSent($Bid->id);
								}
							}
							// else{
							// 	if(($Bid->finish_time <= $now) && ($Bid->reply == 0)){
							// 		DB::table('bid')->where('id',$Bid->id)->update(array('ignore' => 1));
							// 	}
							// }
							// return 'd';
						}else{
							$Bid = BidModel::orderBy('price','asc')
									->orderBy('created_at','asc')
									->where('id_request',$rec)
									->where('status',0)
									->where('sent',0)
									->where('reply',0)
									->first();
							if(!empty($Bid)){
								// Nexmo::SendWinner($Bid->id_user,$rec);
								Helper::MessageSent($Bid->id);			
							}
						}
				// $MUM = MessageUserModel::where('id_request',$rec)->get();
				// if(!empty($MUM)){
				// 	foreach ($MUM as $k => $val) {
				// 		$Bid = BidModel::orderBy('price','asc')
				// 				->orderBy('created_at','asc')
				// 				->where('id','!=',$val->id_bid)
				// 				->where('status','0')
				// 				->first();
				// 		Nexmo::SendWinner($Bid->id_user,$rec);
				// 		Helper::MessageSent($Bid->id,$rec);
				// 	}
				// }else{
				// 		$Bid = BidModel::orderBy('price','asc')
				// 				->orderBy('created_at','asc')
				// 				->where('id_request',$rec)
				// 				->where('status','0')
				// 				->first();					
				// 		Nexmo::SendWinner($Bid->id_user,$rec);
				// 		Helper::MessageSent($Bid->id,$rec);
				// }
				Helper::ExpireRequest($rec);
			}
		}
		echo "<pre>";
		// print_r($ID_REQUEST);
		echo "</pre>";
		return '';
		
		// if(!empty($ID_REQUEST)){
		// 	BidModel::where('id_request')
		// }
	}

	public function getReply()
	{
		
	}

	public function getProcessRequest($idRequestE)
	{
		$idRequest = Helper::Decrypt($idRequestE);
		if($this->admin['level'] == 1){
			// $RM = RequestModel::find($id);
			$BM = RequestModel::find($idRequest);
			$BM->status = 1;
			$BM->save();
			return Redirect::to('admin/request')->with('request','Request has been processed');	
		}else{
			DB::table('request')
				->where('id_user',$this->admin['id'])
				->where('id',$idRequest)
				->update(array('status'=>1));
			return Redirect::to('admin/request')->with('request','Request has been processed');	
		}
	}

	public function getFinishRequest($idRequestE)
	{
		$idRequest = Helper::Decrypt($idRequestE);
		if($this->admin['level'] == 1){
			// $RM = RequestModel::find($id);
			$BM = RequestModel::find($idRequest);
			$BM->status = 2;
			$BM->save();
			return Redirect::to('admin/request')->with('request','Request has been finished');	
		}else{
			DB::table('request')
				->where('id_user',$this->admin['id'])
				->where('id',$idRequest)
				->update(array('status'=>2));
			return Redirect::to('admin/request')->with('request','Request has been finished');	
		}
	}

	// /**
	//  * Show the form for editing the specified resource.
	//  *
	//  * @param  int  $id
	//  * @return Response
	//  */
	// public function getDetail($id)
	// {
	// 	View::share('title','Orders');
	// 	View::share('path','Detail');

	// 	$om  = OrdersModel::find($id);
	// 	$data['orders'] = $om;
	// 	$data['client'] = ClientsModel::find($om->id_client);
	// 	$data['detail'] = OrdersDetailModel::orderBy('id','asc')->where('id_order',$id)->get();
	// 	$data['products'] = ProductsModel::all();
	// 	$data['payments'] = PaymentModel::where('id_order',$id)->get();
	// 	$data['totalPayment'] = Helper::TotalPayment($id);
	// 	return View::make('backend.orders.detail',$data);
	// }


}
