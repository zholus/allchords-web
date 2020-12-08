<?php
declare(strict_types=1);

namespace App\Accounts;

use App\Action\SongsReviews\GetSongsForReview;

final class PermissionsMap
{
    public const MAP = [
        GetSongsForReview::class => [
            'CAN_REVIEW_SONGS'
        ]
    ];
}