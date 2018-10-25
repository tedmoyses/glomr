<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>

<h2>Home page</h2>
<p>Conent for home page goes here</p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>