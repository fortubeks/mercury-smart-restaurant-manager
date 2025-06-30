<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{url('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!--plugins-->
    <link href="{{url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{url('assets/plugins/notifications/css/lobibox.min.css')}}" />
    <!-- loader-->
    <link href="{{url('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{url('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
    <link href="{{url('assets/css/app.css')}}" rel="stylesheet" />
    <link href="{{url('assets/css/icons.css')}}" rel="stylesheet" />
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{url('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{url('assets/css/semi-dark.css')}}" />
    <link rel="stylesheet" href="{{url('assets/css/header-colors.css')}}" />
    <title>{{env('APP_NAME')}}</title>
</head>

<body>
    <!--wrapper-->
    <div id="wrapper" class="wrapper">
        <!--sidebar wrapper -->
        @include('rocker-theme.layouts.partials.sidebar')
        <!--end sidebar wrapper -->
        <!--start header -->
        @include('rocker-theme.layouts.partials.header')
        <!--end header -->
        @include('rocker-theme.layouts.notifications.flash-messages')
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--start page content -->
                @yield('content')
                <!--end page content -->
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{now()->year}}. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->


    <!-- search modal -->

    <!-- end search modal -->




    <!--start switcher-->

    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>
    <!--plugins-->
    <script src="{{url('assets/js/jquery.min.js')}}"></script>
    <script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{url('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
    <script src="{{url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
    <script src="{{url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{url('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{url('assets/plugins/chartjs/js/chart.js')}}"></script>
    <!-- <script src="{{url('assets/js/index.js')}}"></script> -->
    <!--app JS-->
    <script src="{{url('assets/js/app.js')}}"></script>

    <script src="{{url('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>


    <!--notification js -->
    <script src="{{url('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
    <script src="{{url('assets/plugins/notifications/js/notifications.min.js')}}"></script>
    <!-- <script>
        new PerfectScrollbar(".app-container")
    </script> -->
    <script src="{{url('assets/js/helper.js')}}"></script>

    <script>
        window.addEventListener('load', function() {
            $(".dropdown-toggle").dropdown();
            $('input').click(function() {
                this.select();
            });

            $(".money").each(function() {
                let value = $(this).text().trim(); // Get the text inside the td
                if ($.isNumeric(value)) { // Check if it's a number
                    let formattedValue = new Intl.NumberFormat('en-NG', {
                        style: 'currency',
                        currency: 'NGN'
                    }).format(value);
                    $(this).text(formattedValue); // Set the formatted value back
                }
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{url('assets/plugins/select2/js/select2-custom.js')}}"></script>

    <script>
        $(".datepicker").flatpickr();

        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $('#current_shift').change(function() {
            // Get the selected date value
            var selectedDate = $(this).val();

            // Send a POST request to the controller
            $.ajax({
                url: "{{ url('shift/set') }}",
                method: 'POST',
                data: {
                    shift_date: selectedDate,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                },
                success: function(response) {
                    // Reload the current page
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle errors if needed
                }
            });
        });
        setInterval(function() {
            var currentDate = new Date();
            var options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true
            };
            var formattedDate = currentDate.toLocaleString('en-NG', options);
            $('#current-date').text(formattedDate);
        }, 1000); // Update every second
        window.addEventListener('load', function() {

            $('#outlet').change(function() {
                // Get the selected value
                var selectedOutletId = $(this).val();

                // Send an AJAX GET request to update session value
                $.ajax({
                    url: "{{ url('set-outlet') }}",
                    method: 'post',
                    data: {
                        outlet_id: selectedOutletId,
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                    },
                    success: function(response) {
                        window.location.reload(); // Reload the page after successful update
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Handle errors if needed
                    }
                });
            });
        });
    </script>
    <script>
        // Disable scroll wheel
        document.addEventListener('wheel', function(e) {
            if (document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        });

        // Optional: disable arrow keys (up/down)
        document.addEventListener('keydown', function(e) {
            if (
                document.activeElement.type === 'number' &&
                (e.key === 'ArrowUp' || e.key === 'ArrowDown')
            ) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>