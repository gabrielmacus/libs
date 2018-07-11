
<?php if(!empty($data["results"][0]["_related"]["mainBlock"])): ?>
<div class="main-post-list">

    <?php foreach($data["results"][0]["_related"]["mainBlock"] as $post): ?>
        <article class="post" >

            <h2 class="title"><?= $post["title"]?></h2>
            <h3 class="subtitle"><?= $post["subtitle"]?></h3>


            <figure class="media">
                <img class="image" src="<?= $post["_related"]["images"]["0"]["src"]; ?>">
            </figure>

            <!--
            <h3 class="subtitle">
                <strong>Nicolás Russo</strong> puso al descubierto lo que era un secreto a voces: los jugadores tenían incidencia en el armado del equipo a la hora de jugar los partidos del Mundial. Además, dijo que se respetará el contrato con el DT si no se quiere ir.            </h3>-->

        </article>
    <?php endforeach; ?>
</div>

<?php endif; ?>