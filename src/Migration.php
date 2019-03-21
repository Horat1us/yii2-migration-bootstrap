<?php

declare(strict_types=1);

namespace Horat1us\Yii\Bootstrap;

use yii\console;
use yii\base;

/**
 * Class Migration
 * @package Horat1us\Yii\Bootstrap
 */
class Migration extends base\BaseObject implements base\BootstrapInterface
{
    use MigrationTrait {
        getId as private defaultId;
        getReference as private defaultReference;
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
    public $namespaces;

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

    /**
     * @throws base\InvalidConfigException
     */
    protected function getNamespaces(): array
    {
        if (is_string($this->namespaces)) {
            $this->namespaces = [$this->namespaces];
        }

        return $this->namespaces;
    }
}
