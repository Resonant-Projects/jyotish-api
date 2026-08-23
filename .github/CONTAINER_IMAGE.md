# Maintained container image

`container-image.yml` turns an exact revision from this fork into an attested
OCI image in `ghcr.io/resonant-projects/jyotish-api`. Builds run on the
Resonant Projects GARM pool as disposable Incus guests.

The default branch continues to track
[`teal33t/jyotish-api`](https://github.com/teal33t/jyotish-api). Put local fixes
on topic branches, validate them through a pull request here, and open a second
pull request against upstream when the change is generally useful. Keeping the
runtime change in a small commit makes that upstream contribution easy to
review or cherry-pick.

Every published image has a source-SHA tag, an OCI provenance attestation, and
an immutable registry digest. Deploy only the full `name@sha256:...` reference;
the SHA tag is for discovery, not promotion.
