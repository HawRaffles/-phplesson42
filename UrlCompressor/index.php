<?php

require_once __DIR__ . '/../autoload.php';

use Study\UrlCompressor\Actions\CheckUrl;
use Study\UrlCompressor\Actions\ConvertUrl;

do {
    $inputType = readline('Оберіть тип завдання (1 - скоротити URL;  2 - відновити URL; 0 - вихід): ');
} while (!in_array($inputType, [0,1,2]));

try {
    $urlData = new ConvertUrl();
    switch ($inputType) {
        case 0:
            exit();
        case 1:
            do {
                $validUrl = new CheckUrl();
            } while (!$validUrl->urlType);
            echo 'Ваш скорочений URL: ' . $urlData->encode($validUrl->checkedUrl);
            break;
        case 2:
            $inputCode = readline('Введіть закодований URL: ');
            echo 'Ваш відновлений URL: ' . $urlData->decode($inputCode);
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
} catch (Error $error) {
    echo $error->getMessage();
}

echo PHP_EOL;
