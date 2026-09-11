<?php
// Runs the real PHP adapter and swetest binary, including output parsing and true nodes.
require dirname(__DIR__).'/vendor/autoload.php';

$references = json_decode(file_get_contents(__DIR__.'/swiss-longitude-references.json'), true, 512, JSON_THROW_ON_ERROR);
$failures = 0;
foreach ($references['cases'] as $case) {
    [$year, $month, $day] = explode('-', $case['date']);
    $data = (new Jyotish\Lib())->buildData([
        'latitude' => 51.4779, 'longitude' => -0.0015,
        'year' => $year, 'month' => $month, 'day' => $day,
        'hour' => 0, 'min' => 0, 'sec' => 0,
        'time_zone' => '+00:00', 'dst_hour' => 0, 'dst_min' => 0,
        'varga' => ['D1'],
    ])->getData();
    foreach ($case['longitudes'] as $planet => $expected) {
        $actual = $data['graha'][$planet]['longitude'] ?? null;
        $valid = is_numeric($actual) && is_finite((float) $actual) && $actual >= 0 && $actual < 360;
        $delta = $valid ? abs($actual - $expected) : INF;
        $delta = is_finite($delta) ? min($delta, 360 - $delta) : $delta;
        if (!is_finite($delta) || $delta > 0.01) {
            fprintf(STDERR, "%s %s: expected %.9f, actual %s, delta %.9f exceeds 0.01 degrees\n",
                $case['date'], $planet, $expected, var_export($actual, true), $delta);
            $failures++;
        }
    }
}
if ($failures > 0) {
    exit(1);
}
echo "Swiss longitude regression: 36 comparisons passed at 0.01 degrees.\n";
