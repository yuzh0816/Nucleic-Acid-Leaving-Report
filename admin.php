<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff; " />
        <meta name="viewport" content="width=device-width,initial-scale=1"/>
        <meta charset="utf-8">
        <title>核酸请假系统-管理端</title>
        <script src="js/echarts.min.js"></script>
        <link rel="shortcut icon" href="images/favicon.ico.png">
        <link href="css/main.css?version=0.1" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <a class="git-link" href="https://github.com/yuzh0816"></a>
        <div class="container">
            <h1>核酸请假系统-管理端</h1>
            <div id="main" style="width: calc( 100% );height:350px;overflow-x: scroll;"></div>
            
            <br>
            <?php
                header("X-Content-Type-Options:nosniff");
                include_once("src.php");
                
                $time=date("Y-m-d H:i:s");
                
                $readtable = "SELECT * FROM maindata";
                if(isset($_GET["searchName"]))
                {
                    $searchtable="SELECT * FROM `maindata` WHERE `name` LIKE \"%" . $_GET["searchName"] . "%\";";
                    $result = mysqli_query($conn, $searchtable);
                }
                else {
                    $result = mysqli_query($conn, $readtable);
                }
                
                $seperateline = false;
                $recorddaycounts = []; # Record the recent 7 days
                $recordday = []; # Record the recent 7 days' counts
                $adder = 0; # Record the number of one day
                $resname = array();
                $resleave_date = array();
                $resid = array();
                $resremove = array();
                
                // 输出数据
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        array_push($resid,$row["id"]);
                        array_push($resname,$row["name"]);
                        array_push($resleave_date,$row["leave_date"]);
                        array_push($resremove,$row["remove"]);
                    }
                }
                
                if($_POST["checkbox"]!="")
                {
                    $delcheckbox=count($_POST['checkbox']);
                    for($i=0;$i<$delcheckbox;$i++)
                    {
                        $delitem = $_POST['checkbox'][$i]; 
                        $changetable="UPDATE `maindata` SET `remove` = '1' WHERE `maindata`.`id` = " . $delitem . ";";
                        mysqli_query($conn, $changetable);
                        header("content-type:text/html;charset=utf-8");
                        header("refresh: 0"); 
                    }
                }
                
                echo "<form action=\"admin.php\" method=\"get\">
                    <input type=\"text\" name=\"searchName\" placeholder=\"查找姓名\">
                    <input type=\"submit\" value=\"搜索\">
                    </form><br>";
                
                echo "<form action=\"admin.php\" method=\"post\" name=\"formName\">
                    <input type=\"submit\" value=\"删除所选条目\" style=\"margin-bottom: 2em;font-size: 20px;padding: 10px;\">";
                for($i=count($resid)-1;$i>=0;$i--)
                {
                    //echo $resremove[$i];
                    if($resremove[$i]==0)
                    {
                        if( (strtotime($time)%86400-(strtotime($time)-strtotime($resleave_date[$i])))<0 &&  $seperateline==false)
                        {
                            echo "<div style=\"margin-bottom: 15px;margin-top: 10px;padding-bottom: 5px;border-bottom: 1px solid #c1c1c1;width: 100%;font-size: 10px;color: #7e7e7e;\">以上为今日请假人员，共计" . $adder .  "人</div>";
                            $seperateline=true;
                        }
                        if(count($recordday)<=7)
                        {
                            if( floor(strtotime($resleave_date[$i])/86400)-floor(strtotime($resleave_date[$i+1])/86400)!=0 )
                            {
                                array_push($recordday,date("Y-m-d",strtotime($resleave_date[$i+1])));
                                array_push($recorddaycounts,$adder);
                                $adder=1;
                            }
                            else
                            {
                                $adder+=1;
                            }
                        }
                        echo "<div class=\"block-container\">";
                        echo "<input type=\"checkbox\" name=\"checkbox[]\" value=\"" . (int)($i+1) .  "\">";
                        echo "<div class=\"listname\">姓名： " . $resname[$i]. "</div><div class=\"listleavetime\">请假时间： " . $resleave_date[$i]. "</div><br>";
                        echo "</input></div>";
                    }
                }
                
                array_splice($recorddaycounts,0,1);
                array_splice($recordday,0,1);
                if(count($recordday)<7)
                {
                    array_push($recordday,date("Y-m-d",strtotime($resleave_date[$i+1])));
                    array_push($recorddaycounts,$adder);
                }
                $recorddaycounts=array_reverse($recorddaycounts);
                $recordday=array_reverse($recordday);
                
                echo "</form>";
                echo "<script type=\"text/javascript\">
                  // 初始化echarts实例
                  var myChart = echarts.init(document.getElementById('main'));
                  var option = {
                      xAxis: {
                        type: 'category',
                        data: " . json_encode($recordday) ."
                      },
                      yAxis: {
                        type: 'value'
                      },
                      series: [
                        {
                          data: " . json_encode($recorddaycounts) . ",
                          type: 'line',
                          label: {
                            show: true,
                            position: 'bottom',
                            textStyle: {
                              fontSize: 14
                            }
                          }
                        }
                      ]
                    };
                  myChart.setOption(option);
                </script>";
                $conn->close();
            ?>
        </div>
        <br>由 @yuzh 强力驱动
        <br>本项目已<a href="https://github.com/yuzh0816/Nucleic-Acid-Leaving-Report">开源</a>
    </body>
</html>