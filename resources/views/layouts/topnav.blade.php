<div class="top_nav">
    <div class="nav_menu" >
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars" style="color: #FFFFFF;"></i></a>
            </div>

            <script type="text/javascript">
                $(function() {
                   $('body').removeClass('nav-md').addClass('nav-sm');
                   $('.left_col').removeClass('scroll-view').removeAttr('style');
                   $('#sidebar-menu li').removeClass('active');
                   $('#sidebar-menu li ul').slideUp();
                   });
            </script>

            <ul class="nav navbar-nav navbar-right">
                                <!-- User Account: style can be found in dropdown.less -->
                <!-- Messages: style can be found in dropdown.less-->

                <li class="">
                    <a href="javascript:;" id="btn1" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <!-- <img src="{{-- asset('images/img.jpg') --}}" alt=""><label style="color: #FFFFFF;">{{ $user_session->name }}</label> -->
                        <!-- <img src="http://ellipse.ptpjb.com/profiles/photo.do?uid={{ $user_session->username }}" alt=""> -->
                        <label style="color: #FFFFFF;">{{ $user_session->name }}</label>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul id="dropdown" class="dropdown-menu dropdown-usermenu pull-right">
                        <!-- <li><a href="javascript:;" style="color: #000000;"> Profile</a></li> -->
                        <li>
                            <a href="{{route('admin.user.view.view', ['id' => $user_session->id])}}">
                                <i class="fa fa-user pull-right" style="color: #000000;"></i> Profile </a></a>
                            </a>

                            {{-- <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form> --}}
                        </li>
                        <li>
                          <a href="{{ url('/logout') }}"
                             onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();" style="color: #000000";>
                              <i class="fa fa-sign-out pull-right" style="color: #000000;"></i> Log Out </a></a>
                          </a>

                          <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                          </form>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="javascript:;" id="btn2" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                       <label style="color: #FFFFFF;">Login Sebagai </label>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                        <ul id="dropdown2" class="dropdown-menu dropdown-usermenu pull-right">
                            @foreach($data_role_user as $row)
                                  <li>
                                    <a href="{{url('/switchrole/'.$row->id)}}" style="color: #000000;">
                                        {{$row->name}}
                                        <?php if ($user_session->current_id_roles == $row->id): ?>
                                            <i class="fa fa-check-circle" style="margin-left: 10px;"></i>
                                        <?php endif ?>
                                    </a>
                                  </li>
                            @endforeach
                    </ul>
                </li>
                @if(count($data_grupdiv_user)>0)
                <li class="">
                    <a href="javascript:;" id="btn2" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                       <label style="color: #FFFFFF;">Group Divisi Sebagai </label>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                        <ul id="dropdown2" class="dropdown-menu dropdown-usermenu pull-right">
                            @foreach($data_grupdiv_user as $row)
                                  <li>
                                    <a href="{{url('/switchrolegrupdiv/'.$row->id)}}" style="color: #000000;">
                                        {{$row->name}}
                                        <?php if ($user_session->current_grupdiv_id == $row->id): ?>
                                            <i class="fa fa-check-circle" style="margin-left: 10px;"></i>
                                        <?php endif ?>
                                    </a>
                                  </li>
                            @endforeach
                    </ul>
                </li>
                @endif

                @if($multi_sb)
                <li class="">
                    <a href="javascript:;" id="btn3" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                       <label style="color: #FFFFFF;">Strategi Bisnis </label>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                        <ul id="dropdown3" class="dropdown-menu dropdown-usermenu pull-right">
                            @foreach($data_sb as $row)
                                  <li>
                                    <a href="{{url('/switchsb/'.$row->id)}}" style="color: #000000;">
                                        {{$row->name}}
                                        <?php if ($user_session->distrik->strategi_bisnis->id == $row->id): ?>
                                            <i class="fa fa-check-circle" style="margin-left: 10px;"></i>
                                        <?php endif ?>
                                    </a>
                                  </li>
                            @endforeach
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
