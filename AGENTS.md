# Example Storefront — Agent Rules

This repository is the **storefront module** for Adobe Commerce Cloud (Magento 2.4.7).
It contains **only** custom module code under `app/code/Example/`. Magento core lives on the
ACC environment, not in this repo.

## Non-negotiables
- Follow Magento 2 coding standards. Every PHP file: `declare(strict_types=1)`,
  full parameter/return type hints, PHPDoc on every class and method, constructor dependency
  injection only — **never** use the ObjectManager directly.
- **Before pushing**, run local validation and make it pass:
  - `composer install`
  - `vendor/bin/phpcs --standard=phpcs.xml.dist`
  - `vendor/bin/phpunit --configuration phpunit.xml.dist`
- A push or PR triggers CI:
  - **Layer 1 — Code validation** (GitHub runner): PHP lint, PHPCS (Magento2), unit tests.
  - **Layer 2 — ACC build** (real Magento on the ACC environment): `setup:upgrade` +
    `setup:di:compile`. This catches build-only errors that local lint does not.
  - Both layers must be green before merge.

## When CI fails
Invoke the **`/acc-standards`** skill. It documents the coding standards in detail and
explains how to diagnose and fix **ACC build (di:compile) failures** — the class of errors that
pass local linting but break the Adobe Commerce build.

---

## Coding standards (detail)

These are the conventions every change in this repo must follow. They mirror the Magento 2
baseline (`phpcs --standard=Magento2`) plus the stricter conventions applied to all custom code.

### PHP — language & typing
- Start every PHP class file with `declare(strict_types=1);` after the file docblock.
- Full type hints on all parameters and return types; type every class property.
- Prefer **constructor property promotion**:
  `public function __construct(private readonly LoggerInterface $logger) {}`.
- A PHPDoc block on every class and every method (`@param`, `@return`, `@throws` as applicable).
  Use `@phpstan-param array<int, string> $items` for complex array shapes.
- One blank line before every `return`.

### Dependency injection
- Constructor **dependency injection** only. **Never** call `ObjectManager::getInstance()` or
  inject `ObjectManagerInterface` in app code (no service locators).
- **Never** instantiate domain objects with `new` or via the ObjectManager — inject the
  auto-generated `{Class}Factory` and call `->create()`.
- Prefer **service contracts** (`Api/` interfaces) over concrete classes for public APIs; wire
  implementations with a `<preference>` in `etc/di.xml`.
- Use plugins (interceptors: `before`/`after`/`around`) rather than class rewrites/preferences
  when modifying core behaviour. Check `module.xml` `<sequence>` before adding cross-module DI
  to avoid circular dependencies.

### Layout & visibility
- Lines ≤ 120 characters; classes < 300 lines.
- `private` by default; **no `protected`** members or methods (use `private` + DI for
  cross-module calls). `public` only for API/contract methods. No member prefixed with `_`.
- Always `use` imports at the top — no fully-qualified class names inline. No unused `use`.
- Do **not** mark classes or methods `final` (it blocks mocking in unit tests).

### Absolute prohibitions
- `die()`, `exit`, `echo`, `var_dump()`, `print_r()`, `phpinfo()`, `console.log`.
- Superglobals: `$_GET`, `$_POST`, `$_REQUEST`, `$_SESSION`.
- Magic methods (`__get`, `__set`, …), hardcoded values, debugging artifacts
  (`DebuggerUtility`), and left-over merge-conflict markers.

### Logging & error handling
- Inject `Psr\Log\LoggerInterface`. Log with structured context, serializing complex arrays
  before logging (`$this->serializer->serialize($data)`).
- Never suppress errors; catch **specific** exceptions, not the generic `\Exception`.

### Testing
- Unit tests live in `Test/Unit/{Class}Test.php` (PHPUnit, Magento bootstrap).
- Do **not** mark classes/methods `final` — it prevents mocking.
- Use data providers for multi-scenario cases; aim for meaningful coverage of business logic.

---

## Module structure

Custom code lives under `app/code/Example/{ModuleName}/`. Typical layout:

```
app/code/Example/ModuleName/
├── etc/
│   ├── module.xml          (declare dependencies / sequence)
│   ├── di.xml              (preferences, DI bindings, factories)
│   └── ...                 (events.xml, config, routes)
├── Api/
│   ├── Data/
│   │   └── ItemInterface.php
│   └── ItemRepositoryInterface.php
├── Model/
│   ├── Item.php            (data object)
│   ├── ItemRepository.php  (implements RepositoryInterface)
│   └── Service.php         (business logic)
├── Plugin/
├── Observer/
├── Block/
├── ViewModel/
├── Test/
│   └── Unit/{Class}Test.php
└── registration.php
```

Check `app/code/Example/{Module}/etc/module.xml` `<sequence>` before modifying shared APIs or
adding cross-module dependencies.
