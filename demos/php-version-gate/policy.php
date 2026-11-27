<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

namespace VersionGateDemo;

const ORIGINAL_CONSTRAINT = '~8.1.0 || ~8.2.0 || ~8.3.0';
const PROPOSED_CONSTRAINT = '>=8.2.35 <8.3 || >=8.3.36 <8.4';
const PRESETS = [
    '8.1.34', '8.2.0', '8.2.34', '8.2.35',
    '8.3.0', '8.3.35', '8.3.36', '8.3.37', '8.4.27',
];
const MARKER = 'SIMULATED-IMPACT-ONLY';

/**
 * Evaluate only stable numeric versions, not general Composer syntax.
 *
 * @param string $version
 * @param string $policy
 * @return bool
 * @throws \InvalidArgumentException
 */
function acceptsVersion(string $version, string $policy): bool
{
    if (!preg_match('/\A\d+\.\d+\.\d+\z/D', $version)) {
        throw new \InvalidArgumentException('Use a stable three-part version.');
    }
    if ($policy === 'original') {
        return version_compare($version, '8.1.0', '>=')
            && version_compare($version, '8.4.0', '<');
    }
    if ($policy === 'proposed') {
        return (version_compare($version, '8.2.35', '>=')
                && version_compare($version, '8.3.0', '<'))
            || (version_compare($version, '8.3.36', '>=')
                && version_compare($version, '8.4.0', '<'));
    }
    throw new \InvalidArgumentException('Unknown policy.');
}

/**
 * Compare against assumptions from the supplied finding, not a verified advisory.
 *
 * @param string $version
 * @return bool
 */
function belowSuppliedFloor(string $version): bool
{
    return version_compare($version, '8.2.35', '<')
        || (version_compare($version, '8.3.0', '>=')
            && version_compare($version, '8.3.36', '<'));
}

/**
 * Build a deterministic simulation; no command, request, or deployment is run.
 *
 * @param string $version
 * @return array
 * @throws \InvalidArgumentException
 */
function comparison(string $version): array
{
    if (!in_array($version, PRESETS, true)) {
        throw new \InvalidArgumentException('Choose a listed version.');
    }
    $results = [];
    foreach (['original', 'proposed'] as $policy) {
        $accepted = acceptsVersion($version, $policy);
        $showMarker = $accepted && belowSuppliedFloor($version);
        $results[$policy] = [
            'constraint' => $policy === 'original' ? ORIGINAL_CONSTRAINT : PROPOSED_CONSTRAINT,
            'gate' => $accepted ? 'accepted' : 'blocked',
            'simulatedRequest' => $accepted ? 'would_reach_demo_storefront' : 'not_routed',
            'simulatedMarker' => $showMarker ? MARKER : null,
            'outcome' => !$accepted ? 'blocked_by_version_policy'
                : ($showMarker ? 'simulated_impact_only' : 'no_marker_at_supplied_floor'),
        ];
    }
    return [
        'simulation' => true,
        'cveVerification' => 'not_independently_verified',
        'actualRuntime' => ['phpVersion' => PHP_VERSION, 'sapi' => PHP_SAPI],
        'selectedScenarioVersion' => $version,
        'scenarioBelowSuppliedFloor' => belowSuppliedFloor($version),
        'results' => $results,
        'notice' => 'Version comparisons run in PHP. Routing and impact are fictional. '
            . 'No exploit, shell command, deployment, or scan-status update occurs. '
            . 'No CVE exploitability is established. Thresholds are supplied assumptions.',
    ];
}
