<?php
declare(strict_types=1);

namespace App\Action\SongsReviews;

use App\Accounts\Service\AuthService;
use App\Action\Action;
use App\SongsReviews\Service\ReviewService;
use Assert\Assert;
use Symfony\Component\HttpFoundation\Request;

class NewReviewAction extends Action
{
    private AuthService $authService;
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService, AuthService $authService)
    {
        $this->authService = $authService;
        $this->reviewService = $reviewService;
    }

    public function __invoke(Request $request)
    {
        $title = $request->get('title');
        $artistsIds = $request->get('artist_id');
        $genresIds = $request->get('genre_id');
        $chords = $request->get('chords');

        try {
            Assert::lazy()
                ->that($title, 'title')->string()->notEmpty()
                ->that($artistsIds, 'artist_id')->notEmpty()->isArray()
                ->that($genresIds, 'genre_id')->notEmpty()->isArray()
                ->that($chords, 'chords')->notEmpty()
                ->verifyNow();

            $this->reviewService->newReview(
                $this->authService->getUser()->getUserId(),
                $artistsIds,
                $genresIds,
                $title,
                $chords
            );
        } catch (\Throwable $exception) {
            $this->addFlash('errors', $this->errorMessage($exception));

            return $this->redirectToRoute('new_review_page');
        }

        $this->addFlash('success', 'Your song has been added to review');

        return $this->redirectToRoute('new_review_page');
    }
}
