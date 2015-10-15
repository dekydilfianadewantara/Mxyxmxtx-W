@extends('backend.layouts.content')

@section('end-script')
    @parent
    <link href="{{asset('assets/css/gmaps.css')}}" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&sensor=false"></script>
    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip('show')
        })
        setTimeout(function () {
            $('[data-toggle="tooltip"]').tooltip('hide'); //close the tooltip
        }, 4000);

            var geocoder;
            var flightPath;
            var map;
            var directionsDisplay;
            var directionsService = new google.maps.DirectionsService();
            function initialize() {

                directionsDisplay = new google.maps.DirectionsRenderer();
                geocoder = new google.maps.Geocoder();
                
                @if(!empty($detail->longitude_from) && !empty($detail->latitude_from))

                var myLatlng = new google.maps.LatLng({{$detail->latitude_from}},{{$detail->longitude_from}});
              
                var myLatlng2 = new google.maps.LatLng({{$detail->latitude_to}},{{$detail->longitude_to}});


                @else

                var myLatlng = new google.maps.LatLng(-3.601142,118.11035);

                @endif

                var mapOptions = {
                    zoom: 3,
                    center: myLatlng
                }

                map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('directions-panel'));


                var marker = new google.maps.Marker({
                  position: myLatlng,
                  center:myLatlng,
                  map: map,
                  title: 'From'
                });

                var infowindow = new google.maps.InfoWindow({
                    content: 'From: {{$detail->desc_from}}'
                });
                infowindow.open(map,marker);

                @if(!empty($detail->longitude_to) && !empty($detail->latitude_to))
                    var marker2 = new google.maps.Marker({
                        position: myLatlng2,
                        map: map,
                        title: 'To'
                    });

                    var infowindow2 = new google.maps.InfoWindow({
                        content: 'To: {{$detail->desc_to}}'
                    });
                    infowindow2.open(map,marker2);

                @endif
                //polyline
                var flightPlanCoordinates = [
                    new google.maps.LatLng({{$detail->latitude_from}},{{$detail->longitude_from}}),
                    new google.maps.LatLng({{$detail->latitude_to}},{{$detail->longitude_to}})
                ];

                flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                flightPath.setMap(map);
                // end of polyline

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setPosition(myLatlng);
                    infowindow.open(map,marker);
                });

                google.maps.event.addListener(marker2, 'click', function() {
                    infowindow2.setPosition(myLatlng2);
                    infowindow2.open(map,marker2);
                });
            }

            function calcRoute() {
                // console.log('da');
                // var myLatlng = new google.maps.LatLng({{$detail->latitude_from}},{{$detail->longitude_from}});
                  
                    // var myLatlng2 = new google.maps.LatLng({{$detail->latitude_to}},{{$detail->longitude_to}});
                var request = {
                    origin:('{{$detail->latitude_from}},{{$detail->longitude_from}}'),
                    destination:('{{$detail->latitude_to}},{{$detail->longitude_to}}'),
                    travelMode: google.maps.TravelMode.DRIVING
                };
                directionsService.route(request, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        flightPath.setMap(null);
                        directionsDisplay.setDirections(response);
                        }else{
                        $('.routes').html('<h3>Sorry, the route can not be found</h3>');
                    }
                });
            }

        
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@stop

@section('body-content-root')
<?php $admin = Session::get('admin'); ?>
@if($admin['level'] == 1)
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-info-circle"></i> {{$detail->title}}
            <small class="pull-right">Member Since: {{date('M d, Y',strtotime($detail->about->created_at))}}</small>
        </h2>
    </div>
</div>

<div class="row invoice-info">
    <div class="col-sm-3 invoice-col">
            From
        <address>
            {{$detail->desc_from}}<br>
        </address>
    </div>
    <div class="col-sm-3 invoice-col">
        To
        <address>
            {{$detail->desc_to}}<br>
        </address>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <?php #$direction = Helper::GetDistance($detail->latitude_from,$detail->longitude_from,$detail->latitude_to,$detail->longitude_to) ?>
        <b>Status:</b> @if($detail->status==1) <span class="label label-info">Process</span> @elseif($detail->status==2) <span class="label label-success">Done</span> @else <span class="label label-danger">New</span> @endif <br/>
        <b>Budget:</b> $ {{number_format($detail->open_price)}}<br/>
        <b>Open Date:</b> {{date('d F Y',strtotime($detail->open_date))}}<br/>
        <b>Close Date:</b> {{date('d F Y',strtotime($detail->close_date))}}<br/>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <b>Distance:</b> {{$detail->distance}}<br/>
        <b>Duration:</b> {{$detail->duration}}<br/>
        <b>Weight:</b> {{Helper::GetWeight($detail->id)}} Kilogram<br/>
    </div>
</div>

<div class="row">
        <div class="col-xs-7">
        <h3 class="page-header">Maps</h3>
            <div id="googleMap" style="width:100%;height:350px;"></div>
        </div>
        <div class="col-xs-5">
            <h3 class="page-header">
                Panel
                <button class="btn btn-info btn-xs pull-right" onclick="calcRoute();" data-toggle="tooltip" data-html="true" data-placement="top" title="Click to route"><i class="fa fa-fw fa-location-arrow"></i> Route</button>
            </h3>

            <div id="directions-panel" style="width:100%;height:350px;" class="routes"></div>
        </div>

</div>


<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Thing</th>
                    <th style="width:20%">Picture</th>
                    <th style="width:10%">Weight</th>
                    <th>Description</th>    
                </tr>
            </thead>
            <tbody>
                @foreach($item as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>
                        <?php
                        $paths       = public_path('assets/store/request/'.$row->image);
                        ?>
                        @if(!empty($row->image) && is_file($paths))
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/request/'.$row->image)}}" alt="img"/>
                        @else
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/no_image.png')}}" alt="img"/>
                        @endif
                        {{-- <img src="{{URL::to('admin/find-work/image?id='.$row->id)}}"> --}}
                    </td>
                    <td>{{$row->weight}}</td>
                    <td>{{$row->description}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">

</div>

<div class="row no-print">
    <div class="col-xs-12">
        <a href="{{URL::to('admin/find-work')}}" class="btn btn-default">{{trans('button.bc')}}</a>
    </div>
</div>
@else

@if($detail->close_date >  date('Y-m-d H:i:s'))
@if(($detail->status == 0) || ($detail->status > 0 && $idUser == 1))
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-info-circle"></i> {{$detail->title}}
            <small class="pull-right">Member Since: {{date('M d, Y',strtotime($detail->about->created_at))}}</small>
        </h2>
    </div>
</div>

<div class="row invoice-info">
    <div class="col-sm-3 invoice-col">
            From
        <address>
            {{$detail->desc_from}}<br>
        </address>
    </div>
    <div class="col-sm-3 invoice-col">
        To
        <address>
            {{$detail->desc_to}}<br>
        </address>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <?php #$direction = Helper::GetDistance($detail->latitude_from,$detail->longitude_from,$detail->latitude_to,$detail->longitude_to) ?>
        <b>Status:</b> @if($detail->status==1) <span class="label label-info">Process</span> @elseif($detail->status==2) <span class="label label-success">Done</span> @else <span class="label label-danger">New</span> @endif <br/>
        <b>Budget:</b> $ {{number_format($detail->open_price)}}<br/>
        <b>Open Date:</b> {{date('d F Y',strtotime($detail->open_date))}}<br/>
        <b>Close Date:</b> {{date('d F Y',strtotime($detail->close_date))}}<br/>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <b>Distance:</b> {{$detail->distance}}<br/>
        <b>Duration:</b> {{$detail->duration}}<br/>
        <b>Weight:</b> {{Helper::GetWeight($detail->id)}} Kilogram<br/>
    </div>
</div>

<div class="row">
        <div class="col-xs-7">
        <h3 class="page-header">Maps</h3>
            <div id="googleMap" style="width:100%;height:350px;"></div>
        </div>
        <div class="col-xs-5">
            <h3 class="page-header">
                Panel
                <button class="btn btn-info btn-xs pull-right" onclick="calcRoute();" data-toggle="tooltip" data-html="true" data-placement="top" title="Click to route"><i class="fa fa-fw fa-location-arrow"></i> Route</button>
            </h3>

            <div id="directions-panel" style="width:100%;height:350px;" class="routes"></div>
        </div>

</div>


<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Thing</th>
                    <th style="width:20%">Picture</th>
                    <th style="width:10%">Weight</th>
                    <th>Description</th>    
                </tr>
            </thead>
            <tbody>
                @foreach($item as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>
                        <?php
                        $paths       = public_path('assets/store/request/'.$row->image);
                        ?>
                        @if(!empty($row->image) && is_file($paths))
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/request/'.$row->image)}}" alt="img"/>
                        @else
                        <img width="150" style="border: 1px solid #DDD;padding: 5px;" src="{{asset('assets/store/no_image.png')}}" alt="img"/>
                        @endif
                        {{-- <img src="{{URL::to('admin/find-work/image?id='.$row->id)}}"> --}}
                    </td>
                    <td>{{$row->weight}}</td>
                    <td>{{$row->description}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">

</div>

<div class="row no-print">
    <div class="col-xs-12">
        <a href="{{URL::to('admin/find-work')}}" class="btn btn-default">{{trans('button.bc')}}</a>
        @if($detail->status == 0)
        <a href="{{URL::to('admin/find-work/bid/'.Helper::Encrypt($detail->id))}}" class="btn btn-success pull-right">Bid Proposal</a>
        @endif
    </div>
</div>
@else
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-info-circle"></i> {{$detail->title}}
            @if($detail->status==1) <button class="btn btn-info disabled">Expire</button> @elseif($detail->status==2) <button class="btn btn-info disabled">Expire</button> @else <span class="label label-danger">New</span> @endif
        </h2>
    </div>
</div>
@endif

@else
<div class="col-xs-12">
    <div class="box no-border">
            <h3 class="box-title pull-left">Request Has expired</h3>
    </div>
</div>
<div class="col-xs-12">
    <!-- general form elements -->
    <div class="no-border" style="">
        <a href="{{URL::to('admin/find-work')}}" class="btn btn-default">{{trans('button.bc')}}</a>
    </div>
</div>
@endif

@endif

@stop