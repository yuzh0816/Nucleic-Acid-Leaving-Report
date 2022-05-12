<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff; " />
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <meta charset="utf-8">
        <title>核酸请假系统-初始化</title>
        <link rel="shortcut icon" href="images/favicon.ico.png">
        <link href="css/main.css?version=0.1" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <a class="git-link" href="https://github.com/yuzh0816"></a>
        <div class="container">
            <?php
                if($_GET["finish"]==1)
                {
                    echo "<script>"; 
                    echo "location.href='index.php'";
                    echo "</script>"; 
                }
                if(isset($_GET["from"])==0 && $_GET["finish"]!=1)
                {
                    include_once("src.php");
                }
                if(isset($_POST["username"]))
                {
                    if($_POST["servername"]!="") $servername = $_POST["servername"];
                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $dbname = $_POST["dbname"];
                    file_put_contents("src.php","");
                    file_put_contents("src.php","
                    <?php
                        \$servername = \"" . $servername.  "\";
                        \$username = \"" . $username. "\";
                        \$password = \"" . $password. "\";
                        \$dbname = \"" . $dbname. "\";
                        \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);
                    ?>
                    ");
                }
                echo "<h2>请配置数据库信息</h2>
                <form action=\"install.php?finish=1/\" method=\"post\" target=\"_parent\">
                数据库地址: <input type=\"text\" name=\"servername\" placeholder=\"可选，默认localhost\"><br>
                数据库用户名: <input type=\"text\" name=\"username\"><br>
                数据库密码：<input type=\"text\" name=\"password\"><br>
                数据库名称：<input type=\"text\" name=\"dbname\"><br>
                <input type=\"submit\" value=\"提交\">
                </form>";
            ?>
        </div>
        <br>由 @yuzh 强力驱动
        <br>本项目已<a href="https://github.com/yuzh0816/Nucleic-Acid-Leaving-Report">开源</a>
    </body>
</html>