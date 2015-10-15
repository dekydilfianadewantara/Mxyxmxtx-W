@extends('backend.layouts.content')

@section('header-content')
<div class="pull-right">
    <a href="{{URL::to('admin/group/create')}}" class="btn btn-primary">Create</a>
</div>
@stop

@section('body-content')
@if(Session::has('groups'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('groups')}}.
    </div>
@endif

@if(Session::has('groups_alert'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Warning!</b> {{Session::get('groups_alert')}}.
    </div>
@endif
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th>Group Name</th>
                <th>Total User</th>
                <th>Second Register</th>
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
            @foreach($group as $row)
            <?php $total = Helper::GroupTotal($row->id); ?>
            <tr>
                <td>{{$nomor++}}</td>
                <td>{{$row->group_name}}</td>
                <td>{{$total}}</td>
                <td>{{($row->is_detail)?'<span class="label label-success">Active</span>':'<span class="label label-danger">Not Active</span>'}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->updated_at))}}</td>
                <td>
                    @if($row->for_register)
                        <a href="{{URL::to('admin/group/noactive/'.$row->id)}}" class="btn btn-warning btn-xs noactived"><i class="fa fa-fw fa-times-circle"></i> Hide</a>
                    @else
                        <a href="{{URL::to('admin/group/active/'.$row->id)}}" class="btn btn-success btn-xs actived"><i class="fa fa-fw  fa-check-circle"></i> Show</a>
                    @endif
                    <a href="{{URL::to('admin/group/edit/'.$row->id)}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/group/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                    <!-- <a href="">Hapus</a> -->
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$group->links()}}
    </div>
</div>
@stop