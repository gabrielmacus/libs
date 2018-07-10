<!doctype html>
<html lang="es">
<head>

    <?php if(!empty($head)): ?>
        <?php include ($head);?>
    <?php endif; ?>
</head>
<body  class="<?= (!empty($bodyClasses))?implode(" ",$bodyClasses):""; ?>">


<?php if(!empty($header)): ?>
    <header >
        <?php include ($header);?>
    </header>
<?php endif;?>



<?php if(!empty($left)): ?>
    <aside    id="left-bar"><?php include ($left); ?></aside>
<?php endif; ?>

<main data-col-l="9">
    <?php if(!empty($main)): ?>
        <?php include ($main);?>
    <?php endif;?>
</main>

<?php if(!empty($right)): ?>
    <aside  data-col-l="3"  id="right-bar"><?php include ($right); ?></aside>
<?php endif; ?>


<?php if(!empty($footer)): ?>
    <footer>
        <?php include ($footer);?>
    </footer>
<?php endif;?>









</body>
</html>