<?php

declare(strict_types=1);


interface MiddlewareInterface
{
   
    public function handle(array $params, ...$args): void;
}
