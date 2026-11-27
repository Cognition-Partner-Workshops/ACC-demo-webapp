# Example Storefront (ACC demo)

Custom **Magento 2** module (`Example_Storefront`) representing the storefront customizations that run
on **Adobe Commerce Cloud (ACC)**. This repo contains **only** the custom code — Magento core lives
on the ACC environment. It demonstrates how **Devin** accelerates the build → validate → ship loop:
Devin writes standards-compliant code, validates it, opens a PR, and when the ACC build fails Devin
reads the failed CI and fixes it automatically.

## Repository layout

```
app/code/Example/Storefront/   The storefront module (the "source")
  Api/GreeterInterface.php      Service contract
  Model/Greeter.php             Implementation (constructor DI)
  etc/module.xml, di.xml         Module + DI wiring
  Test/Unit/GreeterTest.php      Unit tests
composer.json                   Dev tooling (phpcs Magento2 standard, phpunit)
phpcs.xml.dist                  Coding-standards ruleset (Magento2)
phpunit.xml.dist                Unit test config
.github/workflows/ci.yml        CI pipeline (Layer 1 + Layer 2)
AGENTS.md                       Always-on agent rules
.devin/skills/...               Detailed standards + ACC fix-loop guidance
```

## Local validation (run before every push)

```bash
composer install
find app/code -name '*.php' -print0 | xargs -0 -n1 php -l
vendor/bin/phpcs --standard=phpcs.xml.dist
vendor/bin/phpunit --configuration phpunit.xml.dist
```

## CI pipeline (the ACC loop)

| Stage | Where | What it runs |
| --- | --- | --- |
| **Layer 1 — Code validation** | GitHub-hosted runner | PHP lint, PHPCS (Magento2), PHPUnit |
| **Layer 2 — ACC build** | Real Magento on the ACC environment (via SSH) | `setup:upgrade` + `setup:di:compile` |

Layer 2 is the authentic Adobe Commerce build step. It catches build-only errors (bad DI, missing
classes, circular deps) that pass local linting — the exact failures that normally force manual
IDE round-trips. When it fails, the real Magento error appears in the PR check, and Devin's CI
monitoring picks it up to fix and re-push.

## Required GitHub repository secrets (for Layer 2)

| Secret | Value |
| --- | --- |
| `VM_HOST` | Public IP/hostname of the ACC environment VM |
| `VM_USER` | SSH user (e.g. `ubuntu`) |
| `VM_SSH_KEY` | Private key of the deploy keypair (public key installed on the VM) |

## Coding standards

Enforced via `phpcs --standard=Magento2`. The detailed standards and the ACC build fix-loop guidance
live in the `acc-standards` skill (`.devin/skills/acc-standards/SKILL.md`). Replace the placeholder
standards section with your organization's official document when available.

## Standalone PHP version-policy demo

[demos/php-version-gate](demos/php-version-gate/README.md) contains an isolated, local-only workshop demo.
It compares the original and proposed version constraints with a **harmless simulated marker**, not a CVE exploit.
The demo is separate from the Magento module and Composer autoloading; it does not change runtime requirements,
the deployment workflow, or the code-scan verdict. Its README includes Docker startup instructions and tests.
