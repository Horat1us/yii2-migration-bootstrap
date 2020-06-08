<?php

declare(strict_types=1);

namespace Horat1us\Yii\Migration;

use yii\console;
use yii\base;

/**
 * Class Bootstrap
 * @package Horat1us\Yii\Migration
 */
class Bootstrap extends base\BaseObject implements base\BootstrapInterface
{
    use BootstrapTrait {
        getId as private defaultId;
        getReference as private defaultReference;
        getNamespaces as private defaultNamespaces;
        getAliases as private defaultAliases;
    }

    /**
     * @see MigrationTrait::getId()
     * @var string|null
     */
    public $id = null;

    /**
     * @see MigrationTrait::getReference();
     * @var array|null
     */
    public $reference = null;

    /**
     * @see MigrationTrait::getNamespaces()
     * @var string[]|string
     */
    public $namespaces = [];

    /**
     * @see BootstrapTrait::getAliases()
     * @var string[]|string
     */
    public $aliases = [];

    /**
     * @throws base\InvalidConfigException
     */
    public function bootstrap($app): bool
    {
        if (!$app instanceof console\Application) {
            return false;
        }

        $this->append($app);

        return true;
    }

    protected function getId(): string
    {
        if (is_null($this->id)) {
            return $this->defaultId();
        }

        return $this->id;
    }

    protected function getReference(): array
    {
        if (is_null($this->reference)) {
            return $this->defaultReference();
        }

        return $this->reference;
    }

    protected function getNamespaces(): array
    {
        if (empty($this->namespaces)) {
            return $this->namespaces = $this->defaultNamespaces();
        }
        if (is_string($this->namespaces)) {
            $this->namespaces = [$this->namespaces];
        }

        return $this->namespaces;
    }

    protected function getAliases(): array
    {
        if (empty($this->aliases)) {
            return $this->aliases = $this->defaultAliases();
        }
        return $this->aliases;
    }
}
