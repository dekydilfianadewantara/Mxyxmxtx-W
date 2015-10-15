@extends('backend.layouts.content')

@section('header-content')
<div class="pull-right" style="margin-left:5px">
    <a href="{{URL::to('admin/request/create')}}" class="btn btn-primary">Create</a>
</div>
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
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover request-table">
            <thead>
            <tr>
                <th>Title</th>
                {{-- <th>Open Date</th> --}}
                <th>Close Date</th>
                {{-- <th>Open Price</th> --}}
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Bid</th>
                <th></th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody style="font-size: 12px;">
            @foreach($request as $row)
            <?php $BidTotal = Helper::TotalOfBid($row->id); setlocale(LC_MONETARY, "en_US"); ?>
            <tr>
                <td><a href="{{URL::to('admin/request/detail-request/'.Helper::Encrypt($row->id))}}">{{Str::words($row->title,4, ' ...')}}</a></td>
                {{-- <td>{{date('Y-m-d H:i',strtotime($row->open_date))}}</td> --}}
                <td>{{date('Y-m-d H:i',strtotime($row->close_date))}}</td>
                {{-- <td>$ {{number_format($row->open_price,2)}}</td> --}}
                <td>{{Helper::GetStreetCity($row->desc_from)}}</td>
                <td>{{Helper::GetStreetCity($row->desc_to)}}</td>
                <td>
                    @if($row->status==1)
                        <span class="label label-info">Process</span>
                    @elseif($row->status==2)
                        <span class="label label-success">Done</span>
                    @else
                        <span class="label label-danger">New</span>
                    @endif
                </td>
                <td>
                    @if($BidTotal)
                        <a href="{{URL::to('admin/request/detail-bid/'.Helper::Encrypt($row->id))}}">{{$BidTotal}}</a>
                    @else
                        {{$BidTotal}}
                    @endif
                </td>
                <td>
                    <a href="{{URL::to('admin/request/available-agent/'.Helper::Encrypt($row->id))}}" class="btn btn-warning btn-xs"><i class="fa fa-fw fa-users"></i> Agent</a>
                    @if($row->status==1)
                        <a href="{{URL::to('admin/request/finish-request/'.Helper::Encrypt($row->id))}}" class="btn btn-success btn-xs finish"><i class="fa fa-fw fa-check"></i> Finish</a>
                    @endif

                    @if($row->status==0)
                        <a href="{{URL::to('admin/request/process-request/'.Helper::Encrypt($row->id))}}" class="btn btn-success btn-xs process"><i class="fa fa-fw fa-check"></i> Process</a>
                    @endif

                    @if($row->status==2)
                        <a href="" class="btn btn-success btn-xs disabled"><i class="fa fa-fw fa-check"></i> Finish</a>
                    @endif
                </td>
                <td>
                    <a href="{{URL::to('admin/request/edit/'.Helper::Encrypt($row->id))}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/request/delete-request/'.Helper::Encrypt($row->id))}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$request->links()}}
    </div>
</div>
@stop