# GitHub Issues Seed

Status: Draft
Date updated: 2026-05-27

## Purpose

Create the initial GitHub Issues manually when API or CLI access is not available.

GitHub Issues remain the source of truth for user stories. This file is only a seed.

## Labels to create

- `type: story`
- `type: task`
- `type: bug`
- `area: theme`
- `area: content`
- `area: seo`
- `area: ux-ui`
- `area: devops`
- `area: docs`
- `status: backlog`
- `status: ready`
- `status: in-progress`
- `status: blocked`
- `status: done`
- `priority: high`
- `priority: medium`
- `priority: low`

## Issue 1 - Create GitHub Issue labels

As the project coordinator, I want standard GitHub labels, so that SpanishNova work can be triaged by type, area, status, and priority.

Acceptance criteria:

- Labels from `docs/backlog.md` exist in GitHub.
- Existing Issues can be labeled.

Agent owner: `project-coordinator`

Related docs:

- `docs/backlog.md`

## Issue 2 - Populate initial GitHub user stories

As the project coordinator, I want the initial backlog represented as GitHub Issues, so that agents can work from user stories.

Acceptance criteria:

- Initial backlog rows have matching GitHub Issues.
- `docs/backlog.md` links to the created Issues.
- Each Issue includes acceptance criteria and agent owner.

Agent owner: `project-coordinator`

Related docs:

- `docs/backlog.md`
- `docs/roadmap.md`

## Issue 3 - Document manual PR discipline while branch protection is unavailable

As the project owner, I want a manual GitHub workflow while branch protection is unavailable, so that work does not happen directly on `main`.

Acceptance criteria:

- `docs/workflows/git.md` documents the temporary manual rule.
- Agents are instructed to use branches and PRs.
- `docs/current-state.md` notes branch protection as deferred.

Agent owner: `project-coordinator`

Related docs:

- `docs/workflows/git.md`
- `docs/current-state.md`

## Issue 4 - Confirm active WordPress theme and plugins

As the project owner, I want the active WordPress theme and plugins confirmed, so that documentation reflects the real site state.

Acceptance criteria:

- Active theme is documented.
- Active plugins are documented.
- Unknown plugin state is removed from `docs/current-state.md`.

Agent owner: `devops-release-agent`

Related docs:

- `docs/current-state.md`

## Issue 5 - Define internal linking rules

As a content architect, I want internal linking rules, so that SEO-first content supports discovery and retention.

Acceptance criteria:

- Internal linking rules are documented.
- Rules cover CPT archives, single posts, topics, levels, and related content.
- Rules identify required theme components.

Agent owner: `content-architect`

Related docs:

- `docs/content/README.md`
- `docs/architecture.md`

## Issue 6 - Define SEO metadata rules

As an SEO editor, I want metadata rules, so that content can be published consistently.

Acceptance criteria:

- Title rules are documented.
- Description rules are documented.
- Schema needs are listed.
- Open questions are marked `TBD`.

Agent owner: `seo-editorial-agent`

Related docs:

- `docs/marketing/README.md`

## Issue 7 - Create practice content agent

As the content production system, I want a practice content agent, so that the `practice` CPT has draft-generation guidance.

Acceptance criteria:

- `docs/content-system/agents/practice-agent.md` exists.
- Practice template or template requirement is documented.
- Roadmap requirement is documented.

Agent owner: `content-production-agent`

Related docs:

- `docs/content-system/agents/`

## Issue 8 - Create resources content agent

As the content production system, I want a resources content agent, so that the `resources` CPT has draft-generation guidance.

Acceptance criteria:

- `docs/content-system/agents/resource-agent.md` exists.
- Resource template or template requirement is documented.
- Roadmap requirement is documented.

Agent owner: `content-production-agent`

Related docs:

- `docs/content-system/agents/`

## Issue 9 - Define newsletter/signup integration

As the project owner, I want newsletter/signup integration defined, so that SpanishNova can capture returning learners.

Acceptance criteria:

- Integration options are documented.
- Placement rules are documented.
- Tracking needs are documented.

Agent owner: `seo-editorial-agent`

Related docs:

- `docs/marketing/README.md`

## Issue 10 - Audit archive and single templates

As a learner, I want clear archive and single pages, so that I can find and use Spanish lessons.

Acceptance criteria:

- CPT archive templates are reviewed.
- CPT single templates are reviewed.
- UX, accessibility, and SEO findings are documented.
- Follow-up Issues are created for implementation work.

Agent owner: `ux-ui-agent`

Related docs:

- `docs/ux-ui/README.md`

## Issue 11 - Run WordPress theme QA checklist

As the project owner, I want theme QA completed, so that SpanishNova has a known quality baseline.

Acceptance criteria:

- `docs/checklists/qa.md` is executed.
- Failed checks are documented.
- Follow-up Issues are created for failures.

Agent owner: `qa-verifier`

Related docs:

- `docs/checklists/qa.md`

## Issue 12 - Resolve taxonomy model mismatch

As a content architect, I want one confirmed taxonomy model, so that navigation, content classification, and WordPress registration match.

Acceptance criteria:

- Decide whether SpanishNova uses shared `level/topic` taxonomies or content-specific taxonomies.
- Update `inc/taxonomies.php` if code changes are required.
- Update `header.php` navigation if taxonomy slugs change.
- Update `docs/content/content_clasification.md`.
- Update `docs/architecture.md`.
- Update `docs/current-state.md`.

Agent owner: `content-architect`

Related docs:

- `docs/current-state.md`
- `docs/architecture.md`
- `docs/content/content_clasification.md`
