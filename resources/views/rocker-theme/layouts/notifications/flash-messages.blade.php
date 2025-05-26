<div>
    @if ($message = Session::get('success_message'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif

    @if ($message = Session::get('error_message'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif

    @if ($message = Session::get('errors'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif
    @if ($message = Session::get('error'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif
    @if ($message = Session::get('success'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif

    @if ($message = Session::get('warning'))
    <script>
        window.addEventListener('load', function() {
            Lobibox.notify('warning', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: '{{ $message }}'
            });
        });
    </script>
    @endif


    @if ($message = Session::get('warning_message'))
    <div class="p-4 mb-3 bg-warning-400 rounded">
        <p class="text-warning-800">{{ $message }}</p>
    </div>
    @endif
</div>