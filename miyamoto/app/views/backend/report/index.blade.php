@extends('backend.layouts.content')

@section('header-content')


{{Form::open(array('url'=>'admin/report/filter', 'method'=>'GET'))}}
<div class="pull-right" style="margin-left:5px">
    <button type="submit" class="btn btn-warning">Clear</button>
</div>
{{Form::close()}}

{{Form::open(array('url'=>'admin/report/filter', 'method'=>'GET'))}}
<div class="pull-right" style="margin-left:5px">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
<div class="input-group">
    <input type="text" name="R_date_filter" class="form-control pull-right date-filter" value="{{Session::get('R_date_filter')}}" style="width: 200px;"/>
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
</div>
{{Form::close()}}
@stop

@section('body-content')
@if(Session::has('report'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('report')}}.
    </div>
@endif
<div class="box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover images">
            <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Order ID</th>
                <th>Client Name</th>
                <th>Total</th>
                <th>Payment Status</th>
                <th>Order Status</th>
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
            @foreach($report as $row)
            <tr>
                <td>{{$nomor++}}</td>
                <td>{{date('d F Y',strtotime($row->created_at))}}</td>
                <td><a href="{{URL::to('admin/report/detail/'.$row->id)}}">{{$row->code_order}}</a></td>
                <td><a href="{{URL::to('admin/clients/detail/'.$row->id_client)}}">{{$row->client->name}}</a></td>
                <td>Rp {{number_format($row->total,0,",",".")}}</td>
                <td>
                    @if($row->status)
                        <span class="label label-success">Paid</span>
                    @else
                        <span class="label label-warning">Not Paid</span>
                    @endif
                </td>
                <td>{{Helper::CheckOrder($row->id)}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
    <div class="box-footer clearfix">
        {{$report->links()}}
        <div class="pull-right" style="margin-left:5px">
    <h3>Grand Total : Rp {{number_format($grandTotal,0,',','.')}}</h3>
</div>
    </div>
</div>
<div id="open-modal"></div>
@stop

@section('end-script')
    @parent

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/daterangepicker/daterangepicker-bs3.css')}}"/ >
    <script src="{{asset('assets/js/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript">
    $('.date-filter').daterangepicker({
        format: 'YYYY-MM-DD',
        separator:' / '
    });
    // $(".date-filter").keypress(function(event) {event.preventDefault();});
    </script>
@stop