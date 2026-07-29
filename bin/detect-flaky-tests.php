#!/usr/bin/env php
<?php

/**
 * Basic flaky test detection from JUnit XML reports.
 *
 * Usage:
 *   php bin/detect-flaky-tests.php <artifacts-dir>
 *
 * Heuristics:
 *   1. Tests with status="error" or "failure" (potential flakiness)
 *   2. Tests with unusually high duration (>5x median)
 *   3. Tests with zero duration (potential issue)
 *   4. Skipped/incomplete tests
 */
$artifactsDir = $argv[1] ?? 'build/artifacts';

if (!is_dir($artifactsDir)) {
    echo "Directory not found: {$artifactsDir}\n";
    exit(0);
}

$xmlFiles = glob("{$artifactsDir}/**/*.xml", GLOB_BRACE);

if (empty($xmlFiles)) {
    echo "No JUnit XML files found in {$artifactsDir}\n";
    exit(0);
}

$allTests    = [];
$flakyTests  = [];
$slowTests   = [];
$zeroTests   = [];
$failedTests = [];
$skippedTests = [];

foreach ($xmlFiles as $xmlFile) {
    $xml = @simplexml_load_file($xmlFile);
    if (!$xml) continue;

    foreach ($xml->testsuite as $testsuite) {
        $suiteName = (string) ($testsuite['name'] ?? basename($xmlFile));

        foreach ($testsuite->testcase as $testcase) {
            $name   = (string) ($testcase['name'] ?? 'unknown');
            $class  = (string) ($testcase['classname'] ?? '');
            $time   = (float) ($testcase['time'] ?? 0);
            $full   = $class ? "{$class}::{$name}" : $name;

            $testInfo = [
                'suite'  => $suiteName,
                'name'   => $full,
                'time'   => $time,
                'status' => 'pass',
            ];

            // Check for failures/errors
            if (isset($testcase->failure) || isset($testcase->error)) {
                $testInfo['status'] = 'fail';
                $failedTests[]      = $testInfo;
            }

            // Check for skipped/incomplete
            if (isset($testcase->skipped) || isset($testcase->incomplete)) {
                $testInfo['status'] = 'skipped';
                $skippedTests[]     = $testInfo;
            }

            // Check for zero duration
            if ($time == 0 && $testInfo['status'] === 'pass') {
                $zeroTests[] = $testInfo;
            }

            $allTests[] = $testInfo;
        }
    }
}

if (empty($allTests)) {
    echo "No test cases found in JUnit reports.\n";
    exit(0);
}

// ── Calculate statistics ────────────────────────────────────────────

$times     = array_column($allTests, 'time');
$nonZero   = array_filter($times, fn($t) => $t > 0);
$median    = 0;
$mean      = 0;

if (!empty($nonZero)) {
    sort($nonZero);
    $count = count($nonZero);
    $mid   = intdiv($count, 2);
    $median = ($count % 2 === 0)
        ? ($nonZero[$mid - 1] + $nonZero[$mid]) / 2
        : $nonZero[$mid];
    $mean = array_sum($nonZero) / $count;
}

$threshold = max($median * 5, 5.0); // 5x median or minimum 5 seconds

foreach ($allTests as $test) {
    if ($test['time'] > $threshold && $test['status'] === 'pass') {
        $slowTests[] = $test;
    }
}

// ── Output ──────────────────────────────────────────────────────────

echo "═══════════════════════════════════════\n";
echo "  Flaky Test Detection Report\n";
echo "═══════════════════════════════════════\n\n";

echo 'Total tests  : ' . count($allTests) . "\n";
echo 'Failed       : ' . count($failedTests) . "\n";
echo 'Skipped      : ' . count($skippedTests) . "\n";
echo 'Zero-duration: ' . count($zeroTests) . "\n";
echo "Slow (>{$threshold}s): " . count($slowTests) . "\n";
echo 'Median time  : ' . round($median, 3) . "s\n";
echo 'Mean time    : ' . round($mean, 3) . "s\n";

// Report failed tests
if (!empty($failedTests)) {
    echo "\n─── Failed Tests ───\n";
    foreach (array_slice($failedTests, 0, 20) as $test) {
        echo "  ✗ {$test['name']} ({$test['time']}s)\n";
    }
    if (count($failedTests) > 20) {
        echo '  ... and ' . (count($failedTests) - 20) . " more\n";
    }
}

// Report zero-duration tests
if (!empty($zeroTests)) {
    echo "\n─── Zero-Duration Tests (possible stubs / skipped) ───\n";
    foreach (array_slice($zeroTests, 0, 10) as $test) {
        echo "  ⚠ {$test['name']}\n";
    }
    if (count($zeroTests) > 10) {
        echo '  ... and ' . (count($zeroTests) - 10) . " more\n";
    }
}

// Report slow tests
if (!empty($slowTests)) {
    echo "\n─── Potentially Flaky (slow) Tests ───\n";
    usort($slowTests, fn($a, $b) => $b['time'] <=> $a['time']);
    foreach (array_slice($slowTests, 0, 10) as $test) {
        echo "  ⚠ {$test['name']} ({$test['time']}s)\n";
    }
    if (count($slowTests) > 10) {
        echo '  ... and ' . (count($slowTests) - 10) . " more\n";
    }
}

// Report skipped tests
if (!empty($skippedTests)) {
    echo "\n─── Skipped / Incomplete Tests ───\n";
    foreach (array_slice($skippedTests, 0, 10) as $test) {
        echo "  ⏭ {$test['name']}\n";
    }
    if (count($skippedTests) > 10) {
        echo '  ... and ' . (count($skippedTests) - 10) . " more\n";
    }
}

echo "\n═══════════════════════════════════════\n";

// ── Write to GITHUB_STEP_SUMMARY ───────────────────────────────────

$summaryFile = getenv('GITHUB_STEP_SUMMARY');
if ($summaryFile) {
    $summary  = "\n## 🐛 Flaky Test Detection\n\n";
    $summary .= "| Metric | Count |\n";
    $summary .= "|--------|-------|\n";
    $summary .= '| Total tests | ' . count($allTests) . " |\n";
    $summary .= '| Failed | ' . count($failedTests) . " |\n";
    $summary .= '| Skipped | ' . count($skippedTests) . " |\n";
    $summary .= '| Zero-duration | ' . count($zeroTests) . " |\n";
    $summary .= "| Slow (>{$threshold}s) | " . count($slowTests) . " |\n";

    if (!empty($slowTests)) {
        $summary .= "\n### Slowest Tests\n\n";
        $summary .= "| Test | Duration (s) |\n";
        $summary .= "|------|-------------|\n";
        foreach (array_slice($slowTests, 0, 5) as $test) {
            $summary .= "| `{$test['name']}` | {$test['time']} |\n";
        }
    }

    if (!empty($failedTests)) {
        $summary .= "\n### Failed Tests\n\n";
        $summary .= "| Test | Duration (s) |\n";
        $summary .= "|------|-------------|\n";
        foreach (array_slice($failedTests, 0, 10) as $test) {
            $summary .= "| `{$test['name']}` | {$test['time']} |\n";
        }
    }

    file_put_contents($summaryFile, $summary, FILE_APPEND);
}

// Exit with non-zero if there are many failures (potential systemic flakiness)
if (count($failedTests) > 5) {
    echo "⚠ High failure count detected — possible systemic issue.\n";
    exit(1);
}

exit(0);
