<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Validation;

use Attribute;
use SlackPhp\BlockKit\Component;
use SlackPhp\BlockKit\Parts\Text;

use SlackPhp\BlockKit\Surfaces\Message;
use function mb_strlen;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AllowedWhenPresent implements PropertyRule
{
    public function __construct(
        private string $propertyName,
    ) {
    }

    public function check(Component $component, string $field, mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($component->{$this->propertyName} === null) {
            throw new ValidationException(
                'The "%s" field is only allowed when "%s" is not null.',
                [$field, $this->propertyName],
            );
        }
    }
}
