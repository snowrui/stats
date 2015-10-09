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
    <div class="container">
        <div class="bs-example" data-example-id="simple-jumbotron">
        <div class="jumbotron">
          <h1>欢迎来到金蛋统计系统</h1>
          <p></p>
            <form class="form-inline" action="/login" method="post">
              <div class="form-group">
                <label class="sr-only">Email address</label>
                <input type="text" class="form-control" name="username" placeholder="用户名" value="<?php echo $username ?>">
              </div>
              <div class="form-group">
                <label class="sr-only">Password</label>
                <input type="password" class="form-control" name="password" placeholder="密码" value="<?php echo $password ?>">
              </div>
              <button type="submit" class="btn btn-primary">登录</button>
              <span style="color:red;"><?php echo $error ?></span>
            </form>
        </div>
      </div>
    </div>
</body>
</html>
