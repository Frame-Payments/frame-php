<?php

declare(strict_types=1);

/*
 * Surface manifest generator — frame-php (producer for the conformance audit).
 *
 * Emits the SDK's public resource/method surface as JSON per the schema pinned
 * in frame-docs CROSS_SDK_NAMING.md ("Surface-manifest schema (for the audit)"):
 *
 *   { "sdk", "version", "resources": { "<canonicalResource>": { "class",
 *     "methods": [ { "name", "deprecated" } ] } } }
 *
 * FRA-4516 (producer half). Consumed by the conformance audit in the frame
 * monolith (FRA-4457).
 *
 * Method surface = ReflectionClass over each Frame\Endpoints\* class: public
 * instance methods declared on the endpoint class itself (no base class exists;
 * constructors and inherited methods are excluded defensively).
 *
 * Deprecation = the `@deprecated` phpDoc tag on a method (none exist yet; the M2
 * alias work adds them).
 *
 * Run: php bin/generate-surface-manifest.php [--out=PATH] [--stdout]
 */

$repoRoot = dirname(__DIR__);
require $repoRoot . '/vendor/autoload.php';

const SDK_NAME = 'frame-php';
const ENDPOINTS_NAMESPACE = 'Frame\\Endpoints\\';
const ENDPOINTS_DIR_REL = '/src/Endpoints';

/*
 * php endpoint class (short name) → canonical resource key, for the entries
 * whose canonical name is not the naive lower-camelCase of the class. php
 * classes are already PascalCase plural, so lcfirst() covers the majority.
 * Deprecated resources (Customers, ChargeIntents) keep a legacy key so the
 * audit can see and exclude them from the parity denominator.
 */
const CANONICAL_OVERRIDES = [
    'ThreeDS' => 'threeDsIntents',
    'IdentityVerifications' => 'customerIdentityVerifications',
    'TermsOfService' => 'termsOfService',
    'Customers' => 'customers',         // deprecated → accounts
    'ChargeIntents' => 'chargeIntents', // deprecated → transfers
];

/** Methods that are never part of the public operation surface. */
const NON_SURFACE_METHODS = ['__construct', '__destruct', '__call', '__callStatic', '__get', '__set'];

/**
 * Derive the canonical resource key from a php endpoint class short name.
 * php class form is PascalCase plural (Transfers, PaymentMethods), so the
 * canonical key is simply lcfirst(); irregulars live in CANONICAL_OVERRIDES.
 */
function canonicalResource(string $shortClass): string
{
    if (isset(CANONICAL_OVERRIDES[$shortClass])) {
        return CANONICAL_OVERRIDES[$shortClass];
    }

    return lcfirst($shortClass);
}

/** Read the SDK version from the nearest git tag, falling back to 0.0.0-dev. */
function readVersion(string $repoRoot): string
{
    $tag = @exec('git -C ' . escapeshellarg($repoRoot) . ' describe --tags --abbrev=0 2>/dev/null');
    if (is_string($tag) && $tag !== '') {
        return ltrim($tag, 'v');
    }

    return '0.0.0-dev';
}

/** @return list<class-string> fully-qualified endpoint class names */
function discoverEndpointClasses(string $repoRoot): array
{
    $dir = $repoRoot . ENDPOINTS_DIR_REL;
    $classes = [];
    foreach (scandir($dir) ?: [] as $file) {
        if (!str_ends_with($file, '.php')) {
            continue;
        }
        $short = substr($file, 0, -4);
        $fqcn = ENDPOINTS_NAMESPACE . $short;
        if (class_exists($fqcn)) {
            $classes[] = $fqcn;
        }
    }
    sort($classes);

    return $classes;
}

function isDeprecated(ReflectionMethod $method): bool
{
    $doc = $method->getDocComment();

    return $doc !== false && preg_match('/@deprecated\b/', $doc) === 1;
}

function classIsDeprecated(ReflectionClass $class): bool
{
    $doc = $class->getDocComment();

    return $doc !== false && preg_match('/@deprecated\b/', $doc) === 1;
}

/**
 * @return list<array{name: string, deprecated: bool}>
 */
function surfaceMethods(ReflectionClass $class): array
{
    $methods = [];
    foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        // Only methods declared on the endpoint class itself, instance-level,
        // and not magic/plumbing.
        if ($method->getDeclaringClass()->getName() !== $class->getName()) {
            continue;
        }
        if ($method->isStatic()) {
            continue;
        }
        if (in_array($method->getName(), NON_SURFACE_METHODS, true)) {
            continue;
        }
        $methods[] = [
            'name' => $method->getName(),
            'deprecated' => isDeprecated($method),
        ];
    }

    usort($methods, static fn ($a, $b) => strcmp($a['name'], $b['name']));

    return $methods;
}

function buildManifest(string $repoRoot): array
{
    $resources = [];
    foreach (discoverEndpointClasses($repoRoot) as $fqcn) {
        $class = new ReflectionClass($fqcn);
        $short = $class->getShortName();
        $key = canonicalResource($short);

        // A canonical class and its deprecated backward-compat alias subclass
        // (e.g. IdentityVerifications extends CustomerIdentityVerifications) map
        // to the same canonical key. Keep the canonical class; a deprecated alias
        // must never overwrite it (order-independent: canonical wins either way).
        if (isset($resources[$key]) && classIsDeprecated($class)) {
            continue;
        }

        $resources[$key] = [
            'class' => $short,
            'methods' => surfaceMethods($class),
        ];
    }
    ksort($resources);

    return [
        'sdk' => SDK_NAME,
        'version' => readVersion($repoRoot),
        'resources' => $resources,
    ];
}

// --- CLI ---------------------------------------------------------------------

$toStdout = false;
$outPath = $repoRoot . '/surface-manifest.json';
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--stdout') {
        $toStdout = true;
    } elseif (str_starts_with($arg, '--out=')) {
        $outPath = substr($arg, strlen('--out='));
    }
}

$manifest = buildManifest($repoRoot);
$json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if ($toStdout) {
    fwrite(STDOUT, $json);
} else {
    file_put_contents($outPath, $json);
    $methodCount = array_sum(array_map(static fn ($r) => count($r['methods']), $manifest['resources']));
    fwrite(STDERR, sprintf(
        "Wrote %s: %d resources, %d methods (v%s)\n",
        $outPath,
        count($manifest['resources']),
        $methodCount,
        $manifest['version'],
    ));
}
