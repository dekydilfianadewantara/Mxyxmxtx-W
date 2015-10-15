<?php 
$admin = Session::get('admin');
$CN = Helper::CheckNotif($admin['id']);
?>
{{-- @if($admin['id']!=1) --}}
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle notif" data-toggle="dropdown">
        <i class="fa fa-warning"></i>
        @if($CN)
            <span class="label label-warning notif-number">{{$CN}}</span>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li class="header notif-frase">You have {{$CN}} notifications</li>
        <li>
            <ul class="menu">
                {{Helper::GetNotif($admin['id'])}}
            </ul>
        </li>
        <li class="footer"><a href="#">View all</a></li>
    </ul>
</li>
{{-- @endif --}}