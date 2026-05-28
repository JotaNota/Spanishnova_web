# WordPress Development and Editorial Workflow

Status: Draft

## Before changes

1. Identify whether the target is theme code, database content, media, plugin configuration, or documentation.
2. Confirm active theme and plugin state with WP-CLI or wp-admin when needed.
3. For risky changes, capture a database backup and note rollback steps.

## Theme implementation rules

- Keep changes small and reversible.
- Use WordPress escaping helpers for frontend output.
- Use nonces and capability checks for admin or AJAX behavior.
- Keep CPT and taxonomy changes documented.
- Preserve existing URLs unless a redirect plan exists.

## Editorial rules

- Treat grammar, vocabulary, readings, conversations, practice, and resources as primary content channels.
- Use `level_tax`, `grammar_tax`, `topic_tax`, and native WordPress tags consistently.
- Track missing editorial decisions as `TBD`.
- Keep AI-assisted content workflows documented in `docs/content-system/`.

## Verification checklist

- PHP syntax check for changed PHP files.
- WP-CLI smoke check where available.
- Frontend responsive check for template/CSS changes.
- Accessibility spot-check for user-facing UI.
- SEO checks for title, headings, indexability, links, and structured content.
- Cache purge plan for launch.
- Analytics and conversion events preserved for marketing pages.
