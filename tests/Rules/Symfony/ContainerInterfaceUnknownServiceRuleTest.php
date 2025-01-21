<?php declare(strict_types = 1);

namespace PHPStan\Rules\Symfony;

use PHPStan\Node\Printer\Printer;
use PHPStan\Rules\Rule;
use PHPStan\Symfony\XmlServiceMapFactory;
use PHPStan\Testing\RuleTestCase;
use function class_exists;
use function interface_exists;

/**
 * @extends RuleTestCase<ContainerInterfaceUnknownServiceRule>
 */
final class ContainerInterfaceUnknownServiceRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new ContainerInterfaceUnknownServiceRule((new XmlServiceMapFactory(__DIR__ . '/container.xml'))->create(), self::getContainer()->getByType(Printer::class));
	}

	public function testGetPrivateService(): void
	{
		if (!class_exists('Symfony\Bundle\FrameworkBundle\Controller\Controller')) {
			self::markTestSkipped();
		}
		$this->analyse(
			[
				__DIR__ . '/ExampleController.php',
			],
			[
				[
					'Service "unknown" is not registered in the container.',
					25,
				],
			],
		);
	}

	public function testGetPrivateServiceInAbstractController(): void
	{
		if (!class_exists('Symfony\Bundle\FrameworkBundle\Controller\Controller')) {
			self::markTestSkipped();
		}

		$this->analyse(
			[
				__DIR__ . '/ExampleAbstractController.php',
			],
			[
				[
					'Service "unknown" is not registered in the container.',
					25,
				],
			],
		);
	}

	public function testGetPrivateServiceInLegacyServiceSubscriber(): void
	{
		if (!interface_exists('Symfony\Contracts\Service\ServiceSubscriberInterface')) {
			self::markTestSkipped('The test needs Symfony\Contracts\Service\ServiceSubscriberInterface class.');
		}

		$this->analyse(
			[
				__DIR__ . '/ExampleServiceSubscriber.php',
			],
			[],
		);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/../../../extension.neon',
		];
	}

}
