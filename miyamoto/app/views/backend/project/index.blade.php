@extends('backend.layouts.content')

@section('end-script')
    @parent
    <link href="{{asset('assets/css/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <!-- DATA TABES SCRIPT -->
    <script src="{{asset('assets/js/plugins/datatables/jquery.dataTables.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/plugins/datatables/dataTables.bootstrap.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/js/plugins/datatables/currency.js')}}" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">

        $(function() {
            $('#project').dataTable({
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": false,
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ 0,3,4 ] }
                ],
                "aaSorting": [[1,'asc']]
            });
        });
    </script>
@stop
@section('header-content')
<?php 
$status = Input::get('status');
$URI2 = Request::segment(2);
$admin = Session::get('admin');
?>
{{Form::open(array('url'=>'admin/find-work', 'method'=>'GET', 'class'=>'pull-right search-work'))}}
<?php $search = Input::get('search'); ?>
<div class="input-group">
    <input type="text" name="search" value="{{($search?$search:'')}}" class="form-control pull-right" style="width: 250px;" placeholder="Search work">
    <div class="input-group-btn">
        <button class="btn btn-default"><i class="fa fa-search"></i></button>
    </div>
</div>
{{Form::close()}}

<div class="pull-right">
    <ul class="nav nav-pills">
        <li {{($status=='new' || $URI2=='find-work' && $status == '')?'class="active"':''}}><a href="{{URL::to('admin/find-work?status=new')}}">New</a></li>
        <li {{($status=='current')?'class="active"':''}}><a href="{{URL::to('admin/find-work?status=current')}}">Current</a></li>
        <li {{($status=='bid')?'class="active"':''}}><a href="{{URL::to('admin/find-work?status=bid')}}">Bid</a></li>
        <li {{($status=='won')?'class="active"':''}}><a href="{{URL::to('admin/find-work?status=won')}}">Won</a></li>
        @if($admin['level']==1)
            <li {{($status=='no')?'class="active"':''}}><a href="{{URL::to('admin/find-work?status=no')}}">No Response</a></li>
        @endif
    </ul>
</div>
@stop

@section('body-content')
@if(Session::has('project'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('project')}}.
    </div>
@endif

@if(Session::has('request_cant_delete'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('request_cant_delete')}}.
    </div>
@endif
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover project-table" id="project">
            <thead>
            <tr>
                <th>Title</th>
                <th>Close Date</th>
                <th>Standar Price</th>
                <th>From</th>
                <th>To</th>
                {{-- <th>Status</th> --}}
            </tr>
            </thead>
            <tbody>
            @foreach($project as $row)
            <tr>
                <td><a href="{{URL::to('admin/find-work/detail/'.Helper::Encrypt($row->id))}}">{{$row->title}}</a></td>
                <td class="center-position">{{$row->close_date}}</td>
                <td>${{number_format($row->open_price,2)}}</td>
                <td>{{Helper::GetStreetCity($row->desc_from)}}</td>
                <td>{{Helper::GetStreetCity($row->desc_to)}}</td>
                {{-- <td class="center-position">
                    @if($row->status==1)
                        <span class="label label-info">Process</span>
                    @elseif($row->status==2)
                        <span class="label label-success">Done</span>
                    @else
                        <span class="label label-danger">New</span>
                    @endif
                </td> --}}
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$project->appends(array('status' => Input::get('status')))->links()}}
    </div>
</div>
@stop