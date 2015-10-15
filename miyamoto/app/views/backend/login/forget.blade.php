
<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>Imhanya - Forgot Password</title>
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
            <div class="header">Forgot Password</div>
            <form action="{{URL::to('forgot')}}" method="POST">
                <div class="body bg-gray">
                    
                    @if(Session::has('error_forgot'))
                    <div class="alert alert-danger alert-dismissable" style="margin-left:0">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('error_forgot')}}
                    </div>
                    @endif

                    @if(Session::has('success_forgot'))
                    <div class="alert alert-success alert-dismissable" style="margin-left:0">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{Session::get('success_forgot')}}
                    </div>
                    @endif

                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email Address"/>
                        {{$errors->first('email','<p class="text-red">:message</p>')}}
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block submit">Submit</button>
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
        $(document).on('click','.submit',function(){
            var $sub = $(this);
            $sub.addClass("disabled");
            $sub.html('Waiting ...');
        })
        </script>
    </body>
</html>