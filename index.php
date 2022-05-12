<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff; " />
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <meta charset="utf-8">
        <title>核酸请假系统</title>
        <link rel="shortcut icon" href="images/favicon.ico.png">
        <link href="css/main.css?version=0.1" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <a class="git-link" href="https://github.com/yuzh0816"></a>
        <div class="container">
        <h1>核酸请假系统</h1>
        <div style="font-size: 25px;margin-bottom: 0.5rem;">Tips: 请假三十分钟后自动删除。</div>
        <br>
        <?php
            include_once("src.php");
            header("X-Content-Type-Options:nosniff");
            $time=date("Y-m-d H:i:s");
            $maxid=1;
            
            $readtable = "SELECT * FROM maindata";
            $result = mysqli_query($conn, $readtable);
            $atype = gettype($readtable);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    if(strtotime($row["leave_date"])+1800>=strtotime($time) && $row["remove"]!=1)
                    {
                        echo "姓名： " . $row["name"]. " - 请假时间： " . $row["leave_date"]. "<br>";
                    }
                    $maxid=(int)$row["id"];
                }
            }
            
            $maxid+=1;
            
            if($_COOKIE["myCookies"]==1)
            {
                echo "请勿重复提交！<br>";
            }
            else
            {
                $inserttable = "INSERT INTO maindata (id, name, leave_date) VALUES ('" . $maxid ."', '" . $_POST["fname"] . "', '" . $time . "')";
                if($_POST["fname"]!="")
                {
                    mysqli_query($conn, $inserttable);
                    echo "提交成功！";
                    SetCookie("myCookies",True, time()+15);
                    header("content-type:text/html;charset=utf-8");
                    header("refresh: 0"); 
                }
                $_POST["fname"]="";
            }
            
            $conn->close();
        ?>
        <h2 style="border-top: 1px solid #cfcfcf;padding-top: 15px;">我要请假！</h2>
        <form action="index.php" method="post" target="_parent">
            姓名: <input type="text" name="fname">
            <input type="submit" value="提交">
        </form>
        </div>
        <br>由 @yuzh 强力驱动
        <br>本项目已<a href="https://github.com/yuzh0816/Nucleic-Acid-Leaving-Report">开源</a>
    </body>
</html>