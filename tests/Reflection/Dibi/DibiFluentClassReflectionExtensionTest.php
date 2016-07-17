<?php declare(strict_types = 1);

namespace PHPStan\Reflection\Dibi;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;

class DibiFluentClassReflectionExtensionTest extends \PHPUnit_Framework_TestCase
{

	/** @var \PHPStan\Reflection\Dibi\DibiFluentClassReflectionExtension */
	private $extension;

	protected function setUp()
	{
		$this->extension = new DibiFluentClassReflectionExtension();
	}

	public function dataHasMethod(): array
	{
		return [
			[
				\Dibi\Fluent::class,
				true,
			],
			[
				\stdClass::class,
				false,
			],
		];
	}

	/**
	 * @dataProvider dataHasMethod
	 * @param string $className
	 * @param bool $result
	 */
	public function testHasMethod(string $className, bool $result)
	{
		$classReflection = $this->createMock(ClassReflection::class);
		$classReflection->method('getName')->will($this->returnValue($className));
		$this->assertSame($result, $this->extension->hasMethod($classReflection, 'select'));
	}

	public function testGetMethod()
	{
		$classReflection = $this->createMock(ClassReflection::class);
		$classReflection->method('getName')->will($this->returnValue(\Dibi\Fluent::class));
		$methodReflection = $this->extension->getMethod($classReflection, 'select');
		$this->assertSame('select', $methodReflection->getName());
		$this->assertSame($classReflection, $methodReflection->getDeclaringClass());
		$this->assertFalse($methodReflection->isStatic());
		$this->assertEmpty($methodReflection->getParameters());
		$this->assertTrue($methodReflection->isVariadic());
		$this->assertFalse($methodReflection->isPrivate());
		$this->assertTrue($methodReflection->isPublic());
		$this->assertInstanceOf(ObjectType::class, $methodReflection->getReturnType());
		$this->assertSame(\Dibi\Fluent::class, $methodReflection->getReturnType()->getClass());
		$this->assertFalse($methodReflection->getReturnType()->isNullable());
	}

}
