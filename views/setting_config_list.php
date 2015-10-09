<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title><?php $title; ?></title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- 可选的Bootstrap主题文件（一般不用引入） -->
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="/script/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="/script/bootstrap.min.js"></script>

    <script src="/script/html5shiv.min.js"></script>
    <script src="/script/respond.min.js"></script>

     <script src="/script/Chart.js"></script>

    <link href="/css/dashbord.css" rel="stylesheet">
  </head>
<body>
<?php echo $navbar_content; ?>
    <div class="container" style="margin-top:50px;">
        <div class="panel panel-default">
          <div class="panel-heading">配置列表</div>
          <div class="panel-body">
            <ul class="list-group">
            <?php foreach ($config_list as $value) {?>
              <li class="list-group-item">
                <?php echo $value['config_name'];?>
                <p class="text-right">
                    <a href="/show_update_setting_config?id=<?php echo $value['config_id']; ?>"><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                    <button type="button" class="btn btn-primary btn-sm" onclick="delete_config('<?php echo $value['config_id']?>', this)">删除</button>
                </p>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    // 删除配置
    function delete_config(id, button_object) {
        if (id) {
            // 弹框是否要删除
            var is_delete = window.confirm("你确认删除");
            if (is_delete) {
                $.post('/delete_setting_config', {id:id}, function(result){
                    // 判断是否有错误
                    if (result.error) {
                        alert(result.error);
                    }

                    // 判断是否成功
                    if (result.success) {
                        $(button_object).parent().parent().remove();
                    }
                }, "json");
            }
        } else {
            alert('删除失败，id为空!');
        }
    }

</script>
</html>
