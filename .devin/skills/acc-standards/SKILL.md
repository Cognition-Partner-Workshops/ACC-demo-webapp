---
name: acc-standards
description: Magento coding standards and how to fix Adobe Commerce (ACC) build failures
triggers:
  - user
  - model
allowed-tools:
  - read
  - grep
  - glob
  - exec
---

You are working on the **storefront module** for Adobe Commerce Cloud (Magento 2.4.7).
Apply the standards below to every change, validate locally before pushing, and when CI fails,
use the diagnosis guide to fix it and re-push.

---

## 1. Coding standards

> These are the project's enforced coding standards for custom Magento 2 modules. They are aligned
> with the Magento 2 baseline (`phpcs --standard=Magento2`) plus the stricter conventions the team
> applies to all custom code. Apply every rule to every change.

**PHP — language & typing**
- Start every PHP class file with `declare(strict_types=1);` after the file docblock.
- Full type hints on all parameters and return types. Type all class properties.
- Prefer **constructor property promotion**: `public function __construct(private LoggerInterface $logger) {}`.
- A PHPDoc block on every class and every method (`@param`, `@return`, `@throws` as applicable). Use
  `@phpstan-param array<int, string> $items` for complex array shapes.
- One blank line before every `return`.

**Dependency injection**
- Constructor **dependency injection** only. **Never** call `ObjectManager::getInstance()` or inject
  `ObjectManagerInterface` in app code (no service locators).
- **Never** instantiate domain objects with `new` or via the ObjectManager — inject the auto-generated
  `{Class}Factory` and call `->create()`.
- Prefer **service contracts** (`Api/` interfaces) over concrete classes for public APIs; wire
  implementations with a `<preference>` in `etc/di.xml`.
- Use plugins (interceptors: `before`/`after`/`around`) rather than class rewrites/preferences when
  modifying core behaviour. Check `module.xml` `<sequence>` before adding cross-module DI to avoid
  circular dependencies.

**Layout & visibility**
- Lines ≤ 120 characters; classes < 300 lines.
- `private` by default; **no `protected`** members or methods (use `private` + DI for cross-module
  calls). `public` only for API/contract methods. No member prefixed with `_`.
- Always `use` imports at the top — no fully-qualified class names inline. No unused `use` statements.
- Do **not** mark classes or methods `final` (it blocks mocking in unit tests).

**Absolute prohibitions** (also blocked by CI / commit hooks)
- `die()`, `exit`, `echo`, `var_dump()`, `print_r()`, `phpinfo()`, `console.log`.
- Superglobals: `$_GET`, `$_POST`, `$_REQUEST`, `$_SESSION`.
- Magic methods (`__get`, `__set`, …), hardcoded values, debugging artifacts (`DebuggerUtility`),
  and left-over merge-conflict markers.

**Logging & error handling**
- Inject `Psr\Log\LoggerInterface`. Log with structured context, serializing complex arrays before
  logging (`$this->serializer->serialize($data)`).
- Catch **specific** exceptions, never the generic `\Exception`. Never silently suppress errors.

**Module structure**
- All code under `app/code/<Vendor>/<Module>/`. `registration.php` + `etc/module.xml` are required.
- Configuration in `etc/` (`di.xml`, `events.xml`, `acl.xml`, …) using the correct XSD `schemaLocation`.
- Tests under `Test/Unit/` (PHPUnit, no Magento bootstrap) — keep domain logic unit-testable; use data
  providers for multi-scenario coverage.

**Copyright** — every source file begins with the project copyright docblock.

---

## 2. Validate locally BEFORE pushing

Run these from the repo root and make them green:

```bash
composer install
find app/code -name '*.php' -print0 | xargs -0 -n1 php -l   # syntax
vendor/bin/phpcs --standard=phpcs.xml.dist                  # coding standards
vendor/bin/phpunit --configuration phpunit.xml.dist         # unit tests
```

These are the same checks CI Layer 1 runs. Fix everything here first — it is far cheaper than a
failed ACC build.

---

## 3. The ACC build (Layer 2) and how to fix failures

After Layer 1 passes, CI deploys the module into the **real Magento** on the ACC environment and runs:

```
bin/magento setup:upgrade --keep-generated
bin/magento setup:di:compile
```

`di:compile` analyses the entire dependency-injection graph. It catches a class of errors that
**pass PHP lint and PHPCS but break the build** — exactly the failures that cause painful
push→fail→fix round-trips. When CI Layer 2 is red, read the build log and match the error:

**`Class "Example\Storefront\...\X" does not exist`**
- A constructor type-hint, an `etc/di.xml` `<preference type="...">`, or an `<argument xsi:type="object">`
  references a class/interface that doesn't exist (typo, wrong namespace, or the class was never created).
- Fix: correct the fully-qualified name, or create the missing class/interface.

**`Circular dependency: A depends on B depends on A`**
- Two classes inject each other (directly or transitively).
- Fix: inject a `\Proxy` for one dependency (declare it in `di.xml`), or extract the shared logic
  into a third class so the cycle is broken.

**`Incompatible argument type` / `Missing required argument $x`**
- An `etc/di.xml` `<arguments>` entry doesn't match the constructor signature.
- Fix: align the argument name/type in `di.xml` with the constructor, or correct the constructor.

**`setup:upgrade` failures**
- Usually malformed XML (`module.xml`, `di.xml`) or a declared module dependency that isn't enabled.
- Fix: validate the XML against its XSD `schemaLocation`; add missing deps to `<sequence>` in `module.xml`.

### Fix loop
1. Read the failed CI log and identify the exact class/file/line.
2. Reproduce locally if possible (`vendor/bin/phpcs`, and reason about the DI graph).
3. Make the minimal correct fix that satisfies the standards in section 1.
4. Re-run local validation, commit, and push. CI re-runs both layers automatically.

Always prefer the smallest change that fixes the root cause while keeping the code standards-compliant.
