

# Current State

Status: Draft
Date updated: 2026-05-27

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

## Content model decision

The official custom taxonomy model is:

```txt
level_tax
grammar_tax
topic_tax
```

The project also uses native WordPress tags:

```txt
post_tag
```

Important:

`post_tag` is the native WordPress tag taxonomy. Do not register a new custom taxonomy named `post_tag`.

The project should not use these deprecated taxonomy names:

```txt
level
topic
vocabulary_tax
reading_tax
```

## Current implementation gap

The documentation defines `grammar_tax`, `topic_tax`, and `level_tax` as the official custom taxonomy names.

The current implementation may still be partially out of sync.

Known gap:

- `header.php` references `grammar_tax`.
- `inc/taxonomies.php` must register `grammar_tax`.
- `inc/taxonomies.php` must use `level_tax`, not `level`.
- `inc/taxonomies.php` must use `topic_tax`, not `topic`.
- `vocabulary_tax` and `reading_tax` should not be registered unless the content model changes later.
- Native WordPress tags should be enabled through CPT support/taxonomy association, not by creating a new custom taxonomy named `post_tag`.

## Source of truth

Use this file for current project state.

Use `docs/content/content_clasification.md` as the source of truth for the content model.