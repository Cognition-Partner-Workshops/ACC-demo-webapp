"""Local-only API, transport and markup checks. No exploit payloads."""
import json
from html.parser import HTMLParser
from urllib.error import HTTPError
from urllib.request import Request, urlopen
import unittest

baseUrl = "http://127.0.0.1:8080"


def request(path, method="GET", body=None, headers=None):
    req = Request(baseUrl + path, data=body, method=method, headers=headers or {})
    try:
        with urlopen(req, timeout=10) as response:
            return response.status, response.headers, response.read()
    except HTTPError as error:
        return error.code, error.headers, error.read()


class PageParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.ids = []
        self.presets = []

    def handle_starttag(self, tag, attrs):
        attributes = dict(attrs)
        if "id" in attributes:
            self.ids.append(attributes["id"])
        if tag == "option":
            self.presets.append(attributes["value"])


class DemoTests(unittest.TestCase):
    def post(self, value):
        return request("/api/compare", "POST", json.dumps(value).encode(),
                       {"Content-Type": "application/json"})

    def test_presets_and_real_runtime(self):
        status, _, body = request("/api/runtime")
        self.assertEqual(status, 200)
        runtime = json.loads(body)
        self.assertTrue(runtime["simulation"])
        self.assertEqual(runtime["sapi"], "cli-server")
        for version in runtime["presets"]:
            with self.subTest(version=version):
                status, _, body = self.post({"version": version})
                self.assertEqual(status, 200)
                data = json.loads(body)
                self.assertTrue(data["simulation"])
                self.assertEqual(data["actualRuntime"]["phpVersion"], runtime["phpVersion"])
                self.assertEqual(data["selectedScenarioVersion"], version)
                self.assertEqual(data["cveVerification"], "not_independently_verified")
                self.assertIsNone(data["results"]["proposed"]["simulatedMarker"])
                self.assertEqual(body, self.post({"version": version})[2])

    def test_old_version_marker_and_gate(self):
        status, _, body = self.post({"version": "8.3.0"})
        self.assertEqual(status, 200)
        results = json.loads(body)["results"]
        self.assertEqual(results["original"]["gate"], "accepted")
        self.assertEqual(results["original"]["simulatedMarker"], "SIMULATED-IMPACT-ONLY")
        self.assertEqual(results["proposed"]["gate"], "blocked")
        self.assertEqual(results["proposed"]["simulatedRequest"], "not_routed")

    def test_floor_and_ceiling(self):
        for version in ["8.2.35", "8.3.36", "8.3.37"]:
            results = json.loads(self.post({"version": version})[2])["results"]
            for result in results.values():
                self.assertEqual(result["gate"], "accepted")
                self.assertIsNone(result["simulatedMarker"])
        results = json.loads(self.post({"version": "8.4.27"})[2])["results"]
        for result in results.values():
            self.assertEqual(result["gate"], "blocked")
            self.assertIsNone(result["simulatedMarker"])

    def test_invalid_inputs(self):
        for value in [{}, [], None, "8.3.0", 83, {"version": None},
                      {"version": ["8.3.0"]}, {"version": "unknown"},
                      {"version": "8.3.0", "extra": "ignored?"}]:
            with self.subTest(value=value):
                self.assertEqual(self.post(value)[0], 400)
        self.assertEqual(request("/api/compare", "POST", b"{", {"Content-Type": "application/json"})[0], 400)
        self.assertEqual(request("/api/compare", "POST", b"{}")[0], 415)
        self.assertEqual(request("/api/compare", "POST", b" " * 1025,
                                 {"Content-Type": "application/json"})[0], 413)

    def test_http_controls(self):
        self.assertEqual(request("/api/compare")[0], 405)
        self.assertEqual(request("/api/runtime", "POST")[0], 405)
        self.assertEqual(request("/", "POST")[0], 405)
        self.assertEqual(request("/api/compare", "POST", b'{"version":"8.3.0"}',
                                 {"Content-Type": "application/json", "Sec-Fetch-Site": "cross-site"})[0], 403)
        for path in ["/policy.php", "/router.php", "/compose.yaml", "/tests/policy_test.php", "/missing"]:
            self.assertEqual(request(path)[0], 404)
        _, headers, _ = request("/")
        self.assertIn("script-src 'self'", headers["Content-Security-Policy"])
        self.assertEqual(headers["Cache-Control"], "no-store")
        self.assertEqual(headers["X-Content-Type-Options"], "nosniff")
        self.assertEqual(request("/", "HEAD")[2], b"")

    def test_markup_and_assets(self):
        status, _, body = request("/")
        self.assertEqual(status, 200)
        parser = PageParser()
        parser.feed(body.decode())
        self.assertEqual(len(parser.ids), len(set(parser.ids)))
        for requiredId in ["version", "run", "original", "proposed", "response", "download", "error", "summary"]:
            self.assertIn(requiredId, parser.ids)
        runtime = json.loads(request("/api/runtime")[2])
        self.assertEqual(parser.presets, runtime["presets"])
        for asset, contentType in [("/app.js", "text/javascript"), ("/style.css", "text/css")]:
            status, headers, _ = request(asset)
            self.assertEqual(status, 200)
            self.assertTrue(headers["Content-Type"].startswith(contentType))


if __name__ == "__main__":
    unittest.main(verbosity=2)
