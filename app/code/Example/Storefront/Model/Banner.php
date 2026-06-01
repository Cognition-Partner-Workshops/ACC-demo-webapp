<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\Data\BannerInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Banner entity model.
 */
class Banner extends AbstractModel implements BannerInterface
{
    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Banner::class);
    }

    /**
     * @inheritDoc
     */
    public function getBannerId(): ?int
    {
        $value = $this->getData(self::BANNER_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setBannerId(int $bannerId): BannerInterface
    {
        return $this->setData(self::BANNER_ID, $bannerId);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): BannerInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getSubtitle(): ?string
    {
        return $this->getData(self::SUBTITLE);
    }

    /**
     * @inheritDoc
     */
    public function setSubtitle(?string $subtitle): BannerInterface
    {
        return $this->setData(self::SUBTITLE, $subtitle);
    }

    /**
     * @inheritDoc
     */
    public function getImageUrl(): ?string
    {
        return $this->getData(self::IMAGE_URL);
    }

    /**
     * @inheritDoc
     */
    public function setImageUrl(string $imageUrl): BannerInterface
    {
        return $this->setData(self::IMAGE_URL, $imageUrl);
    }

    /**
     * @inheritDoc
     */
    public function getCtaLabel(): ?string
    {
        return $this->getData(self::CTA_LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setCtaLabel(?string $ctaLabel): BannerInterface
    {
        return $this->setData(self::CTA_LABEL, $ctaLabel);
    }

    /**
     * @inheritDoc
     */
    public function getCtaLink(): ?string
    {
        return $this->getData(self::CTA_LINK);
    }

    /**
     * @inheritDoc
     */
    public function setCtaLink(?string $ctaLink): BannerInterface
    {
        return $this->setData(self::CTA_LINK, $ctaLink);
    }

    /**
     * @inheritDoc
     */
    public function getSortOrder(): ?int
    {
        $value = $this->getData(self::SORT_ORDER);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setSortOrder(int $sortOrder): BannerInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive(): ?bool
    {
        $value = $this->getData(self::IS_ACTIVE);
        return $value !== null ? (bool) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $isActive): BannerInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): BannerInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): BannerInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId): BannerInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerGroupId(): ?int
    {
        $value = $this->getData(self::CUSTOMER_GROUP_ID);
        return $value !== null ? (int) $value : null;
    }

    /**
     * @inheritDoc
     */
    public function setCustomerGroupId(?int $customerGroupId): BannerInterface
    {
        return $this->setData(self::CUSTOMER_GROUP_ID, $customerGroupId);
    }
}
