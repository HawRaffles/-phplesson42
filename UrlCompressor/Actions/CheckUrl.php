<?php

namespace Study\UrlCompressor\Actions;

class CheckUrl
{
    const TIMEOUT = 6;
    private static string $userAgent;
    private static array $statusCodes;
    public string $checkedUrl;
    public bool $urlType;

    public function __construct()
    {
        self::$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (HTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36';
        self::$statusCodes = [
            200 => true,
            301 => true,
            302 => true,
            404 => true
        ];
        $this->urlType = false;
        $this->checkedUrl = $this->CheckInput();
        $responseCode = $this->GetRequest();
        $this->ValidateResponse($responseCode);
    }

    private function CheckInput(): string
    {
        do {
            $input = readline('Введіть корректний URL: ');
        } while (!filter_var($input, FILTER_VALIDATE_URL));
        return $input;
    }

    private function GetRequest(): int
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->checkedUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
    }

    private function ValidateResponse(int $code): void
    {
        if (isset(self::$statusCodes[$code]))
            $this->urlType = true;
    }
}
