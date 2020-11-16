<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(config('EVENT_TYPE_THEME') == 'flofest'): ?>
    <title>Flofest</title>
    <?php else: ?>
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <link rel="icon" href="<?php echo e(asset('img/logo-1-150x150.png')); ?>" sizes="32x32" />
    <link rel="icon" href="<?php echo e(asset('img/logo-1-300x300.png')); ?>" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo e(asset('img/logo-1-300x300.png')); ?>" />
    <meta name="msapplication-TileImage" content="<?php echo e(asset('img/logo-1-300x300.png')); ?>" />
    <?php endif; ?>

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->
    <link href="<?php echo e(asset('css/normalize.css?v=1.1')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/appv1.css?v=1.1')); ?>" rel="stylesheet">
</head>

<body class="theme-<?php echo e(config('EVENT_TYPE_THEME', 'htf')); ?>">
    <div id="app">

        <app></app>
    </div>
    <script>
        window.locale = '<?php echo e(app()->getLocale()); ?>';

        window.appConfigs = {
            'theme': "<?php echo e(config('EVENT_TYPE_THEME', 'htf')); ?>"
        }
    </script>

    <script src="<?php echo e(mix('js/appv2.js')); ?>"></script>
</body>

</html><?php /**PATH /home/kaperskyguru/Projects/PHPProjects/inscriptions/resources/views/spa.blade.php ENDPATH**/ ?>