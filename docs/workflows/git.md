# Git Workflow

Status: Draft scaffold

## Branching

- Use short task branches.
- Keep theme, documentation, and content-system changes separated when practical.
- Do not mix generated content batches with theme code unless required.

## Commits

- Commit focused changes.
- Use clear messages that name the changed area.
- Do not commit runtime uploads, cache, secrets, or local environment files.

## Review

- Review changed PHP templates for escaping and WordPress conventions.
- Review content architecture changes for URL and SEO impact.
- Review documentation changes for stale assumptions.

## Release notes

For launch-impacting changes, record:

- What changed
- Affected templates or content types
- SEO impact
- Rollback path
- Verification performed
