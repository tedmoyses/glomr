<?php $__env->startSection('content'); ?>
<h1>Error!</h1>
<p>Something went wrong here - perhaps that file does not exist?</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>