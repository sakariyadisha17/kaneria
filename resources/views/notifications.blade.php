<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        @if ($message = Session::get('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ $message }}",
                icon: 'success',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if ($message = Session::get('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ $message }}",
                icon: 'error',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if ($message = Session::get('warning'))
            Swal.fire({
                title: 'Warning!',
                text: "{{ $message }}",
                icon: 'warning',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if ($message = Session::get('info'))
            Swal.fire({
                title: 'Info!',
                text: "{{ $message }}",
                icon: 'info',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Swal.fire({
                    title: 'Error!',
                    text: "{{ $error }}",
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
            @endforeach
        @endif
    });
</script>
