<?php
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";
    if(isset($_GET["from"])==0)
    {
        $url="install.php?from=src";
        echo "<script>"; 
        echo "location.href='" . $url ."'";
        echo "</script>"; 
    }
?>