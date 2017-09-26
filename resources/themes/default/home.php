<?php
require("partials/header.php");
?>
<a href="http://localhost/slim-blog/public/admin/dumphome">Re-Dump</a>
<ul>
<?php
// print_r($args['posts']);
foreach($args['posts'] as $post):?>
    <li><a href="blog/<?= $post['slug'] ?>.html">
    <?= $post['title'] ?></a>
     by <?= $post['user']['name']?>
    </li>
<?php endforeach;
?>
</ul>
<?php
require("partials/footer.php");
?>