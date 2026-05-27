# Current State

Status: Draft
Date updated: 2026-05-27

## Purpose

Track what exists, what is in progress, and what still needs work.

## Repository

- Repository: `https://github.com/JotaNota/Spanishnova_web.git`
- Current working branch: `codex/docs-agent-governance`
- Local scope: custom WordPress theme
- Theme name: `SpanishNova`
- Theme text domain: `spanishnova`
- Documentation: active under `docs/`
- Agent contract: active in `AGENTS.md`
- Git workflow: branch and PR based

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

Roadmaps exist for:

- Grammar
- Vocabulary
- Reading
- Conversation

## In progress

- Documentation scaffold
- Agent workflow definition
- Editorial system alignment
- Current state inventory

## Not started

- GitHub Issues backlog population
- GitHub branch protection for `main`
- Practice content agent
- Resources content agent
- Analytics setup documentation
- Newsletter/signup integration documentation
- Internal linking rules
- SEO metadata rules
- Publishing cadence
- Launch checklist execution

## Known gaps

- Active WordPress plugins are not documented.
- Active theme state is not confirmed with WP-CLI.
- Database content inventory is not documented.
- Existing page/content count is not documented.
- Production hosting and deployment process are `TBD`.
- Monetization model is `TBD`.

## Next validation

- Confirm active theme with WordPress.
- Confirm active plugins.
- Populate GitHub Issues as user stories.
- Link issues from `docs/backlog.md`.
- Update this file after each completed issue.
