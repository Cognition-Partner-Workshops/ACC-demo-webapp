<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api;

/**
 * Repository contract for persisting order gift messages.
 *
 * The concrete implementation is provided by the platform on the ACC environment;
 * the storefront module integrates against this contract only.
 *
 * @api
 */
interface GiftMessageRepositoryInterface
{
    /**
     * Persist a gift message note.
     *
     * @param string $message
     * @return void
     */
    public function save(string $message): void;
}
