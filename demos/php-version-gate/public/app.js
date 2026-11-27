'use strict';

const versionSelect = document.getElementById('version');
const runButton = document.getElementById('run');
const errorBox = document.getElementById('error');
const responseBox = document.getElementById('response');
const downloadButton = document.getElementById('download');
const summaryBox = document.getElementById('summary');
let lastResult = null;

function field(lane, name, value) {
  lane.querySelector(`[data-field="${name}"]`).textContent = value;
}

function reset() {
  lastResult = null;
  downloadButton.disabled = true;
  errorBox.hidden = true;
  responseBox.textContent = 'Run a comparison to see the response.';
  summaryBox.textContent = 'Choose a version and run the comparison. Both policies receive the same scenario input.';
  for (const policy of ['original', 'proposed']) {
    const lane = document.getElementById(policy);
    delete lane.dataset.state;
    field(lane, 'status', 'Not run');
    field(lane, 'gate', 'Waiting for a scenario.');
    field(lane, 'route', 'No request has been simulated.');
    field(lane, 'impact', 'No marker has been generated.');
    field(lane, 'marker', 'Not run');
  }
}

function render(result) {
  const version = result.selectedScenarioVersion;
  for (const policy of ['original', 'proposed']) {
    const lane = document.getElementById(policy);
    const data = result.results[policy];
    const accepted = data.gate === 'accepted';
    const hasMarker = data.simulatedMarker !== null;
    lane.dataset.state = hasMarker ? 'warning' : 'good';
    field(lane, 'status', accepted ? 'Accepted' : 'Blocked');
    field(lane, 'gate', `Scenario PHP ${version} ${accepted ? 'matches' : 'does not match'} this version constraint.`);
    field(lane, 'route', accepted
      ? 'In this fictional deployment, a request would reach the storefront.'
      : 'The policy rejects the scenario before deployment. No request is simulated.');
    field(lane, 'impact', hasMarker
      ? 'A fixed string illustrates possible impact under the supplied assumptions. Nothing executes.'
      : accepted ? 'No marker: this scenario meets the supplied floor. This is not proof of security.'
        : 'No marker: this scenario was blocked by the version policy.');
    field(lane, 'marker', hasMarker ? data.simulatedMarker : 'No marker');
  }
  const original = result.results.original;
  const proposed = result.results.proposed;
  summaryBox.textContent = original.simulatedMarker
    ? `PHP ${version} passes the original range but is rejected by the proposed floor. The original lane shows a fictional marker only. This demonstrates a version-policy gap, not a CVE exploit.`
    : original.gate === 'blocked' && proposed.gate === 'blocked'
      ? `PHP ${version} is outside both policies. A newer version can still be rejected by a compatibility ceiling; this is not a vulnerability verdict.`
      : `PHP ${version} passes both version policies and shows no marker under the supplied assumptions. Passing a version check alone does not establish that a storefront is secure.`;
  responseBox.textContent = JSON.stringify(result, null, 2);
  document.getElementById('actual-version').textContent = result.actualRuntime.phpVersion;
  document.getElementById('actual-sapi').textContent = result.actualRuntime.sapi;
  lastResult = result;
  downloadButton.disabled = false;
}

versionSelect.addEventListener('change', reset);
runButton.addEventListener('click', async () => {
  reset();
  runButton.disabled = true;
  versionSelect.disabled = true;
  runButton.textContent = 'Comparing…';
  try {
    const response = await fetch('/api/compare', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ version: versionSelect.value }),
      signal: AbortSignal.timeout(10000),
    });
    if (!response.ok) throw new Error('The comparison failed. Retry the local demo.');
    render(await response.json());
  } catch (error) {
    errorBox.textContent = error.name === 'TimeoutError'
      ? 'The demo did not respond in time. Retry when the server is available.'
      : 'Could not reach the demo API. Check that the demo server is running, then retry.';
    errorBox.hidden = false;
    summaryBox.textContent = 'No comparison result is available. An error is not a blocked version gate.';
  } finally {
    runButton.disabled = false;
    versionSelect.disabled = false;
    runButton.textContent = 'Run comparison →';
  }
});

downloadButton.addEventListener('click', () => {
  if (!lastResult) return;
  const blob = new Blob([JSON.stringify(lastResult, null, 2) + '\n'], { type: 'application/json' });
  const objectUrl = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = objectUrl;
  link.download = `SIMULATION-ONLY-php-${lastResult.selectedScenarioVersion}.json`;
  document.body.appendChild(link);
  link.click();
  link.remove();
  setTimeout(() => URL.revokeObjectURL(objectUrl), 1000);
});

fetch('/api/runtime', { signal: AbortSignal.timeout(10000) })
  .then(response => {
    if (!response.ok) throw new Error('Runtime unavailable');
    return response.json();
  })
  .then(runtime => {
    document.getElementById('actual-version').textContent = runtime.phpVersion;
    document.getElementById('actual-sapi').textContent = runtime.sapi;
  })
  .catch(() => {
    document.getElementById('actual-version').textContent = 'Unavailable';
    document.getElementById('actual-sapi').textContent = 'Unavailable';
  });
