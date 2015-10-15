@extends('backend.layouts.content')

@section('end-script')
    @parent

    <script type="text/javascript" src="{{asset('assets/js/AdminLTE/jquery.blockUI.js')}}"></script>
    <script type="text/javascript">
    $(document).ready(function() { 

        $(document).on('click','.detail-user',function(){
            waiting();
        });
        function waiting(){
            $.blockUI({ css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff' 
            },baseZ:9999 });
        }
    });
    </script>
@stop

@section('header-content')
<div class="pull-right" style="margin-left:5px">
    <a href="{{URL::to('admin/users/create')}}" class="btn btn-primary">Create</a>
</div>
{{Form::open(array('url'=>'admin/users', 'method'=>'GET'))}}
<?php $search = Input::get('search'); ?>
<div class="input-group">
    <input type="text" name="search" value="{{($search?$search:'')}}" class="form-control pull-right" style="width: 150px;" placeholder="Search user name">
    <div class="input-group-btn">
        <button class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</div>
{{Form::close()}}
@stop

@section('body-content')
@if(Session::has('users'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('users')}}.
    </div>
@endif

@if(Session::has('users_alert'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('users_alert')}}.
    </div>
@endif
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Group</th>
                <th>Modified at</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php 
            if(Input::get('page')){
                $page = Input::get('page');
            }else{
                $page = 1;
            }
            $nomor = $page + ($page-1) * ($limit-1);
            ?>
            <tbody>
            @foreach($users as $row)
            <tr>
                <td>{{$nomor++}}.</td>
                <td><a class="detail-user" data-iduser="{{$row->id}}">{{$row->name}}</a></td>
                <td>{{$row->email}}</td>
                <td>{{$row->phone}}</td>
                <td>{{($row->is_active)?'<span class="label label-success">Active</span>':'<span class="label label-danger">Not Active</span>'}}</td>
                <td>{{$row->group->group_name}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->updated_at))}}</td>
                <td>
                    <a href="{{URL::to('admin/users/edit/'.$row->id)}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/users/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$users->links()}}
    </div>
</div>
<div id="open-modal"></div>
@stop