<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<style type="text/css">
    .modal.left_modal,
    .modal.right_modal {
        position: fixed;
        z-index: 99999;
    }

    .modal.left_modal .modal-dialog,
    .modal.right_modal .modal-dialog {
        position: fixed;
        margin: auto;
        width: 32%;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    @media(max-width: 768px) {

        .modal.left_modal .modal-dialog,
        .modal.right_modal .modal-dialog {
            position: fixed;
            margin: auto;
            width: 60%;
            height: 100%;
            -webkit-transform: translate3d(0%, 0, 0);
            -ms-transform: translate3d(0%, 0, 0);
            -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
        }
    }

    .modal-dialog {
        /* max-width: 100%; */
        margin: 1.75rem auto;
    }

    @media (min-width: 576px) {
        .left_modal .modal-dialog {
            max-width: 100%;
        }

        .right_modal .modal-dialog {
            max-width: 100%;
        }
    }

    .modal.left_modal .modal-content,
    .modal.right_modal .modal-content {
        /*overflow-y: auto;
    overflow-x: hidden;*/
        height: 100vh !important;
    }

    .modal.left_modal .modal-body,
    .modal.right_modal .modal-body {
        padding: 15px 15px 30px;
    }

    .modal-backdrop {
        display: none;
    }

    /*Left*/
    .modal.left_modal.fade .modal-dialog {
        left: -50%;
        -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
        -o-transition: opacity 0.3s linear, left 0.3s ease-out;
        transition: opacity 0.3s linear, left 0.3s ease-out;
    }

    .modal.left_modal.fade.show .modal-dialog {
        left: 0;
        box-shadow: 0px 0px 19px rgba(0, 0, 0, .5);
    }

    /*Right*/
    .modal.right_modal.fade .modal-dialog {
        right: -50%;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }



    .modal.right_modal.fade.show .modal-dialog {
        right: 0;
        box-shadow: 0px 0px 19px rgba(0, 0, 0, .5);
    }

    /* ----- MODAL STYLE ----- */
    .modal-content {
        border-radius: 0;
        border: none;
    }



    .modal-header.left_modal,
    .modal-header.right_modal {

        padding: 10px 15px;
        border-bottom-color: #EEEEEE;
        background-color: #FAFAFA;
    }

    .modal_outer .modal-body {
        /*height:90%;*/
        overflow-y: auto;
        overflow-x: hidden;
        height: 91vh;
    }

    .vg-btn-ssp-sms {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #5d1996a6;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-whatsapp {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #164a12;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-mail {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #0061ffc9;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-call {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #ff8400c9;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-success {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #5ed84f;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-info {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #0dc9c6;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-primary {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #6967ce;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-danger {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #fa626b;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-warning {
        display: inline-block;
        padding-top: 3px;
        padding-bottom: 3px;
        padding-left: 5px;
        padding-right: 5px;
        background-color: #f59042;
        color: #fff;
        border-radius: 5px;
        width: 27px;
    }

    .vg-btn-ssp-warning:hover {
        color: #fff;
        border-color: #f59042;
        background-color: #f59042;
    }

    .form-section {
        line-height: 3rem;
        margin-bottom: 20px;
        color: #82a3de;
        border-bottom: 1px solid #9cb6e5;
    }

    #SearchLeadHeader {
        height: 35px !important;
        width: 318px !important;
        border-radius: 0.50rem !important;
    }

    @media (max-width: 767px) {
        #SearchLeadHeader {
            height: 35px !important;
            width: 140px !important;
            border-radius: 0.50rem !important;
        }
    }

    .modal-body-search {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: hidden;

    }

    .search-model-content {
        border-radius: 15px !important;
    }

    .modal-scroll,
    .modal-timeline {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;

    }

    .search-lead-heading {
        background: rgba(20, 90, 133, .1);
        color: #145A85;
        padding: 10px;
        border-left: 5px solid rgba(20, 90, 133);
    }

    .search-lead-view-button {
        width: 100px;
        background: #145A85 !important;
    }
</style>
<style type="text/css">
    .NotifHead {
        font-size: 14px;
        font-weight: bold;
    }

    .NotifDate {
        font-size: 10px;
    }
</style>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Active+') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('public/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/accsource/logo.png') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/forms/toggle/switchery.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/plugins/forms/switch.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-switch.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/charts/chartist.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/charts/chartist-plugin-tooltip.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/components.css') }}">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/menu/menu-types/vertical-menu-modern.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/chat-application.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/dashboard-analytics.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/timeline.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/css/pages/timeline.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/timeline/vertical-timeline.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/style.css') }}">
    <!-- END: Custom CSS-->

    <!-- BEGIN: New CDN-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/css/plugins/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/app-assets/fonts/simple-line-icons/style.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.css"
        integrity="sha512-gAU9FxrcktP/m5fRrn5P4FmIutdMP/kpVKsPerqffFy9gKQkR4cxrcrK3PtgTAgFiiN7b5+fwRbpCcO1F5cPew=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    




</head>

<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar" data-open="click"
    data-menu="vertical-menu-modern" data-color="{{ Auth::user()->navbar_color }}" data-col="2-columns">

    @include('layouts.navigation', [
        'menus' => $menus,
        'get_menus' => $get_menus,
        'menu_groups' => $menu_groups,
        'access_menus' => $access_menus,
        'child_menus' => $child_menus,
        'master_get_menus' => $master_get_menus,
    ])

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            <div class="content-body">
                {{ $slot }}
            </div>
            <div class="modal fade" id="view_model_show" tabindex="-1" role="dialog"
                aria-labelledby="view_model_show" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content search-model-content">
                        <section class="contact-form">
                            <div class="modal-header text-center bg-ghostwhite">
                                <h5 class="modal-title">Search Result</h5>
                                <button type="btn btn-danger" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                    x
                                </button>
                            </div>
                            <form method="post" action="">
                                @csrf
                                <div id="search_modal_form"></div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border  fixed-bottom navbar-shadow">
        <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span
                class="float-md-center d-block d-md-inline-block">{{ date('Y') }} &copy; Copyright <a
                    class="text-bold-800 grey darken-2" href="" target="_blank">Active+</a></span>
        </div>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('public/app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/vendors/js/forms/toggle/switchery.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('public/app-assets/js/scripts/forms/switch.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('public/app-assets/vendors/js/charts/chartist.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js') }}" type="text/javascript">
    </script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('public/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/js/core/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/js/scripts/customizer.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/vendors/js/jquery.sharrre.js') }}" type="text/javascript"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Data Table-->
    <script src="{{ asset('public/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('public/app-assets/js/scripts/tables/datatables/datatable-basic.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('public/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('public/app-assets/js/scripts/forms/select/form-select2.min.js') }}" type="text/javascript">
    </script>
    <!-- END: Data Table-->
    <script src="{{ asset('public/app-assets/js/scripts/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/app-assets/vendors/js/extensions/toastr.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script src="{{ asset('public/app-assets/js/scripts/drag_drop.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.all.js"
        integrity="sha512-LfOY+aB6HZ6FbLZlt2S4c+/aa0HHvh7noXwDm9wFPIzYZzeudQ/mGwDTASYfzf0lHDBOr5HB2/Zzau08kX5HmQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.{{ session('type') }}("{{ session('title') }} {{ session('message') }}");
        @endif


        function goBack() {
            window.history.back();
        }

        $(document).on('click', ".NotificationsUrl", function() {
            var notification_id = $(this).attr("Value");
            $.post("{{ route('notification_viewed') }}", {
                "_token": "{{ csrf_token() }}",
                "notification_id": notification_id
            }, function(data) {});
        });

        $(document).on('click', "#ReadAll", function() {
            var auth_id = $(this).attr("Value");
            $.post("{{ route('notification_all_read') }}", {
                "_token": "{{ csrf_token() }}",
                "auth_id": auth_id
            }, function(data) {});
        });

        $(document).on('click', "#ViewAll", function() {
            var auth_id = $(this).attr("Value");
            $.post("{{ route('notification_all_read') }}", {
                "_token": "{{ csrf_token() }}",
                "auth_id": auth_id
            }, function(data) {});
        });

        // $(document).ready(function(){
        // setInterval(function(){
        //     load_last_notification();
        // }, 10000);
        // function load_last_notification(){
        //     $.ajax({
        //         url:"{{ route('get_remainders') }}",
        //         method:"POST",
        //          data : {"_token":"{{ csrf_token() }}"},
        //         success:function(data){
        //             var RemainderId=data;

        //             if (RemainderId!=0)
        //             {
        //                 toastr["info"]("<i class='fas fa-bell'></i> You Have 1 Remainder !<br><br><a href=''><button class='btn btn-sm btn-success'>Do Now</button></a>&nbsp;<button class='btn btn-sm btn-danger Later' value="+RemainderId+">Later</button>");
        //             }
        //         }
        //     });
        // }
        // });

        $(document).on('click', ".Later", function() {
            var RemainderId = $(this).attr("value");
            alert(RemainderId);
            $.post("{{ route('get_remainder_later') }}", {
                _token: "{{ csrf_token() }}",
                RemainderId: RemainderId
            }, function(data) {
                toastr.warning('<p>Success <br>Remainder Added In Calendar</p>');
            });
        });

        // Search Starts Here
        $('#SearchLeadHeader').keypress(function(e) {
            var key = e.which;
            if (key == 13) {
                var selected_result = $("#SearchLeadHeader").val();
                search_items(selected_result)
            }
        });

        $(".CentralSearchBtn").click(function() {
            var selected_result = $("#SearchLeadHeader").val();
            search_items(selected_result);
        });

        function search_items(selected_result) {
            if (selected_result != "") {
                $.ajax({
                    url: "{{ route('search_model') }}",
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        selected_result: selected_result
                    },
                    success: function(data) {
                        $("#search_modal_form").html(data);
                        $("#view_model_show").modal('show');
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: '',
                    text: 'Please enter LeadId, MobileNumber, ClientCode or EmailId to get best search results...'
                });
            }
        }
        // Search Ends Here
        // setInterval(function(){
        // Get_Notifications();
        // }, 5000);

        // function Get_Notifications(){
        //    $.ajax({
        //       url:"{{ route('get_notifications_popup') }}",
        //       method:"POST",
        //       success:function(data){
        //          var Notificatins=data;
        //          if(Notificatins!=0)
        //          {
        //             var NotificatinsArray = Notificatins.split('*_*_*_*_*');
        //             var i;
        //             for (i = 0; i < NotificatinsArray.length; ++i)
        //             {
        //                toastr["info"](NotificatinsArray[i]);
        //                play_notification();
        //             }
        //          }
        //       }
        //    });
        // }
        // ClassicEditor
        //     .create(document.querySelector('#task-textarea'))
        //     .catch(error=>{
        //         console.error(error);
        //     });
    </script>
    {{ $page_level_scripts }}
    @include('sweetalert::alert')
</body>
<!-- END: Body-->

</html>
