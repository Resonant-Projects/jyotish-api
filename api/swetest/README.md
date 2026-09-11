# Swiss Ephemeris engine

Swiss Ephemeris is developed by [Astrodienst](https://www.astro.com/swisseph/).
The pinned upstream source carries its AGPL-3.0 or professional-license terms
in `LICENSE`, `LICENSE.TXT`, and `agpl-3.0.txt`; those files ship with the image.

The container builds Swiss Ephemeris **2.10.03** from upstream commit
`175e1fcb3108bcd5c0d146c803f51dcf23508012`. The source archive SHA-256 is
`3fc81e72be98d4de80e9bf56fa6c6d1368327ee23f99db7415341f3b60e5f466`.
`api/Dockerfile` verifies the archive, replaces the historical `src/` directory
inside the image, and compiles `swetest`. The image includes the corresponding
upstream source and license notices. The checked-in 1.78 source is historical
and is no longer the container's build input.

The data files remain pinned separately by `SWISSEPH_EPHEMERIS_REF` and
`sweph/ephemeris.sha256`. Startup verifies their checksums and rejects Moshier
fallback. The PHP adapter retains `-ut`, `-sid1`, `-eswe`, and true lunar nodes.
It passes a Universal Time date and clock to Swiss Ephemeris, which converts
them to a Julian date and applies its Delta T model. No extra UTC-to-TT adjustment is applied by the adapter.

## Future-date repair

The 1.78 model predicted Delta T of 219.769878 seconds at
`2101-01-01T00:00:00Z`; 2.10.03 predicts 93.646192 seconds. The older model moved
the sidereal Moon to 268.6998148 degrees instead of the independent modern
reference 268.678726321 degrees. At the unchanged 0.01-degree oracle tolerance,
that is a failure. Future Delta T is an engine model prediction, not a
measurement of future Earth rotation.

## Regression check

Every container build runs:

```sh
php tests/swiss-longitudes.php
```

This invokes the real `Lib::buildData` PHP adapter, its `swetest` subprocess and
output parser. It compares all nine grahas at four dates, including 1899, 1980,
2101, and 2399, at 0.01 degrees. The old binary fails the Moon checks for 2101
and 2399. True-node references distinguish the lunar-node policy.

`tests/swiss-longitude-references.json` was independently generated using
`pyswisseph==2.10.3.2`, Swiss 2.10.03, `set_sid_mode(SIDM_LAHIRI)`, and
`calc_ut(julday(year, month, day, 0), body, FLG_SWIEPH | FLG_SIDEREAL)`.
All returned flags were 65602. The regression verifies the reference file hashes
against the image data before calculating. Rahu uses `TRUE_NODE`, and Ketu is Rahu plus
180 degrees modulo 360. The same checksum-verified `semo_18.se1` and
`sepl_18.se1` files were used. Reference values must be generated independently,
not copied from the API response under test.
