
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Imhanya - Log in</title>
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

        <div class="form-box" id="login-box">
            <div class="header">Sign In</div>
            <form action="{{URL::to('login')}}" method="POST">
                <div class="body bg-gray">
                    
                    @if(Session::has('error_login'))
                    <div class="alert alert-danger alert-dismissable" style="margin-left:0">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('error_login')}}
                    </div>
                    @endif

                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username"/>
                        {{$errors->first('username','<p class="text-red">:message</p>')}}
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                        {{$errors->first('password','<p class="text-red">:message</p>')}}
                    </div>          
                    {{-- <div class="form-group">
                        <input type="checkbox" name="remember_me"/> Remember me
                    </div> --}}
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block waiting">Sign me in</button>  
                    <p><a href="{{URL::to('forgot')}}">I forgot my password</a></p>
                    <a href="{{URL::to('register')}}" class="text-center">Register a new membership</a>
                </div>
            </form>

            {{-- <div class="margin text-center">
                <span>Sign in using social networks</span>
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
        $(document).on('click','.waiting',function(){
            $('.waiting').addClass('disabled');
            $('.waiting').html('Waiting ...');
        })
        </script>
    </body>
</html>