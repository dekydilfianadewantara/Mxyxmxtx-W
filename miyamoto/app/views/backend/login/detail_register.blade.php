
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Imhanya | Registration Page</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{asset('assets/css/AdminLTE.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">

        <div class="form-box register" id="login-box">
            <div class="header">Register New Membership</div>
           {{--  @if(Session::has('register'))
                <div class="body bg-gray">
                    <center><p class="text-green">Thank you for register</p></center>
                </div>
            @else --}}
            {{Form::open(array('url'=>'complete', 'id'=>'request', 'method'=>'POST'))}}
                <div class="body bg-gray">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="charge_per_kilometer" autocomplete="off" value="{{Input::old('charge_per_kilometer')}}" class="form-control" placeholder="Charge per kilometer"/>
                                {{$errors->first('charge_per_kilometer','<p class="text-red">:message</p>')}}
                            </div>
                            
                            <div class="col-xs-6">
                                <select class="form-control" name="vehicles_size">
                                    <option value="">- Vehicles Size -</option>
                                    @foreach($size as $row)
                                        @if(Input::old('vehicles_size')==$row->id)
                                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                        @else
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                {{$errors->first('vehicles_size','<p class="text-red">:message</p>')}}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" name="start_available" autocomplete="off" value="{{Input::old('start_available')}}" class="form-control start_time" placeholder="Start time available"/>
                                {{$errors->first('start_available','<p class="text-red">:message</p>')}}
                            </div>
                            <div class="col-xs-6">
                                <input type="text" name="end_available" autocomplete="off" value="{{Input::old('end_available')}}" class="form-control end_time" placeholder="End time available"/>
                                {{$errors->first('end_available','<p class="text-red">:message</p>')}}
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <select class="form-control" name="payment_method">
                            <option value="">- Payment Method -</option>
                            @foreach($payment as $row)
                                @if(Input::old('payment_method')==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        {{$errors->first('payment_method','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="text" autocomplete="off" value="{{Input::old('latitude')}}" class="form-control latitude" placeholder="Latitude" disabled/>
                                <input type="hidden" name="latitude" class="latitude" value="{{Input::old('latitude')}}"/>
                                {{$errors->first('latitude','<p class="text-red">:message</p>')}}
                            </div>
                            <div class="col-xs-6">
                                <input type="text" autocomplete="off" value="{{Input::old('longitude')}}" class="form-control longitude" placeholder="Longitude" disabled/>
                                <input type="hidden" name="longitude" class="longitude" value="{{Input::old('longitude')}}"/>

                                {{$errors->first('longitude','<p class="text-red">:message</p>')}}
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input id="pac-input" class="controls sub" type="text" placeholder="Search Place">
                        <div id="googleMap" class="location lat_tour" style="height:350px;"></div>
                    </div>
                </div>
                <div class="footer">                    

                    <button type="submit" class="btn bg-olive btn-block waiting">Sign me up</button>

                    <a href="{{URL::to('login')}}" class="text-center">I already have a membership</a>
                </div>
            {{Form::close()}}
            {{-- @endif --}}

            {{-- <div class="margin text-center">
                <span>Register using social networks</span>
                <br/>
                <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
                <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
                <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>

            </div> --}}
        </div>
        <ol class='tourbus-legs' id='my-tour-id'>

          <li data-orientation='left' data-width="200" data-left='390' data-top="270">
            <p class="black">How to fill latitude and longitude form ?</p>
            <a href='javascript:void(0);' class='tourbus-next loc'>Next</a>
          </li>

          <li data-el='.lat_tour' data-orientation='left' data-width='230' data-top="345" data-left="420">
            <p class="black">Please search your location</p>
            <a href='javascript:void(0);' class='tourbus-next'>Next</a>
          </li>

          <li data-el='.location' data-highlight='true' data-orientation='right' data-left="400" data-top="500" data-width='250'>
            <p class="black">Click to your location and then press <i>Get It</i> Button</p>
            <a href='javascript:void(0);' class='tourbus-stop'>Done!</a>
          </li>

        </ol>

        <!-- jQuery 2.0.2 -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/js/AdminLTE/jquery-tourbus.min.js')}}"></script>
        <link href="{{asset('assets/js/AdminLTE/jquery-tourbus.min.css')}}" media='all' rel='stylesheet' type='text/css' />
        <script type="text/javascript">
        $(document).on('click','.waiting',function(){
            $('.waiting').addClass('disabled');
            $('.waiting').html('Waiting ...');
        });

        $(window).load( function() { 
          var tour = $('#my-tour-id').tourbus( {
            autoDepart: true,
            onLegStart: function( leg, bus ) {
                // highlight where required
                if( leg.rawData.highlight ) {
                  leg.$target.addClass('intro-tour-highlight');
                  $('.intro-tour-overlay').show();
                }
            },
            onLegEnd: function( leg ) {
                // remove highlight when leaving this leg
                if( leg.rawData.highlight ) {
                  leg.$target.removeClass('intro-tour-highlight');
                  $('.intro-tour-overlay').hide();
                }
            }
          } );
          var tourbus = $('#my-tour-id').data('tourbus');
          tourbus.on( 'click', '.loc', function() {
              // handle click on element matching selector
              // _inside any tour leg_
              $(".controls").focus(); 
            } );
        } );
        </script>
        <!-- Bootstrap -->
        <script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <link href="{{asset('assets/css/gmaps.css')}}" rel="stylesheet">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&sensor=false"></script>
        <script>
          var map;
            @if(!empty(Input::old('latitude')) && !empty(Input::old('longitude')))
                    var myCenter = new google.maps.LatLng({{Input::old('latitude')}},{{Input::old('longitude')}});
            @else
          var myCenter=new google.maps.LatLng(-3.601142,118.11035);
          @endif
          var geocoder;
          var markers = [];
          // var tempat;
          function initialize()
          {
            var markers = [];
            var mapProp = {
                center:myCenter,
                zoom:4,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
           
            var input = document.getElementById('pac-input');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var searchBox = new google.maps.places.SearchBox(input);
            
            google.maps.event.addListener(searchBox, 'places_changed', function() {
                var places = searchBox.getPlaces();
          
                for (var i = 0, marker; marker = markers[i]; i++) {
                    marker.setMap(null);
                }
            
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
                var image = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };
                var marker = new google.maps.Marker({
                    map: map,
                    icon: image,
                    title: place.name,
                    center:place.geometry.location,
                    position: place.geometry.location
                });
          
                markers.push(marker);
          
                bounds.extend(place.geometry.location);
            }
          
                map.fitBounds(bounds);
            });

            @if(!empty(Input::old('latitude')) && !empty(Input::old('longitude')))
                    var dari = new google.maps.LatLng({{Input::old('latitude')}},{{Input::old('longitude')}});

                    var tanda1 = new google.maps.Marker({
                        position: dari,
                        center:dari,
                        map: map,
                        title: 'Your location'
                    });

                    var infoBox1 = new google.maps.InfoWindow({
                        content: 'Your location'
                    });

                    infoBox1.open(map,tanda1);
            @endif
            
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });
            google.maps.event.addListener(map, 'bounds_changed', function() {
                var bounds = map.getBounds();
                searchBox.setBounds(bounds);
            });
          }

            function placeMarker(location) {
                setAllMap(null);
            
            var marker = new google.maps.Marker({
                position: location,
                center: location,
                map: map,
            });
            markers.push(marker);

            getAddress(location);

            var infowindow = new google.maps.InfoWindow({
                content: 'Latitude: ' + location.lat().toFixed(6) + '<br>Longitude: ' + location.lng().toFixed(6) + '<br>Place: <span class="palace"></span><br><a class="btn btn-primary btn-xs getit" data-descfrom="" data-long="'+location.lng().toFixed(6)+'" data-lat="'+location.lat().toFixed(6)+'">Get It</a>'
            });
            infowindow.open(map,marker);
          }
          function setAllMap(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
            markers = [];
          }
          function getAddress(latLng) {
            geocoder.geocode( {'latLng': latLng},
              function(results, status) {
                if(status == google.maps.GeocoderStatus.OK) {
                  if(results[0]) {
                    $('.palace').html(results[0].formatted_address);
                  }
                }
              });
            }
        google.maps.event.addDomListener(window, 'load', initialize);
        </script>

        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.datetimepicker.css')}}"/ >
        <script src="{{asset('assets/js/jquery.datetimepicker.js')}}"></script>
        <script type="text/javascript">
        jQuery('.start_time').datetimepicker({ 
            format:'Y-m-d',
            closeOnDateSelect:true,
            timepicker:false,
            minDate:'0',
            scrollInput:false
        });
        jQuery('.end_time').datetimepicker({
            format:'Y-m-d',
            closeOnDateSelect:true,
            timepicker:false,
            minDate:'0',
            scrollInput:false
        });

        $(document).ready(function() {
            $(document).on('click','.getit',function(){
              $('.longitude').val($(this).data('long'));
              $('.latitude').val($(this).data('lat'));
            });
            $('#request').on("keyup keypress", function(e) {
              var code = e.keyCode || e.which; 
              if (code  == 13) {               
                e.preventDefault();
                return false;
              }
            });

        });
        </script>  
    <div class='intro-tour-overlay'></div>

    </body>
</html>