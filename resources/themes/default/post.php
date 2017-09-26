<?php
require("partials/header.php");
?>



<h1><?= $args['title'] ?></h1>
<h3><?= $args['author'] ?></h3>
<small><?= $args['created_at'] ?></small>
<p><?= $args['content'] ?></p>


<?php
require("partials/footer.php");
?>