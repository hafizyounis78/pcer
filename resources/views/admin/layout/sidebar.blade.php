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

        @php
            // echo 'user_id='.auth()->user()->id;
                 $class='';
                 if(auth()->user()->user_type_id!=8){
                     $class=' page-sidebar-menu-closed';
                 }
        @endphp
        <ul class="page-sidebar-menu {{$class}}  " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200">

            <li class="nav-item">
                <a href="{{url('home')}}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="arrow"></span>
                </a>
            </li>
        @if(auth()->user()->id!=100)<!-- 100=guest user -->
            <li class="nav-item">
                <a href="{{url('patient')}}" class="nav-link nav-toggle">
                    <i class="fa fa-search"></i>
                    <span class="title">Search Patient</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            <li class="nav-item start">
                <a href="{{url('patient/list')}}" class="nav-link nav-toggle">
                    <i class="icon-docs"></i>
                    <span class="title">Patient List</span>
                    <span class="arrow"></span>
                </a>
            </li>
            <li class="nav-item start">
                <a href="{{url('appointment')}}" class="nav-link nav-toggle">
                    <i class="icon-docs"></i>
                    <span class="title">Appointments</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @if((auth()->user()->user_type_id==8 ||auth()->user()->user_type_id==11) && auth()->user()->id!=100)
                <li class="nav-item start">
                    <a href="{{url('reports')}}" class="nav-link nav-toggle">
                        <i class="icon-eye"></i>
                        <span class="title">Reports</span>
                        <span class="arrow"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{url('project')}}" class="nav-link nav-toggle">
                        <i class="icon-social-dribbble"></i>
                        <span class="title">Projects</span>
                        <span class="arrow"></span>
                    </a>

                </li>
            @endif
            @if(auth()->user()->id!=100)<!-- 100=guest user -->
            <li class="nav-item start">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-bulb"></i>
                    <span class="title">Attendance</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item ">

                        <a href="{{url('attendance')}}" class="nav-link nav-toggle">
                            <span class="title">Attendance Sheet</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{url('attendance/create')}}" class="nav-link nav-toggle">
                            <span class="title">New Attendance</span>
                        </a>
                    </li>
                </ul>

            </li>
            @endif
            @if(auth()->user()->user_type_id==8 && auth()->user()->id!=100)
                <li class="heading">
                    <h3 class="uppercase">Setting</h3>
                </li>
                <li class="nav-item">
                    <a href="{{url('setting/s2')}}" class="nav-link nav-toggle">
                        <i class="icon-diamond"></i>
                        <span class="title">Lookups</span>
                        <span class="arrow"></span>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="{{url('user')}}" class="nav-link nav-toggle">
                        <i class="icon-user"></i>
                        <span class="title">Users</span>
                        <span class="arrow"></span>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="{{url('setting/table')}}" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">Tables</span>
                        <span class="arrow"></span>
                    </a>

                </li>
                <li class="nav-item">
                    <a href="{{url('setting/organization')}}" class="nav-link nav-toggle">
                        <i class="icon-flag"></i>
                        <span class="title">Organization</span>
                        <span class="arrow"></span>
                    </a>

                </li>

            @endif


        </ul>
        <!-- END SIDEBAR MENU -->


        <!-- BEGIN PROFILE SIDEBAR -->


    </div>


    <!-- END SIDEBAR -->
</div>
