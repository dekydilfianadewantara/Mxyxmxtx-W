
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
            {{Form::open(array('url'=>'register', 'method'=>'POST'))}}
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="text" name="full_name" autocomplete="off" value="{{Input::old('full_name')}}" class="form-control" placeholder="Full name"/>
                        {{$errors->first('full_name','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" autocomplete="off" value="{{Input::old('email')}}" class="form-control" placeholder="Email"/>
                        {{$errors->first('email','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-5">
                                <select class="form-control" name="code_area">
                                    <option value="">- Country Code -</option>
                                    @foreach($list_country as $row)
                                        @if(Input::old('code_area')==$row->country_areacode)
                                            <option value="{{$row->country_areacode}}" selected>{{$row->country_name}} ({{$row->country_areacode}})</option>
                                        @else
                                            <option value="{{$row->country_areacode}}">{{$row->country_name}} ({{$row->country_areacode}})</option>
                                        @endif
                                    @endforeach
                                </select>
                                {{$errors->first('code_area','<p class="text-red">:message</p>')}}
                            </div>
                            <div class="col-xs-7">
                                <input type="text" name="phone" autocomplete="off" value="{{Input::old('phone')}}" class="form-control" placeholder="Phone Number"/>
                                <p class="help-block">Example : <span class="strike">+263</span><span class="number" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Phone Number">773182743</span></p>
                                {{$errors->first('phone','<p class="text-red">:message</p>')}}
                                @if(Session::has('phone_unique')) <p class="text-red">{{Session::get('phone_unique')}}</p> @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="want_option">
                            <option value="">- Choose Category -</option>
                            @foreach($group as $row)
                                @if(Input::old('want_option')==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->group_name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->group_name}}</option>
                                @endif
                            @endforeach
                        </select>
                        {{$errors->first('want_option','<p class="text-red">:message</p>')}}
                    </div> 
                    <div class="form-group">
                        <textarea name="address" class="form-control" placeholder="Address">{{Input::old('address')}}</textarea>
                        {{$errors->first('address','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                        {{$errors->first('password','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <input type="password" name="retype_password" class="form-control" placeholder="Retype password"/>
                        {{$errors->first('retype_password','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <label for="capcha">{{HTML::image(Captcha::img(), 'Captcha image')}}</label>
                            </div>
                            <div class="col-xs-8">
                                <input type="text" name="captcha" class="form-control" autocomplete="off" placeholder="Captcha" id="capcha">
                                {{$errors->first('captcha','<p class="text-red">:message</p>')}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer">                    

                    <button type="submit" class="btn bg-olive btn-block waiting">Sign me up</button>

                    <a href="{{URL::to('login')}}" class="text-center">I already have a membership</a>
                </div>
            {{Form::close()}}

            {{-- <div class="margin text-center">
                <span>Register using social networks</span>
                <br/>
                <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>
                <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>
                <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>

            </div> --}}
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>     
        <script type="text/javascript">
        $('.number').tooltip('show')
        $(document).on('click','.waiting',function(){
            $('.waiting').addClass('disabled');
            $('.waiting').html('Waiting ...');
        })
        </script>
    </body>
</html>