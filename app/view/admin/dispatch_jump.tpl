<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>kplphp极速极简后台框架</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="__PUBLICS__/favicon.ico"/>
    <style>
        *{
            padding: 0;
            margin: 0;
            list-style: none;
        }
        #he{
            /* width: 100%; */
            display: flex;/*弹性盒模型*/
            justify-content: center;/*主轴方向居中显示*/
            align-items: center;/*交叉轴方向居中显示*/
            height: 100vh;
            background-color: #232e6d;
        }
        ul{
            height: 200px;
        }
        li{
            float: left;
            width: 20px;
            height: 20px;
            border-radius: 20px;
            margin-right: 10px;
        }
        li:nth-child(1){
            background-color: #f62e74;
            animation: love1 4s infinite;
        }
        li:nth-child(2){
            background-color: #f45330;
            animation: love2 4s infinite;
            animation-delay: 0.15s;
        }
        li:nth-child(3){
            background-color: #ffc883;
            animation: love3 4s infinite;
            animation-delay: 0.3s;
        }
        li:nth-child(4){
            background-color: #30d268;
            animation: love4 4s infinite;
            animation-delay: 0.45s;
        }
        li:nth-child(5){
            background-color: #006cb4;
            animation: love5 4s infinite;
            animation-delay: 0.6s;
        }
        li:nth-child(6){
            background-color: #784697;
            animation: love4 4s infinite;
            animation-delay: 0.75s;
        }
        li:nth-child(7){
            background-color: #ffc883;
            animation: love3 4s infinite;
            animation-delay: 0.9s;
        }
        li:nth-child(8){
            background-color: #f45330;
            animation: love2 4s infinite;
            animation-delay: 1.05s;
        }
        li:nth-child(9){
            background-color: #f62e74;
            animation: love1 4s infinite;
            animation-delay: 1.2s;
        }
        @keyframes love1{
        30%,50%{height: 60px; transform: translateY(-30px);}
        75%,100%{height: 20px; transform: translateY(0);}
        }
        @keyframes love2{
        30%,50%{height: 125px; transform: translateY(-62.5px);}
        75%,100%{height: 20px; transform: translateY(0);}

        }
        @keyframes love3{
        30%,50%{height: 160px; transform: translateY(-75px);}
        75%,100%{height: 20px; transform: translateY(0);}
        }
        @keyframes love4{
        30%,50%{height: 180px; transform: translateY(-60px);}
        75%,100%{height: 20px; transform: translateY(0);}
        }
        @keyframes love5{
        30%,50%{height: 190px; transform: translateY(-45px);}
        75%,100%{height: 20px; transform: translateY(0);}
        }
    </style>

</head>
<body class="error-page bg-white">
<div>
    <div id="he">
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <div class="system-message">
        <?php switch ($code) {?>
        <?php case 1:?>
        <h1>:)</h1>
        <p class="success"><?php echo(strip_tags($msg));?></p>
        <?php break;?>
        <?php case 0:?>
        <h1>:(</h1>
        <p class="error"><?php echo(strip_tags($msg));?></p>
        <?php break;?>
        <?php } ?>
        <p class="detail"></p>
        <p class="jump">
            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
        </p>
    </div>
    <script type="text/javascript">
        (function(){
            // var wait = document.getElementById('wait'),
            //     href = document.getElementById('href').href;
            // var interval = setInterval(function(){
            //     var time = --wait.innerHTML;
            //     if(time <= 0) {
            //         location.href = href;
            //         clearInterval(interval);
            //     };
            // }, 1000);
        })();
    </script>
</div>

</body>
</html>