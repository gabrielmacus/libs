<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


<?php if(!empty($header)): ?>
<header>
    <?php include ($header);?>
</header>
<?php endif;?>

<?php if(!empty($left)): ?>
<aside id="left-bar"><?php include ($left); ?></aside>
<?php endif; ?>

<main></main>

<?php if(!empty($right)): ?>
<aside id="right-bar"><?php include ($right); ?></aside>
<?php endif; ?>


<?php if(!empty($footer)): ?>
    <footer>
        <?php include ($footer);?>
    </footer>
<?php endif;?>









</body>
</html>