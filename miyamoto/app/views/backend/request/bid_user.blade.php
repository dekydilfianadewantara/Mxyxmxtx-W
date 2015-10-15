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
            $('#bid_user').dataTable({
                "bPaginate": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": false,
                "bAutoWidth": false,
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ 0,3,4,5 ] }
                ]
            });
        });
    </script>
@stop

@section('header-content')
{{-- <div class="pull-right" style="margin-left:5px">
    <a href="{{URL::to('admin/request/create')}}" class="btn btn-primary">Create</a>
</div> --}}
@stop

@section('body-content')
@if(Session::has('request'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('request')}}.
    </div>
@endif

@if(Session::has('request_cant_delete'))
    <div class="alert alert-warning alert-dismissable">
        <i class="fa fa-warning"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{Session::get('request_cant_delete')}}.
    </div>
@endif
<h2 class="page-header">
    <i class="fa fa-info-circle"></i> {{$request->title}}
</h2>
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover request-table" id="bid_user">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Estimated Duration</th>
                <th>Status</th>
                <th>Cover Letter</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bid as $row)
            <tr>
                <td><a data-id="{{$row->id}}">{{$row->about->name}}</a></td>
                <td>${{number_format($row->price,2)}}</td>
                <td>{{($row->time)?date('Y-m-d H:i',strtotime($row->time)):'-'}}</td>
                <td>
                    @if($row->status)
                        <span class="label label-success">Accepted</span>
                    @else
                        <span class="label label-warning">Waiting</span>
                    @endif
                </td>
                <td>{{$row->cover_letter}}</td>
                <td>
                    @if($row->status)
                        <a class="btn btn-success btn-xs disabled"><i class="fa fa-fw fa-check-circle"></i> Accepted</a>
                    @else
                        <a href="{{URL::to('admin/request/accept-bid/'.Helper::Encrypt($request->id).'/'.Helper::Encrypt($row->id))}}" class="btn btn-info btn-xs accept-bid"><i class="fa fa-fw fa-check"></i> Accept</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$bid->links()}}
    </div>
</div>
    <div class="box-footer clearfix">
        <!-- general form elements -->
        <div class="box no-border" style="padding:10px">
            <a href="{{URL::to('admin/request')}}" class="btn btn-default">{{trans('button.bc')}}</a>
        </div>
    </div>
@stop