<?php
// Диаграмма будет представлена для значений следующего массива:
$values = ['Max' => [2, 2, 5, 4, 6, 12, 10, 11, 10], 
           'Dima' => [8, 2, 9, 4, 6, 12, 10, 11, 10], 
           'Igor' => [4, 2, 5, 4, 6, 7, 10, 7, 10]];

// Количество столбцов диаграммы:
$columns = count($values['Max']); // Предполагаем, что все массивы имеют одинаковую длину


$width = 400; // Увеличено для красоты
$height = 200;
// Задаем пространство между колонками:
$padding = 5;


$group_width = $width / $columns;
$column_width = ($group_width - $padding) / count($values);


$im = imagecreate($width, $height);
$gray = imagecolorallocate($im, 0xcc, 0xcc, 0xcc);
$gray_lite = imagecolorallocate($im, 0xee, 0xee, 0xee);
$gray_dark = imagecolorallocate($im, 0x7f, 0x7f, 0x7f);
$white = imagecolorallocate($im, 0xff, 0xff, 0xff);
$colors = [
    'Max' => imagecolorallocate($im, 0xff, 0x00, 0x00), // Красный
    'Dima' => imagecolorallocate($im, 0x00, 0x00, 0xff), // Синий
    'Igor' => imagecolorallocate($im, 0x00, 0xff, 0x00)  // Зеленый
];

// Заполняем фон картинки
imagefilledrectangle($im, 0, 0, $width, $height, $white);


$maxv = 0;
foreach ($values as $user => $user_values) {
    foreach ($user_values as $value) {
        $maxv = max($value, $maxv);
    }
}

// Рисуем каждую колонку для каждого пользователя
for ($i = 0; $i < $columns; $i++) {
    $x_start = $i * $group_width;
    $user_index = 0;

    foreach ($values as $user => $user_values) {
        $column_height = ($height / 100) * (($user_values[$i] / $maxv) * 100);
        $x1 = $x_start + $user_index * $column_width;
        $y1 = $height - $column_height;
        $x2 = $x1 + $column_width - $padding;
        $y2 = $height;

        // Рисуем колонку
        imagefilledrectangle($im, $x1, $y1, $x2, $y2, $colors[$user]);

        // Для 3D эффекта
        imageline($im, $x1, $y1, $x1, $y2, $gray_lite);
        imageline($im, $x1, $y2, $x2, $y2, $gray_lite);
        imageline($im, $x2, $y1, $x2, $y2, $gray_dark);

        $user_index++;
    }
}


header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
