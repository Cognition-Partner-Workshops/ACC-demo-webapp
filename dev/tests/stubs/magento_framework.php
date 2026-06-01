<?php
/**
 * Copyright © Example Co. All rights reserved.
 *
 * Minimal Magento Framework stubs for unit testing without the full framework.
 * This module-only repo does not ship magento/framework as a dependency;
 * these stubs provide just enough class/interface definitions so PHPUnit
 * can load, mock, and test module classes that extend Magento types.
 */

// @codingStandardsIgnoreFile -- stubs, not application code

// --- Magento\Framework\Phrase ---------------------------------------------------
namespace Magento\Framework {
    class Phrase
    {
        private string $text;
        private array $arguments;

        public function __construct(string $text, array $arguments = [])
        {
            $this->text = $text;
            $this->arguments = $arguments;
        }

        public function __toString(): string
        {
            return $this->text;
        }

        public function render(): string
        {
            return $this->text;
        }
    }
}

// --- Magento\Framework\Model ---------------------------------------------------
namespace Magento\Framework\Model {
    abstract class AbstractModel
    {
        protected function _construct(): void
        {
        }

        protected function _init(string $resourceModel): void
        {
        }

        /**
         * @param string|null $key
         * @return mixed
         */
        public function getData($key = null)
        {
            return null;
        }

        /**
         * @param string|array $key
         * @param mixed $value
         * @return $this
         */
        public function setData($key, $value = null)
        {
            return $this;
        }
    }
}

// --- Magento\Framework\Model\ResourceModel\Db ---------------------------------
namespace Magento\Framework\Model\ResourceModel\Db {
    abstract class AbstractDb
    {
        protected function _construct(): void
        {
        }

        protected function _init(string $mainTable, string $idFieldName): void
        {
        }

        /**
         * @param object $object
         * @param mixed  $value
         * @param string|null $field
         * @return $this
         */
        public function load($object, $value, $field = null)
        {
            return $this;
        }

        /**
         * @param object $object
         * @return $this
         */
        public function save($object)
        {
            return $this;
        }

        /**
         * @param object $object
         * @return $this
         */
        public function delete($object)
        {
            return $this;
        }
    }
}

// --- Magento\Framework\Model\ResourceModel\Db\Collection -----------------------
namespace Magento\Framework\Model\ResourceModel\Db\Collection {
    abstract class AbstractCollection
    {
        protected function _construct(): void
        {
        }

        protected function _init(string $model, string $resourceModel): void
        {
        }
    }
}

// --- Magento\Framework\Exception -----------------------------------------------
namespace Magento\Framework\Exception {
    use Magento\Framework\Phrase;

    class LocalizedException extends \Exception
    {
        public function __construct(
            Phrase $phrase,
            \Exception $cause = null,
            int $code = 0
        ) {
            parent::__construct((string) $phrase, $code, $cause);
        }
    }

    class NoSuchEntityException extends LocalizedException
    {
    }

    class CouldNotSaveException extends LocalizedException
    {
    }

    class CouldNotDeleteException extends LocalizedException
    {
    }
}

// --- Magento\Framework\Api -----------------------------------------------------
namespace Magento\Framework\Api {
    interface SearchCriteriaInterface
    {
        /**
         * @return array
         */
        public function getFilterGroups(): array;
    }

    interface SearchResultsInterface
    {
        /**
         * @return array
         */
        public function getItems(): array;

        /**
         * @param array $items
         * @return void
         */
        public function setItems(array $items);

        /**
         * @return int
         */
        public function getTotalCount(): int;

        /**
         * @param int $count
         * @return void
         */
        public function setTotalCount(int $count);

        /**
         * @param SearchCriteriaInterface $searchCriteria
         * @return void
         */
        public function setSearchCriteria(SearchCriteriaInterface $searchCriteria);
    }

    class SearchCriteriaBuilder
    {
        /**
         * @param string $field
         * @param mixed  $value
         * @param string|null $conditionType
         * @return $this
         */
        public function addFilter(string $field, $value, ?string $conditionType = null): self
        {
            return $this;
        }

        /**
         * @return SearchCriteriaInterface
         */
        public function create(): SearchCriteriaInterface
        {
            return new class implements SearchCriteriaInterface {
                public function getFilterGroups(): array
                {
                    return [];
                }
            };
        }
    }

    class SearchResultsInterfaceFactory
    {
        /**
         * @param array $data
         * @return SearchResultsInterface
         */
        public function create(array $data = []): SearchResultsInterface
        {
            return new class implements SearchResultsInterface {
                public function getItems(): array
                {
                    return [];
                }

                public function setItems(array $items)
                {
                }

                public function getTotalCount(): int
                {
                    return 0;
                }

                public function setTotalCount(int $count)
                {
                }

                public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
                {
                }
            };
        }
    }
}

// --- Auto-generated factory stubs ----------------------------------------------
namespace Example\Storefront\Model {
    class SubscriptionFactory
    {
        /**
         * @param array $data
         * @return Subscription
         */
        public function create(array $data = []): Subscription
        {
            return new Subscription();
        }
    }
}

namespace Example\Storefront\Model\ResourceModel\Subscription {
    class CollectionFactory
    {
        /**
         * @param array $data
         * @return Collection
         */
        public function create(array $data = []): Collection
        {
            return new Collection();
        }
    }
}

// --- Global translation function -----------------------------------------------
namespace {
    if (!function_exists('__')) {
        /**
         * @param string $text
         * @param mixed  ...$args
         * @return \Magento\Framework\Phrase
         */
        function __(string $text, ...$args): \Magento\Framework\Phrase
        {
            return new \Magento\Framework\Phrase($text, $args);
        }
    }
}
