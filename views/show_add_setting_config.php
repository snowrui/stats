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

    <!-- jsoneditor的核心文件 -->

    <link rel="stylesheet" type="text/css" href="/css/jsoneditor2.css">
    <script type="text/javascript" src="/script/jsoneditor2.js"></script>

    <!-- ace code editor -->
    <script type="text/javascript" src="/script/ace.js"></script>
    <script type="text/javascript" src="/script/mode-json.js"></script>
    <script type="text/javascript" src="/script/theme-textmate.js"></script>
    <script type="text/javascript" src="/script/theme-jsoneditor.js"></script>

    <!-- json lint -->
    <script type="text/javascript" src="/script/jsonlint.js"></script>
    <script type="text/javascript" src="/script/json2.js"></script>
  </head>
<body>
<?php echo $navbar_content; ?>
    <div class="container" style="margin-top:50px;">
        <div class="panel panel-default">
          <div class="panel-heading">添加配置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/settings">返回列表</a></div>
          <div class="panel-body">
            <form action="/settings" method="post">
              <div class="form-group">
                <div id="config_jsoneditor"></div>
                <input type="hidden" value="" name="config" id="config_value">
              </div>
              <button type="button" class="btn btn-primary" onclick="update_config()">添加</button>
            </form>
          </div>
        </div>
    </div>
</body>
<script type="text/javascript">

    $(function() {
        var request_editor;
        var result = <?php echo $config; ?>;
        var container = document.getElementById('config_jsoneditor');
        container.style.height = '500px';
        var options = {
            mode: 'form',
            modes: ['text', 'code', 'form', 'tree', 'view'], // allowed modes
            change: function() {
                var editor_text = request_editor.getText();
                $('#config_value').attr('value', editor_text);
            }
        };
        var editor = new jsoneditor.JSONEditor(container, options);
        request_editor = editor;
        editor.set(result);

        $('#config_value').attr('value', editor.getText());
    });

    function update_config() {
        var config_value = $('#config_value').attr('value');
        $.post('/add_setting_config', {config:config_value}, function(result){
            // 判断是否有错误
            if (result.error) {
                alert(result.error);
            }

            // 判断是否成功
            if (result.success) {
                alert(result.success);
            }
        }, "json");

        return false;
    }
</script>
</html>
