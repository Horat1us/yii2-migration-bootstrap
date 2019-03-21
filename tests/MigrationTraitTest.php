<?php

declare(strict_types=1);

namespace Horat1us\Yii\Bootstrap\Tests;

use PHPUnit\Framework\TestCase;
use Horat1us\Yii\Bootstrap;
use yii\console;

/**
 * Class MigrationTratiTest
 * @package Horat1us\Yii\Bootstrap\Tests
 */
class MigrationTraitTest extends TestCase
{
    public function testDefaultMethodValues(): void
    {
        $bootstrap = new class
        {
            use Bootstrap\MigrationTrait {
                append as public;
            }

            protected function getNamespaces(): array
            {
                return [__NAMESPACE__];
            }
        };

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @var console\Application $app */
        $app = $this->getMockBuilder(console\Application::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $bootstrap->append($app);

        $this->assertEquals(
            [
                'class' => console\controllers\MigrateController::class,
                'migrationNamespaces' => [__NAMESPACE__,],
            ],
            $app->controllerMap['migrate']
        );
    }
}
