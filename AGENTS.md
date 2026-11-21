# SpanishNova Agent Contract

This repository is the SpanishNova custom WordPress theme inside a full WordPress site.

Agents must treat SpanishNova as a content-led WordPress product with:

- SEO-first editorial architecture
- Custom theme development
- Structured content types
- AI-assisted content generation
- Human review before publishing

## Operating rules

- Work to completion when the task is clear.
- Ask only when a decision changes scope, data, publishing, or production behavior.
- Keep changes small and reversible.
- Preserve existing user edits.
- Mark unknowns as `TBD`.
- Do not work directly on `main`.
- Use one branch per GitHub Issue or one branch per small documentation task.
- Do not mix unrelated work in one branch or pull request.
- Do not edit WordPress core or third-party plugin code from this theme repo.
- Do not commit uploads, cache, secrets, database dumps, or environment-specific files.

## WordPress rules

- Treat the theme as the code owner for presentation, CPT registration, taxonomies, and templates.
- Current CPTs are definitive and scalable:
  - `grammar`
  - `vocabulary`
  - `readings`
  - `conversations`
  - `practice`
  - `resources`
- Official custom taxonomies:
  - `level_tax`
  - `grammar_tax`
  - `topic_tax`
- Native WordPress tags use `post_tag`. Do not register a custom taxonomy named `post_tag`.
- Use WordPress escaping helpers for frontend output.
- Use nonces and capability checks for admin, form, or AJAX behavior.
- Preserve URL structure unless a redirect plan exists.
- Verify PHP syntax for changed PHP files.

## Documentation rules

- Use `docs/` as the project documentation source.
- Use GitHub Issues as the source of truth for user stories.
- Use `docs/backlog.md` as a planning view, not as a replacement for GitHub Issues.
- Update `docs/current-state.md` when completed work changes the project state.
- Update `docs/roadmap.md` when sequencing changes.
- Keep `docs/content-system/` intact unless the task targets the editorial system.
- Record architecture decisions in `docs/decisions/`.
- Update `docs/requirements.md`, `docs/phases.md`, or `docs/architecture.md` when the project shape changes.

## Agent roles

Use these roles for development with agents:

- `project-coordinator` - scopes tasks, assigns agents, integrates results, verifies completion.
- `wp-theme-developer` - implements PHP templates, theme setup, CPT/taxonomy changes, assets.
- `content-architect` - owns content model, IA, editorial structure, internal linking rules.
- `seo-editorial-agent` - plans SEO-first content briefs, metadata rules, keyword clusters, publishing cadence.
- `content-production-agent` - creates drafts through `docs/content-system/agents/`.
- `ux-ui-agent` - reviews templates, responsive behavior, accessibility, navigation, reading experience.
- `qa-verifier` - checks PHP syntax, WordPress behavior, templates, SEO basics, accessibility basics.
- `devops-release-agent` - handles backup, deployment, cache, rollback, launch checks.

## Existing content agents

Existing content agents live in:

- `docs/content-system/agents/grammar-agent.md`
- `docs/content-system/agents/vocabulary-agent.md`
- `docs/content-system/agents/reading-agent.md`
- `docs/content-system/agents/conversation-agent.md`

These agents create drafts only. They must not publish to WordPress.

## Default workflow

1. `project-coordinator` maps the task to a GitHub Issue user story or creates a local `TBD` backlog row until the issue exists.
2. `project-coordinator` confirms the branch name and file scope.
3. Work happens on a non-`main` branch.
4. `content-architect` checks content structure impact when content structure changes.
5. `wp-theme-developer` implements theme changes when code changes are needed.
6. `seo-editorial-agent` checks SEO and internal linking impact when SEO changes are involved.
7. `ux-ui-agent` checks frontend experience when UI changes are involved.
8. `qa-verifier` verifies the result.
9. `project-coordinator` updates backlog, roadmap, and current state.
10. Changes reach `main` only through pull request review.
11. `devops-release-agent` is used only for deployment, launch, cache, backup, or rollback work.

## Completion standard

A task is complete only when:

- Changed files are known.
- Relevant docs are updated.
- Verification was run or the gap is stated.
- Risks and `TBD` items are listed.
