<?php

namespace SoloSearch\Tests;

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\App;
use Slim\Factory\AppFactory;

abstract class TestCase extends BaseTestCase
{
    protected ?App $app = null;
    protected ?Container $container = null;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app = $this->createApp();
        $this->container = $this->app->getContainer();
    }

    protected function createApp(): App
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
        $container = $containerBuilder->build();

        AppFactory::setContainer($container);
        return AppFactory::create();
    }

    protected function tearDown(): void
    {
        $this->app = null;
        $this->container = null;
        parent::tearDown();
    }
}
