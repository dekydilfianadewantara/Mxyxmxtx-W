@extends('backend.layouts.content')

@section('end-script')
    @parent

    <script type="text/javascript" src="{{ asset('fileman/js/jquery-ui.min.js') }}"></script>
    <link href="{{ asset('fileman/css/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        function openCustomRoxy(){
          $('#roxyCustomPanel').dialog({modal:true, width:875,height:600});
        }
        function closeCustomRoxy(){
          $('#roxyCustomPanel').dialog('close');
        }        
    </script>


@stop

@section('body-content')
@if(Session::has('about'))
    <div class="alert alert-success alert-dismissable">
        <i class="fa fa-check"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Success!</b> {{Session::get('about')}}.
    </div>
@endif
<div class="box">
    {{Form::open(array('url'=>'admin/about', 'method'=>'POST', 'files'=>true))}}
        <div class="box-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="name">Name</label>
                        <input type="text" name="name" value="{{$about->name}}" class="form-control" id="name" placeholder="Name">
                        {{$errors->first('name','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="{{$about->email}}" class="form-control" id="email" placeholder="Email Address">
                        {{$errors->first('email','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="facebook">Facebook</label>
                        <input type="text" name="facebook" value="{{$about->facebook}}" class="form-control" id="facebook" placeholder="Facebook Account">
                        {{$errors->first('facebook','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-6">
                        <label for="twitter">Twitter</label>
                        <input type="text" name="twitter" value="{{$about->twitter}}" class="form-control" id="twitter" placeholder="Twitter Account">
                        {{$errors->first('twitter','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="instagram">Instagram</label>
                        <input type="text" name="instagram" value="{{$about->instagram}}" class="form-control" id="instagram" placeholder="Instagram Account">
                        {{$errors->first('instagram','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-6">
                        <label for="gplus">Google Plus</label>
                        <input type="text" name="gplus" value="{{$about->gplus}}" class="form-control" id="gplus" placeholder="Google Plus Account">
                        {{$errors->first('gplus','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone_number" value="{{$about->phone}}" class="form-control" id="phone" placeholder="Phone Number">
                        {{$errors->first('phone_number','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="col-xs-6">
                        <label for="address">Address</label>
                        <input type="text" name="address" value="{{$about->address}}" class="form-control" id="address" placeholder="Address">
                        {{$errors->first('address','<p class="text-red">:message</p>')}}
                    </div>
                </div>
            </div>


            {{--  
            <div class="form-group">
                <label for="picture">Picture</label><br/>
                <input type="text" id="txtSelectedFile" class="form-control" name="picture" readonly="readonly" onfocus="this.blur()" style="border:1px solid #ccc;cursor:pointer;padding:4px;" value="{{$about->picture}}">
                {{$errors->first('picture','<p class="text-red">:message</p>')}}<br/>
                <a href="javascript:openCustomRoxy()">
                    <?php $paths = public_path($about->picture); ?>
                    @if(!empty($about->picture) && is_file($paths))
                        <img src="{{asset($about->picture)}}" id="customRoxyImage" style="max-width:450px; border:1px solid grey;" alt="img"/>
                    @else
                        <img src="{{asset('assets/store/no_image.png')}}" id="customRoxyImage" style="max-width:450px; border:1px solid grey;" alt="img"/>
                    @endif
                </a>
                <div id="roxyCustomPanel" style="display: none;">
                  <iframe src="/fileman/index.html?integration=custom&txtFieldId=txtSelectedFile" style="width:100%;height:100%" frameborder="0"></iframe>
                </div>
                <p class="help-block">Click to select a picture.</p>
            </div>   --}}
        </div><!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    {{Form::close()}}
</div>
@stop