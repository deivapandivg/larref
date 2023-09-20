<!-- BEGIN: Header-->
<style type="text/css">

    .main-menu.menu-light .navigation > li ul li > a 
    {
        padding: 10px 18px 10px 30px !important;
    }
    .main-menu.menu-light .navigation > li ul .hover > a, .main-menu.menu-light .navigation > li ul:hover > a 
    {
      color: #313c6d; 
    }
    .main-menu.menu-light .navigation > li ul .active 
    {
        background: transparent; 
    }
    .main-menu.menu-light .navigation > li ul .active > a 
    {
    color: #fa626b;
    font-weight: 700; 
    }
    .main-menu.menu-light .navigation > li ul .hover > a:before {
          content: "";
          display: block;
          width: 7px;
          height: 7px;
          border-radius: 50%;
          background-color: #fa626b;
          border-color: #fa626b;
          position: absolute;
          left: 25px !important;
          top: 50%;
          -webkit-transform: translate(-10px, -50%);
          -moz-transform: translate(-10px, -50%);
          -ms-transform: translate(-10px, -50%);
          -o-transform: translate(-10px, -50%);
          transform: translate(-10px, -50%);
          opacity: 1;
          -webkit-transition: all 0.2s ease;
          -o-transition: all 0.2s ease;
          -moz-transition: all 0.2s ease;
          transition: all 0.2s ease; }
    .main-menu.menu-light .navigation > li ul .active > a:before 
    {
      content: "";
      display: block;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background-color: #fa626b;
      border-color: #fa626b;
      position: absolute;
      left: 25px !important;
      top: 50%;
      -webkit-transform: translate(-10px, -50%);
      -moz-transform: translate(-10px, -50%);
      -ms-transform: translate(-10px, -50%);
      -o-transform: translate(-10px, -50%);
      transform: translate(-10px, -50%);
      opacity: 1;
      -webkit-transition: all 0.2s ease;
      -o-transition: all 0.2s ease;
      -moz-transition: all 0.2s ease;
      transition: all 0.2s ease; 
    }
    .main-menu.menu-light .navigation > li ul .active .hover > a 
    {
        background-color: transparent; 
    }
    .avatar_else img {
    width: 100%;
    max-width: 100%;
    height: auto;
    border: 2px solid #fff !important;
    border-radius: 1000px; }
    .avatar img {
    width: 35px !important;
    height: 35px !important;
    border: 0 none;
    border-radius: 1000px; }
    .menu_hover:hover{
        color: white;
        background-color: #6967ce;
        border-radius: 25px;
    }
</style>
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="collapse navbar-collapse show" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"> </i></a></li>
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                    <li class="dropdown nav-item mega-dropdown d-none d-md-block"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="fa fa-cog fa-spin"></i></a>
                        <ul class="mega-dropdown-menu dropdown-menu row" style="padding: 10px;">
                            @if($access_menus!="") 
                                @foreach($master_get_menus as $master_get_menu)
                                    @if($master_get_menu->menu_group_id==12)
                                        <a class="col-md-3 menu_hover" href="{{ route($master_get_menu->menu_link) }}" style="padding: 15px;">
                                            <i class="{{ $master_get_menu->menu_icon }}"></i>
                                            <span class="menu-title" data-i18n="" value="{{ $master_get_menu->menu_id }}"><b>{{ $master_get_menu->menu_name }}</b></span>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </li>
                    <li class="nav-item">
                        <div class="input-group" style="margin-top: 10px;">
                            <div class="autocomplete">
                               <input type="text" class="form-control position-relative" name="search" id="SearchLeadHeader" placeholder="Search Lead" style="height: 35px;  border-radius: 0.50rem!important;" required>
                            </div>
                            <div class="input-group-prepend CentralSearchBtn">
                               <span class="input-group-text" style="height: 35px; background-color:#ffffff00;color: white;border: #ffffff00; padding: 10px !important;margin-left: -3px; "><i class="fa fa-search fa-2x"></i></span>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                            <i class="ficon ft-bell bell-shake" id="notification-navbar-link"></i>
                           @if($notifications_counts>0)
                                @if ($notifications_counts!=0) 
                                <span class="badge badge-pill badge-sm badge-danger badge-up badge-glow">{{ $notifications_counts }}</span>
                                @endif
                           @else
                                 <span class="badge badge-pill badge-sm badge-danger badge-up badge-glow">0</span>
                           @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <div class="arrow_box_right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span><div class="offset-8 text-white ReadAll"><button type="button" class="btn-sm btn-primary text-white"><i class="fa fa-check"></i><a class="text-white" href="{{ route('notifications') }}" id="ReadAll" value="{{ Auth::user()->id }}"><b>Read all</b></a></button></div></h6>
                                </li>
                                <li class="scrollable-container media-list w-100">
                                    @if($notifications_counts>0)
                                       @foreach($notifications as $notification)
                                       <div class="NotificationsUrl bg-white" value="{{ $notification->notification_id }}">
                                          <a href="{{ route($notification->url) }}">
                                             <div class="media">
                                                <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan"></i></div>
                                                <div class="media-body">
                                                    <h6 class="media-heading">{{ $notification->title }}</h6>
                                                    <p class="notification-text font-small-3 text-muted">{{ $notification->description }}</p>
                                                    <small>
                                                        <time class="media-meta text-muted">{{ $notification->created_at }}</time>
                                                    </small>
                                                </div>
                                             </div>
                                          </a>
                                       </div>
                                       @endforeach
                                    @else
                                       <div class="media">
                                         <div class="media-left align-self-center"><i class="fa fa-bell bell-shake icon-bg-circle bg-cyan"></i>
                                         </div>
                                          <div class="media-body">
                                             <p class="notification-text font-small-3 text-muted tex-center">0 Notification
                                             </p>
                                          </div>
                                      </div>
                                    @endif
                                 </li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center ViewAll" id="ViewAll" href="{{ route('notifications') }}" value="{{ Auth::user()->id }}"><b>View all</b></a></li>
                            </div>
                        </ul>
                    </li>
                    <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-mail"> </i></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <div class="arrow_box_right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span></h6>
                                </li>
                                <li class="scrollable-container media-list w-100"><a href="javascript:void(0)">
                                        <!-- <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="{{ asset('public/app-assets/images/portrait/small/avatar-s-19.png') }}" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Margaret Govan</h6>
                                                <p class="notification-text font-small-3 text-muted">I like your portfolio, let's start.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                            </div>
                                        </div> -->
                                    </a><a href="javascript:void(0)">
                                        <!-- <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-busy rounded-circle"><img src="{{ asset('public/app-assets/images/portrait/small/avatar-s-2.png') }}" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Bret Lezama</h6>
                                                <p class="notification-text font-small-3 text-muted">I have seen your work, there is</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Tuesday</time></small>
                                            </div>
                                        </div> -->
                                    </a><a href="javascript:void(0)">
                                       <!--  <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="{{ asset('public/app-assets/images/portrait/small/avatar-s-3.png') }}" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Carie Berra</h6>
                                                <p class="notification-text font-small-3 text-muted">Can we have call in this week ?</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Friday</time></small>
                                            </div>
                                        </div> -->
                                    </a><a href="javascript:void(0)">
                                        <!-- <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-away rounded-circle"><img src="{{ asset('public/app-assets/images/portrait/small/avatar-s-6.png') }}" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Eric Alsobrook</h6>
                                                <p class="notification-text font-small-3 text-muted">We have project party this saturday.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">last month</time></small>
                                            </div>
                                        </div> -->
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all messages</a></li>
                            </div>
                        </ul>
                    </li>
                    @if(Auth::user()->profile_upload!='')
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"> <span class="avatar avatar-online"><img src="{{ asset('public/profile_uploads/'.Auth::user()->profile_upload) }}" alt="avatar"><i></i></span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="arrow_box_right">
                                <a class="dropdown-item" href="#"><span class="avatar-online"><img src="{{ asset('public/profile_uploads/'.Auth::user()->profile_upload) }}" alt="avatar" class="img-circle avatar-50"><span class="user-name text-bold-700 ml-1">{{Auth::user()->first_name}}</span></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user_profile_edit') }}"><i class="ft-user"></i> Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <div class="dropdown-divider"></div><button type="submit" class="dropdown-item"><i class="ft-power"></i> Logout</button>     
                                </form>
                            </div>
                        </div>
                    </li>
                    @else
                    @php $firstStringCharacter = substr(Auth::user()->first_name, 0, 1);
                       $profilePic = array(
                          'a.png' => 'a',
                          'b.png' => 'b',
                          'c.png' => 'c',
                          'd.png' => 'd',
                          'e.png' => 'e',
                          'f.png' => 'f',
                          'g.png' => 'g',
                          'h.png' => 'h',
                          'i.png' => 'i',
                          'j.png' => 'j',
                          'k.png' => 'k',
                          'l.png' => 'l',
                          'm.png' => 'm',
                          'n.png' => 'n',
                          'o.png' => 'o',
                          'p.png' => 'p',
                          'q.png' => 'q',
                          'r.png' => 'r',
                          's.png' => 's',
                          't.png' => 't',
                          'u.png' => 'u',
                          'v.png' => 'v',
                          'w.png' => 'w',
                          'x.png' => 'x',
                          'y.png' => 'y',
                          'z.png' => 'z',
                       );
                       $searchkey = strtolower($firstStringCharacter);
                       $value = array_search($searchkey,$profilePic);
                     @endphp
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"> <span class="avatar avatar_else avatar-online"><img src="{{ asset('public/default_profile_images/'.$value) }}" alt="avatar"><i></i></span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="arrow_box_right">
                                <a class="dropdown-item" href="#"><span class="avatar-online"><img src="{{ asset('public/default_profile_images/'.$value) }}" alt="avatar" class="img-circle avatar-50"><span class="user-name text-bold-700 ml-1">{{Auth::user()->first_name}}</span></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user_profile_edit') }}"><i class="ft-user"></i> Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <div class="dropdown-divider"></div><button type="submit" class="dropdown-item"><i class="ft-power"></i> Logout</button>     
                                </form>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->

 <!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" data-img="{{ asset(Auth::user()->background_image) }}">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row position-relative">
            <li class="nav-item"><a class="navbar-brand" href="">
                <img class="brand-logo" alt="admin logo" src="{{ asset('public/accsource/logo1.png') }}"  style="width:155px; margin-top:-39px; margin-left: 20px;"/>
                   <!--  <h3 class="brand-text">Active +</h3> -->
                </a></li>
            <li class="nav-item d-none d-md-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-disc font-medium-3" data-ticon="ft-disc"></i></a></li>
            <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
    </div>
    <div class="navigation-background"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @if($access_menus!="") 
                @foreach($get_menus as $get_menu)
                   @if($get_menu->menu_group_id==1)
                    <li class="nav-item {{ request()->is($get_menu->menu_link) ? 'active' : '' }}">
                        <a href="{{ route($get_menu->menu_link) }}">
                            <i class="{{ $get_menu->menu_icon }}"></i>
                            <span class="menu-title" data-i18n="" value="{{ $get_menu->menu_id }}">{{ $get_menu->menu_name }}  </span>
                        </a>
                    </li>
                    @elseif($get_menu->menu_group_id!=1 && $get_menu->menu_group_id!=12)
                        @if(!in_array($get_menu->menu_group_id, $CompletedMenuGroupsArr))
                        @php array_push($CompletedMenuGroupsArr, $get_menu->menu_group_id); @endphp
                        <li class="nav-item" value="{{ $get_menu->menu_group_id }}"><a href=""><i class="{{ $get_menu->menu_group_icon }}"></i><span class="menu-title" data-i18n="">{{ $get_menu->menu_group_name }}</span></a>
                            <ul class="menu-content">
                                @foreach($child_menus as $child_menu)
                                    @if($get_menu->menu_group_id==$child_menu->menu_group_id)
                                        <li class="menu-item {{ request()->is($child_menu->menu_link) ? 'active' : '' }}" value="{{$child_menu->menu_group_id}}"><a class="menu-item" href="{{ route($child_menu->menu_link) }}"><i class="{{ $child_menu->menu_icon }}"></i>
                                        <span class="menu-title" data-i18n="">{{ $child_menu->menu_name }}  </span></a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>

<!-- END: Main Menu-->
