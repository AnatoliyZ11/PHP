<?php
    session_start();

?>

<head>
    <link rel="stylesheet" href="css/main.css"/>
</head>
<?php

        if ($_SESSION["ERROR"] == 2){
            echo "<div class=''> Файл успешно загружен!</div>";
            unset($_SESSION["ERROR"]);
        }

        if ($_SESSION["ERROR"] == 1){
            echo "<div class=''> Неправильный формат!</div>";
            unset($_SESSION["ERROR"]);
        }
        if ($_SESSION["ERROR"] == 3){
            echo "<div class=''> Файл уже существует в системе!</div>";
            unset($_SESSION["ERROR"]);
        }
?>
<?php
    $dir = __DIR__ . '/prew/';
    $files1 = scandir($dir);
    foreach ($files1 as $value)
    {
        if ($value != '.' and $value != '..')
        {
            echo '<a href="img/' . $value . '" target="_blank" ><img src="prew/' . $value . '" alt="img" class="galary_img"></a>';
        }

    }
?>

<footer>

<body>
    <div>
        <form action="components/uploading.php" method="post" enctype="multipart/form-data" >
            <input type="file" name="filename[]" multiple>
            <input type="submit" value="Загрузить" />
        </form>
    </div>

</footer>
</body>
