<?php
class Permission {
	public static function CheckGroupName($idGroup)
	{
		return UsersGroupModel::find($idGroup)->group_name;
	}
	
	public static function cekAccess($level,$uri2)
	{
		$cm = ControllersModel::where("url",$uri2)->first();
		if(count($cm) == 0){
			return false;
		}

		$am = AccessModel::where('id_group',$level)
			->where('id_controller',$cm->id)
			->first();
		if(count($am) > 0){
			return true;
		}else{
			return false;
		}
	}

	public static function cekController($uri2)
	{
		$cm = ControllersModel::where("url",$uri2)->first();
		if(count($cm) == 0){
			return "kosong";
		}
	}

	public static function isParent($id)
	{
		$cm = ControllersModel::find($id);
		if(empty($cm->id_parent) && empty($cm->url))
		{
			return true;
		}
	}

	public static function isMenu($id)
	{
		$cm = ControllersModel::find($id);
		if(empty($cm->id_parent) && !empty($cm->url))
		{
			return true;
		}
	}

	public static function checkOnAccess($group, $controller)
	{
		return AccessModel::where('id_group',$group)
				->where('id_controller',$controller)
				->first();
	}

	public static function cekchild($parent)
	{
		return $cm = ControllersModel::where('id_parent',$parent)->orderBy('id','asc')->get();
	}

	public static function urlchild($parent,$uri)
	{
		$cm = ControllersModel::where('id_parent',$parent)->where('url',$uri)->first();
		if(count($cm) > 0){
			return $cm->url;
		}
	}

	public static function accessgroup($group,$ctrl)
	{
		$am = AccessModel::where('id_group',$group)->where('id_controller',$ctrl)->count();
		if($am > 0){
			return true;
		}else{
			return false;
		}
	}

	public static function ShowMenu($group,$uri=null,$uri1=null)
	{
		$cm = ControllersModel::orderBy('id','asc')->get();
		foreach ($cm as $key => $row) {
			if(empty($row->id_parent) && empty($row->url))
			{

				if(!self::accessgroup($group,$row->id)):

				if($uri1 == 'admin' && $uri == self::urlchild($row->id,$uri))
				{
					echo "
					<li class='treeview active'>
			            <a href='#'>
			                <i class='".$row->icon."'></i>
			                <span>".$row->name."</span>
			                <i class='fa fa-angle-left pull-right'></i>
			            </a>
			            <ul class='treeview-menu'>
			                ";
			                foreach (self::cekchild($row->id) as $key => $value) {
								if(!self::accessgroup($group,$value->id)):

				                	if($uri == $value->url):
				                	echo "
					                <li class='active'>
					                    <a href='".URL::to('admin/'.$value->url)."'>
					                        <i class='fa fa-angle-double-right'></i> ".$value->name."</a>
					                </li>
				                	";
				                	else:
				                		echo "
						                <li>
						                    <a href='".URL::to('admin/'.$value->url)."'>
						                        <i class='fa fa-angle-double-right'></i> ".$value->name."</a>
						                </li>
					                	";
			                		endif;

			                	endif;
			                }
			            echo "</ul> 
			        </li>
					";
				}else{
					echo "
					<li class='treeview'>
			            <a href='#'>
			                <i class='".$row->icon."'></i>
			                <span>".$row->name."</span>
			                <i class='fa fa-angle-left pull-right'></i>
			            </a>
			            <ul class='treeview-menu'>
			                ";
			                foreach (self::cekchild($row->id) as $key => $value) {
								if(!self::accessgroup($group,$value->id)):
			                		echo "
					                <li>
					                    <a href='".URL::to('admin/'.$value->url)."'>
					                        <i class='fa fa-angle-double-right'></i> ".$value->name."</a>
					                </li>
				                	";
			                	endif;
			                }
			            echo "</ul> 
			        </li>
					";
				}
				endif;
			}elseif(empty($row->id_parent) && !empty($row->url)){
				if(!self::accessgroup($group,$row->id)):

					if($uri1 == 'admin' && $uri == $row->url){ 
						if($row->url == 'users'){// tambah baru
							echo "
					        <li class='active'>
					            <a href='".URL::to('admin',$row->url)."'>
					                <i class='".$row->icon."''></i> <span>".$row->name."</span>".Helper::UsersNotif()."
					            </a>
					        </li>
							";
						}else{ //tambah baru
							echo "
					        <li class='active'>
					            <a href='".URL::to('admin',$row->url)."'>
					                <i class='".$row->icon."''></i> <span>".$row->name."</span>
					            </a>
					        </li>
							";
						}
					}else{
						if($row->url == 'users'){// tambah baru
							echo "
					        <li>
					            <a href='".URL::to('admin',$row->url)."'>
					                <i class='".$row->icon."'></i> <span>".$row->name."</span>".Helper::UsersNotif()."
					            </a>
					        </li>
							";
						}else{
							echo "
					        <li>
					            <a href='".URL::to('admin',$row->url)."'>
					                <i class='".$row->icon."'></i> <span>".$row->name."</span>
					            </a>
					        </li>
							";
						}
					}

				endif;
			}
		}
	}
}