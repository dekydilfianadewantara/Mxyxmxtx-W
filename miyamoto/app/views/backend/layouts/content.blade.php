@extends('backend.layouts.base')

@section('head-script')
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('header')

<header class="header">
            <a href="{{URL::to('home')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                Imhanya
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        {{-- @include('backend.layouts.part.message') --}}
                        <!-- Notifications: style can be found in dropdown.less -->
                        
                        @include('backend.layouts.part.notif')
                        <!-- Tasks: style can be found in dropdown.less -->
                        
                        {{-- @include('backend.layouts.part.task') --}}
                        <!-- User Account: style can be found in dropdown.less -->
                        
                        @include('backend.layouts.part.user')
                    </ul>
                </div>
            </nav>
        </header>
@stop

@section('body')
<?php 
    $uri2  = Request::segment(2); 
    $uri3  = Request::segment(3); 
?>
<div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    @include('backend.layouts.part.search')
                
                    <!-- /.search form -->
                    
                    @include('backend.layouts.part.menu')
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1 class="pull-left">
                        {{$title}}
                        <small>{{$path}}</small>
                    </h1>
                    @yield('header-content')
                    
                    {{-- <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">{{$title}}</li>
                    </ol> --}}
                    <div class="clearfix"></div>
                </section>

                <!-- Main content -->
                <section class="content @if($uri2=='find-work' && $uri3=='detail' || $uri2=='request' && $uri3=='available-agent') invoice @endif">
                    @yield('body-content-root')
                    <div class="row">
                        @yield('body-content-child')
                        <div class="col-xs-12">
                            @yield('body-content')
                        </div>
                    </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

@stop

@section('end-script')

    <!-- jQuery 2.0.2 -->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.10.3 -->
    <script src="{{ asset('assets/js/jquery-ui-1.10.3.min.js')}}" type="text/javascript"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/js/AdminLTE/app.js')}}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/AdminLTE/custom.js')}}" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes 
    <script src="{{ asset('assets/js/AdminLTE/demo.js')}}" type="text/javascript"></script>-->
    <script>
        $(function () {
          $('.newusers').tooltip('show')
        })
        setTimeout(function () {
            $('.newusers').tooltip('hide'); //close the tooltip
        }, 1000);
    </script>
@stop