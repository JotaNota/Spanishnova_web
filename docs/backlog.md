# Backlog

Status: Draft
Date updated: 2026-05-27

## Purpose

Track what needs to be done.

GitHub Issues are the source of truth for user stories.

This file is a planning view. Do not use it as a replacement for GitHub Issues.

## Source of truth

- Repository: `https://github.com/JotaNota/Spanishnova_web.git`
- User stories: GitHub Issues
- Local planning view: this file
- Current state: `docs/current-state.md`
- Roadmap: `docs/roadmap.md`

## Issue rules

Each user story must be a GitHub Issue.

Each issue should include:

- User story
- Goal
- Acceptance criteria
- Scope
- Out of scope
- Agent owner
- Verification
- Related docs

## Labels

Recommended labels:

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

## Backlog view

| ID | GitHub Issue | Title | Area | Priority | Status | Agent | Notes |
|---|---|---|---|---|---|---|---|
| BL-001 | TBD | Create GitHub Issue labels | docs | High | Backlog | project-coordinator | Required before issue triage. |
| BL-002 | TBD | Populate initial GitHub user stories | docs | High | Backlog | project-coordinator | Convert roadmap items into issues. |
| BL-003 | TBD | Configure GitHub branch protection for `main` | docs | High | Backlog | project-coordinator | Require PRs before merge. |
| BL-004 | TBD | Confirm active WordPress theme and plugins | devops | High | Backlog | devops-release-agent | Needed for accurate current state. |
| BL-005 | TBD | Define internal linking rules | seo | High | Backlog | content-architect | Supports SEO-first content. |
| BL-006 | TBD | Define SEO metadata rules | seo | High | Backlog | seo-editorial-agent | Titles, descriptions, schema TBD. |
| BL-007 | TBD | Create practice content agent | content | Medium | Backlog | content-production-agent | CPT exists; agent missing. |
| BL-008 | TBD | Create resources content agent | content | Medium | Backlog | content-production-agent | CPT exists; agent missing. |
| BL-009 | TBD | Define newsletter/signup integration | marketing | Medium | Backlog | seo-editorial-agent | Integration TBD. |
| BL-010 | TBD | Audit archive and single templates | ux-ui | High | Backlog | ux-ui-agent | Reading experience and responsive QA. |
| BL-011 | TBD | Run WordPress theme QA checklist | qa | High | Backlog | qa-verifier | Before launch readiness. |

## Maintenance

- Add new work as GitHub Issues first.
- Update this file when issues are created, reprioritized, started, or completed.
- Update `docs/current-state.md` when work lands.
- Update `docs/roadmap.md` when sequence changes.
