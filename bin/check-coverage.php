#!/usr/bin/env php
<?php

/**
 * Coverage threshold gate for CI.
 *
 * Usage:
 *   php bin/check-coverage.php --min=70 --drop=2 --unit=build/logs/unit-clover.xml --integration=build/logs/integration-clover.xml
 *
 * Exit codes:
 *   0  — all checks passed
 *   1  — coverage below minimum threshold
 *   2  — coverage drop exceeds allowed delta (reads COVERAGE_PREV if set)
 *   3  — file parse error
 */

$opts = getopt('', ['min:', 'drop:', 'unit:', 'integration:']);

$minThreshold   = (float) ($opts['min'] ?? 70);
$dropThreshold  = (float) ($opts['drop'] ?? 2);
$unitFile       = $opts['unit'] ?? '';
$integrationFile = $opts['integration'] ?? '';

echo "═══════════════════════════════════════\n";
echo "  Coverage Gate\n";
echo "═══════════════════════════════════════\n\n";

echo "Configuration:\n";
echo "  Minimum threshold : {$minThreshold}%\n";
echo "  Max allowed drop  : {$dropThreshold}%\n";
echo "  Unit file         : {$unitFile}\n";
echo "  Integration file  : {$integrationFile}\n\n";

// ── Parse clover XML files ──────────────────────────────────────────

function parseCloverFile(string $path): ?array
{
    if (!file_exists($path)) {
        echo "  ⚠  File not found: {$path}\n";
        return null;
    }

    $xml = @simplexml_load_file($path);
    if ($xml === false) {
        echo "  ✗  Failed to parse: {$path}\n";
        return null;
    }

    $project = $xml->project ?? null;
    if (!$project) {
        echo "  ✗  No <project> in: {$path}\n";
        return null;
    }

    $metrics = $project->metrics ?? null;
    if (!$metrics) {
        echo "  ✗  No <metrics> in: {$path}\n";
        return null;
    }

    $elements     = (int) ($metrics['elements'] ?? 0);
    $covered      = (int) ($metrics['coveredelements'] ?? 0);
    $statements   = (int) ($metrics['statements'] ?? $elements);
    $coveredStmt  = (int) ($metrics['coveredstatements'] ?? $covered);
    $lines        = (int) ($metrics['lines'] ?? 0);
    $coveredLines = (int) ($metrics['coveredlines'] ?? 0);

    $effectiveTotal   = max($elements, $statements, $lines);
    $effectiveCovered = max($covered, $coveredStmt, $coveredLines);

    $pct = $effectiveTotal > 0
        ? round(($effectiveCovered / $effectiveTotal) * 100, 2)
        : 0.0;

    return [
        'elements'     => $effectiveTotal,
        'covered'      => $effectiveCovered,
        'percentage'   => $pct,
    ];
}

// Parse unit coverage
$unitResult = $unitFile ? parseCloverFile($unitFile) : null;

// Parse integration coverage (primary gate — most comprehensive)
$integrationResult = $integrationFile ? parseCloverFile($integrationFile) : null;

if (!$unitResult && !$integrationResult) {
    echo "✗ No coverage files found. Cannot gate.\n";
    exit(3);
}

// ── Display results ──────────────────────────────────────────────────

echo "Results:\n";

if ($unitResult) {
    $pct = $unitResult['percentage'];
    $indicator = $pct >= $minThreshold ? '✓' : '✗';
    echo "  {$indicator} Unit          : {$pct}% ({$unitResult['covered']}/{$unitResult['elements']})\n";
}

if ($integrationResult) {
    $pct = $integrationResult['percentage'];
    $indicator = $pct >= $minThreshold ? '✓' : '✗';
    echo "  {$indicator} Integration   : {$pct}% ({$integrationResult['covered']}/{$integrationResult['elements']})\n";
}

// Use integration as the primary gate metric (it covers more code paths)
$primaryResult = $integrationResult ?? $unitResult;
$combinedPct   = $primaryResult['percentage'];

echo "\n  Primary metric (integration): {$combinedPct}%\n";

// ── Check minimum threshold ─────────────────────────────────────────

$failed = false;

echo "\n─── Threshold Checks ───\n";

if ($combinedPct < $minThreshold) {
    echo "✗ FAIL: Coverage {$combinedPct}% is below minimum {$minThreshold}%\n";
    $failed = true;
} else {
    echo "✓ PASS: Coverage {$combinedPct}% meets minimum {$minThreshold}%\n";
}

// ── Check drop threshold ────────────────────────────────────────────

$previousCoverage = getenv('COVERAGE_PREV') ?: false;

if ($previousCoverage !== false && is_numeric($previousCoverage)) {
    $prev   = (float) $previousCoverage;
    $delta  = $prev - $combinedPct;

    echo "\n  Previous coverage : {$prev}%\n";
    echo "  Current coverage  : {$combinedPct}%\n";
    echo "  Delta             : -" . round($delta, 2) . "%\n";

    if ($delta > $dropThreshold) {
        echo "✗ FAIL: Coverage dropped by {$delta}% (max allowed: {$dropThreshold}%)\n";
        $failed = true;
    } else {
        echo "✓ PASS: Coverage drop within threshold ({$delta}% ≤ {$dropThreshold}%)\n";
    }
} else {
    echo "\n  ℹ  No previous coverage baseline (COVERAGE_PREV not set). Drop check skipped.\n";
    echo "     To enable drop checks, set COVERAGE_PREV as an env variable.\n";
}

// ── Output for GITHUB_STEP_SUMMARY ─────────────────────────────────

$summaryFile = getenv('GITHUB_STEP_SUMMARY');
if ($summaryFile) {
    $summary  = "\n## 📊 Coverage Gate\n\n";
    $summary .= "| Metric | Value | Status |\n";
    $summary .= "|--------|-------|--------|\n";

    if ($unitResult) {
        $status = $unitResult['percentage'] >= $minThreshold ? '✅' : '❌';
        $summary .= "| Unit | {$unitResult['percentage']}% | {$status} |\n";
    }
    if ($integrationResult) {
        $status = $integrationResult['percentage'] >= $minThreshold ? '✅' : '❌';
        $summary .= "| Integration | {$integrationResult['percentage']}% | {$status} |\n";
    }

    $status = $combinedPct >= $minThreshold ? '✅' : '❌';
    $summary .= "| **Gate** | **{$combinedPct}%** | {$status} |\n";
    $summary .= "| Min Threshold | {$minThreshold}% | — |\n";

    if ($previousCoverage !== false && is_numeric($previousCoverage)) {
        $dropStatus = $delta <= $dropThreshold ? '✅' : '❌';
        $summary .= "| Drop Check | -" . round($delta, 2) . "% (≤{$dropThreshold}%) | {$dropStatus} |\n";
    }

    file_put_contents($summaryFile, $summary, FILE_APPEND);
}

// ── Verdict ─────────────────────────────────────────────────────────

echo "\n═══════════════════════════════════════\n";
if ($failed) {
    echo "  ✗ COVERAGE GATE FAILED\n";
    echo "═══════════════════════════════════════\n";
    exit(1);
} else {
    echo "  ✓ COVERAGE GATE PASSED\n";
    echo "═══════════════════════════════════════\n";
    exit(0);
}
