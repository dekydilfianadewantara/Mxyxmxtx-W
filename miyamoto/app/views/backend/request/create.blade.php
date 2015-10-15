@extends('backend.layouts.content')

@section('end-script')
    @parent
    <link href="{{asset('assets/css/gmaps.css')}}" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&sensor=false"></script>
    <script>
      var map;
      var myCenter=new google.maps.LatLng(-3.601142,118.11035);
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
            content: 'Longitude: ' + location.lng().toFixed(6) + '<br>Latitude: ' + location.lat().toFixed(6) + '<br>Place: <span class="palace"></span><br><a class="btn btn-primary btn-xs from_place" data-descfrom="" data-long="'+location.lng().toFixed(6)+'" data-lat="'+location.lat().toFixed(6)+'">From</a> <a class="btn btn-info btn-xs to_place" data-descto="" data-long="'+location.lng().toFixed(6)+'" data-lat="'+location.lat().toFixed(6)+'">To</a>'
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
        // var tempat;
        geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                $('.palace').html(results[0].formatted_address);
                // var sta=results[0].formatted_address;
                // var n = sta.split(",");
                // $('.palace').attr('data-place',sta);
              }
            }
          });
                // console.log(tempat);

        // var marker = new google.maps.Marker({
        //   position: latLng,
        //   map: map,
        // });
        // markers.push(marker);
        // var infowindow = new google.maps.InfoWindow({
        //   content: 'Longitude: ' + latLng.lng() + '<br>Latitude: ' + latLng.lat() + '<br>Place: <span id="palace"></span><br><a class="btn btn-primary btn-xs from_place" data-long="'+latLng.lng()+'" data-lat="'+latLng.lat()+'">From</a> <a class="btn btn-info btn-xs to_place" data-long="'+latLng.lng()+'" data-lat="'+latLng.lat()+'">To</a>'
        // });
        // infowindow.open(map,marker);
        // console.log(tempat);
        // geocoder.geocode( {'latLng': latLng},
        //   function(results, status) {
        //     if(status == google.maps.GeocoderStatus.OK) {
        //       if(results[0]) {
              
        //         // document.getElementById("address").value = results[0].formatted_address;
        //         // var sta=results[2].formatted_address;
        //         // var n = sta.split(",");
        //         // document.getElementById("kota").value = n[n.length-2];
        //         tempat = results[0].formatted_address;
        //       }
        //       else {
        //         // document.getElementById("address").value = "No results";
        //       }
        //     }
        //     else {
              
        //       // document.getElementById("address").value = status;
        //     }
        //   });

        }
    google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <script src="{{asset('assets/js/AdminLTE/jquery.form-validator.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/jquery.datetimepicker.css')}}"/ >
    <script src="{{asset('assets/js/jquery.datetimepicker.js')}}"></script>
    <script type="text/javascript">
    $.validate({
        form : '#request',
        onSuccess : function() {
            // $(document).on('click','.waiting',function(){
            $('.wait').addClass('disabled');
            $('.wait').html('Waiting ...');
    // })
        }
    });

    jQuery('.open_date').datetimepicker({ 
        format:'Y-m-d H:i',
        minDate:'0',
        scrollInput:false
    });
    jQuery('.close_date').datetimepicker({
        format:'Y-m-d H:i',
        minDate:'0',
        scrollInput:false
    });

    $(document).ready(function() {
        // $("#request").on('submit',function(e) {
        //     e.preventDefault();
        // });
        $(document).on('click','.from_place',function(){
          $('.longitude_from').val($(this).data('long'));
          $('.latitude_from').val($(this).data('lat'));
        });
        $(document).on('click','.to_place',function(){
          $('.longitude_to').val($(this).data('long'));
          $('.latitude_to').val($(this).data('lat'));
        });
        $('#request').on("keyup keypress", function(e) {
          var code = e.keyCode || e.which; 
          if (code  == 13) {               
            e.preventDefault();
            return false;
          }
        });
        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".add-dinamic"); //Fields wrapper
        var add_button      = $(".add-item"); //Add button ID
        
        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                $(wrapper).append('<tr>'+
                '<td><input type="text" name="name[]" class="form-control" autocomplete="off" placeholder="Name" data-validation="required" data-validation-error-msg="The name field is required."/></td>'+
                '<td><input type="text" name="weight[]" autocomplete="off" class="form-control" placeholder="Weight on Kg" data-validation="number" data-validation-error-msg="The weight field must be a number."/></td>'+
                '<td><input type="file" name="picture[]"/></td>'+
                '<td><textarea name="description[]" class="form-control" autocomplete="off" placeholder="Description" data-validation="required" data-validation-error-msg="The description field is required."></textarea></td>'+
                '<td><a href="" class="btn btn-danger btn-xs delete-item"><i class="fa fa-fw fa-trash-o"></i> Delete</a></td>'+
            '</tr>'); //add input box
            }
        });
        
        $(document).on("click",".delete-item", function(e){ //user click on remove text
            e.preventDefault(); $(this).parents('.add-dinamic tr').remove(); x--;
        })
    });
    </script>
@stop

@section('body-content-child')
{{Form::open(array('url'=>'admin/request/create', 'id'=>'request', 'method'=>'POST', 'files'=>true))}}
<div class="col-xs-12">
    <div class="box box-info">
        <div class="box-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" autocomplete="off" value="{{Input::old('title')}}" class="form-control" id="title" placeholder="Title" data-validation="required" data-validation-error-msg="The title field is required.">
                        {{$errors->first('title','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="standard_price">Standard Price</label>
                        <input type="text" name="standard_price" autocomplete="off" value="{{Input::old('standard_price')}}" class="form-control" id="standard_price" placeholder="Standard Price" data-validation="number" data-validation-error-msg="The standard price field is required or must be a number.">
                        {{$errors->first('standard_price','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="open_date">Open Date</label>
                        <input type="text" name="open_date" autocomplete="off" value="{{Input::old('open_date')}}" class="form-control open_date" id="open_date" placeholder="yyyy-mm-dd hh:mm" data-validation="required" data-validation-error-msg="The open date field is required.">
                        {{$errors->first('open_date','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="close_date">Close Date</label>
                        <input type="text" name="close_date" autocomplete="off" value="{{Input::old('close_date')}}" class="form-control close_date" id="close_date" placeholder="yyyy-mm-dd hh:mm" data-validation="required" data-validation-error-msg="The close date field is required.">
                        {{$errors->first('close_date','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Longitude From</label>
                        <input type="text" value="{{Input::old('longitude_from')}}" class="form-control longitude_from" placeholder="Longitude From" disabled>
                        <input type="hidden" name="longitude_from" class="longitude_from" value="{{Input::old('longitude_from')}}"/>
                        {{$errors->first('longitude_from','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Latitude From</label>
                        <input type="text" value="{{Input::old('latitude_from')}}" class="form-control latitude_from" placeholder="Latitude From" disabled>

                        <input type="hidden" name="latitude_from" class="latitude_from" value="{{Input::old('latitude_from')}}"/>
                        {{$errors->first('latitude_from','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Longitude To</label>
                        <input type="text" value="{{Input::old('longitude_to')}}" class="form-control longitude_to" placeholder="Longitude To" disabled>

                        <input type="hidden" name="longitude_to" class="longitude_to" value="{{Input::old('longitude_to')}}"/>
                        {{$errors->first('longitude_to','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="form-group">
                        <label>Latitude To</label>
                        <input type="text" value="{{Input::old('latitude_to')}}" class="form-control latitude_to" placeholder="Latitude To" disabled>

                        <input type="hidden" name="latitude_to" class="latitude_to" value="{{Input::old('latitude_to')}}"/>
                        {{$errors->first('latitude_to','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-12">
                        <input id="pac-input" class="controls sub" type="text" placeholder="Search Place">
                        <div id="googleMap" style="height:350px;"></div>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div>
</div>
<div class="col-xs-12">
    <!-- general form elements -->
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title pull-left">Items</h3>
            <div class="pull-right" style="margin-left:5px">
                <a style="margin:10px 10px 0 10px;color:white" class="btn btn-sm btn-primary add-item">Add Item</a>
            </div>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Product</th>
                <th>Weight</th>
                <th>Picture</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="add-dinamic">
            {{-- @foreach($item as $row) --}}
            {{-- <tr>
                <td>{{$row->name}}</td>
                <td>{{$row->weight}} Kg</td>
                <td>
                    <?php
                    $paths = public_path($row->picture);
                    ?>
                    @if(!empty($row->picture) && is_file($paths))
                        <img width="100" src="{{asset($row->picture)}}" alt="img"/>
                    @else
                        <img width="100" src="{{asset('assets/store/no_image.png')}}" alt="img"/>
                    @endif 
                </td>
                <td>{{$row->description}}</td>
                <td>{{date('d F Y, H:i:s',strtotime($row->updated_at))}}</td>
                <td>
                    <a href="{{URL::to('admin/request/edit-item/'.$row->id)}}" class="btn btn-info btn-xs"><i class="fa fa-fw fa-edit"></i> Edit</a>
                    <a href="{{URL::to('admin/request/delete/'.$row->id)}}" class="btn btn-danger btn-xs delete"><i class="fa fa-fw fa-trash-o"></i> Delete</a>
                </td>
            </tr> --}}
            {{-- @endforeach --}}
            </tbody>
        </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
<div class="col-xs-12">
    <!-- general form elements -->
    <div class="box no-border" style="padding:10px">
        <a href="{{URL::to('admin/request')}}" class="btn btn-default">{{trans('button.bc')}}</a>
        <button type="submit" class="btn btn-primary wait">Submit</button>
    </div>
</div>
{{Form::close()}}

@stop