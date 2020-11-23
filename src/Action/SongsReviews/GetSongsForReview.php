<?php
declare(strict_types=1);

namespace App\Action\SongsReviews;

use App\Action\Action;
use Symfony\Component\HttpFoundation\Response;

final class GetSongsForReview extends Action
{
    public function __invoke()
    {
        return new Response('ok');
    }
}
