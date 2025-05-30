@section('footer')
    <!-- Toastr -->
    <script src="{{ url('vendor/toastr.min.js') }}" defer></script>
    <script src="{{ url('js/toastr.js') }}" defer></script>
@endsection
@if (session()->has('success'))
    <script>
        window.addEventListener('load', function() {
            msg = "<?php echo session('success'); ?>";
            toastr.success(msg, 'Success', {
                timeOut: 5000
            });
        });
    </script>
@endif
@if (session()->has('fail'))
    <script>
        window.addEventListener('load', function() {
            msg = "<?php echo session('fail'); ?>";
            toastr.success(msg, 'Failure', {
                timeOut: 5000
            });
        });
    </script>
@endif
