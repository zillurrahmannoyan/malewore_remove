 <div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper hide">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler"> </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>

            @if(Auth::user()->role != 'user')
            <li class="nav-item start dashboard">
                <a href="{{ URL::route('backend.dashboard.index') }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item sds">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-folder"></i>
                    <span class="title">SDS</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item start">
                        <a href="{{ URL::route('backend.sds.index') }}" class="nav-link ">
                            <span class="title">All Sheets</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('backend.sds.add') }}" class="nav-link ">
                            <span class="title">Add New</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item tds">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-folder"></i>
                    <span class="title">TDS</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item start">
                        <a href="{{ URL::route('backend.tds.index') }}" class="nav-link ">
                            <span class="title">All Sheets</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('backend.tds.add', ['v' => 2]) }}" class="nav-link ">
                            <span class="title">Add New</span>
                        </a>
                    </li>
                </ul>
            </li>

            @if(Auth::user()->role == 'admin')
            <li class="nav-item users">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">Users</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item start">
                        <a href="{{ URL::route('backend.users.index') }}" class="nav-link ">
                            <span class="title">All Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('backend.users.add') }}" class="nav-link ">
                            <span class="title">Add New</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="settings">
                <a href="{{ URL::Route('backend.settings.index') }}">
                <i class="icon-settings"></i>
                <span class="title">Settings</span>
                </a>
            </li>
            @endif
            
            @endif


        </ul>
        <!-- END SIDEBAR MENU -->

        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>

