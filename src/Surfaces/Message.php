<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces;

use SlackPhp\BlockKit\{FauxProperty,
    Property,
    Surfaces\MessageDirective\DeleteOriginal,
    Surfaces\MessageDirective\ReplaceOriginal,
    Surfaces\MessageDirective\ReplyBroadcast,
    Surfaces\MessageDirective\ResponseType,
    Surfaces\MessageDirective\UnfurlLinks,
    Surfaces\MessageDirective\UnfurlMedia};
use SlackPhp\BlockKit\Blocks\Block;
use SlackPhp\BlockKit\Collections\{AttachmentCollection, BlockCollection};
use SlackPhp\BlockKit\Hydration\OmitType;
use SlackPhp\BlockKit\Validation\{AllowedWhenPresent, RequiresAnyOf, UniqueIds, ValidCollection, ValidString};

/**
 * @see https://api.slack.com/surfaces
 */
#[OmitType, RequiresAnyOf('blocks', 'text', 'attachments')]
class Message extends Surface
{
    #[Property, ValidCollection(50, 0), UniqueIds]
    public BlockCollection $blocks;

    #[FauxProperty('response_type')]
    public ?ResponseType $responseType;

    #[FauxProperty('replace_original')]
    public ?ReplaceOriginal $replaceOriginal;

    #[FauxProperty('delete_original')]
    public ?DeleteOriginal $deleteOriginal;

    #[FauxProperty('unfurl_links')]
    public ?UnfurlLinks $unfurlLinks;

    #[FauxProperty('unfurl_media')]
    public ?UnfurlMedia $unfurlMedia;

    #[FauxProperty('reply_broadcast'), AllowedWhenPresent('threadTs')]
    public ?ReplyBroadcast $replyBroadcast;

    #[Property, ValidString]
    public ?string $text;

    #[Property, ValidCollection(10, 0)]
    public AttachmentCollection $attachments;

    #[Property]
    public ?bool $mrkdwn;

    #[Property('thread_ts'), ValidString]
    public ?string $threadTs;

    /**
     * @param BlockCollection|array<Block|string>|null $blocks
     * @param AttachmentCollection|array<Attachment>|null $attachments
     */
    public function __construct(
        BlockCollection|array|null $blocks = null,
        ?ResponseType $directive = null,
        ?string $text = null,
        AttachmentCollection|array|null $attachments = null,
        ?bool $mrkdwn = null,
        ?string $threadTs = null,
        ?bool $ephemeral = null,
        ?bool $replaceOriginal = null,
        ?bool $deleteOriginal = null,
        ?bool $unfurlLinks = null,
        ?bool $unfurlMedia = null,
        ?bool $replyBroadcast = null,
    ) {
        parent::__construct($blocks);
        $this->attachments = AttachmentCollection::wrap($attachments);
        $this->responseType($directive ?? ($ephemeral ? ResponseType::EPHEMERAL : null));
        $this->text($text);
        $this->mrkdwn($mrkdwn);
        $this->threadTs($threadTs);
        $this->replaceOriginal($replaceOriginal);
        $this->deleteOriginal($deleteOriginal);
        $this->unfurlLinks($unfurlLinks);
        $this->unfurlMedia($unfurlMedia);
        $this->replyBroadcast($replyBroadcast);
    }

    /**
     * Configures message to send privately to the user.
     *
     * This is default behavior for most interactions, and doesn't necessarily need to be explicitly configured.
     */
    public function ephemeral(): static
    {
        return $this->responseType(ResponseType::EPHEMERAL);
    }

    /**
     * Configures message to send to the entire channel.
     */
    public function inChannel(): static
    {
        return $this->responseType(ResponseType::IN_CHANNEL);
    }

    /**
     * Configures message to "replace_original" mode.
     */
    public function replaceOriginal(ReplaceOriginal|array|bool|null $replaceOriginal = true): static
    {
        $this->replaceOriginal = ReplaceOriginal::fromValue($replaceOriginal);

        return $this;
    }

    /**
     * Configures message to "delete_original" mode.
     */
    public function deleteOriginal(DeleteOriginal|array|bool|null $deleteOriginal = true): static
    {
        $this->deleteOriginal = DeleteOriginal::fromValue($deleteOriginal);

        return $this;
    }

    /**
     * Configures message to "unfurl_links" mode.
     */
    public function unfurlLinks(UnfurlLinks|array|bool|null $unfurlLinks = true): static
    {
        $this->unfurlLinks = UnfurlLinks::fromValue($unfurlLinks);

        return $this;
    }

    /**
     * Configures message to "unfurl_media" mode.
     */
    public function unfurlMedia(UnfurlMedia|array|bool|null $unfurlMedia = true): static
    {
        $this->unfurlMedia = UnfurlMedia::fromValue($unfurlMedia);

        return $this;
    }

    /**
     * Configures message to "reply_broadcast" mode.
     */
    public function replyBroadcast(ReplyBroadcast|array|bool|null $replyBroadcast = true): static
    {
        $this->replyBroadcast = ReplyBroadcast::fromValue($replyBroadcast);

        return $this;
    }

    public function responseType(ResponseType|array|null $responseType): static
    {
        $this->responseType = ResponseType::fromValue($responseType);

        return $this;
    }

    public function text(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function mrkdwn(?bool $mrkdwn): static
    {
        $this->mrkdwn = $mrkdwn;

        return $this;
    }

    public function threadTs(string|null $threadTs): static
    {
        $this->threadTs = $threadTs;

        return $this;
    }

    public function attachments(AttachmentCollection|Attachment|null ...$attachments): static
    {
        $this->attachments->append(...$attachments);

        return $this;
    }
}
