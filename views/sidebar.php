
    <div class="container-fluid">
      <div class="row">

        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
<?php foreach ($sidebar as $k => $data) {?>
    <?php if ($data['is_show']) { ?>
        <?php if ($k == 'default') {?>
            <li><a href="/dashboard/add"><?php echo $data['name']; ?></a></li>
        <?php } else if ($data['id'] == Flight::request()->query->id || $data['id'] == $stas_id) {?>
        <li class="active"><a href="/dashboard?id=<?php echo $data['id']; ?>"><?php echo $data['name']; ?><span class="sr-only">(current)</span></a></li>
        <?php } else {?>
            <li><a href="/dashboard?id=<?php echo $data['id']; ?>"><?php echo $data['name']; ?></a></li>
        <?php } ?>
    <?php } ?>
<?php } ?>
          </ul>
<!--
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
          </ul>
-->
        </div>
