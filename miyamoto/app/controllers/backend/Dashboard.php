<?php

class Dashboard extends BaseController {
	// public $limit = 10;
	public $admin;

	public function __construct()
	{
		$this->admin = Session::get('admin');
	}

	public function index()
	{
		View::share('path','Index');
		View::share('title','Dashboard');
		$SessionYear       = Session::get('D_year') ? Session::get('D_year') : date('Y');
		$month             = ($SessionYear==date('Y'))?self::MonthName(date('n')):array('January','February','March','April','May','June','July','August','September','October','November','December');
		$data['month']     = '"'. implode('","', $month) .'"';
		$data['yearRange'] = range(2014,date('Y'));
		$data['year']      = ($SessionYear) ? $SessionYear : date('Y');
		$data['group']     = UsersGroupModel::where('id','!=',1)->orderBy('id','desc')->get();
		if(count($data['group'])>0){
			foreach ($data['group'] as $key => $value) {
				$data['chart'][$key] = ($SessionYear==date('Y'))?implode(",", self::ChartItemSalesOfNow($data['year'],$value->id)):implode(",", self::ChartItemSales($data['year'],$value->id));
			}
		}

		$data['request']   = RequestModel::where('id_user',$this->admin['id'])->count();
		$data['users']     = UserModel::count();
		$data['bid']       = 0;
		$data['noresponse']= RequestModel::where('status',0)->where('expire',1)->count();
		$rm = RequestModel::where('id_user',$this->admin['id'])->get();
		if(!empty($rm)){
			foreach ($rm as $key => $value) {
				$ir[] = $value->id;
			}
			if(!empty($ir)){
				$data['bid'] = BidModel::whereIn('id_request',$ir)->count();
			}
		}
		if($this->admin['level']==1){

			$Other = RequestModel::orderBy('id','desc')->get();
			if(!empty($Other)){
				foreach ($Other as $i => $row) {
					$RIM = RequestItemModel::where('id_request',$row->id)->get();
					foreach ($RIM as $key => $value) {
						$IDREQUEST[] = $value->id_request;
					}
				
					
				}
				if(!empty($IDREQUEST)){
					$data['notifbid'] = BidModel::whereIn('id_request',$IDREQUEST)->orderBy('created_at','desc')->take(20)->get();
				}
			}
		}else{

			$Other = RequestModel::orderBy('id','desc')->get();
			if(!empty($Other)){
				foreach ($Other as $i => $row) {
					$RIM = RequestItemModel::where('id_request',$row->id)->get();
					foreach ($RIM as $key => $value) {
						$Berat[] = $value->weight;
					}
					if(!empty($Berat)){
						// $this->admin['id'];
					$DataAgent  = DB::table('user_detail')
									->join('vehicles_size','user_detail.id_vehicles_size','=','vehicles_size.id')
									// ->where('vehicles_size.size','>',array_sum($Berat))
									->where('user_detail.id_user',$this->admin['id'])->first();
						if(!empty($DataAgent)){
							if($DataAgent->size > array_sum($Berat)){
								$lat1  = $row->latitude_from;
								$lng1  = $row->longitude_from;
								// foreach ($DataAgent as $j => $rec) {
									$jarak = Helper::Jarak($lat1,$lng1,$DataAgent->latitude,$DataAgent->longitude);
									if($jarak <= 10){
										$IDREQUEST[] = $row->id;
									}
								// }
							}
						}
					}
					
				}
				if(!empty($IDREQUEST)){
					$data['notifbid'] = BidModel::whereIn('id_request',$IDREQUEST)->where('id_user','!=',$this->admin['id'])->take(15)->get();
				}
			}
		}
		// $data['notifbid'] = 
		// $data['user']
		return View::make('backend.dashboard.index',$data);
	}
	public function postIndex()
	{
		$sales = Input::get('sales');
		if($sales){
			Session::put('D_year',$sales);
		}
		return Redirect::to('admin/home');
	}

	public function notification()
	{
		// return View::make('backend.dashboard.notification',$data);	
	}

	public function MonthName($month)
	{
		for ($i=1; $i <=$month ; $i++) { 
			switch ($i) {
				case 1:
					$Mth[] = 'January';
				break;
				
				case 2:
					$Mth[] = 'February';
				break;
				
				case 3:
					$Mth[] = 'March';
				break;
				
				case 4:
					$Mth[] = 'April';
				break;
				
				case 5:
					$Mth[] = 'May';
				break;
				
				case 6:
					$Mth[] = 'June';
				break;
				
				case 7:
					$Mth[] = 'July';
				break;
				
				case 8:
					$Mth[] = 'August';
				break;
				
				case 9:
					$Mth[] = 'September';
				break;
				
				case 10:
					$Mth[] = 'October';
				break;
				
				case 11:
					$Mth[] = 'November';
				break;
				
				case 12:
					$Mth[] = 'December';
				break;
			}
		}
		return $Mth;
	}

	public function logout()
	{
		// Session::forget('admin');
		Session::flush();
		return Redirect::to('login');
	}

	public function ChartItemSales($year,$level)
	{
		$vm = DB::table('users')
             ->select(DB::raw('month(created_at) as month, year(created_at) as year'))
             ->whereRaw('year(created_at) = '.$year)
             ->where('level',$level)
             ->get();
             	if(count($vm) > 0){
			        $mon['jan'] = 0;
			        $mon['feb'] = 0;
			        $mon['mar'] = 0;
			        $mon['apr'] = 0;
			        $mon['mei'] = 0;
			        $mon['jun'] = 0;
			        $mon['jul'] = 0;
			        $mon['agu'] = 0;
			        $mon['sep'] = 0;
			        $mon['oct'] = 0;
			        $mon['nov'] = 0;
			        $mon['dec'] = 0;

				    foreach ($vm as $row) {
				    	if($row->month == 1){
					        $mon['jan']++;
				    	}elseif($row->month == 2){
					        $mon['feb']++;
				    	}elseif($row->month == 3){
					        $mon['mar']++;
				    	}elseif($row->month == 4){
					        $mon['apr']++;
				    	}elseif($row->month == 5){
					        $mon['mei']++;
				    	}elseif($row->month == 6){
					        $mon['jun']++;
				    	}elseif($row->month == 7){
					        $mon['jul']++;
				    	}elseif($row->month == 8){
					        $mon['agu']++;
				    	}elseif($row->month == 9){
					        $mon['sep']++;
				    	}elseif($row->month == 10){
					        $mon['oct']++;
				    	}elseif($row->month == 11){
					        $mon['nov']++;
				    	}elseif($row->month == 12){
					        $mon['dec']++;
				    	}
				    }
				    return $mon;
				}else{
					$mon['jan'] = 0;
			        $mon['feb'] = 0;
			        $mon['mar'] = 0;
			        $mon['apr'] = 0;
			        $mon['mei'] = 0;
			        $mon['jun'] = 0;
			        $mon['jul'] = 0;
			        $mon['agu'] = 0;
			        $mon['sep'] = 0;
			        $mon['oct'] = 0;
			        $mon['nov'] = 0;
			        $mon['dec'] = 0;
			        return $mon;
				}
	}

	public function ChartItemSalesOfNow($year,$level)
	{
		$months = date('n');
		$vm = DB::table('users')
             ->select(DB::raw('month(created_at) as month, year(created_at) as year'))
             ->whereRaw('month(created_at) <= '.$months)
             ->whereRaw('year(created_at) = '.$year)
             ->where('level',$level)
             ->get();
         		for ($i=1; $i <= $months; $i++) { 
         			$mon[$i] = 0;
         		}
             	if(count($vm) > 0){
				    foreach ($vm as $key => $row) {
				    	
				    	if($row->month == 1){
					        $mon[1]++;
				    	}elseif($row->month == 2){
					        $mon[2]++;
				    	}elseif($row->month == 3){
					        $mon[3]++;
				    	}elseif($row->month == 4){
					        $mon[4]++;
				    	}elseif($row->month == 5){
					        $mon[5]++;
				    	}elseif($row->month == 6){
					        $mon[6]++;
				    	}elseif($row->month == 7){
					        $mon[7]++;
				    	}elseif($row->month == 8){
					        $mon[8]++;
				    	}elseif($row->month == 9){
					        $mon[9]++;
				    	}elseif($row->month == 10){
					        $mon[10]++;
				    	}elseif($row->month == 11){
					        $mon[11]++;
				    	}elseif($row->month == 12){
					        $mon[12]++;
				    	}
				    }
				}
			    return $mon;
	}

	
}