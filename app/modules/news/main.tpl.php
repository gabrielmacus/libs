
<div class="post-list" data-row data-row-gutter="1%" data-row-l="4">

    <?php foreach ($data["results"] as $key => $result): ?>

        <article class="post block" data-col-l="3" data-position="relative">

            <h2 class="title">
                <?= $result["title"]; ?>
            </h2>

            <h3 class="subtitle">
                <?= $result["subtitle"];?>
            </h3>


            <?php if(!empty($result["_related"]["images"])): ?>
                <figure class="media">
                    <img  class="image" src="<?= $result["_related"]["images"][0]["src"] ?>">
                </figure>
            <?php endif; ?>

            <!--
            <h3 class="subtitle">
                <?= $result["body"]; ?>
            </h3>-->

        </article>

    <?php endforeach; ?>


</div>