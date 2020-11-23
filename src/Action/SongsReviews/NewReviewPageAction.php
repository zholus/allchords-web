<?php
declare(strict_types=1);

namespace App\Action\SongsReviews;

use App\Action\Action;
use Symfony\Component\HttpFoundation\Response;

final class NewReviewPageAction extends Action
{
    public function __invoke(): Response
    {
        return $this->render('songs_reviews/song_new.twig');
    }
}
