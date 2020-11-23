<?php
declare(strict_types=1);

namespace App\Action;

use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class Action extends AbstractController
{
    public function errorMessage(\Throwable $exception): string
    {
        try {
            throw $exception;
        } catch (DomainException $exception) {
            $errorMessage = $exception->getMessage();
        } catch (\Throwable $exception) {
            $errorMessage = 'Unexpected error';
        }

        return $errorMessage;
    }
}
