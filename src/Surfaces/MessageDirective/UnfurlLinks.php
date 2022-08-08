<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces\MessageDirective;

use SlackPhp\BlockKit\Exception;

enum UnfurlLinks
{
    case UNFURL_LINKS;
    case DONT_UNFURL_LINKS;

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return match ($this) {
            self::UNFURL_LINKS => ['unfurl_links' => 'true'],
            self::DONT_UNFURL_LINKS => ['unfurl_links' => 'false'],
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
            return $data ? self::UNFURL_LINKS : self::DONT_UNFURL_LINKS;
        }

        if (is_array($data)) {
            $data = array_filter($data);
            return match ($data) {
                ['unfurl_links' => 'true'] => self::UNFURL_LINKS,
                ['unfurl_links' => 'false'] => self::DONT_UNFURL_LINKS,
                [] => null,
                default => throw new Exception('Invalid Unfurl Links enum encountered: %s', [json_encode($data)]),
            };
        }

        return null;
    }
}
