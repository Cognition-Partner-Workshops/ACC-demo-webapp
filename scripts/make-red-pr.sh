#!/usr/bin/env bash
#
# make-red-pr.sh — regenerate a fresh "ACC build fails" demo branch on demand.
#
# Creates a throwaway branch from origin/main, plants a build-only defect that PASSES
# Layer 1 (php -l, PHPCS, PHPUnit) but FAILS Layer 2 (the real Magento `setup:di:compile`
# on the ACC environment), pushes it, and prints the command to open the PR.
#
# Use this when the canonical red PR has already been fixed and you need a clean
# red→green story for a live demo.
#
# Usage: scripts/make-red-pr.sh
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "${ROOT}"

BRANCH="demo/force-red-$(date +%s)"
MODEL_DIR="app/code/Example/Storefront/Model"
CLASS_FILE="${MODEL_DIR}/PromoCalculator.php"

git fetch origin --quiet
git switch -c "${BRANCH}" origin/main

cat > "${CLASS_FILE}" <<'PHP'
<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace Example\Storefront\Model;

use Example\Storefront\Api\PricingRuleProviderInterface;

/**
 * Calculates promotional pricing for the storefront.
 *
 * NOTE: depends on PricingRuleProviderInterface, which intentionally does not exist —
 * this passes local lint/PHPCS but breaks the real Magento di:compile build.
 */
class PromoCalculator
{
    /**
     * @param PricingRuleProviderInterface $pricingRuleProvider
     */
    public function __construct(private readonly PricingRuleProviderInterface $pricingRuleProvider)
    {
    }

    /**
     * Apply the active promotional rule to a base price.
     *
     * @param float $basePrice
     * @return float
     */
    public function apply(float $basePrice): float
    {
        $discount = $this->pricingRuleProvider->getDiscountFactor();

        return round($basePrice * $discount, 2);
    }
}
PHP

git add "${CLASS_FILE}"
git commit -m "feat: add PromoCalculator service for storefront promotions"

git push -u origin "${BRANCH}"

cat <<EOF

================================================================================
Red demo branch pushed: ${BRANCH}

Open the PR (Layer 1 will pass, Layer 2 will fail on di:compile):

  gh pr create --base main --head ${BRANCH} \\
    --title "feat: add PromoCalculator service for storefront promotions" \\
    --body "Adds a promotional pricing calculator to Example_Storefront."

Expected: Layer 1 green; Layer 2 red with
  Class "Example\\Storefront\\Api\\PricingRuleProviderInterface" does not exist
Then hand the red PR to Devin to fix from the failed CI log.
================================================================================
EOF
