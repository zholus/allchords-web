<?php
declare(strict_types=1);

namespace App\Action\SongsReviews\Ajax;

use App\Action\Action;
use App\SongsReviews\Model\Artist;
use App\SongsReviews\Service\ReviewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetArtistsAction extends Action
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function __invoke(Request $request): Response
    {
        $page = (int)$request->get('page', 1);
        $artistTitle = $request->get('title');

        [$artists, $paginationData] = $this->reviewService->getArtistsPaginated($artistTitle, 10, $page);

        return new JsonResponse([
            'data' => array_map(
                fn(Artist $artist) => ['id' => $artist->getId(), 'title' => $artist->getTitle()],
                $artists
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
