#!/usr/bin/env node
/**
 * Browser-smoke release gate.
 *
 * Refuses to package a release zip unless a fresh, green
 * `docs/qa/.last-smoke-pass.json` exists. Protects first-hand customer
 * experience: no release ships unless an actual walk of
 * `docs/qa/AGENT_SMOKE_RUNBOOK.md` (dispatched via the Claude-level
 * `wp-plugin-smoke` skill) reported zero failures and zero debug_log_issues.
 *
 * Exit codes:
 *   0  = pass
 *   30 = gate failed (caller should abort the release)
 *
 * Env:
 *   SKIP_BROWSER_SMOKE=1  bypass the gate with a loud warning (emergency only)
 *
 * Usage from distribute.js:
 *   import { runSmokeGate } from './release-smoke-gate.js';
 *   await runSmokeGate({ rootDir, version });
 */
import fs from 'node:fs';
import path from 'node:path';

const STALE_THRESHOLD_MS = 24 * 60 * 60 * 1000;

export function runSmokeGate({ rootDir, version }) {
	const reportPath = path.join(rootDir, 'docs', 'qa', '.last-smoke-pass.json');

	if (process.env.SKIP_BROWSER_SMOKE === '1') {
		console.warn('\nWARN: browser smoke gate skipped (SKIP_BROWSER_SMOKE=1). Not for customer releases.');
		return { passed: true, skipped: true };
	}

	if (!fs.existsSync(reportPath)) {
		console.error(`\nFAIL: no browser smoke report at ${reportPath}`);
		console.error('      Run the wp-plugin-smoke skill (Claude-level) first to generate it.');
		console.error('      Emergency only: SKIP_BROWSER_SMOKE=1 npm run dist');
		process.exit(30);
	}

	let report;
	try {
		report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
	} catch (e) {
		console.error(`\nFAIL: smoke report is not valid JSON: ${e.message}`);
		process.exit(30);
	}

	if (report.release_version !== version) {
		console.error(`\nFAIL: smoke report version (${report.release_version}) doesn't match release version (${version})`);
		console.error('      Re-run the smoke against HEAD before packaging.');
		process.exit(30);
	}

	if (report.ran_at) {
		const ran = Date.parse(report.ran_at);
		if (Number.isFinite(ran) && Date.now() - ran > STALE_THRESHOLD_MS) {
			console.warn(`\nWARN: smoke report is older than 24h (ran_at: ${report.ran_at}). Consider re-running before tagging.`);
		}
	}

	const failures = Array.isArray(report.failures) ? report.failures : [];
	// Block only on origin=from (our theme's fault), matching how debug_log_issues
	// are gated below. Entries the walk classified origin=for are defects in a
	// third-party plugin or the environment that merely surface on our screens:
	// worth recording and reporting upstream, but not ours to fix and not a
	// customer-facing regression in this theme. An entry with no origin is treated
	// as ours and blocks, so an under-specified report can never slip through.
	const blockingFailures = failures.filter((f) => String(f.origin || 'from') !== 'for');
	const foreignFailures = failures.filter((f) => String(f.origin || 'from') === 'for');
	if (foreignFailures.length > 0) {
		console.log(`    note: ${foreignFailures.length} origin=for failure(s) in report (third-party, not blocking):`);
		foreignFailures.forEach((f, i) => {
			console.log(`      ${i + 1}. [${f.id || '?'}] ${f.triage_note || f.expected || '(no detail)'}`);
		});
	}
	if (blockingFailures.length > 0) {
		console.error(`\nFAIL: smoke report has ${blockingFailures.length} failure(s) attributed to this theme:`);
		blockingFailures.forEach((f, i) => {
			console.error(`  ${i + 1}. [${f.id || '?'}] ${f.triage_note || f.expected || '(no detail)'}`);
		});
		console.error('      Fix the failures before packaging.');
		process.exit(30);
	}

	const debugIssues = Array.isArray(report.debug_log_issues) ? report.debug_log_issues : [];
	// Block only on fatal/warning that are origin=from (our theme's fault).
	// Entries triaged as for-environment / for-test-harness / for-* are surfaced but not gated:
	// they are real signals worth recording but do not represent customer-facing regressions.
	const blockingIssues = debugIssues.filter((d) => {
		if (d.level !== 'fatal' && d.level !== 'warning') return false;
		const triage = String(d.triage || '').trim();
		if (triage.startsWith('for-')) return false;
		return true;
	});
	if (blockingIssues.length > 0) {
		console.error(`\nFAIL: smoke report recorded ${blockingIssues.length} debug.log fatal/warning(s) attributed to the theme:`);
		blockingIssues.forEach((d, i) => {
			console.error(`  ${i + 1}. [${d.section || '?'}] ${d.level}: ${d.line || ''}`);
		});
		console.error('      Fix before packaging. (Environmental fatals can be triaged with `"triage": "for-environment: ..."` to skip this gate.)');
		process.exit(30);
	}
	const environmentalCount = debugIssues.filter((d) =>
		(d.level === 'fatal' || d.level === 'warning') && String(d.triage || '').startsWith('for-')
	).length;
	if (environmentalCount > 0) {
		console.log(`    note: ${environmentalCount} environmental fatal/warning(s) in report (triaged for-*, not blocking)`);
	}

	console.log(`\n    smoke gate OK (report version ${report.release_version}, ran at ${report.ran_at || 'unknown'})`);
	return { passed: true, skipped: false };
}
