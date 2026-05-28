# Current State

Status: Draft
Date updated: 2026-05-28

## Purpose

Track what exists, what is in progress, and what still needs work.

## Repository

- Repository: `https://github.com/JotaNota/Spanishnova_web.git`
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
  - `taxonomy-level_tax.php`
  - `taxonomy-topic_tax.php`

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

Official custom taxonomies:

- `level_tax`
- `grammar_tax`
- `topic_tax`

Native WordPress tags:

- `post_tag`

Important:

`post_tag` means regular WordPress tags. Do not register a custom taxonomy named `post_tag`.

Deprecated taxonomy names that must not be used:

- `level`
- `topic`
- `vocabulary_tax`
- `reading_tax`

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

- Editorial system alignment
- Current state inventory

## Not started

- GitHub Issues backlog population
- GitHub branch protection for `main`
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
- Active WordPress validation is pending because WP-CLI availability depends on the local environment.

## Next validation

- Confirm active theme with WordPress.
- Confirm active plugins.
- Populate GitHub Issues as user stories.
- Link issues from `docs/backlog.md`.
- Confirm official taxonomies in wp-admin or WP-CLI.
- Update this file after each completed issue.

## Source of truth

Use this file for current project state.

Use `docs/content/content_clasification.md` as the source of truth for the content model.
