@extends('backend.layouts.content')

@section('header-content')
<div class="pull-right" style="margin-left:5px">
    <a href="{{URL::to('admin/clients/create')}}" class="btn btn-primary">Create</a>
</div>
{{Form::open(array('url'=>'admin/clients', 'method'=>'GET'))}}
<?php $search = Input::get('search'); ?>
<div class="input-group">
    <input type="text" name="search" value="{{($search?$search:'')}}" class="form-control pull-right" style="width: 150px;" placeholder="Search client name">
    <div class="input-group-btn">
        <button class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</div>
{{Form::close()}}
@stop

@section('body-content')
@if(Session::has('clients'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('clients')}}.
    </div>
@endif

@if(Session::has('clients_alert'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Warning!</b> {{Session::get('clients_alert')}}.
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
                <th>Order Total</th>
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
            @foreach($clients as $row)
            <?php $total = Helper::OrderTotal($row->id); ?>
            <tr>
                <td>{{$nomor++}}.</td>
                <td><a class="detail-client" data-idclient="{{$row->id}}">{{$row->name}}</a></td>
                <td>{{$row->email}}</td>
                <td>{{$row->phone}}</td>
                <td>{{$total}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->updated_at))}}</td>
                <td>
                    <a href="{{URL::to('admin/clients/edit/'.$row->id)}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/clients/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$clients->links()}}
    </div>
</div>
<div id="open-modal"></div>
@stop