# Threat model — Cognition-Partner-Workshops/ACC-demo-webapp

## System overview
`Example_Storefront` is a custom Magento 2.4.7 module deployed to Adobe Commerce Cloud (ACC). The repo holds only custom code. Magento core and the PHP runtime live on the ACC appliance. The module exposes one service contract, `Api/GreeterInterface::getGreeting(string $customerName)`. It is implemented by `Model/Greeter.php`, which trims the name, falls back to `Guest`, logs it through PSR-3 `LoggerInterface` and returns `Welcome, <name>!`. DI wiring is in `etc/di.xml`. The module has no controllers, layouts, `webapi.xml`, `.htaccess`/web-server config or DB schema, so callers inside Magento decide how the greeting is rendered.

**User facts:**
- The ACC appliance runs both in the user's cloud and on-prem and is **business-critical**.
- The storefront on it is **reachable by anyone on the internet** (public storefront).

## Components
| Component | Covers |
|---|---|
| Storefront Module | `app/code/Example/Storefront/**` (Api, Model, etc, registration.php) |
| Unit Tests | `app/code/Example/Storefront/Test/**`, `phpunit.xml.dist` |
| CI/CD Pipeline | `.github/workflows/**`, `composer.json`, `phpcs.xml.dist` |
| ACC Appliance | The ACC VM/appliance (cloud + on-prem) that CI reaches over SSH, with its PHP runtime/SAPI and web server; `acc-build.sh` (not in this repo) |
| Agent Config | `AGENTS.md`, `.devin/**` |

## Trust boundaries and entry points
1. **Storefront visitor → web server → PHP → module.** Unauthenticated internet requests (user-stated) reach Magento's front controller through the appliance's web server and PHP SAPI. A customer name (profile data the customer controls, or guest input) flows into `getGreeting`, then into the logs and back to whatever template renders it. The module does not HTML-escape its output.
2. **Contributor → CI → ACC appliance.** `.github/workflows/ci.yml` runs on `pull_request` and on `push` to main. The `acc-build` job uses `appleboy/ssh-action@v1.2.0` with `VM_HOST`/`VM_USER`/`VM_SSH_KEY` and runs `/home/ubuntu/acc-demo/platform/scripts/acc-build.sh <head sha>` on one shared ACC environment. Code from a same-repo PR therefore runs (`setup:upgrade`, `setup:di:compile`) on the appliance before review. Fork PRs don't get secrets.
3. **Supply chain.** Third-party actions are pinned by tag, not by SHA (`actions/checkout@v4`, `shivammathur/setup-php@v2`, `appleboy/ssh-action@v1.2.0`). The Composer dependencies (`psr/log`, `magento/magento-coding-standard`, `phpunit`) have no committed lockfile.

## PHP runtime and PHP2Shell (CVE-2026-109618 / CVE-2026-109619), this scan's focus
- **The threat:** two memory-safety bugs in the core PHP runtime, both unauthenticated network RCE (CVSS 4.0 9.2), in CISA KEV and actively exploited.
  - CVE-2026-109618 is OOM `longjmp` corruption of request memory. It was validated on mod_php; FPM, FrankenPHP and LSAPI are considered susceptible. `memory_limit=-1` is only a partial mitigation.
  - CVE-2026-109619 is stale global state in persistent workers (mod_php, FPM, LSAPI) after a fatal error. The session upload-progress default is a trigger, and `error_reporting`, `@` and `display_errors`/`log_errors` don't mitigate it.
  - Fixed in 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12. Both bugs work against simple apps with no custom flaw.
- **What the repo shows:**
  - `composer.json` accepts `php: ~8.1.0 || ~8.2.0 || ~8.3.0`. That covers all of 8.1, 8.2 < 8.2.35 and 8.3 < 8.3.36, and rules out the 8.4/8.5 fixed lines. There is no `config.platform.php` floor and no lockfile.
  - CI Layer 1 runs PHP 8.3 CLI on an ephemeral `ubuntu-latest` runner via `setup-php`. That is short-lived CLI, not a network-facing persistent worker, so CI is not the exposed runtime.
  - The repo has no Dockerfile or Bitnami image.
- **User facts:**
  - The user does **not know** the appliance's PHP version and asked to **treat it as possibly vulnerable**.
  - The user does **not know** the SAPI and asked to **assume persistent workers** (exposed to both CVEs).
  - The storefront is **internet-facing**.
  - There is no infrastructure repo for the appliance (user-confirmed earlier).
- **Inference (not user-stated):** standard Adobe Commerce Cloud runs PHP-FPM on Linux, which is a persistent-worker SAPI. Magento 2.4.7 supports PHP 8.2/8.3.

## Assets
- **ACC appliance (cloud + on-prem): critical** (user-stated). This includes the SSH deploy key `VM_SSH_KEY` and the host it opens. PHP2Shell RCE on the appliance gives full control of it.
- Storefront customer sessions and data rendered alongside the greeting.
- Integrity of the module code shipped to ACC.

## Attackers (user: all matter)
- Anonymous internet users hitting the public storefront (user-stated reachability). The PHP2Shell attacker needs no account.
- Customers who control their own name.
- Contributors with PR/push access to the repo.
- CI supply chain (compromised action or Composer package).

## Existing controls
- `declare(strict_types=1)`, constructor DI only (no ObjectManager), PHPCS Magento2 standard, PHPUnit.
- Layer 1 runs before Layer 2 (`needs: validate`), and Layer 2 has a concurrency group.
- GitHub withholds secrets from fork PRs.
- No PHP-runtime control exists in this repo.

## Security Invariants
- **PHP runtime memory safety (CWE-416 / CWE-908 / CWE-787; CVE-2026-109618, CVE-2026-109619):** every PHP runtime that serves this module is ≥ 8.2.35 / 8.3.36 / 8.4.27 / 8.5.12, including mod_php, FPM, LSAPI and Bitnami/distro images. Gap: nothing in the repo enforces this. The fix is to raise the `composer.json` `require.php` floor to `>=8.2.35 <8.3 || >=8.3.36` (or add `config.platform.php` to match), so builds on an unpatched runtime fail Composer's platform check. The appliance's runtime is described nowhere in code and is unknown (user), so it is treated as vulnerable.
- **Output encoding / XSS (CWE-79):** `Greeter::getGreeting` returns the raw customer name. Every caller must escape it with `Magento\Framework\Escaper::escapeHtml` in templates. The anti-pattern is echoing the return value in a `.phtml`, or in JSON-in-HTML, without the escaper. Gap: no rendering code exists yet, so escaping is the caller's job.
- **Log injection (CWE-117):** names are logged through PSR-3 `LoggerInterface::info` with `sprintf`. Names in logs are user-accepted risk. CR/LF forging that reaches log parsers or alerting is not covered by that acceptance.
- **Object instantiation / DI (CWE-470):** classes are built only by constructor injection wired in `etc/di.xml`. `ObjectManager::getInstance()` and class names taken from input are forbidden (AGENTS.md, PHPCS Magento2).
- **CI/CD command injection and secrets (CWE-78, CWE-829; CICD-SEC-4/8):**
  - The `acc-build` script interpolates only `github.event.pull_request.head.sha || github.sha`, which an attacker controls only as a hex SHA. No other `${{ github.event.* }}` text (titles, branch names, bodies) may enter `script:`.
  - `VM_SSH_KEY` is used only by `appleboy/ssh-action`.
  - Gap: actions are pinned by tag rather than SHA, and same-repo PRs run on the critical appliance before review.
- **Dependency integrity (CWE-1104):** no `composer.lock` is committed, so CI resolves the newest matching versions on every run. Gap: commit a lockfile.
- **Not applicable today:** SQL injection, SSRF, path traversal, CSRF, deserialization and crypto. The module has no DB, HTTP client, file, controller or crypto code.

## Out of scope / accepted risk (user-stated)
- **Logging customer names (PII in logs) is accepted risk.** Log injection that only puts the name in the logs falls under this; log forging that reaches anything else does not.
- Siemens RUGGEDCOM APE1808 / PAN-OS: not part of this system (no such device in the repo).

## Open questions
- Actual PHP patch level and SAPI on the cloud and on-prem appliances (user doesn't know; treated as vulnerable persistent workers).
- Whether the appliance was patched after 2026-10-21 and needs a webshell hunt.
- Which templates or endpoints render the `getGreeting` output (affects XSS reach).
- Branch protection, and who can open same-repo PRs.
