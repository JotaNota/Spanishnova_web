# Git Workflow

Status: Draft

## Branching

- Do not work directly on `main`.
- Create one branch per GitHub Issue or one branch per small documentation task.
- Use branch names in this format:
  - `codex/issue-<number>-short-name`
  - `codex/docs-short-name` when no issue exists yet
- Keep theme, documentation, and content-system changes in separate branches when practical.
- Do not mix generated content batches with theme code.
- Do not let multiple agents edit the same files on separate branches without a coordinator.

## Issue to branch flow

1. Create or select a GitHub Issue.
2. Confirm acceptance criteria.
3. Create a branch from updated `main`.
4. Make scoped changes.
5. Verify the change.
6. Open a pull request.
7. Merge only after review and checks.

## Pull requests

- Every change to `main` must go through a pull request.
- PRs must reference the related GitHub Issue.
- PRs must include:
  - Summary
  - Changed files or areas
  - Verification performed
  - Known risks
  - Screenshots when UI changed
- Keep PRs small enough to review.
- Do not merge unrelated work in one PR.

## Main branch protection

Branch protection is the preferred setup for `main`.

If branch protection is unavailable on the current GitHub plan, use manual PR discipline:

- Do not commit directly to `main`.
- Do not push direct changes to `main`.
- Open a PR for every branch.
- Review the diff before merge.
- Confirm the PR references an Issue or backlog row.

Recommended GitHub settings when available:

- Require pull request before merging.
- Require at least one approval.
- Require conversation resolution before merging.
- Require status checks when CI exists.
- Block force pushes.
- Block deletion.
- Require branches to be up to date before merging when CI exists.

## Commits

- Commit focused changes.
- Use clear messages that name the changed area.
- Do not commit runtime uploads, cache, secrets, or local environment files.
- Do not commit directly to `main`.

## Review

- Review changed PHP templates for escaping and WordPress conventions.
- Review content architecture changes for URL and SEO impact.
- Review documentation changes for stale assumptions.
- Confirm the PR does not include unrelated files.
- Confirm `docs/current-state.md`, `docs/backlog.md`, or `docs/roadmap.md` were updated when needed.

## Release notes

For launch-impacting changes, record:

- What changed
- Affected templates or content types
- SEO impact
- Rollback path
- Verification performed
