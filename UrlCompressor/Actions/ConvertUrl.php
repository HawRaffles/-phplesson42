<?php

namespace Study\UrlCompressor\Actions;

use InvalidArgumentException;
use Study\UrlCompressor\Interfaces\IUrlDecoder;
use Study\UrlCompressor\Interfaces\IUrlEncoder;

class ConvertUrl implements IUrlEncoder, IUrlDecoder
{
    const FILE = __DIR__ . '/data';
    private static array $encodeData = [];

    public function __construct()
    {
        if (file_exists(self::FILE))
            self::$encodeData = json_decode(file_get_contents(self::FILE), true);
    }

    private static function SaveData(string $url, string $key)
    {
        if (!in_array($url, self::$encodeData))
            self::$encodeData[$key] = $url;

        $fileData = fopen(self::FILE, "w+");
        flock($fileData, LOCK_EX);
        fwrite($fileData, json_encode(self::$encodeData));
        fclose($fileData);
    }

    public function encode(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
            throw new InvalidArgumentException('Невалідний URL: ' . $url);
        $hash = md5($url);
        $key = substr($hash, 0, 6);
        self::SaveData($url, $key);
        return $key;
    }

    public function decode(string $code): string
    {
        if (!isset(self::$encodeData[$code]))
            throw new InvalidArgumentException('Скорочений URL відсутній в базі!');
        return self::$encodeData[$code];
    }
}
