<div class="navbar">
  <a href="/" title="title line" class="brand">Title link</a>
  <img src="assets/images/menu.svg" alt="responsive menu toggle" id="menu-icon"/>
  <nav>
      <a href="/history.html">Other page</a>
      <a href="/projects.html">More pages</a>
  </nav>
</div>

<?php echo $__env->make('header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
