<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model\Cache;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;

/**
 * Cache type for hero banner data.
 *
 * Enables Varnish/Redis-backed caching of banner queries to support
 * high-concurrency scenarios (1000+ parallel sessions, 100K+ customers).
 * Banners are cached per store + customer group combination.
 */
class BannerCacheType extends TagScope
{
    /**
     * Cache type identifier used in cache configuration.
     */
    public const TYPE_IDENTIFIER = 'example_banner';

    /**
     * Cache tag used for selective invalidation.
     */
    public const CACHE_TAG = 'EXAMPLE_BANNER';

    /**
     * Default TTL for banner cache entries (1 hour).
     */
    public const DEFAULT_LIFETIME = 3600;

    /**
     * @param FrontendPool $cacheFrontendPool
     */
    public function __construct(FrontendPool $cacheFrontendPool)
    {
        parent::__construct(
            $cacheFrontendPool->get(self::TYPE_IDENTIFIER),
            self::CACHE_TAG
        );
    }
}
