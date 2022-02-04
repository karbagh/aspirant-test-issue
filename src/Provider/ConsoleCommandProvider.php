<?php declare(strict_types=1);

namespace App\Provider;

use App\Services\Apple\Trailers\AppleTrailerClientService;
use Doctrine\ORM\EntityManagerInterface;
use App\Command\{FetchDataCommand, RouteListCommand};
use App\Container\Container;
use App\Support\{CommandMap, ServiceProviderInterface};
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\RouteCollectorInterface;

class ConsoleCommandProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->set(RouteListCommand::class, static function (ContainerInterface $container) {
            return new RouteListCommand($container->get(RouteCollectorInterface::class));
        });

        $container->set(FetchDataCommand::class, static function (ContainerInterface $container) {
            return new FetchDataCommand($container->get(LoggerInterface::class), $container->get(EntityManagerInterface::class));
        });

        $container->get(CommandMap::class)->set(RouteListCommand::getDefaultName(), RouteListCommand::class);
        $container->get(CommandMap::class)->set(FetchDataCommand::getDefaultName(), FetchDataCommand::class);
    }
}
