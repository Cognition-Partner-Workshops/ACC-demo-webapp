# PHP version gate: standalone simulation

A local, interactive workshop demo comparing the ACC module's PHP constraint with the illustrative floor in the supplied finding. **It is not a PHP2Shell exploit, a vulnerable Magento deployment, or evidence confirming a CVE.** Running the demo does not modify the Magento module or any code-scan record.

## Run

Requires Docker Engine with Compose v2 or later. The pinned image is fetched on first run. No credentials, Composer installation, or Magento services are needed.

From the repository root:

```sh
cd demos/php-version-gate
docker compose up -d
```

On **your own computer**, open `http://127.0.0.1:8080`. In Devin, use the authenticated browser preview. The host port binds to loopback, not to all host interfaces. If port 8080 is busy, stop the conflicting local service or change the host port in `compose.yaml` and the test URL together.

```sh
docker compose down
```

The PHP container is non-root, has a read-only filesystem and source mounts, drops all Linux capabilities, and sits only on an internal Docker network with no gateway. A small read-only nginx proxy is the only service published, on host loopback, so the browser can reach the demo. The app makes no outbound requests, stores no application data, and runs no shell commands or dynamic code. Source mounts are read-only; the proxy uses disposable memory-backed cache directories. The Docker command disables PHP process-execution functions as an additional precaution. The demo uses PHP's development server; do not publish it as a production service.

## Present the demo

1. Keep **8.3.0** selected. Click **Run comparison**. The original policy accepts the version and displays `SIMULATED-IMPACT-ONLY`. The proposed policy rejects the version and displays no marker.
2. Select **8.3.36**, then run again. Both gates accept it. Neither displays a marker under the supplied assumptions.
3. Select **8.4.27**. Both gates reject it because the policies stop before PHP 8.4. Newer is not the same as compatible.
4. Expand **Inspect the demo API response**, then download the JSON. It explicitly records `simulation: true`, the actual PHP version, and the different selected scenario version.

The fictional request never leaves this app. The marker is a constant returned by ordinary PHP code. It does not represent a command or payload executing.

## What is real, and what is modeled?

| Element | Meaning |
| --- | --- |
| Version-range evaluation | Runs server-side using PHP `version_compare` for preset stable versions only. |
| Original range | `~8.1.0 || ~8.2.0 || ~8.3.0`, taken from the supplied ACC module. |
| Proposed range | `>=8.2.35 <8.3 || >=8.3.36 <8.4`, taken from the supplied finding. |
| Actual runtime | Pinned PHP image, observed as 8.3.37 in this session. Reported by the server, never changed by the selector. This is not a claim that the image is free of vulnerabilities. |
| Routing and impact | Fictional, deterministic story illustrating a missing version floor. No exploit is present. |
| CVEs / fixed releases | CVE-2026-109618, CVE-2026-109619 and their claimed fixes were **not independently verified**. Treat them as unverified input, not as an advisory. |
| Code-scan finding | Remains **inconclusive**. This simulation cannot confirm or dismiss it. |

The matcher is intentionally narrow. It is not a general Composer constraint parser and does not run `composer install`. The simulated gate represents enforcement of a deployment policy; the real ACC build script and storefront remain uninspected. This app does not inspect distro backports, Apache modules, PHP-FPM, actual request reachability, or runtime vulnerabilities.

Real validation needs a verified vendor advisory and an authorized representative target. A PHP version string alone, especially for distro-patched builds, does not prove remote code execution.

## Verify

With the demo running:

```sh
docker compose exec -T demo php /app/tests/policy_test.php
python3 tests/test_api.py
```

The PHP suite covers stable-version policy boundaries, marker conditions, invalid versions, and deterministic results. The Python standard-library suite checks the live HTTP API, preset consistency, response labeling, invalid requests, source-file denial, security headers and asset delivery. It does not automate a browser.

Example harmless request:

```sh
curl --fail http://127.0.0.1:8080/api/compare \
  -H 'Content-Type: application/json' \
  -d '{"version":"8.3.0"}'
```

The API only accepts the listed versions. It has no URL, filename, command, or executable payload input.

## Files

- `compose.yaml`: isolated, pinned local runtime and loopback-only proxy.
- `nginx.conf`: proxy to the internal PHP service.
- `policy.php`: stable-version range evaluation and fixed simulation output.
- `router.php`: allowlisted static routes and bounded JSON API.
- `public/`: responsive interface, no external assets or dependencies.
- `tests/`: policy and HTTP checks.
