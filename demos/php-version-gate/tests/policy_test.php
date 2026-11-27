<?php
/**
 * Copyright © Example Co. All rights reserved.
 */

declare(strict_types=1);

require dirname(__DIR__) . '/policy.php';

$checks = 0;
/**
 * Check a policy assertion.
 *
 * @param bool $condition
 * @param string $description
 * @return void
 */
function check(bool $condition, string $description): void
{
    global $checks;
    if (!$condition) {
        fwrite(STDERR, "FAIL: $description\n");
        exit(1);
    }
    ++$checks;
}

$cases = [
    ['8.0.99', false, false], ['8.1.0', true, false], ['8.1.34', true, false],
    ['8.2.0', true, false], ['8.2.9', true, false], ['8.2.34', true, false],
    ['8.2.35', true, true], ['8.2.99', true, true],
    ['8.3.0', true, false], ['8.3.9', true, false], ['8.3.35', true, false],
    ['8.3.36', true, true], ['8.3.37', true, true], ['8.3.99', true, true],
    ['8.4.0', false, false], ['8.4.27', false, false], ['8.5.12', false, false],
    ['9.0.0', false, false],
];
foreach ($cases as [$version, $original, $proposed]) {
    check(\VersionGateDemo\acceptsVersion($version, 'original') === $original, "original $version");
    check(\VersionGateDemo\acceptsVersion($version, 'proposed') === $proposed, "proposed $version");
}
foreach (\VersionGateDemo\PRESETS as $version) {
    $first = \VersionGateDemo\comparison($version);
    $second = \VersionGateDemo\comparison($version);
    check($first === $second, "deterministic $version");
    check($first['simulation'] === true, "simulation tag $version");
    check($first['actualRuntime']['phpVersion'] === PHP_VERSION, "actual runtime $version");
    check($first['selectedScenarioVersion'] === $version, "scenario runtime $version");
    check($first['results']['proposed']['simulatedMarker'] === null, "proposed never shows marker $version");
    check($first['results']['original']['simulatedMarker'] ===
        (\VersionGateDemo\belowSuppliedFloor($version) ? \VersionGateDemo\MARKER : null), "marker $version");
}
foreach (['8.3', '8.3.0RC1', '8.3.0\n', 'not-a-version', '8.3.36.1'] as $invalidVersion) {
    try {
        \VersionGateDemo\acceptsVersion($invalidVersion, 'original');
        check(false, "invalid rejected $invalidVersion");
    } catch (\InvalidArgumentException) {
        check(true, 'invalid version rejected');
    }
}
try {
    \VersionGateDemo\comparison('8.3.99');
    check(false, 'unlisted preset rejected');
} catch (\InvalidArgumentException) {
    check(true, 'unlisted preset rejected');
}
echo "PASS: $checks policy assertions\n";
