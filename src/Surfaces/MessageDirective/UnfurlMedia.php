<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces\MessageDirective;

use SlackPhp\BlockKit\Exception;

enum UnfurlMedia
{
    case UNFURL_MEDIA;
    case DONT_UNFURL_MEDIA;

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return match ($this) {
            self::UNFURL_MEDIA => ['unfurl_media' => 'true'],
            self::DONT_UNFURL_MEDIA => ['unfurl_media' => 'false'],
        };
    }

    /**
     * @param self|array<string, string>|bool|null $data
     * @return static|null
     */
    public static function fromValue(self|array|bool|null $data): ?self
    {
        if ($data instanceof self) {
            return $data;
        }

        if (is_bool($data)) {
            return $data ? self::UNFURL_MEDIA : self::DONT_UNFURL_MEDIA;
        }

        if (is_array($data)) {
            $data = array_filter($data);
            return match ($data) {
                ['unfurl_media' => 'true'] => self::UNFURL_MEDIA,
                ['unfurl_media' => 'false'] => self::DONT_UNFURL_MEDIA,
                [] => null,
                default => throw new Exception('Invalid Unfurl Media enum encountered: %s', [json_encode($data)]),
            };
        }

        return null;
    }
}
