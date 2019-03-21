<?php

declare(strict_types=1);

namespace Horat1us\Yii\Migration\Tests;

use PHPUnit\Framework\TestCase;
use Horat1us\Yii\Migration;
use yii\console;

/**
 * Class BootstrapTraitTest
 * @package Horat1us\Yii\Migration\Tests
 */
class BootstrapTraitTest extends TestCase
{
    public function testDefaultMethodValues(): void
    {
        $bootstrap = new class
        {
            use Migration\BootstrapTrait {
                append as public;
            }

            protected function getNamespaces(): array
            {
                return [__NAMESPACE__];
            }

            protected function getAliases(): array
            {
                return [];
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
