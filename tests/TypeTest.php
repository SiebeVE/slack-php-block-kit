<?php

namespace SlackPhp\BlockKit\Tests;

use SlackPhp\BlockKit\Blocks\Section;
use SlackPhp\BlockKit\{Component, Exception, Kit, Type};
use Throwable;

/**
 * @covers \SlackPhp\BlockKit\Type
 */
class TypeTest extends TestCase
{
    public function testCanMapDefinedElementClassToADefinedType(): void
    {
        $this->assertEquals(Type::SECTION, Type::fromClass(new Section()));
    }

    public function testThrowsErrorIfMappingClassesNotRegisteredInTypeMaps(): void
    {
        $unknownClass = new class () extends Component {};
        $this->expectException(Exception::class);
        Type::fromClass($unknownClass);
    }

    public function testCanMapDefinedElementTypeToADefinedClass(): void
    {
        $this->assertEquals(Section::class, Type::SECTION->toClass());
    }

    public function testThrowsErrorIfMappingTypesNotRegisteredInTypeMaps(): void
    {
        $this->expectException(Throwable::class);
        Type::fromValue('shoe');
    }
}
