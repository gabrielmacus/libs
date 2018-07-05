
<div class="post-list" data-row data-row-gutter="1%" data-row-l="4">

    <?php foreach ($data["results"] as $key => $result): ?>

        <article class="post" data-col-l="3" data-row>

            <h2 class="title">
                <?= $result["title"]; ?>
            </h2>

            <h3 class="subtitle">
                <?= $result["body"]; ?>
            </h3>


            <?php if(!empty($result["_related"]["images"])): ?>
                <figure class="media">
                    <img class="image" src="<?= $result["_related"]["images"][0]["src"] ?>">
                </figure>
            <?php endif; ?>
        </article>

    <?php endforeach; ?>


</div>