<?php

declare(strict_types=1);

namespace Horat1us\Yii\Migration;

use yii\console;
use yii\base;

/**
 * Trait BootstrapTrait
 * @package Horat1us\Yii\Migration
 */
trait BootstrapTrait
{
    /**
     * @throws base\InvalidConfigException
     */
    protected function append(
        console\Application $application
    ): void {
        $application->setAliases($this->getAliases());

        $reference = $this->getReference();
        if (!array_key_exists('class', $reference)) {
            throw new base\InvalidConfigException("Invalid reference, missing class name");
        }
        $reference['migrationNamespaces'] = $this->getNamespaces();

        $id = $this->getId();
        $controllerMap = &$application->controllerMap;

        if (!array_key_exists($id, $controllerMap)) {
            $controllerMap[$id] = $reference;
            return;
        }

        if (is_string($controllerMap[$id])) {
            $controllerMap[$id] = [
                'class' => $controllerMap[$id],
            ];
        }

        if (!array_key_exists('class', $controllerMap[$id])) {
            $controllerMap[$id]['class'] = $reference['class'];
        }

        if ($controllerMap[$id]['class'] !== $reference['class']) {
            throw new base\InvalidConfigException(
                "Incompatible {$application->id} controller map. Controller {$id} already configured as "
                . $controllerMap[$id]['class'] . ", {$reference['class']} expected"
            );
        }

        $controllerMap[$id]['migrationNamespaces'] = array_merge(
            $controllerMap[$id]['migrationNamespaces'] ?? [],
            $this->getNamespaces()
        );
    }


    /**
     * @return string[] migrations namespaces to append to controller migration namespaces
     * array keys SHALL NOT be defined for correct migrationNamespaces merge
     */
    abstract protected function getNamespaces(): array;

    /**
     * @return string[] aliases for migrations autoload
     * - key - alias name - migrations namespaces (`Horat1us/Yii/Migrations`)
     * - value - path - path to migrations folder (`@vendor/horat1us/package/migrations`)
     */
    abstract protected function getAliases(): array;

    /**
     * @return string controller ID to define route `php yii migrate`
     */
    protected function getId(): string
    {
        return 'migrate';
    }

    /**
     * @return array class reference
     */
    protected function getReference(): array
    {
        return [
            'class' => console\controllers\MigrateController::class,
        ];
    }
}
