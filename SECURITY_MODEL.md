# Security model — Cognition-Partner-Workshops/ACC-demo-webapp

_Threat model for this repository, built from the code and the maintainers' answers. Security scans start from this file; keep it up to date. The ACC VM and `/home/ubuntu/acc-demo/platform/scripts/acc-build.sh` live outside this repo (no infrastructure repository) and are not covered._

## System overview
A custom Magento 2.4.7 module (`Example_Storefront`) intended to run on Adobe Commerce Cloud (ACC); in this demo it is deployed to an OSS Magento host ("ACC environment VM") rather than licensed ACC. The repo contains only module code plus tooling and CI:

- `app/code/Example/Storefront/Api/GreeterInterface.php` — `@api` service contract `getGreeting(string $customerName)`.
- `app/code/Example/Storefront/Model/Greeter.php` — implementation; trims the name, logs `Greeting generated for "<name>"` via `Psr\Log\LoggerInterface`, returns `Welcome, <name>!` (unescaped).
- `app/code/Example/Storefront/etc/di.xml` — `<preference>` wiring the interface to `Greeter`.
- No controllers, routes, `webapi.xml`, GraphQL schema, templates, DB schema or ACL in the module today; `Greeter` is only reachable by other code that calls the contract.
- `.github/workflows/ci.yml` — Layer 1 (lint/PHPCS/PHPUnit on a GitHub runner) and Layer 2 (`appleboy/ssh-action@v1.2.0` SSHs to `secrets.VM_HOST` as `secrets.VM_USER` with `secrets.VM_SSH_KEY` and runs `acc-build.sh "<PR head SHA or github.sha>"`, which deploys the module and runs `setup:upgrade` + `setup:di:compile` on a shared Magento).

## Components
| Name | Covers |
| --- | --- |
| **Storefront module** | `app/code/Example/Storefront/**` (Api, Model, etc, registration.php), excluding tests |
| **CI pipeline** | `.github/workflows/**`, the Layer 2 SSH deploy, and the remote `acc-build.sh` it invokes |
| **Build tooling** | `composer.json`, `phpcs.xml.dist`, `phpunit.xml.dist`, `app/code/**/Test/**` |
| **Agent config** | `AGENTS.md`, `.devin/**` (instructions consumed by automated agents) |

## Trust boundaries and entry points
1. **Storefront user → Magento → `GreeterInterface::getGreeting`**: `$customerName` is assumed customer-controlled (e.g. the account first name). Output is returned raw; any caller rendering it into HTML must escape it. Name is written to Magento logs.
2. **Contributor → GitHub PR/push → CI**: `pull_request` events (any PR, including from forks) run Layer 1. Layer 2 runs on `pull_request` too, but GitHub withholds repo secrets from fork PRs, so in practice Layer 2 runs for branches pushed by users with write access, and for `push` to `main`.
3. **CI → ACC VM (SSH)**: Layer 2 executes code chosen by the PR author (module PHP, `di.xml`, `setup:upgrade` hooks) on the shared Magento host with the deploy user's privileges. The SHA string is interpolated into the remote shell command.
4. **Repo content → automated agents**: `AGENTS.md` and `.devin/skills` are read and followed by Devin; changes to them steer agent behaviour.

## Assets
- `VM_SSH_KEY` / access to the ACC VM (shell on the Magento host and anything it can reach).
- Integrity of the deployed storefront code and the shared Magento install.
- Customer names / PII passing through `Greeter` (and Magento logs).
- GitHub Actions secrets and the `main` branch.

## Attacker profiles
_All of the profiles below are in scope; none is ruled out._

- Anonymous or registered storefront customer controlling `$customerName`.
- Outside contributor opening a fork PR.
- Collaborator with write access (or a compromised account / third-party action) able to run Layer 2.
- Supply-chain: compromised Composer packages (`magento/magento-coding-standard`, `phpunit/phpunit`, `psr/log`), or GitHub Actions pinned by tag rather than SHA (`actions/checkout@v4`, `shivammathur/setup-php@v2`, `appleboy/ssh-action@v1.2.0`).

## Existing controls
- `declare(strict_types=1)`, typed signatures, constructor DI only (no ObjectManager), PHPCS Magento2 ruleset in CI.
- Layer 2 `needs: validate` and runs serially (`concurrency: acc-build-environment`).
- GitHub's default of withholding secrets from fork `pull_request` runs.
- `composer.lock` is git-ignored (no lockfile pinning) — a gap, not a control.

## Out of scope / accepted risk
- **Accepted risk:** logging and PII-handling issues in this demo code — e.g. customer names written to logs by `Greeter::getGreeting`, log injection via the name, PII retention in Magento logs. Findings whose only impact is such logging/PII exposure are low or accepted risk.
- Magento core and the ACC platform itself are outside this repo.

## Open questions
- Contents and hardening of `acc-build.sh` and the ACC VM (not reviewable here).
- Repository settings: branch protection on `main`, who has write access, whether fork PRs require approval to run workflows.
