@extends('backend.layouts.content')

@section('body-content')

@if(Session::has('messages'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('messages')}}.
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
                <th>Message</th>
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
            @foreach($messages as $row)
            <tr>
                <td>{{$nomor++}}.</td>
                <td>{{$row->name}}</td>
                <td>{{$row->email}}</td>
                <td>{{$row->handphone}}</td>
                <td>{{Str::words($row->content,3, ' ...')}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->created_at))}}</td>
                <td>
                    <a href="#" data-idmessage="{{$row->id}}" class="btn btn-info btn-xs message-detail"><i class="fa fa-fw fa-search-plus"></i> View</a>
                    <a href="{{URL::to('admin/messages/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$messages->links()}}
    </div>
</div>
<div id="open-modal"></div>
@stop