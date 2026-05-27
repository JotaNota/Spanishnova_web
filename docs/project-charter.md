# Project Charter

Status: Draft
Date created: 2026-05-27

## Purpose

SpanishNova is an SEO-first Spanish learning platform based on structured WordPress content and AI-assisted editorial generation.

## Stakeholders

- Owner: Juan Diaz
- Technical lead: Juan Diaz + AI agents
- Content lead: AI-assisted editorial system
- Marketing lead: TBD
- Operations lead: TBD

## Goals

1. Build a structured WordPress content platform for Spanish learning.
2. Publish scalable SEO content across grammar, vocabulary, readings, conversations, practice, and resources.
3. Support AI-assisted content production with editorial templates and content roadmaps.
4. Maintain a custom WordPress theme as the presentation layer.

## Success metrics

- SEO traffic
- Indexed pages
- Content velocity
- Retention
- Internal linking coverage
- Newsletter/signups
- Future monetization readiness

## Constraints

- The repository is a custom WordPress theme inside a full local WordPress site.
- The site architecture depends on custom post types and taxonomies registered by the theme.
- Existing `docs/content-system/` documentation and assets must remain available.
- Runtime uploads, cache, and environment-specific values should not be committed.
- Unknown requirements must be marked as `TBD`.
