<?php declare(strict_types = 1);

namespace PHPStan\Rules\Symfony;

use PHPStan\Node\Printer\Printer;
use PHPStan\Rules\Rule;
use PHPStan\Symfony\ConsoleApplicationResolver;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<UndefinedArgumentRule>
 */
final class UndefinedArgumentRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		return new UndefinedArgumentRule(new ConsoleApplicationResolver(__DIR__ . '/console_application_loader.php'), self::getContainer()->getByType(Printer::class));
	}

	public function testGetArgument(): void
	{
		$this->analyse(
			[
				__DIR__ . '/ExampleCommand.php',
			],
			[
				[
					'Command "example-rule" does not define argument "undefined".',
					42,
				],
			],
		);
	}

	public static function getAdditionalConfigFiles(): array
	{
		return [
			__DIR__ . '/argument.neon',
		];
	}

}
