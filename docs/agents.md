# SpanishNova Agent Setup

Status: Draft
Date created: 2026-05-27

## Needed agents

| Agent | Purpose | Use when |
|---|---|---|
| `project-coordinator` | Scope, sequencing, integration, final verification | Any multi-agent task |
| `wp-theme-developer` | Theme PHP, templates, CPTs, taxonomies, assets | Building or changing the WordPress theme |
| `content-architect` | Content model, IA, taxonomy use, internal linking | Changing structure or content strategy |
| `seo-editorial-agent` | SEO briefs, metadata rules, keyword clusters, publishing cadence | Planning SEO content |
| `content-production-agent` | Draft content generation through content-system agents | Creating grammar, vocabulary, reading, or conversation drafts |
| `ux-ui-agent` | Templates, responsive UX, accessibility, navigation | Changing frontend experience |
| `qa-verifier` | Syntax, WordPress checks, SEO basics, accessibility basics | Before considering work complete |
| `devops-release-agent` | Backup, deployment, cache, rollback, launch checks | Launch or production work |

Individual prompt files live in `docs/agent-system/agents/`.

## Existing content agents

The repo already has content-specific agent prompts:

- `docs/content-system/agents/grammar-agent.md`
- `docs/content-system/agents/vocabulary-agent.md`
- `docs/content-system/agents/reading-agent.md`
- `docs/content-system/agents/conversation-agent.md`

These are draft generators. They must not edit PHP, CSS, templates, or publish to WordPress.

## Missing content agents

Current CPTs also include:

- `practice`
- `resources`

Recommended future agents:

- `practice-agent`
- `resource-agent`

Prompt files for these agents should be added to `docs/agent-system/agents/` when the work begins.

## Roadmap format

Content agents use `.csv` roadmaps from `docs/content-system/content-plan/`.

## Default agent flow

### Theme feature

1. `project-coordinator`
2. `wp-theme-developer`
3. `ux-ui-agent`
4. `qa-verifier`

### Content architecture change

1. `project-coordinator`
2. `content-architect`
3. `seo-editorial-agent`
4. `wp-theme-developer` if code changes are needed
5. `qa-verifier`

### Content batch

1. `project-coordinator`
2. `seo-editorial-agent`
3. `content-production-agent`
4. `content-architect`
5. `qa-verifier`

### Launch

1. `project-coordinator`
2. `qa-verifier`
3. `devops-release-agent`

## Rules

- One agent owns one scope.
- Do not let two agents edit the same file at the same time.
- Do not work directly on `main`.
- Use one branch per GitHub Issue or one branch per small documentation task.
- Changes reach `main` only through pull requests.
- Do not mix unrelated work in one PR.
- Use GitHub Issues as user stories.
- Every implementation task should reference an issue when available.
- If no issue exists, add a `TBD` row to `docs/backlog.md` and create the issue before execution when possible.
- Update `docs/current-state.md` after completed work.
- Update `docs/roadmap.md` when order or phase changes.
- Draft content stays in `docs/content-system/content/`.
- Publishing to WordPress requires explicit approval.
- Production deployment requires backup and rollback notes.
