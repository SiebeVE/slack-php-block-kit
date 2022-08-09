<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces\MessageDirective;

use SlackPhp\BlockKit\Exception;

enum ReplyBroadcast
{
    case REPLY_BROADCAST;
    case DONT_REPLY_BROADCAST;

    /**
     * @return array<string, bool>
     */
    public function toArray(): array
    {
        return match ($this) {
            self::REPLY_BROADCAST => ['reply_broadcast' => true],
            self::DONT_REPLY_BROADCAST => ['reply_broadcast' => false],
        };
    }

    /**
     * @param self|array<string, bool>|bool|null $data
     * @return static|null
     */
    public static function fromValue(self|array|bool|null $data): ?self
    {
        if ($data instanceof self) {
            return $data;
        }

        if (is_bool($data)) {
            return $data ? self::REPLY_BROADCAST : self::DONT_REPLY_BROADCAST;
        }

        if (is_array($data)) {
            return match ($data) {
                ['reply_broadcast' => true] => self::REPLY_BROADCAST,
                ['reply_broadcast' => false] => self::DONT_REPLY_BROADCAST,
                ['reply_broadcast' => null] => null,
                default => throw new Exception('Invalid Reply Broadcast enum encountered: %s', [json_encode($data)]),
            };
        }

        return null;
    }
}
