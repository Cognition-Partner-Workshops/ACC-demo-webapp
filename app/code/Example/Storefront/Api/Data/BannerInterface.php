<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Api\Data;

/**
 * Data interface for the hero banner entity.
 *
 * @api
 */
interface BannerInterface
{
    /**
     * Column name constants.
     */
    public const BANNER_ID  = 'banner_id';
    public const TITLE      = 'title';
    public const SUBTITLE   = 'subtitle';
    public const IMAGE_URL  = 'image_url';
    public const CTA_LABEL  = 'cta_label';
    public const CTA_LINK   = 'cta_link';
    public const SORT_ORDER = 'sort_order';
    public const IS_ACTIVE  = 'is_active';
    public const CREATED_AT        = 'created_at';
    public const UPDATED_AT        = 'updated_at';
    public const STORE_ID          = 'store_id';
    public const CUSTOMER_GROUP_ID = 'customer_group_id';

    /**
     * Get banner ID.
     *
     * @return int|null
     */
    public function getBannerId(): ?int;

    /**
     * Set banner ID.
     *
     * @param int $bannerId
     * @return self
     */
    public function setBannerId(int $bannerId): self;

    /**
     * Get banner title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set banner title.
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self;

    /**
     * Get banner subtitle.
     *
     * @return string|null
     */
    public function getSubtitle(): ?string;

    /**
     * Set banner subtitle.
     *
     * @param string|null $subtitle
     * @return self
     */
    public function setSubtitle(?string $subtitle): self;

    /**
     * Get image URL.
     *
     * @return string|null
     */
    public function getImageUrl(): ?string;

    /**
     * Set image URL.
     *
     * @param string $imageUrl
     * @return self
     */
    public function setImageUrl(string $imageUrl): self;

    /**
     * Get CTA button label.
     *
     * @return string|null
     */
    public function getCtaLabel(): ?string;

    /**
     * Set CTA button label.
     *
     * @param string|null $ctaLabel
     * @return self
     */
    public function setCtaLabel(?string $ctaLabel): self;

    /**
     * Get CTA button link.
     *
     * @return string|null
     */
    public function getCtaLink(): ?string;

    /**
     * Set CTA button link.
     *
     * @param string|null $ctaLink
     * @return self
     */
    public function setCtaLink(?string $ctaLink): self;

    /**
     * Get sort order.
     *
     * @return int|null
     */
    public function getSortOrder(): ?int;

    /**
     * Set sort order.
     *
     * @param int $sortOrder
     * @return self
     */
    public function setSortOrder(int $sortOrder): self;

    /**
     * Get is-active flag.
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool;

    /**
     * Set is-active flag.
     *
     * @param bool $isActive
     * @return self
     */
    public function setIsActive(bool $isActive): self;

    /**
     * Get created-at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set created-at timestamp.
     *
     * @param string $createdAt
     * @return self
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * Get updated-at timestamp.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set updated-at timestamp.
     *
     * @param string $updatedAt
     * @return self
     */
    public function setUpdatedAt(string $updatedAt): self;

    /**
     * Get store ID.
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set store ID.
     *
     * @param int $storeId
     * @return self
     */
    public function setStoreId(int $storeId): self;

    /**
     * Get customer group ID.
     *
     * @return int|null
     */
    public function getCustomerGroupId(): ?int;

    /**
     * Set customer group ID.
     *
     * @param int|null $customerGroupId
     * @return self
     */
    public function setCustomerGroupId(?int $customerGroupId): self;
}
