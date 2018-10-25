<html>
  <head>
    <title>Custom site - <?php echo $__env->yieldContent('title'); ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed" rel="stylesheet">
    <?php
        $assets = array_map(function ($asset){
          switch(pathinfo($asset, PATHINFO_EXTENSION)){
            case "js":
              return '<script type="text/javascript" src="' . htmlentities($asset) .  '" /></script>';
            case "css":
              return '<link rel="stylesheet" href="' . htmlentities($asset) . '" />';
            default:
              return '';
          }
        }, (array) $assets);
        echo implode("\n", $assets);
      ?>
    <style>
      <?php echo $__env->yieldContent('pagecss'); ?>
    </style>
  </head>
  <body>
    <?php echo $__env->make('nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="container">
      <?php echo $__env->yieldContent('content'); ?>
    </div>
  </body>
</html>
