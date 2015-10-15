@extends('backend.layouts.content')

@section('body-content')
    <?php $admin = Session::get('admin'); ?>
	<div class="row">
        @if($admin['level']==1)
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            {{$users}}
                        </h3>
                        <p>
                            Users
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{URL::to('admin/users')}}" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>
                            {{$noresponse}}
                        </h3>
                        <p>
                            No Response Request
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{URL::to('admin/find-work?status=no')}}" class="small-box-footer">
                        More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @else
                        {{-- <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                        {{$bid}}
                                    </h3>
                                    <p>
                                        User Bid
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>
                                        {{$request}}
                                    </h3>
                                    <p>
                                        Request
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{URL::to('admin/request')}}" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-maroon">
                                <div class="inner">
                                    <h3>
                                        0
                                    </h3>
                                    <p>
                                        Products
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios7-pricetag-outline"></i>
                                </div>
                                <a href="{{URL::to('admin/products')}}" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        0
                                    </h3>
                                    <p>
                                        New Orders
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{URL::to('admin/orders')}}" class="small-box-footer">
                                    More info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div> --}}
        @endif
	</div>

    <div class="row">
        @if($admin['level']==1)
        <div class="col-lg-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">User Register</h3>
                    <h3 class="box-title pull-right">
                        {{Form::open(array('url' => 'admin/home', 'method' => 'post'))}}
                        <select class="form-control" name="sales" onchange="this.form.submit()">
                            @foreach($yearRange as $row)
                                <option value="{{$row}}" {{($year==$row) ? 'selected' : ''}}>{{$row}}</option>
                            @endforeach
                        </select>
                        {{Form::close()}}
                    </h3>
                </div>
                <div class="box-body chart-responsive">
                    <canvas id="myChart" style="width:100%; height:300px;" height="400"></canvas>
                    {{-- <div class="chart" id="revenue-chart" style="height: 300px;"></div> --}}
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </div>
        @endif
        @if(!empty($notifbid))
        <div class="col-lg-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">User Bid Notification</h3>
                </div>
                <div class="box-body">
                    <ul class="timeline">
                        @foreach($notifbid as $row)
                        <li>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{Helper::get_timeago(strtotime($row->created_at))}}</span>
                                <h3 class="timeline-header"><a>{{$row->about->name}}</a> has made a ​bid</h3>
                                <div class="timeline-body">Deliver <b><i>{{$row->request->title}}</i></b> from <b><i>{{$row->request->desc_from}}</i></b> to <b><i>{{$row->request->desc_to}}</i></b> at <b><i>${{number_format($row->price,2)}}</i></b> </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
@stop

@section('end-script')
    @parent
    <script src="{{asset('assets/js/Chart.js')}}"></script>
    <script type="text/javascript">
    $(function () {
        var data = {
            labels: [{{$month}}],
            datasets: [
            @foreach($group as $key => $row)
            <?php 
                if($key==0){
                    $color = '127,198,219'; 
                }elseif ($key==1) {
                    $color = '234,244,246'; 
                }elseif ($key==2) {
                    $color = '128,118,106'; 
                }elseif ($key==3) {
                    $color = '236,228,218'; 
                }elseif ($key==4) {
                    $color = '32,35,40'; 
                }elseif ($key==5) {
                    $color = '48,94,191'; 
                }elseif ($key==6) {
                    $color = '103,174,255'; 
                }elseif ($key==7) {
                    $color = '2,70,214'; 
                }elseif ($key==8) {
                    $color = '169,169,169'; 
                }
            ?>
                {
                    label: "{{$row->group_name}}",
                    fillColor: "rgba({{$color}},0.5)",
                    strokeColor: "rgba({{$color}},0.5)",
                    pointColor: "rgba({{$color}},0.5)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba({{$color}},0.5)",
                    data: [{{$chart[$key]}}]
                },
            @endforeach
            ]
        };
        var opts = {
            scaleShowValues: true, 
            scaleValuePaddingX: 13,
            scaleValuePaddingY: 13
        };
        var ctx = $("#myChart").get(0).getContext("2d");
        var myNewChart = new Chart(ctx).Line(data,opts);
    })
    </script>
@stop