<?php
declare(strict_types=1);

namespace App\Action\SongsReviews\Ajax;

use App\Action\Action;
use App\SongsReviews\Model\Genre;
use App\SongsReviews\Service\ReviewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetGenresAction extends Action
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function __invoke(Request $request): Response
    {
        $page = (int)$request->get('page', 1);
        $genreTitle = $request->get('title');

        [$genres, $paginationData] = $this->reviewService->getGenresPaginated($genreTitle, 10, $page);

        return new JsonResponse([
            'data' => array_map(
                fn(Genre $genreDto) => ['id' => $genreDto->getId(), 'title' => $genreDto->getTitle()],
                $genres
            ),
            'pagination' => [
                'current-page' => $paginationData->getCurrentPage(),
                'elements-on-page' => $paginationData->getElementsOnPage(),
                'total-elements-count' => $paginationData->getTotalElementsCount(),
                'total-pages-count' => $paginationData->getTotalPagesCount(),
            ]
        ]);
    }
}
