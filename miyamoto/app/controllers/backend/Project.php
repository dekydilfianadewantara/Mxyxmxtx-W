<?php

class Project extends BaseController {

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
		View::share('title','Find Work');
		View::share('path','Index');
		$data['limit'] = $this->limit;
		$cari          = Input::get('search');
		$status        = Input::get('status');
		$dateNow  = date('Y-m-d H:i').':00';
		if($status=='current'){
			$qr = DB::table('request')
					->join('bid','request.id','=','bid.id_request')
					->where('bid.id_user',$this->admin['id'])
					->where('request.status',1)
					->select('request.id', 'request.title', 'request.close_date', 'request.open_price', 'request.desc_from', 'request.desc_to')->paginate($this->limit);
		}elseif ($status=='won') {
			$qr = DB::table('request')
					->join('bid','request.id','=','bid.id_request')
					->where('bid.id_user',$this->admin['id'])
					->where('request.status',2)
					->select('request.id', 'request.title', 'request.close_date', 'request.open_price', 'request.desc_from', 'request.desc_to')->paginate($this->limit);
		}elseif ($status=='bid') {
			$qr = DB::table('request')
					->join('bid','request.id','=','bid.id_request')
					->where('bid.id_user',$this->admin['id'])
					->where('request.status',0)
					->select('request.id', 'request.title', 'request.close_date', 'request.open_price', 'request.desc_from', 'request.desc_to')->paginate($this->limit);
		}elseif ($status=='no' && $this->admin['level'] == 1) {
			$qr = DB::table('request')->where('status',0)->where('expire',1)->paginate($this->limit);
		}
		
		if ($status=='new' || empty($status)) {
			$qr	= DB::table('request');
			if($cari){
				$qr = $qr->where('title','LIKE',"%$cari%");
			}
			$qr = $qr->orderBy('id','desc')->where('close_date','>',$dateNow)->where('status',0)->paginate($this->limit);
		}

		$data['project'] = $qr;
		return View::make('backend.project.index',$data);
	}

	public function getImage()
	{
		$id = Input::get('id');
		$RIM1 = RequestItemModel::find($id);
		// header("Content-type: " . $RIM1->imageType);
		echo $RIM1->image;

	}

	// public function getCreate()
	// {
	// 	View::share('title','Request');
	// 	View::share('path','Index');
	// 	return View::make('backend.request.create');
	// }

	// public function postCreate()
	// {
	// 	$rules = array(
	// 		'title'          => 'required',
	// 		'open_date'      => 'required',
	// 		'close_date'     => 'required',
	// 		'longitude_from' => 'required',
	// 		'latitude_from'  => 'required',
	// 		'longitude_to'   => 'required',
	// 		'latitude_to'    => 'required',
	// 		'open_price'    => 'required'
	// 		);
	// 	$valid = Validator::make(Input::all(),$rules);
	// 	if($valid->fails())
	// 	{
	// 		return Redirect::to('admin/request')->withErrors($valid)->withInput();
	// 	}else{
	// 		$RM = new RequestModel;
	// 		$RM->id_user        = $this->admin['id'];
	// 		$RM->title          = Input::get('title');
	// 		$RM->open_price     = Input::get('open_price');
	// 		$RM->open_date      = Input::get('open_date');
	// 		$RM->close_date     = Input::get('close_date');
	// 		$RM->longitude_from = Input::get('longitude_from');
	// 		$RM->latitude_from  = Input::get('latitude_from');
	// 		$RM->longitude_to   = Input::get('longitude_to');
	// 		$RM->latitude_to    = Input::get('latitude_to');
	// 		$RM->save();

	// 		$name = Input::get('name');
	// 		$weight = Input::get('weight');
	// 		// $picture = Input::get('picture');
	// 		$description = Input::get('description');
	// 		if(!empty($name)){
	// 			foreach ($name as $key => $value) {
	// 				$RIM = new RequestItemModel;
	// 				$RIM->id_request  = $RM->id;
	// 				$RIM->name        = $name[$key];
	// 				$RIM->weight      = $weight[$key];
	// 				// $RIM->image       = $picture[$key];
	// 				$RIM->description = $description[$key];
	// 				$RIM->save();
	// 			}
	// 		}
	// 		return Redirect::to('admin/request')->with('request','Data has been added');
	// 	}
	// }

	// public function getEdit($id)
	// {
	// 	View::share('title','Request');
	// 	View::share('path','Edit');
	// 	if($this->admin['level'] == 1){
	// 		$RM              = RequestModel::find($id);
	// 		$RIM             = RequestItemModel::where('id_request',$id)->get();
	// 	}else{
	// 		$RM              = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
	// 		$RIM             = RequestItemModel::where('id_request',$id)->get();
	// 	}
	// 	$data['request'] = $RM;
	// 	$data['item']    = $RIM;
	// 	return View::make('backend.request.edit',$data);
	// }

	// public function postUpdate($id)
	// {
	// 	$rules = array(
	// 		'title'          => 'required',
	// 		'open_date'      => 'required',
	// 		'close_date'     => 'required',
	// 		'longitude_from' => 'required',
	// 		'latitude_from'  => 'required',
	// 		'longitude_to'   => 'required',
	// 		'latitude_to'    => 'required',
	// 		'open_price'     => 'required'
	// 	);
	// 	$valid = Validator::make(Input::all(),$rules);
	// 	if($valid->fails())
	// 	{
	// 		return Redirect::to('admin/request/edit/'.$id)->withErrors($valid)->withInput();
	// 	}else{
	// 		// $picture            = Input::get('picture');
	// 		$RM                 = RequestModel::find($id);
			
	// 		if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
	// 		{
	// 			$RM->title          = Input::get('title');
	// 			$RM->open_price     = Input::get('open_price');
	// 			$RM->open_date      = Input::get('open_date');
	// 			$RM->close_date     = Input::get('close_date');
	// 			$RM->longitude_from = Input::get('longitude_from');
	// 			$RM->latitude_from  = Input::get('latitude_from');
	// 			$RM->longitude_to   = Input::get('longitude_to');
	// 			$RM->latitude_to    = Input::get('latitude_to');
	// 			// $RM->picture        = ($picture?$picture:'');
	// 			$RM->save();

	// 			$NewItem    = Input::get('new_item');
				
	// 			$IdItem     = Input::get('id_item');
	// 			$ItemName   = Input::get('name');
	// 			$ItemWeight = Input::get('weight');
	// 			// $ItemPict   = Input::get('picture');
	// 			$ItemDesc   = Input::get('description');

	// 			$ItemName1   = Input::get('name1');
	// 			$ItemWeight1 = Input::get('weight1');
	// 			// $ItemPict1   = Input::get('picture1');
	// 			$ItemDesc1   = Input::get('description1');
	// 			if(!empty($IdItem)){
	// 				foreach ($IdItem as $key => $value) {
	// 					$RIM = RequestItemModel::find($value);
	// 					$RIM->id_request  = $id;
	// 					$RIM->name        = $ItemName[$key];
	// 					$RIM->description = $ItemDesc[$key];
	// 					$RIM->weight      = $ItemWeight[$key];
	// 					// $RIM->image       = $ItemPict[$key];
	// 					$RIM->save();
	// 				}
	// 			}
	// 			if(!empty($NewItem)){
	// 				foreach ($NewItem as $key1 => $value1) {
	// 					$RIM1 = new RequestItemModel;
	// 					$RIM1->id_request  = $id;
	// 					$RIM1->name        = $ItemName1[$key1];
	// 					$RIM1->description = $ItemDesc1[$key1];
	// 					$RIM1->weight      = $ItemWeight1[$key1];
	// 					// $RIM1->image       = $ItemPict1[$key1];
	// 					$RIM1->save();
	// 				} 
	// 			}
	// 			return Redirect::to('admin/request/edit/'.$id)->with('request','Data has been updated');
	// 		}
	// 	}
	// }
	
	// public function getDetailRequest($id)
	// {
	// 	View::share('title','Request');
	// 	View::share('path','Detail');
	// 	if($this->admin['level'] == 1){
	// 		$RM              = RequestModel::find($id);
	// 		$RIM             = RequestItemModel::where('id_request',$id)->get();
	// 	}else{
	// 		$RM              = RequestModel::where('id_user',$this->admin['id'])->where('id',$id)->first();
	// 		$RIM             = RequestItemModel::where('id_request',$id)->get();
	// 	}
	// 	$data['request'] = $RM;
	// 	$data['item']    = $RIM;
	// 	return View::make('backend.request.detail',$data);
	// }
	// public function getDeleteItem($idRequest,$idItem)
	// {
	// 	$RM = RequestModel::find($idRequest);
	// 	if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
	// 	{
	// 		if($RM->status==0){
	// 			RequestItemModel::where('id_request',$idRequest)->where('id',$idItem)->delete();
	// 			return Redirect::to('admin/request/edit/'.$idRequest)->with('can_delete','The item has been deleted');
	// 		}else{
	// 			return Redirect::to('admin/request/edit/'.$idRequest)->with('cannot_delete','The item can not be deleted');
	// 		}
	// 	}
	// }

	// public function getDeleteRequest($idRequest)
	// {
	// 	$RM = RequestModel::find($idRequest);
	// 	if($this->admin['level'] == 1 || $RM->id_user == $this->admin['id'])
	// 	{
	// 		if($RM->status==0){
	// 			RequestItemModel::where('id_request',$idRequest)->delete();
	// 			$RM->delete();
	// 			return Redirect::to('admin/request')->with('request','Data has been deleted');
	// 		}else{
	// 			return Redirect::to('admin/request')->with('request_cant_delete','Data can not be deleted');
	// 		}
	// 	}
	// }
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDetail($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Find Work');
		View::share('path','Detail');
		$data['detail'] = RequestModel::find($id);
		$data['item']   = RequestItemModel::where('id_request',$id)->orderBy('id','desc')->get();
		$User = BidModel::where('id_request',$id)->where('status',1)->where('id_user',$this->admin['id'])->first();
		$data['idUser'] = ((count($User))?count($User):'0');
		return View::make('backend.project.detail',$data);
	}

	public function getBid($idR)
	{
		$id = Helper::Decrypt($idR);
		View::share('title','Find Work');
		View::share('path','Bid Proposal');
		$data['detail'] = RequestModel::find($id);
		$data['bid'] = BidModel::where('id_user',$this->admin['id'])->where('id_request',$id)->first();
		return View::make('backend.project.bid',$data);
	}

	public function postBid($idR)
	{
		$id = Helper::Decrypt($idR);
		$rules = array(
			'estimation'   => 'required',
			'price'        => 'required|numeric',
			'cover_letter' => 'required'
		);
		$valid = Validator::make(Input::all(),$rules);
		if($valid->fails())
		{
			return Redirect::to('admin/find-work/bid/'.$idR)->withErrors($valid)->withInput();
		}else{
			$Bid = BidModel::where('id_user',$this->admin['id'])->where('id_request',$id)->first();
			if($Bid){
				if($Bid->price != Input::get('price')){
					
					//Send SMS
					$idUser = $this->admin['id'];
					$PRICE  = Input::get('price');
					$rm     = RequestModel::find($id);
					$RIM    = RequestItemModel::where('id_request',$id)->get();
					foreach ($RIM as $key => $value) {
						$Berat[] = $value->weight;
					}
					if(!empty($Berat)){
					$DataAgent  = DB::table('user_detail')
									->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
									->where('vehicles_size.size','>',array_sum($Berat))
									->where('user_detail.id_user','!=',$idUser)->get();
					}else{
					$DataAgent  = DB::table('user_detail')
									->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
									->where('user_detail.id_user','!=',$idUser)
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
							Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$id);
						}
					}

				}
				$Bid->price        = Input::get('price');
				$Bid->time         = Input::get('estimation');
				$Bid->cover_letter = Input::get('cover_letter');
				$Bid->save();
				
				return Redirect::to('admin/find-work/bid/'.$idR)->with('project','You have just update the proposal');
			}else{
				$BM = new BidModel;
				$BM->id_user      = $this->admin['id'];
				$BM->id_request   = $id;
				$BM->price        = Input::get('price');
				$BM->time         = Input::get('estimation');
				$BM->cover_letter = Input::get('cover_letter');
				$BM->save();

				//Send SMS
				$idUser = $this->admin['id'];
				$PRICE = Input::get('price');
				$rm = RequestModel::find($id);
				$RIM = RequestItemModel::where('id_request',$id)->get();
				foreach ($RIM as $key => $value) {
					$Berat[] = $value->weight;
				}
				if(!empty($Berat)){
				$DataAgent  = DB::table('user_detail')
								->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
								->where('vehicles_size.size','>',array_sum($Berat))
								->where('user_detail.id_user','!=',$idUser)->get();
				}else{
				$DataAgent  = DB::table('user_detail')
								->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
								->where('user_detail.id_user','!=',$idUser)
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
						Nexmo::SendNotifToOther($value->id_user,$from,$to,$PRICE,$title,$id);
					}
				}
				return Redirect::to('admin/find-work/bid/'.$idR)->with('project','Thank you for bid this job');
			}
		}
	}
 

}
