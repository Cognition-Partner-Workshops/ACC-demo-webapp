<?php
/**
 * Copyright © Example Co. All rights reserved.
 *
 * Test bootstrap: loads Composer autoloader then defines minimal Magento
 * framework stubs so unit tests can mock framework classes without
 * requiring magento/framework as a dependency.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

/*
 * ---------------------------------------------------------------
 * Magento Framework Stubs
 * ---------------------------------------------------------------
 * Only the signatures that our code and tests reference are
 * declared here. They are intentionally empty — PHPUnit will
 * create mock objects from them.
 * ---------------------------------------------------------------
 */

// --- Model layer -----------------------------------------------------------

// phpcs:disable PSR1.Classes.ClassDeclaration.MultipleClasses
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Magento2.PHP.FinalImplementation

if (!class_exists(\Magento\Framework\Model\AbstractModel::class)) {
    // @codingStandardsIgnoreStart
    /**
     * Stub for Magento\Framework\Model\AbstractModel.
     */
    class MagentoFrameworkModelAbstractModel
    {
        /**
         * Stub _init.
         *
         * @param string $resourceModel
         * @return void
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        protected function _init(string $resourceModel): void
        {
        }

        /**
         * Stub _construct.
         *
         * @return void
         */
        protected function _construct(): void
        {
        }

        /**
         * Stub getData.
         *
         * @param string $key
         * @return mixed
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function getData(string $key = '')
        {
            return null;
        }

        /**
         * Stub setData.
         *
         * @param string|array $key
         * @param mixed $value
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function setData($key, $value = null)
        {
            return $this;
        }
    }
    class_alias('MagentoFrameworkModelAbstractModel', 'Magento\Framework\Model\AbstractModel');
    // @codingStandardsIgnoreEnd
}

// --- Resource model layer --------------------------------------------------

if (!class_exists(\Magento\Framework\Model\ResourceModel\Db\AbstractDb::class)) {
    /**
     * Stub for Magento\Framework\Model\ResourceModel\Db\AbstractDb.
     */
    class MagentoFrameworkModelResourceModelDbAbstractDb
    {
        /**
         * Stub _init.
         *
         * @param string $mainTable
         * @param string $idFieldName
         * @return void
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        protected function _init(string $mainTable, string $idFieldName): void
        {
        }

        /**
         * Stub _construct.
         *
         * @return void
         */
        protected function _construct(): void
        {
        }

        /**
         * Stub load.
         *
         * @param \Magento\Framework\Model\AbstractModel $object
         * @param mixed $value
         * @param string|null $field
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function load(
            \Magento\Framework\Model\AbstractModel $object,
            $value,
            $field = null
        ) {
            return $this;
        }

        /**
         * Stub save.
         *
         * @param \Magento\Framework\Model\AbstractModel $object
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function save(\Magento\Framework\Model\AbstractModel $object)
        {
            return $this;
        }

        /**
         * Stub delete.
         *
         * @param \Magento\Framework\Model\AbstractModel $object
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function delete(\Magento\Framework\Model\AbstractModel $object)
        {
            return $this;
        }
    }
    class_alias(
        'MagentoFrameworkModelResourceModelDbAbstractDb',
        'Magento\Framework\Model\ResourceModel\Db\AbstractDb'
    );
}

// --- Collection layer ------------------------------------------------------

if (!class_exists(
    \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::class
)) {
    /**
     * Stub for AbstractCollection.
     */
    class MagentoFrameworkCollectionAbstractCollection
    {
        /**
         * Stub _init.
         *
         * @param string $model
         * @param string $resourceModel
         * @return void
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        protected function _init(string $model, string $resourceModel): void
        {
        }

        /**
         * Stub _construct.
         *
         * @return void
         */
        protected function _construct(): void
        {
        }

        /**
         * Stub getItems.
         *
         * @return array
         */
        public function getItems(): array
        {
            return [];
        }

        /**
         * Stub getSize.
         *
         * @return int
         */
        public function getSize(): int
        {
            return 0;
        }
    }
    class_alias(
        'MagentoFrameworkCollectionAbstractCollection',
        'Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection'
    );
}

// --- API / Search layer ----------------------------------------------------

if (!interface_exists(\Magento\Framework\Api\SearchResultsInterface::class)) {
    /**
     * Stub for SearchResultsInterface.
     */
    interface MagentoFrameworkApiSearchResultsInterface
    {
        /**
         * Get items.
         *
         * @return array
         */
        public function getItems(): array;

        /**
         * Set items.
         *
         * @param array $items
         * @return $this
         */
        public function setItems(array $items);

        /**
         * Get search criteria.
         *
         * @return \Magento\Framework\Api\SearchCriteriaInterface
         */
        public function getSearchCriteria();

        /**
         * Set search criteria.
         *
         * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
         * @return $this
         */
        public function setSearchCriteria(
            \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
        );

        /**
         * Get total count.
         *
         * @return int
         */
        public function getTotalCount(): int;

        /**
         * Set total count.
         *
         * @param int $totalCount
         * @return $this
         */
        public function setTotalCount(int $totalCount);
    }
    class_alias(
        'MagentoFrameworkApiSearchResultsInterface',
        'Magento\Framework\Api\SearchResultsInterface'
    );
}

if (!interface_exists(\Magento\Framework\Api\SearchCriteriaInterface::class)) {
    /**
     * Stub for SearchCriteriaInterface.
     */
    interface MagentoFrameworkApiSearchCriteriaInterface
    {
    }
    class_alias(
        'MagentoFrameworkApiSearchCriteriaInterface',
        'Magento\Framework\Api\SearchCriteriaInterface'
    );
}

if (!class_exists(\Magento\Framework\Api\SearchCriteriaBuilder::class)) {
    /**
     * Stub for SearchCriteriaBuilder.
     */
    class MagentoFrameworkApiSearchCriteriaBuilder
    {
        /**
         * Add filter.
         *
         * @param string $field
         * @param mixed $value
         * @param string $conditionType
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function addFilter(
            string $field,
            $value,
            string $conditionType = 'eq'
        ) {
            return $this;
        }

        /**
         * Add sort order.
         *
         * @param \Magento\Framework\Api\SortOrder $sortOrder
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function addSortOrder(\Magento\Framework\Api\SortOrder $sortOrder)
        {
            return $this;
        }

        /**
         * Add filters as an OR group.
         *
         * @param array $filters
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function addFilters(array $filters)
        {
            return $this;
        }

        /**
         * Create search criteria.
         *
         * @return \Magento\Framework\Api\SearchCriteriaInterface
         */
        public function create()
        {
            return new class implements \Magento\Framework\Api\SearchCriteriaInterface {
            };
        }
    }
    class_alias(
        'MagentoFrameworkApiSearchCriteriaBuilder',
        'Magento\Framework\Api\SearchCriteriaBuilder'
    );
}

if (!class_exists(\Magento\Framework\Api\SortOrder::class)) {
    /**
     * Stub for SortOrder.
     */
    class MagentoFrameworkApiSortOrder
    {
    }
    class_alias(
        'MagentoFrameworkApiSortOrder',
        'Magento\Framework\Api\SortOrder'
    );
}

if (!class_exists(\Magento\Framework\Api\SortOrderBuilder::class)) {
    /**
     * Stub for SortOrderBuilder.
     */
    class MagentoFrameworkApiSortOrderBuilder
    {
        /**
         * Set field.
         *
         * @param string $field
         * @return $this
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function setField(string $field)
        {
            return $this;
        }

        /**
         * Set ascending direction.
         *
         * @return $this
         */
        public function setAscendingDirection()
        {
            return $this;
        }

        /**
         * Create sort order.
         *
         * @return \Magento\Framework\Api\SortOrder
         */
        public function create()
        {
            return new \Magento\Framework\Api\SortOrder();
        }
    }
    class_alias(
        'MagentoFrameworkApiSortOrderBuilder',
        'Magento\Framework\Api\SortOrderBuilder'
    );
}

if (!interface_exists(
    \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class
)) {
    /**
     * Stub for CollectionProcessorInterface.
     */
    interface MagentoFrameworkApiCollectionProcessorInterface
    {
        /**
         * Process.
         *
         * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
         * @param mixed $collection
         * @return void
         */
        public function process(
            \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
            $collection
        ): void;
    }
    class_alias(
        'MagentoFrameworkApiCollectionProcessorInterface',
        'Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface'
    );
}

// --- Exception layer -------------------------------------------------------

if (!class_exists(\Magento\Framework\Exception\LocalizedException::class)) {
    /**
     * Stub for LocalizedException.
     */
    class MagentoFrameworkExceptionLocalizedException extends \Exception
    {
        /**
         * Constructor.
         *
         * @param \Magento\Framework\Phrase|string $phrase
         * @param \Exception|null $cause
         * @param int $code
         */
        public function __construct($phrase = '', ?\Exception $cause = null, int $code = 0)
        {
            parent::__construct((string) $phrase, $code, $cause);
        }
    }
    class_alias(
        'MagentoFrameworkExceptionLocalizedException',
        'Magento\Framework\Exception\LocalizedException'
    );
}

if (!class_exists(\Magento\Framework\Exception\NoSuchEntityException::class)) {
    /**
     * Stub for NoSuchEntityException.
     */
    class MagentoFrameworkExceptionNoSuchEntityException extends
        \Magento\Framework\Exception\LocalizedException
    {
    }
    class_alias(
        'MagentoFrameworkExceptionNoSuchEntityException',
        'Magento\Framework\Exception\NoSuchEntityException'
    );
}

if (!class_exists(\Magento\Framework\Exception\CouldNotSaveException::class)) {
    /**
     * Stub for CouldNotSaveException.
     */
    class MagentoFrameworkExceptionCouldNotSaveException extends
        \Magento\Framework\Exception\LocalizedException
    {
    }
    class_alias(
        'MagentoFrameworkExceptionCouldNotSaveException',
        'Magento\Framework\Exception\CouldNotSaveException'
    );
}

if (!class_exists(\Magento\Framework\Exception\CouldNotDeleteException::class)) {
    /**
     * Stub for CouldNotDeleteException.
     */
    class MagentoFrameworkExceptionCouldNotDeleteException extends
        \Magento\Framework\Exception\LocalizedException
    {
    }
    class_alias(
        'MagentoFrameworkExceptionCouldNotDeleteException',
        'Magento\Framework\Exception\CouldNotDeleteException'
    );
}

// --- Setup layer -----------------------------------------------------------

if (!interface_exists(\Magento\Framework\Setup\ModuleDataSetupInterface::class)) {
    /**
     * Stub for ModuleDataSetupInterface.
     */
    interface MagentoFrameworkSetupModuleDataSetupInterface
    {
        /**
         * Get connection.
         *
         * @return \Magento\Framework\DB\Adapter\AdapterInterface
         */
        public function getConnection();

        /**
         * Get table name.
         *
         * @param string $tableName
         * @return string
         */
        public function getTable(string $tableName): string;
    }
    class_alias(
        'MagentoFrameworkSetupModuleDataSetupInterface',
        'Magento\Framework\Setup\ModuleDataSetupInterface'
    );
}

if (!interface_exists(\Magento\Framework\Setup\Patch\DataPatchInterface::class)) {
    /**
     * Stub for DataPatchInterface.
     */
    interface MagentoFrameworkSetupPatchDataPatchInterface
    {
        /**
         * Apply.
         *
         * @return void
         */
        public function apply(): void;

        /**
         * Get dependencies.
         *
         * @return array
         */
        public static function getDependencies(): array;

        /**
         * Get aliases.
         *
         * @return array
         */
        public function getAliases(): array;
    }
    class_alias(
        'MagentoFrameworkSetupPatchDataPatchInterface',
        'Magento\Framework\Setup\Patch\DataPatchInterface'
    );
}

if (!interface_exists(\Magento\Framework\DB\Adapter\AdapterInterface::class)) {
    /**
     * Stub for AdapterInterface.
     */
    interface MagentoFrameworkDBAdapterAdapterInterface
    {
        /**
         * Insert.
         *
         * @param string $table
         * @param array $bind
         * @return int
         */
        public function insert(string $table, array $bind): int;
    }
    class_alias(
        'MagentoFrameworkDBAdapterAdapterInterface',
        'Magento\Framework\DB\Adapter\AdapterInterface'
    );
}

// --- View layer ------------------------------------------------------------

if (!interface_exists(
    \Magento\Framework\View\Element\Block\ArgumentInterface::class
)) {
    /**
     * Stub for ArgumentInterface.
     */
    interface MagentoFrameworkViewElementBlockArgumentInterface
    {
    }
    class_alias(
        'MagentoFrameworkViewElementBlockArgumentInterface',
        'Magento\Framework\View\Element\Block\ArgumentInterface'
    );
}

// --- Serializer layer ------------------------------------------------------

if (!interface_exists(\Magento\Framework\Serialize\SerializerInterface::class)) {
    /**
     * Stub for SerializerInterface.
     */
    interface MagentoFrameworkSerializeSerializerInterface
    {
        /**
         * Serialize.
         *
         * @param mixed $data
         * @return string
         */
        public function serialize($data): string;

        /**
         * Unserialize.
         *
         * @param string $string
         * @return mixed
         */
        public function unserialize(string $string);
    }
    class_alias(
        'MagentoFrameworkSerializeSerializerInterface',
        'Magento\Framework\Serialize\SerializerInterface'
    );
}

// --- Store layer -----------------------------------------------------------

if (!interface_exists(\Magento\Store\Model\StoreManagerInterface::class)) {
    /**
     * Stub for StoreManagerInterface.
     */
    interface MagentoStoreModelStoreManagerInterface
    {
        /**
         * Get store.
         *
         * @return \Magento\Store\Api\Data\StoreInterface
         */
        public function getStore();
    }
    class_alias(
        'MagentoStoreModelStoreManagerInterface',
        'Magento\Store\Model\StoreManagerInterface'
    );
}

if (!interface_exists(\Magento\Store\Api\Data\StoreInterface::class)) {
    /**
     * Stub for StoreInterface.
     */
    interface MagentoStoreApiDataStoreInterface
    {
        /**
         * Get store ID.
         *
         * @return int
         */
        public function getId();
    }
    class_alias(
        'MagentoStoreApiDataStoreInterface',
        'Magento\Store\Api\Data\StoreInterface'
    );
}

// --- Cache layer -----------------------------------------------------------

if (!class_exists(\Magento\Framework\App\Cache\Type\FrontendPool::class)) {
    /**
     * Stub for FrontendPool.
     */
    class MagentoFrameworkAppCacheTypeFrontendPool
    {
        /**
         * Get frontend.
         *
         * @param string $identifier
         * @return \Magento\Framework\Cache\FrontendInterface
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function get(string $identifier)
        {
            return null;
        }
    }
    class_alias(
        'MagentoFrameworkAppCacheTypeFrontendPool',
        'Magento\Framework\App\Cache\Type\FrontendPool'
    );
}

if (!interface_exists(\Magento\Framework\Cache\FrontendInterface::class)) {
    /**
     * Stub for cache FrontendInterface.
     */
    interface MagentoFrameworkCacheFrontendInterface
    {
        /**
         * Load.
         *
         * @param string $identifier
         * @return string|false
         */
        public function load(string $identifier);

        /**
         * Save.
         *
         * @param string $data
         * @param string $identifier
         * @param array $tags
         * @param int|null $lifeTime
         * @return bool
         */
        public function save(
            string $data,
            string $identifier,
            array $tags = [],
            ?int $lifeTime = null
        ): bool;

        /**
         * Remove.
         *
         * @param string $identifier
         * @return bool
         */
        public function remove(string $identifier): bool;

        /**
         * Clean.
         *
         * @param string $mode
         * @param array $tags
         * @return bool
         */
        public function clean(string $mode, array $tags = []): bool;
    }
    class_alias(
        'MagentoFrameworkCacheFrontendInterface',
        'Magento\Framework\Cache\FrontendInterface'
    );
}

if (!class_exists(\Magento\Framework\Cache\Frontend\Decorator\TagScope::class)) {
    /**
     * Stub for TagScope.
     */
    class MagentoFrameworkCacheFrontendDecoratorTagScope
    {
        /**
         * @var \Magento\Framework\Cache\FrontendInterface|null
         */
        private $frontend;

        /**
         * @var string
         */
        private string $tag;

        /**
         * Constructor.
         *
         * @param \Magento\Framework\Cache\FrontendInterface|null $frontend
         * @param string $tag
         */
        public function __construct($frontend = null, string $tag = '')
        {
            $this->frontend = $frontend;
            $this->tag = $tag;
        }

        /**
         * Load.
         *
         * @param string $identifier
         * @return string|false
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function load(string $identifier)
        {
            return false;
        }

        /**
         * Save.
         *
         * @param string $data
         * @param string $identifier
         * @param array $tags
         * @param int|null $lifeTime
         * @return bool
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function save(
            string $data,
            string $identifier,
            array $tags = [],
            ?int $lifeTime = null
        ): bool {
            return true;
        }

        /**
         * Clean.
         *
         * @param string $mode
         * @param array $tags
         * @return bool
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function clean(string $mode = '', array $tags = []): bool
        {
            return true;
        }
    }
    class_alias(
        'MagentoFrameworkCacheFrontendDecoratorTagScope',
        'Magento\Framework\Cache\Frontend\Decorator\TagScope'
    );
}

// --- Component registrar ---------------------------------------------------

if (!class_exists(\Magento\Framework\Component\ComponentRegistrar::class)) {
    /**
     * Stub for ComponentRegistrar.
     */
    class MagentoFrameworkComponentComponentRegistrar
    {
        public const MODULE = 'module';
        public const THEME  = 'theme';

        /**
         * Register.
         *
         * @param string $type
         * @param string $componentName
         * @param string $path
         * @return void
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public static function register(
            string $type,
            string $componentName,
            string $path
        ): void {
        }
    }
    class_alias(
        'MagentoFrameworkComponentComponentRegistrar',
        'Magento\Framework\Component\ComponentRegistrar'
    );
}

// --- Auto-generated factory stubs ------------------------------------------

if (!class_exists(\Example\Storefront\Model\BannerFactory::class)) {
    /**
     * Stub for auto-generated BannerFactory.
     */
    class ExampleStorefrontModelBannerFactory
    {
        /**
         * Create a new Banner instance.
         *
         * @param array $data
         * @return \Example\Storefront\Model\Banner
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function create(array $data = [])
        {
            return new \Example\Storefront\Model\Banner();
        }
    }
    class_alias(
        'ExampleStorefrontModelBannerFactory',
        'Example\Storefront\Model\BannerFactory'
    );
}

if (!class_exists(
    \Example\Storefront\Model\ResourceModel\Banner\CollectionFactory::class
)) {
    /**
     * Stub for auto-generated CollectionFactory.
     */
    class ExampleStorefrontModelResourceModelBannerCollectionFactory
    {
        /**
         * Create a new Collection instance.
         *
         * @param array $data
         * @return \Example\Storefront\Model\ResourceModel\Banner\Collection
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function create(array $data = [])
        {
            return new \Example\Storefront\Model\ResourceModel\Banner\Collection();
        }
    }
    class_alias(
        'ExampleStorefrontModelResourceModelBannerCollectionFactory',
        'Example\Storefront\Model\ResourceModel\Banner\CollectionFactory'
    );
}

if (!class_exists(
    \Example\Storefront\Api\Data\BannerSearchResultsInterfaceFactory::class
)) {
    /**
     * Stub for auto-generated BannerSearchResultsInterfaceFactory.
     */
    class ExampleStorefrontApiDataBannerSearchResultsInterfaceFactory
    {
        /**
         * Create a new search results instance.
         *
         * @param array $data
         * @return \Example\Storefront\Api\Data\BannerSearchResultsInterface
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function create(array $data = [])
        {
            return null;
        }
    }
    class_alias(
        'ExampleStorefrontApiDataBannerSearchResultsInterfaceFactory',
        'Example\Storefront\Api\Data\BannerSearchResultsInterfaceFactory'
    );
}

// --- Filter / FilterBuilder ------------------------------------------------

if (!class_exists(\Magento\Framework\Api\Filter::class)) {
    /**
     * Stub for Magento\Framework\Api\Filter.
     */
    class MagentoFrameworkApiFilter
    {
        /**
         * @var string
         */
        private string $field = '';

        /**
         * @var mixed
         */
        private $value;

        /**
         * @var string
         */
        private string $conditionType = 'eq';

        /**
         * Get field.
         *
         * @return string
         */
        public function getField(): string
        {
            return $this->field;
        }

        /**
         * Set field.
         *
         * @param string $field
         * @return $this
         */
        public function setField(string $field)
        {
            $this->field = $field;
            return $this;
        }

        /**
         * Get value.
         *
         * @return mixed
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * Set value.
         *
         * @param mixed $value
         * @return $this
         */
        public function setValue($value)
        {
            $this->value = $value;
            return $this;
        }

        /**
         * Get condition type.
         *
         * @return string
         */
        public function getConditionType(): string
        {
            return $this->conditionType;
        }

        /**
         * Set condition type.
         *
         * @param string $conditionType
         * @return $this
         */
        public function setConditionType(string $conditionType)
        {
            $this->conditionType = $conditionType;
            return $this;
        }
    }
    class_alias(
        'MagentoFrameworkApiFilter',
        'Magento\Framework\Api\Filter'
    );
}

if (!class_exists(\Magento\Framework\Api\FilterBuilder::class)) {
    /**
     * Stub for Magento\Framework\Api\FilterBuilder.
     */
    class MagentoFrameworkApiFilterBuilder
    {
        /**
         * @var string
         */
        private string $field = '';

        /**
         * @var mixed
         */
        private $value;

        /**
         * @var string
         */
        private string $conditionType = 'eq';

        /**
         * Set field.
         *
         * @param string $field
         * @return $this
         */
        public function setField(string $field)
        {
            $this->field = $field;
            return $this;
        }

        /**
         * Set value.
         *
         * @param mixed $value
         * @return $this
         */
        public function setValue($value)
        {
            $this->value = $value;
            return $this;
        }

        /**
         * Set condition type.
         *
         * @param string $conditionType
         * @return $this
         */
        public function setConditionType(string $conditionType)
        {
            $this->conditionType = $conditionType;
            return $this;
        }

        /**
         * Create the filter.
         *
         * @return \Magento\Framework\Api\Filter
         */
        public function create(): \Magento\Framework\Api\Filter
        {
            $filter = new \Magento\Framework\Api\Filter();
            $filter->setField($this->field);
            $filter->setValue($this->value);
            $filter->setConditionType($this->conditionType);
            $this->field = '';
            $this->value = null;
            $this->conditionType = 'eq';
            return $filter;
        }
    }
    class_alias(
        'MagentoFrameworkApiFilterBuilder',
        'Magento\Framework\Api\FilterBuilder'
    );
}

// --- Phrase -----------------------------------------------------------------

if (!class_exists(\Magento\Framework\Phrase::class)) {
    /**
     * Stub for Phrase.
     */
    class MagentoFrameworkPhrase
    {
        /**
         * @var string
         */
        private string $text;

        /**
         * @param string $text
         * @param array $arguments
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function __construct(string $text = '', array $arguments = [])
        {
            $this->text = $text;
        }

        /**
         * To string.
         *
         * @return string
         */
        public function __toString(): string
        {
            return $this->text;
        }
    }
    class_alias('MagentoFrameworkPhrase', 'Magento\Framework\Phrase');
}

/**
 * Magento's __() helper — returns a Phrase object.
 *
 * @return \Magento\Framework\Phrase
 */
if (!function_exists('__')) {
    /**
     * Translation stub.
     *
     * @param string $text
     * @param mixed ...$args
     * @return \Magento\Framework\Phrase
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    function __(string $text, ...$args): \Magento\Framework\Phrase
    {
        return new \Magento\Framework\Phrase($text, $args);
    }
}
