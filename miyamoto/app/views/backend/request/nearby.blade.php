@extends('backend.layouts.content')

@section('end-script')
    @parent
    <link href="{{asset('assets/css/gmaps.css')}}" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&sensor=false"></script>
    <script>
            var cityCircle;
            var geocoder;
            var flightPath;
            var map;
            var directionsDisplay;
            var directionsService = new google.maps.DirectionsService();
            var myLatlng;
            var myLatlng2;
            function initialize() {

                

                directionsDisplay = new google.maps.DirectionsRenderer();
                geocoder = new google.maps.Geocoder();
                
                @if(!empty($detail->longitude_from) && !empty($detail->latitude_from))

                myLatlng = new google.maps.LatLng({{$detail->latitude_from}},{{$detail->longitude_from}});
              
                myLatlng2 = new google.maps.LatLng({{$detail->latitude_to}},{{$detail->longitude_to}});


                @else

                var myLatlng = new google.maps.LatLng(-3.601142,118.11035);

                @endif

                var mapOptions = {
                    zoom: 5,
                    center: myLatlng
                }

                map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('directions-panel'));


                var marker = new google.maps.Marker({
                    position: myLatlng,
                    center:myLatlng,
                    map: map,
                    title: 'Radius 10 Kilometer'
                });

                var infowindow = new google.maps.InfoWindow({
                    content: 'From: {{$detail->desc_from}}'
                });
                infowindow.open(map,marker);

                // for (var city in citymap) {
                    var populationOptions = {
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        map: map,
                        center: myLatlng,
                        radius: 10000
                    };
                    // Add the circle for this city to the map.
                    cityCircle = new google.maps.Circle(populationOptions);
                // }

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
                    new google.maps.LatLng({{$detail->latitude_to}},{{$detail->longitude_to}}),
                    new google.maps.LatLng({{$detail->latitude_from}},{{$detail->longitude_from}})
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

                //arround
                @if(!empty($arround))
                var image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
                var agent_arround = [
                    @foreach($arround as $key => $row)
                        [{{$row['lat']}},{{$row['lng']}},"{{$row['user']}}"],
                    @endforeach
                ];
                 for( i = 0; i < agent_arround.length; i++ ) {
                    var position_agent = new google.maps.LatLng(agent_arround[i][0], agent_arround[i][1]);
                    // bounds.extend(position);
                    var marker_agent = new google.maps.Marker({
                        position: position_agent,
                        map: map,
                        icon: image,
                        title: agent_arround[i][2]
                    });
                }
                @endif
                //arround

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setPosition(myLatlng);
                    infowindow.open(map,marker);
                });

                google.maps.event.addListener(marker2, 'click', function() {
                    infowindow2.setPosition(myLatlng2);
                    infowindow2.open(map,marker2);
                });
            }
            function startbutton()
            {
                map.setCenter(myLatlng);
                map.setZoom(12);
            }
            // function finishbutton()
            // {
            //     map.setCenter(myLatlng2);
            // }
        
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@stop

@section('body-content')
@if($detail->close_date >  date('Y-m-d H:i:s'))
@if($detail->status!=0)
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-info-circle"></i> {{$detail->title}}
            @if($detail->status==1) <button class="btn btn-info disabled">Expire</button> @elseif($detail->status==2) <button class="btn btn-info disabled">Expire</button> @else <span class="label label-danger">New</span> @endif
        </h2>
    </div><!-- /.col -->
</div>
@else
<div class="row">
    <div class="col-xs-12">
        <h2 class="page-header">
            <i class="fa fa-info-circle"></i> {{$detail->title}}
            <small class="pull-right">Post Since: {{date('M d, Y',strtotime($detail->created_at))}}</small>
        </h2>
    </div><!-- /.col -->
</div>
<!-- info row -->
<div class="row invoice-info">
    <div class="col-sm-3 invoice-col">
            From
        <address>
            {{-- <strong>Indonesia</strong><br> --}}
            {{$detail->desc_from}}<br>
        </address>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        To
        <address>
            {{-- <strong></strong><br> --}}
            {{$detail->desc_to}}<br>
        </address>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <b>Status:</b> @if($detail->status==1) <span class="label label-info">Process</span> @elseif($detail->status==2) <span class="label label-success">Done</span> @else <span class="label label-danger">New</span> @endif <br/>
        <b>Budget:</b> $ {{number_format($detail->open_price,2)}}<br/>
        <b>Open Date:</b> {{date('d F Y',strtotime($detail->open_date))}}<br/>
        <b>Close Date:</b> {{date('d F Y',strtotime($detail->close_date))}}<br/>
    </div><!-- /.col -->
    <div class="col-sm-3 invoice-col">
        <b>Distance:</b> {{$detail->distance}}<br/>
        <b>Duration:</b> {{$detail->duration}}<br/>
        <b>Weight:</b> {{Helper::GetWeight($detail->id)}} Kilogram<br/>
    </div>
</div><!-- /.row -->

<!-- Table row -->
<div class="row">
    {{-- <div class="col-xs-12 table-responsive"> --}}
        <div class="col-xs-9">
            <h3 class="page-header">Maps</h3>
            <div id="googleMap" style="width:100%;height:350px;"></div>
            <div class="start-finish-button">
                {{-- <button class="btn btn-success btn-xs pull-right" onclick="finishbutton();">Finish</button> --}}
                <button class="btn btn-success btn-xs pull-right" onclick="startbutton();">Find Agent</button>
            </div>
        </div>
        <div class="col-xs-3">
            <h3 class="page-header">
                Information
                {{-- <button class="btn btn-info btn-xs pull-right" onclick="calcRoute();" data-toggle="tooltip" data-html="true" data-placement="top" title="Click to route"><i class="fa fa-fw fa-location-arrow"></i> Route</button> --}}
            </h3>
            <div class="box-body">
                <dl>
                    <dt>Agent</dt>
                    <dd>{{count($arround)}} Agent</dd>
                    {{-- <dt>Sent Notification</dt>
                    <dd>4 SMS</dd> --}}
                </dl>
            </div>
            <div id="directions-panel" style="width:100%;height:350px;" class="routes"></div>
        </div>

</div>

{{-- <div class="row">
    <div class="col-xs-12">
    </div>
</div>
 --}}
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
                        {{-- <img src="{{URL::to('admin/request/image?id='.$row->id)}}"> --}}
                    </td>
                    <td>{{$row->weight}}</td>
                    <td>{{$row->description}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div><!-- /.col -->
</div><!-- /.row -->

<div class="row">
    <!-- accepted payments column -->
     
 
</div><!-- /.row -->

<!-- this row will not appear when printing -->
<div class="row no-print">
    <div class="col-xs-12">
        <a href="{{URL::to('admin/request')}}" class="btn btn-default">{{trans('button.bc')}}</a>
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
        <a href="{{URL::to('admin/request')}}" class="btn btn-default">{{trans('button.bc')}}</a>
    </div>
</div>
@endif
@stop