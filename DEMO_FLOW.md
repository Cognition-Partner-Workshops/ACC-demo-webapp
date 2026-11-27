# Progressive Demo Flow: Devin for Adobe Commerce Cloud

This demo shows how Devin accelerates development on an Adobe Commerce Cloud (ACC) project by
combining repository-level code guardrails with a real Magento build environment. The story is not
"Devin fixed a lint error." The story is: **Devin can reason from authentic ACC build failures,
remediate the custom module, and keep the storefront protected through rollback.**

## Demo thesis

ACC teams lose time when code passes local checks but fails during the slower Magento build phase:
missing dependency-injection classes, invalid preferences, broken XML, circular dependencies, and
other issues that only appear when Magento compiles the full application graph.

This repository demonstrates a safer loop:

1. Developers and Devin work only in the custom module repository.
2. Fast validation catches syntax, coding-standards, and unit-test issues.
3. CI deploys the module to a real Magento/ACC-like environment and runs the build.
4. If the build fails, the platform rolls back to the last good module state.
5. Devin reads the real failed CI log, fixes the root cause, pushes, and turns CI green.

## What is already built

| Component | Purpose |
| --- | --- |
| `main` branch | Clean baseline with the working `Example_Storefront` module. |
| `demo/catalog-badge` branch | Intentional build-only defect for the live fail/fix demo. |
| `.github/workflows/ci.yml` | Two-layer CI pipeline: code validation, then real Magento build over SSH. |
| `.devin/skills/acc-standards/SKILL.md` | Devin-facing coding standards and ACC build failure diagnosis guide. |
| ACC platform VM | Persistent Magento 2.4.7-p3 environment with rollback-aware deploy/build script. |
| `RUNBOOK.md` | Operational steps to spin down, restart, and rotate the VM IP / `VM_HOST` secret. |

## Characters in the story

- **Engineer / presenter:** asks for a small Magento customization and opens/re-runs a PR.
- **GitHub Actions:** proves what fast validation can and cannot catch.
- **ACC build environment:** runs the real Magento build (`setup:upgrade`, `setup:di:compile`).
- **Devin:** uses repository standards plus CI failure logs to produce the correct fix.
- **Business stakeholder:** sees that a failed build does not take the storefront down.

## Recommended live narrative

### 1. Open with the problem

Message to the audience:

> "On Adobe Commerce projects, the painful failures are often not basic syntax errors. They happen
> after code is pushed, when Magento compiles the dependency graph in the cloud build. That costs
> developer time and creates release risk. This demo shows Devin closing that loop."

Show:

- The repository is intentionally small: it contains only `app/code/Example/Storefront`, not Magento core.
- The platform/Magento runtime is separate, mirroring how custom code is deployed to ACC.
- The CI file has two jobs: `Layer 1 - Code validation` and `Layer 2 - ACC build (real Magento)`.

### 2. Show the guardrails before any failure

Open these files:

- `AGENTS.md`
- `.devin/skills/acc-standards/SKILL.md`
- `phpcs.xml.dist`
- `.github/workflows/ci.yml`

Key talking points:

- Devin has persistent project instructions, not just a one-off prompt.
- Standards are encoded where Devin can read them before changing code.
- CI enforces the same discipline: PHP lint, Magento PHPCS, and PHPUnit first.
- The real value comes after those fast checks pass: Magento build validation.

### 3. Establish the green baseline

Use `main` as the known-good state.

Expected result:

- `Layer 1 - Code validation` is green.
- `Layer 2 - ACC build (real Magento)` is green.
- The storefront is healthy.

Presenter framing:

> "This is the baseline. The custom module can be deployed into Magento, compiled, and served. Now
> we'll introduce the kind of defect that normally burns time because it is only visible in the ACC
> build."

### 4. Force CI red with a build-only Magento defect

Fastest path: use the existing branch/PR from `demo/catalog-badge`.

That branch adds a `CatalogBadge` service but intentionally injects a missing interface:

```php
use Example\Storefront\Api\StockResolverInterface;

public function __construct(StockResolverInterface $stockResolver)
```

`StockResolverInterface` does not exist, so the code is syntactically valid and standards-compliant
enough for Layer 1, but Magento cannot compile the dependency graph.

Expected CI behavior:

1. `Layer 1 - Code validation` passes.
2. `Layer 2 - ACC build (real Magento)` fails during `setup:di:compile`.
3. The build log shows a real Magento error similar to:

```text
Impossible to process constructor argument Parameter #0 [ <required>
  Example\Storefront\Api\StockResolverInterface ] of CatalogBadge class
Class "Example\Storefront\Api\StockResolverInterface" does not exist
Rolling back module to last good state
ACC BUILD FAILED
```

Presenter framing:

> "This is the important failure mode. The code made it through fast validation, but the real
> Magento build caught a dependency-injection problem. This is exactly the type of failure Devin can
> remediate from CI context."

### 5. Prove rollback protects the storefront

After the failed build, show that the storefront is still healthy.

Expected result:

- Storefront returns HTTP 200.
- The previously deployed good module version remains active.
- The broken `CatalogBadge` code is not left deployed in the Magento runtime.

Presenter framing:

> "The build failed, but the customer-facing site did not go down. We have a safe failure and a clear
> machine-readable error for Devin to act on."

### 6. Hand the red PR to Devin

Use a concise prompt such as:

```text
Please investigate the failing ACC CI on this PR, use the ACC standards in the repo, fix the
root cause, run the relevant local validation, and push the fix back to the branch.
```

What Devin should do:

1. Read the failing GitHub Actions logs.
2. Identify `Example\Storefront\Api\StockResolverInterface` as the missing service contract.
3. Apply the minimal standards-compliant fix, likely by adding:
   - `app/code/Example/Storefront/Api/StockResolverInterface.php`
   - `app/code/Example/Storefront/Model/StockResolver.php`
   - a `di.xml` preference from the interface to the implementation
   - unit coverage for the new behavior if useful for the narrative
4. Run local validation.
5. Push the fix.

Expected result after Devin pushes:

- CI re-runs automatically.
- Layer 1 passes.
- Layer 2 deploys to the ACC environment and `setup:di:compile` passes.
- PR turns green.

Presenter framing:

> "Devin did not guess from a vague failure. It used the same signal a Magento engineer would use:
> the real `di:compile` error from the ACC build log, plus the repository's coding standards."

### 7. Close with the business value

End state to show:

- The PR moved from red to green.
- The code remains standards-compliant.
- The build passed in the real Magento environment.
- Rollback kept the storefront healthy during the failed attempt.

Closing message:

> "This is the developer loop compressed: create feature, validate fast, catch real ACC failures,
> automatically remediate from CI, and protect production-like runtime state. Devin becomes the
> engineer who can keep iterating until the actual cloud build is green."

## How to force CI red on demand

### Option A: Use the existing red demo PR

Use the PR backed by `demo/catalog-badge`. It already contains the intentional build-only defect.
If the failed check is stale, re-run the GitHub Actions jobs from the PR checks UI.

Use this option for the cleanest live demo because the failure is already known and reproducible.

### Option B: Recreate the red branch from `main`

If the demo PR has been fixed and you need a fresh red PR, run the helper script. It creates a
throwaway branch from `origin/main`, plants a build-only defect, and pushes it:

```bash
scripts/make-red-pr.sh
```

The script prints the `gh pr create` command to open the PR.

Expected outcome:

- Layer 1 passes.
- Layer 2 fails with a missing-interface Magento DI compile error
  (`Class "Example\Storefront\Api\PricingRuleProviderInterface" does not exist`).
- The ACC build script rolls back to the last good deployed module.

### Option C: Manually introduce the same class of defect

In any feature branch, add or modify a constructor dependency or `di.xml` preference so it references
a class/interface that does not exist. Keep the PHP syntactically valid and do not add unit coverage
that instantiates the broken class.

Example defect pattern:

```php
use Example\Storefront\Api\MissingInterface;

public function __construct(MissingInterface $missing)
```

Why this works:

- PHP lint can parse unknown type hints.
- PHPCS checks style, not Magento object graph validity.
- Unit tests only fail if they instantiate the broken path.
- Magento `setup:di:compile` resolves the full dependency graph and fails.

## How to force Devin remediation

For the most compelling demo, do not tell Devin the exact fix. Give it the outcome and let it use CI:

```text
This PR is failing the ACC build. Please inspect the failed CI logs, identify the Magento root cause,
fix it according to the repo's ACC standards, and push the remediation.
```

A successful Devin remediation should include:

- A specific explanation of the failed Magento dependency-injection path.
- A code fix rather than a CI bypass.
- Passing local validation before push.
- A new commit on the PR branch.
- Green GitHub checks after the re-run.

## Backup paths for a live presentation

| Situation | Backup move |
| --- | --- |
| GitHub Actions is slow | Show the existing failed CI log, then ask Devin to fix from that log. |
| PR was already fixed | Recreate the red branch with Option B. |
| Layer 1 fails unexpectedly | Position it as an earlier guardrail; have Devin fix that first, then continue to Layer 2. |
| ACC build environment is busy | The workflow has concurrency enabled; explain that real shared build environments should serialize deploys. |
| Storefront health is questioned | Show that the failed build rolled back and the storefront still returns HTTP 200. |

## Suggested demo pacing

1. **2 minutes:** Explain repository/platform split and why ACC build failures matter.
2. **3 minutes:** Show guardrails (`AGENTS.md`, skill, CI workflow).
3. **4 minutes:** Show green baseline and the failing `demo/catalog-badge` PR.
4. **3 minutes:** Highlight the real Magento `di:compile` error and rollback.
5. **5-10 minutes:** Ask Devin to remediate; narrate its investigation and pushed fix.
6. **2 minutes:** Show CI green and summarize the value.

## Sound bites

- "Fast checks catch common mistakes; the ACC layer catches Magento reality."
- "This is not a mocked failure. It is the Magento compiler rejecting the dependency graph."
- "The failed build is safe because rollback keeps the last good module deployed."
- "Devin is using the same CI evidence a senior Magento engineer would use, then pushing the fix."
- "The demo shows autonomous remediation of cloud-build failures, not just code generation."
