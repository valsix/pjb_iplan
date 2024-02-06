<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PT PJB | RKAP ONLINE</title>

    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('build/css/custom.min.css') }}" rel="stylesheet">
</head>

<body class="login" style="background-image: url({{ asset('images/image4155.png') }});">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                 @if (session('message'))
                  <div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      {{ session('message') }}
                  </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    <h1>Login Form</h1>
                    {!! csrf_field() !!}
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <input type="text" name="username" class="form-control" placeholder="Username"  value="{{ old('username') }}" onkeydown="upperCaseF(this)" required="" />
                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>

                    <script type="text/javascript">
                      function upperCaseF(a){
                        setTimeout(function(){
                          a.value = a.value.toUpperCase();
                        }, 1);
                      }
                    </script>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" placeholder="Password" required="" />
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary form-control submit" style="background-color: #fff; color: #555555; margin-left: 0px;" type="submit" value="Log In">
                        <!-- <a class="reset_pass" href="#">Lost your password?</a> -->
                    </div>

                    <br>
                    <br>
                    <div class="clearfix"></div>

                    <div class="separator">
                        <!-- <p class="change_link">New to site?
                            <a href="#signup" class="to_register"> Create Account </a>
                        </p> -->

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <img src="{{ asset('images/logopjb.png') }}" style="max-width:200px;">

                            <h4>PT. Pembangkitan Jawa-Bali</h4>
                            <p>©2017 All Rights Reserved.</p>
                        </div>
                    </div>
                </form>

                <div>
                  <p>Recommended Browser -
                    <img src="{{ asset('images/chrome.png') }}" style="max-width:30px;"> Google Chrome
                    <br>
                    Minimal Version - 65
                  </p>
                </div>

            </section>
        </div>

        <div id="register" class="animate form registration_form">
            <section class="login_content">
                <form>
                    <h1>Create Account</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Username" required="" />
                    </div>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" required="" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" required="" />
                    </div>
                    <div>
                        <a class="btn btn-default submit" href="index.html">Submit</a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Already a member ?
                            <a href="#signin" class="to_register"> Log in </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>
