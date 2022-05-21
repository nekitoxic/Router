<?php

namespace App\Router;

class Responce
{
    protected int $statusCode;
    protected string $url;
    protected static string $content;
    
    public function __construct(int $statusCode, string $url, string $content = '')
    {
        $this->setStatusCode($statusCode ?? 200);
        $this->setUrl($url ?? '');
        $this->setContent($content);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContent(): string
    {
        return self::$content;
    }

    public function setContent(string $content): self
    {
        self::$content = $content;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'statusCode'    => $this->statusCode,
            'url'           => $this->url,
            'content'       => self::$content
        ];
    }
}