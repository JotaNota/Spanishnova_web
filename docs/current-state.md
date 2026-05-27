# Current State

Status: Draft
Date updated: 2026-05-27

## Purpose

Track what exists, what is in progress, and what still needs work.

## Repository

- Repository: `https://github.com/JotaNota/Spanishnova_web.git`
- Current working branch: `codex/github-free-workflow`
- Local scope: custom WordPress theme
- Theme name: `SpanishNova`
- Theme text domain: `spanishnova`
- Documentation: active under `docs/`
- Agent contract: active in `AGENTS.md`
- Git workflow: branch and PR based
- Branch protection: deferred because current GitHub account plan does not support it for this workflow
- GitHub Issue creation from this session: blocked by integration permissions

## Implemented

### Theme foundation

- Theme setup file exists: `inc/setup.php`
- Asset enqueue file exists: `inc/enqueue.php`
- CPT registration exists: `inc/cpt.php`
- Taxonomy registration exists: `inc/taxonomies.php`
- Main templates exist:
  - `front-page.php`
  - `archive.php`
  - `single.php`
  - `search.php`
  - `taxonomy-level.php`
  - `taxonomy-topic.php`

### Content types

Registered CPTs:

- `grammar`
- `vocabulary`
- `readings`
- `conversations`
- `practice`
- `resources`

Each CPT has archive and single template files.

### Taxonomies

Registered taxonomies:

- `level`
- `topic`

Observed taxonomy mismatch:

- `header.php` calls `get_term_link()` with `grammar_tax`.
- `docs/content/content_clasification.md` describes `level_tax`, `grammar_tax`, `vocabulary_tax`, and `reading_tax`.
- `inc/taxonomies.php` currently registers only `level` and `topic`.
- This must be resolved before relying on taxonomy navigation.

### Documentation

Core docs exist:

- `docs/README.md`
- `docs/project-charter.md`
- `docs/requirements.md`
- `docs/phases.md`
- `docs/architecture.md`
- `docs/agents.md`
- `docs/guidance-schema.md`
- `docs/backlog.md`
- `docs/roadmap.md`
- `docs/current-state.md`

### Agent system

Development agent prompts exist under `docs/agent-system/agents/`.

Content draft agents exist under `docs/content-system/agents/`.

### Content system

Templates exist for:

- Grammar
- Vocabulary
- Reading
- Conversation
- Practice
- Resources

Roadmaps exist for:

- Grammar
- Vocabulary
- Reading
- Conversation
- Practice
- Resources

## In progress

- Editorial system alignment
- Current state inventory
- Manual GitHub Issue creation workflow

## Not started

- GitHub Issues backlog population
- Manual GitHub Issue creation from `docs/github-issues-seed.md`
- GitHub branch protection for `main` when available
- Analytics setup documentation
- Newsletter/signup integration documentation
- Internal linking rules
- SEO metadata rules
- Publishing cadence
- Launch checklist execution

## Known gaps

- WP-CLI is not available in this local shell.
- Active WordPress plugins are not documented.
- `wp-content/plugins/` is not present in the local filesystem snapshot.
- Active theme state is not confirmed with WP-CLI.
- Database content inventory is not documented.
- Existing page/content count is not documented.
- Production hosting and deployment process are `TBD`.
- Monetization model is `TBD`.
- Taxonomy model is inconsistent across code, navigation, and content classification docs.

## Next validation

- Confirm active theme with WordPress.
- Confirm active plugins.
- Resolve taxonomy model before implementing navigation changes.
- Populate GitHub Issues as user stories.
- Link issues from `docs/backlog.md`.
- Use `docs/github-issues-seed.md` until Issues can be created through GitHub API or CLI.
- Update this file after each completed issue.
