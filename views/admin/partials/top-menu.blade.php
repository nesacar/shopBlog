<div class="top-menu">
    @if(false)
    <ul class="nav navbar-nav navbar-left">
        <li>
            <a href="javascript:void(0);" class="sidebar-toggle"><i class="icon-arrow-left"></i></a>
        </li>
        <li>
            <a href="#cd-nav" class="cd-nav-trigger"><i class="icon-support"></i></a>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="icon-settings"></i>
            </a>
            <ul class="dropdown-menu dropdown-md dropdown-list theme-settings" role="menu">
                <li class="li-group">
                    <ul class="list-unstyled">
                        <li class="no-link" role="presentation">
                            Fixed Header
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right fixed-header-check">
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="li-group">
                    <ul class="list-unstyled">
                        <li class="no-link" role="presentation">
                            Fixed Sidebar
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right fixed-sidebar-check">
                            </div>
                        </li>
                        <li class="no-link" role="presentation">
                            Horizontal bar
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right horizontal-bar-check">
                            </div>
                        </li>
                        <li class="no-link" role="presentation">
                            Toggle Sidebar
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right toggle-sidebar-check">
                            </div>
                        </li>
                        <li class="no-link" role="presentation">
                            Compact Menu
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right compact-menu-check" checked>
                            </div>
                        </li>
                        <li class="no-link" role="presentation">
                            Hover Menu
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right hover-menu-check">
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="li-group">
                    <ul class="list-unstyled">
                        <li class="no-link" role="presentation">
                            Boxed Layout
                            <div class="ios-switch pull-right switch-md">
                                <input type="checkbox" class="js-switch pull-right boxed-layout-check">
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="no-link"><button class="btn btn-default reset-options">Reset Options</button></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-coffee"></i>
            </a>
            <ul class="dropdown-menu dropdown-md dropdown-list theme-settings" role="menu">
                <li class="li-group">
                    <ul class="list-unstyled">
                        <li class="no-link" role="presentation"><a href="{{ url('admin/tables') }}"><i class="fa fa-wrench"></i>Baza podataka</a></li>
                        <li class="no-link" role="presentation"><a href="{{ url('admin/tables') }}"><i class="fa fa-bars"></i>Meni kreator</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    @endif
    <ul class="nav navbar-nav navbar-right">
        @if(false)
        <li>
            <a href="javascript:void(0);" class="show-search"><i class="icon-magnifier"></i></a>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope-open"></i><span class="badge badge-danger pull-right">6</span></a>
            <ul class="dropdown-menu title-caret dropdown-lg" role="menu">
                <li><p class="drop-title">You have 6 new  messages!</p></li>
                <li class="dropdown-menu-list slimscroll messages">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online on"></div>@if(false)<img class="img-circle" src="assets/images/avatar2.png" alt="">@endif</div>
                                <p class="msg-name">Michael Lewis</p>
                                <p class="msg-text">Yeah science!</p>
                                <p class="msg-time">3 minutes ago</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online off"></div>@if(false)<img class="img-circle" src="assets/images/avatar4.png" alt="">@endif</div>
                                <p class="msg-name">John Doe</p>
                                <p class="msg-text">Hi Nick</p>
                                <p class="msg-time">8 minutes ago</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online off"></div>@if(false)<img class="img-circle" src="assets/images/avatar3.png" alt="">@endif</div>
                                <p class="msg-name">Emma Green</p>
                                <p class="msg-text">Let's meet!</p>
                                <p class="msg-time">56 minutes ago</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online on"></div>@if(false)<img class="img-circle" src="assets/images/avatar5.png" alt="">@endif</div>
                                <p class="msg-name">Nick Doe</p>
                                <p class="msg-text">Nice to meet you</p>
                                <p class="msg-time">2 hours ago</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online on"></div>@if(false)<img class="img-circle" src="assets/images/avatar2.png" alt="">@endif</div>
                                <p class="msg-name">Michael Lewis</p>
                                <p class="msg-text">Yeah science!</p>
                                <p class="msg-time">5 hours ago</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="msg-img"><div class="online off"></div>@if(false)<img class="img-circle" src="assets/images/avatar4.png" alt="">@endif</div>
                                <p class="msg-name">John Doe</p>
                                <p class="msg-text">Hi Nick</p>
                                <p class="msg-time">9 hours ago</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="drop-all"><a href="#" class="text-center">All Messages</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-bell"></i><span class="badge badge-danger pull-right">3</span></a>
            <ul class="dropdown-menu title-caret dropdown-lg" role="menu">
                <li><p class="drop-title">You have 3 pending tasks!</p></li>
                <li class="dropdown-menu-list slimscroll tasks">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <div class="task-icon badge badge-success"><i class="fa fa-user"></i></div>
                                <span class="badge badge-roundless badge-default pull-right">1m</span>
                                <p class="task-details">New user registered</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="task-icon badge badge-primary"><i class="fa fa-refresh"></i></div>
                                <span class="badge badge-roundless badge-default pull-right">24m</span>
                                <p class="task-details">3 Charts refreshed</p>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="task-icon badge badge-danger"><i class="fa fa-phone"></i></div>
                                <span class="badge badge-roundless badge-default pull-right">24m</span>
                                <p class="task-details">2 Missed calls</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="drop-all"><a href="#" class="text-center">All Tasks</a></li>
            </ul>
        </li>
        @endif
        @if(\Auth::check())
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="user-name">{{ \Auth::user()->username }}<i class="fa fa-angle-down"></i></span>
                @if(false)<img class="img-circle avatar" src="assets/images/avatar1.png" width="40" height="40" alt="">@endif
            </a>
            <ul class="dropdown-menu dropdown-list" role="menu">
                @if(false)
                <li role="presentation"><a href="profile.html"><i class="icon-user"></i>Profile</a></li>
                <li role="presentation"><a href="calendar.html"><i class="icon-calendar"></i>Calendar</a></li>
                <li role="presentation"><a href="inbox.html"><i class="icon-envelope-open"></i>Inbox<span class="badge badge-success pull-right">4</span></a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation"><a href="lock-screen.html"><i class="icon-lock"></i>Lock screen</a></li>
                @endif
                <li role="presentation"><a href="{{ url('logout') }}"><i class="icon-key m-r-xs"></i>Log out</a></li>
            </ul>
        </li>
        @endif
        @if(false)
        <li>
            <a href="javascript:void(0);" id="showRight">
                <i class="icon-bubbles"></i>
            </a>
        </li>
        @endif
    </ul><!-- Nav -->
</div><!-- Top Menu -->