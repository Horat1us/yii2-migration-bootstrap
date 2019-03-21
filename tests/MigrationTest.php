<?php

declare(strict_types=1);

namespace Horat1us\Yii\Bootstrap\Tests;

use PHPUnit\Framework\TestCase;
use Horat1us\Yii\Bootstrap;
use yii\console;
use yii\base;
use yii\db;

/**
 * Class MigrationTraitTest
 * @package Horat1us\Yii\Bootstrap\Tests
 */
class MigrationTest extends TestCase
{
    protected const ID = 'test-migrate';

    /** @var console\Application */
    protected $app;

    /** @var Bootstrap\Migration */
    protected $bootstrap;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->createMock(console\Application::class);
        $this->bootstrap = new Bootstrap\Migration([
            'id' => static::ID,
            'reference' => [
                'class' => __CLASS__,
            ],
            'namespaces' => __NAMESPACE__,
        ]);
    }

    public function testInvalidReference(): void
    {
        $this->bootstrap->reference = []; // without class name

        $this->expectException(base\InvalidConfigException::class);
        $this->expectExceptionMessage("Invalid reference, missing class name");

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->bootstrap->bootstrap($this->app);
    }

    public function testConfigureNewReference(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertTrue($this->bootstrap->bootstrap($this->app));

        $this->assertArrayHasKey(static::ID, $this->app->controllerMap);
        $this->assertArrayHasKey('class', $this->app->controllerMap[static::ID]);
        $this->assertEquals(__CLASS__, $this->app->controllerMap[static::ID]['class']);
    }

    public function testSetNamespaces(): void
    {
        $this->app->controllerMap[static::ID] = [
            'migrationNamespaces' => [db\Migration::class],
        ];

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertTrue($this->bootstrap->bootstrap($this->app));

        $migrationNamespaces = &$this->app->controllerMap[static::ID]['migrationNamespaces'];
        $this->assertCount(2, $migrationNamespaces);
        $this->assertEquals(db\Migration::class, $migrationNamespaces[0]);
        $this->assertEquals(__NAMESPACE__, $migrationNamespaces[1]);
    }

    public function testAppendToStringReference(): void
    {
        $this->bootstrap->reference = [
            'class' => $this->app->controllerMap[static::ID] = console\controllers\MigrateController::class,
        ];

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertTrue($this->bootstrap->bootstrap($this->app));

        $this->assertIsArray($this->app->controllerMap[static::ID]);
        $this->assertEquals($this->bootstrap->reference + [
                'migrationNamespaces' => [__NAMESPACE__]
            ], $this->app->controllerMap[static::ID]);
    }

    public function testIncompatibleControllerMap(): void
    {
        $this->app->controllerMap[static::ID] = console\controllers\MigrateController::class;

        $this->expectException(base\InvalidConfigException::class);
        $this->expectExceptionMessage(
            'Incompatible  controller map. Controller test-migrate already configured as yii\console\controllers\MigrateController, Horat1us\Yii\Bootstrap\Tests\MigrationTest expected' // phpcs:ignoreLine
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->bootstrap->bootstrap($this->app);
    }

    public function testBootstrapNotConsoleApplication(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $app = $this->getMockBuilder(base\Application::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertFalse($this->bootstrap->bootstrap($app));
    }

    public function testDefaultConfiguration(): void
    {
        $this->bootstrap->id = null;
        $this->bootstrap->reference = null;
        $this->bootstrap->namespaces = __NAMESPACE__;

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertTrue($this->bootstrap->bootstrap($this->app));

        $this->assertEquals(
            [
                'class' => console\controllers\MigrateController::class,
                'migrationNamespaces' => [__NAMESPACE__,],
            ],
            $this->app->controllerMap['migrate']
        );
    }
}
