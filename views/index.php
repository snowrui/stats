
<?php echo $navbar_content; ?>
<?php echo $sidebar_content; ?>


<!-- 图表设置 -->
<?php if ($chart_data) { ?>
  <?php foreach ($chart_data as $chart_k => $chart_conf) {?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h2 class="sub-header"><?php echo $chart_conf['name']; ?></h2>
  <div style="width:100%">

        <div>

          <canvas id="canvas<?php echo $chart_k;?>" height="100" width="500"></canvas>
        </div>
  </div>
</div>

  <?php }?>

<?php }?>

<!-- 数据表显示 -->
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<!--
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzBEOEZEQiIvPjxnPjx0ZXh0IHg9Ijc0LjA0Njg3NSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojRkZGRkZGO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L2c+PC9zdmc+" data-holder-rendered="true">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzM5REJBQyIvPjxnPjx0ZXh0IHg9Ijc0LjA0Njg3NSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojMUUyOTJDO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L2c+PC9zdmc+" data-holder-rendered="true">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
          </div>

-->

<?php if ($conf) { // 如果设置连接功能?>
          <h2 class="sub-header"><?php echo $conf['name']; ?></h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <?php foreach ($field_names as $k => $v) {?>

                      <?php if (Flight::request()->query->orderby == $k) {?>
                                  <th><a href="?orderby=<?php echo $k;?>&order=<?php if (Flight::request()->query->order == 'ASC') {echo 'DESC';} else {echo 'ASC';}?>"><?php echo $v; ?></a></th>
                      <?php } else {?>
                                  <th><a href="?orderby=<?php echo $k;?>&order=DESC"><?php echo $v?></a></th>
                      <?php } ?>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $k => $row) {?>
                                <tr>
                    <?php foreach ($field_names as $k => $v) {?>

                            <?php if ($links) { // 如果设置连接功能?>

                                <?php foreach ($links as $link_k => $link_v) {?>
                                    <?php if ($link_k == $k) {?>
                                        <td><a href="<?php echo sprintf($link_v, $row[$k]);?>"><?php echo $row[$k]?></a></td>
                                    <?php } else {?>
                                        <td><?php echo $row[$k]?></td>
                                    <?php } ?>
                                <?php } ?>

                            <?php } else {?>

                                <td><?php echo $row[$k]?></td>
                            <?php } ?>

                    <?php } ?>
                                </tr>
                <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
<?php }?>

<?php if ($chart_data) { ?>
  <?php foreach ($chart_data as $chart_k => $chart_conf) {?>

  <script>
    //var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var lineChartData = {
      labels : [
        <?php echo $chart_conf['data']['x'];?>
      ],
      datasets : [
        {
          label: "My First dataset",

                    fillColor : "rgba(151,187,205,0.2)",
          strokeColor : "rgba(151,187,205,1)",
          pointColor : "rgba(151,187,205,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(151,187,205,1)",
          data : [
            <?php echo $chart_conf['data']['y'];?>
          ]
        },
        /*
        {
          label: "My Second dataset",
                    fillColor : "rgba(220,220,220,0.2)",
          strokeColor : "rgba(220,220,220,1)",
          pointColor : "rgba(220,220,220,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data : [
          randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()
          ]
        }
        */
      ]
    }
  window.onload = function(){
    var ctx = document.getElementById("canvas<?php echo $chart_k;?>").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
      responsive: true
    });
  }
  </script>
<?php } ?>

<?php } ?>



