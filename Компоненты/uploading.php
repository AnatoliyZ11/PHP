<?php
// Название <input type="file">
$filename = 'filename';

// Разрешенные расширения файлов.
$allow = array(
    'png',
    'jpeg',
    'jpg',
    'gif'
);

// Запрещенные расширения файлов.
$deny = array(
    'phtml',
    'php',
    'php3',
    'php4',
    'php5',
    'php6',
    'php7',
    'phps',
    'cgi',
    'pl',
    'asp',
    'aspx',
    'shtml',
    'shtm',
    'htaccess',
    'htpasswd',
    'ini',
    'log',
    'sh',
    'js',
    'html',
    'htm',
    'css',
    'sql',
    'spl',
    'scgi',
    'fcgi'
);

// Директория куда будут загружаться файлы.
$dir = "G:\OSPanel\domains\localhost";
$path = $dir . '\img\\';
$path_prew = $dir . '\prew\\';
//echo $path_prew;
$watermark = $dir . '\watermark\water.png';
$font = $dir . "\fonts\a_AssuanTitulStrDs.ttf";
$info = getimagesize($watermark);
$width_w = $info[0];
$height_w = $info[1];
$type_w = $info[2];
echo $_FILES[$filename];
if (isset($_FILES[$filename]))
{
    // Проверим директорию для загрузки.
    if (!is_dir($path))
    {
        mkdir($path, 0777, true);
    }
    // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
    $files = array();
    $diff = count($_FILES[$filename]) - count($_FILES[$filename], COUNT_RECURSIVE);
    if ($diff == 0)
    {
        $files = array(
            $_FILES[$filename]
        );
    }
    else
    {
        foreach ($_FILES[$filename] as $k => $l)
        {
            foreach ($l as $i => $v)
            {
                $files[$i][$k] = $v;
            }
        }
    }

    foreach ($files as $file)
    {
        $error = $success = '';

        // Проверим на ошибки загрузки.
        if (!empty($file['error']) || empty($file['tmp_name']))
        {
            switch (@$file['error'])
            {
                case 1:
                case 2:
                    $error = 'Превышен размер загружаемого файла.';
                break;
                case 3:
                    $error = 'Файл был получен только частично.';
                break;
                case 4:
                    $error = 'Файл не был загружен.';
                break;
                case 6:
                    $error = 'Файл не загружен - отсутствует временная директория.';
                break;
                case 7:
                    $error = 'Не удалось записать файл на диск.';
                break;
                case 8:
                    $error = 'PHP-расширение остановило загрузку файла.';
                break;
                case 9:
                    $error = 'Файл не был загружен - директория не существует.';
                break;
                case 10:
                    $error = 'Превышен максимально допустимый размер файла.';
                break;
                case 11:
                    $error = 'Данный тип файла запрещен.';
                break;
                case 12:
                    $error = 'Ошибка при копировании файла.';
                break;
                default:
                    $error = 'Файл не был загружен - неизвестная ошибка.';
                break;
            }
        }
        elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name']))
        {
            $error = 'Не удалось загрузить файл.';
        }
        else
        {
            // Оставляем в имени файла только буквы, цифры и некоторые символы.
            $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
            $name = mb_eregi_replace($pattern, '-', $file['name']);
            $name = mb_ereg_replace('[-]+', '-', $name);

            // Т.к. есть проблема с кириллицей в названиях файлов (файлы становятся недоступны).
            // Сделаем их транслит:
            $converter = array(
                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'e',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'y',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'sch',
                'ь' => '',
                'ы' => 'y',
                'ъ' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',

                'А' => 'A',
                'Б' => 'B',
                'В' => 'V',
                'Г' => 'G',
                'Д' => 'D',
                'Е' => 'E',
                'Ё' => 'E',
                'Ж' => 'Zh',
                'З' => 'Z',
                'И' => 'I',
                'Й' => 'Y',
                'К' => 'K',
                'Л' => 'L',
                'М' => 'M',
                'Н' => 'N',
                'О' => 'O',
                'П' => 'P',
                'Р' => 'R',
                'С' => 'S',
                'Т' => 'T',
                'У' => 'U',
                'Ф' => 'F',
                'Х' => 'H',
                'Ц' => 'C',
                'Ч' => 'Ch',
                'Ш' => 'Sh',
                'Щ' => 'Sch',
                'Ь' => '',
                'Ы' => 'Y',
                'Ъ' => '',
                'Э' => 'E',
                'Ю' => 'Yu',
                'Я' => 'Ya',
            );

            $name = strtr($name, $converter);
            $parts = pathinfo($name);

            if (empty($name) || empty($parts['extension']))
            {
                $error = 'Недопустимое тип файла';
            }
            elseif (!empty($allow) && !in_array(strtolower($parts['extension']) , $allow))
            {
                $error = 'Недопустимый тип файла';
            }
            elseif (!empty($deny) && in_array(strtolower($parts['extension']) , $deny))
            {
                $error = 'Недопустимый тип файла';
            }
            else
            {
                // Чтобы не затереть файл с таким же названием, добавим префикс.
                $i = 0;
                $prefix = '';
                while (is_file($path . $parts['filename'] . $prefix . '.' . $parts['extension']))
                {
                    $prefix = '(' . ++$i . ')';
                }
                $name = $parts['filename'] . $prefix . '.' . $parts['extension'];


                if (copy($file['tmp_name'], $path_prew . $name))
                {
                    $success = 'Файл «' . $name . '» успешно загружен.';
                    if (!move_uploaded_file($file['tmp_name'], $path . $name))
                    {
                        echo "Shit  " . $path . $name;
                    }
                }
                else
                {
                    $error = 'Не удалось загрузить файл.';
                }
            }
        }
        // Выводим сообщение о результате загрузки.
        if (!empty($success))
        {
            echo '<p>' . $success . '</p>';
        }
        else
        {
            echo '<p>' . $error . '</p>';
        }
        // работа с Изображениями
        $filename = $path . $name;
        $filename_prew = $path_prew . $name;

        $info = getimagesize($filename);
        $width = $info[0];
        $height = $info[1];
        $type = $info[2];

        switch ($type)
        {
            case 1:
                $img = imageCreateFromGif($filename);
                imageSaveAlpha($img, true);
                $img_prew = imageCreateFromGif($filename_prew);
                imageSaveAlpha($img_prew, true);
            break;
            case 2:
                $img = imageCreateFromJpeg($filename);
                $img_prew = imageCreateFromJpeg($filename_prew);
            break;
            case 3:
                $img = imageCreateFromPng($filename);
                imageSaveAlpha($img, true);
                $img_prew = imageCreateFromPng($filename_prew);
                imageSaveAlpha($img_prew, true);
            break;
        }

        // изменение размеров для превью
        $w = 500;
        $h = 0;

        if (empty($w))
        {
            $w = ceil($h / ($height / $width));
        }
        if (empty($h))
        {
            $h = ceil($w / ($width / $height));
        }

        $tmp = imageCreateTrueColor($w, $h);
        if ($type == 1 || $type == 3)
        {
            imagealphablending($tmp, true);
            imageSaveAlpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }

        $tw = ceil($h / ($height / $width));
        $th = ceil($w / ($width / $height));
        if ($tw < $w)
        {
            imageCopyResampled($tmp, $img_prew, ceil(($w - $tw) / 2) , 0, 0, 0, $tw, $h, $width, $height);
        }
        else
        {
            imageCopyResampled($tmp, $img_prew, 0, ceil(($h - $th) / 2) , 0, 0, $w, $th, $width, $height);
        }

        $img_prew = $tmp;

        // нанесение даты на превью и сохранение
        $black = imagecolorallocate($img_prew, 10, 10, 10);
        $back = imagecolorallocate($img_prew, 255, 255, 255);
        $text = "Сегодня";
        imagefilledrectangle($img_prew, 0, 0, 280, 28, $back);
        imagettftext($img_prew, 24, 0, 20, 20, $black, $font, $text);

        switch ($type)
        {
            case 1:
                imagegif($img_prew, $filename_prew);
            break;
            case 2:
                imagejpeg($img_prew, $filename_prew, 100);
            break;
            case 3:
                imagepng($img_prew, $filename_prew);
            break;
        }
        imagedestroy($img_prew);

        // работа с полным изображением
        $im1 = imageCreateFromPng($watermark);
        imageSaveAlpha($im1, true);

        imagecopyresampled($img, $im1, 10, 10, 0, 0, $width / 6, $height / 6, $width_w, $height_w);

        switch ($type)
        {
            case 1:
                imagegif($img, $filename);
            break;
            case 2:
                imagejpeg($img, $filename, 100);
            break;
            case 3:
                imagepng($img, $filename);
            break;
        }

        imagedestroy($img);

    }

}

 header('Location: ../index.php');
?>

