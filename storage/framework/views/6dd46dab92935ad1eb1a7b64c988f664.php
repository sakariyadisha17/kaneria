<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        <?php if($message = Session::get('success')): ?>
            Swal.fire({
                title: 'Success!',
                text: "<?php echo e($message); ?>",
                icon: 'success',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        <?php endif; ?>

        <?php if($message = Session::get('error')): ?>
            Swal.fire({
                title: 'Error!',
                text: "<?php echo e($message); ?>",
                icon: 'error',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        <?php endif; ?>

        <?php if($message = Session::get('warning')): ?>
            Swal.fire({
                title: 'Warning!',
                text: "<?php echo e($message); ?>",
                icon: 'warning',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        <?php endif; ?>

        <?php if($message = Session::get('info')): ?>
            Swal.fire({
                title: 'Info!',
                text: "<?php echo e($message); ?>",
                icon: 'info',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                Swal.fire({
                    title: 'Error!',
                    text: "<?php echo e($error); ?>",
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    });
</script>
<?php /**PATH D:\wamp64\www\old_project\kaneria\resources\views/notifications.blade.php ENDPATH**/ ?>