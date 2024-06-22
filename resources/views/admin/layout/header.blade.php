<!-- BEGIN HEADER -->
<meta name="csrf-token" content="{{ csrf_token()}}">
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            {{--<a href="{{url('/')}}">
                <img src="{{url('/')}}/assets/layouts/layout4/img/logo-light.png" alt="logo" class="logo-default"/> </a>--}}
            {{-- @if(auth()->user()->user_type_id==8)--}}
            <h1 style="color: white;font-weight: bold">GPCeR</h1>
            <div class="menu-toggler sidebar-toggler" style="margin-bottom: 10px">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
            {{--  @endif--}}
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <span class="username username-hide-on-mobile"> {{auth()->user()->name}} </span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            <img alt="" class="img-circle" src="{{url('/')}}/assets/layouts/layout4/img/images.jpg"/>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{url('user/profile')}}">
                                    <i class="icon-user"></i> My Profile </a>
                            </li>
                            <li>
                                <a href="{{url('/logout')}}">
                                    <i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
@push('css')
{{--    <style>
        @media (min-width: 992px) {
            .page-sidebar-closed .page-sidebar .page-sidebar-menu.page-sidebar-menu-closed > li > a {

                padding-top: 16px !important;
            }
        }

    </style>--}}
@endpush
@push('js')
<script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!}
        function show_alert_message(msg, status) {
            if (status)
                toastr.success(msg, "E-Pain Clinic");
            else
                toastr.error(msg, "E-Pain Clinic");


            toastr.options = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "500",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
</script>
@endpush

<!-- END HEADER -->
