<!-- sidebar menu: : style can be found in sidebar.less -->
<?php
    $uri3  = Request::segment(3);
    $uri   = Request::segment(2);
    $uri1  = Request::segment(1);
    $admin = Session::get('admin');
?>
                    <ul class="sidebar-menu">
                        <li @if($uri1=='admin' && $uri=='home') class="active" @endif>
                            <a href="{{URL::to('admin')}}">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        @if($admin['level']==1)
                        
                        <li @if($uri=='find-work') class="active" @endif>
                            <a href="{{URL::to('admin/find-work')}}">
                                <i class="fa fa-bullhorn"></i> <span>Find Work</span>
                            </a>
                        </li> 
                        <li @if($uri=='request') class="active" @endif>
                            <a href="{{URL::to('admin/request')}}">
                                <i class="fa fa-shopping-cart"></i> <span>Request</span>
                            </a>
                        </li>
                        <li @if($uri=='users') class="active" @endif>
                            <a href="{{URL::to('admin/users')}}">
                                <i class="fa fa-users"></i> <span>Users</span>{{Helper::UsersNotif()}} 
                            </a>
                        </li>
                        <li class="treeview @if($uri=='group' || $uri=='permission') active @endif">
                            <a href="#">
                                <i class="fa fa-list-ul"></i>
                                <span>Group Permission</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li @if($uri=='group') class="active" @endif>
                                    <a href="{{URL::to('admin/group')}}">
                                        <i class="fa fa-angle-double-right"></i> Group
                                    </a>
                                </li>
                                <li @if($uri=='permission') class="active" @endif>
                                    <a href="{{URL::to('admin/permission')}}">
                                        <i class="fa fa-angle-double-right"></i> Permission
                                    </a>
                                </li>
                            </ul> 
                        </li>
                       {{--  <li @if($uri=='about') class="active" @endif>
                            <a href="{{URL::to('admin/about')}}">
                                <i class="fa fa-info-circle"></i> <span>About</span>
                            </a>
                        </li> --}}
                        @else
                        {{Permission::ShowMenu($admin['level'],$uri,$uri1)}}
                        @endif
                    </ul>