<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use App\Services\Apple\Trailers\AppleTrailerDBService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Routing\RouteCollector;
use Twig\Environment;

final class HomeController extends Controller
{
    private AppleTrailerDBService $appleTrailerDBService;

    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private Environment $twig,
        private EntityManagerInterface $em,
        private RouteCollector $collector
    ) {
        $this->appleTrailerDBService = new AppleTrailerDBService();
    }

    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        try {
            $data = $this->twig->render('home/index.html.twig', $this->mergeWithData([
                'trailers' => $this->appleTrailerDBService->getAll($this->em),
            ],
            'index'));
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

    public function show(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        try {
            $data = $this->twig->render('home/movie/index.html.twig', $this->mergeWithData([
                'trailer' => $this->appleTrailerDBService->show((int) $_REQUEST['id'], $this->em),
            ],
                'index'));
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }
        $response->getBody()->write($data);

        return $response;
    }
}
