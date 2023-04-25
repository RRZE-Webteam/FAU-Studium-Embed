<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Tests\Application;

use Fau\DegreeProgram\Output\Application\ArrayPropertiesAccessor;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ArrayPropertiesAccessorTest extends TestCase
{
    /**
     * @dataProvider dataProviderGet
     */
    public function testGet(array $array, string $path, mixed $value): void
    {
        $sut = new ArrayPropertiesAccessor();
        $this->assertSame($value, $sut->get($array, $path));
    }

    public function testGetNotExistedPath(): void
    {
        $this->expectException(RuntimeException::class);
        $sut = new ArrayPropertiesAccessor();
        $sut->get([], 'path.sub');
    }

    public function testSet(): void
    {
        $sut = new ArrayPropertiesAccessor();
        $array = [
            'path1' => 1,
            'path' => [
                'subpath' => 2,
            ],
        ];
        $sut->set($array, 'path.subpath', 3);
        $this->assertSame(3, $array['path']['subpath']);
    }

    public function testSetNewPath(): void
    {
        $sut = new ArrayPropertiesAccessor();
        $array = [
            'path1' => 1,
            'path' => [
                'subpath' => 2,
            ],
        ];

        $sut->set($array, 'non_existed_path', 3);
        $this->assertSame(3, $array['non_existed_path']);
    }

    public function testSetDoesNotAllowReplaceScalar(): void
    {
        $sut = new ArrayPropertiesAccessor();
        $array = [
            'path1' => 1,
            'path' => [
                'subpath' => 2,
            ],
        ];
        $sut->set($array, 'path.subpath.nested', 3);
        $this->assertSame(2, $array['path']['subpath']);
        $this->assertNotTrue(isset($array['path']['subpath']['nested']));
    }

    public static function dataProviderGet(): iterable
    {
        yield 'normal' => [[
            'path1' => 1,
            'path' => [
                'subpath' => 2,
            ],
        ], 'path.subpath', 2, ];
        yield 'whole' => [['array' => 1], '', ['array' => 1]];
    }
}
