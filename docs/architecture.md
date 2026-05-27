# SpanishNova WordPress Architecture

Status: Draft

## Repository boundary

This repository is the custom SpanishNova WordPress theme. It lives inside a full local WordPress installation.

WordPress core, database state, uploads, plugin configuration, and environment settings live outside this theme unless documented otherwise.

## Site model

SpanishNova is a WordPress site with:

- Custom theme: `spanishnova`
- Structured editorial content
- SEO-first publishing goals
- AI-assisted content workflows under `docs/content-system/`

## Content types

Current custom post types:

- `grammar`
- `vocabulary`
- `readings`
- `conversations`
- `practice`
- `resources`

These post types are considered definitive and scalable.

## Taxonomies

Current registered taxonomies:

- `level`
- `topic`

These are the taxonomies currently registered in theme code.

## Taxonomy mismatch to resolve

Current code and documentation do not fully agree:

- `inc/taxonomies.php` registers `level` and `topic`.
- `header.php` references `grammar_tax`.
- `docs/content/content_clasification.md` describes `level_tax`, `grammar_tax`, `vocabulary_tax`, and `reading_tax`.

Resolve this before implementing taxonomy navigation or SEO topic clusters.

## Theme responsibilities

- Register theme supports and navigation menus.
- Register custom post types.
- Register shared taxonomies.
- Provide archive and single templates.
- Enqueue frontend assets.
- Render accessible, SEO-compatible frontend markup.

## Content system

`docs/content-system/` contains the existing AI-assisted editorial system, including agents, templates, roadmaps, and generated/source content.

This documentation scaffold references that system but does not replace it.

## Customization policy

1. Prefer theme code for presentation and content registration already owned by this project.
2. Avoid editing WordPress core or third-party plugin source directly.
3. If a vendor plugin must be patched, document the reason, version, files changed, and upgrade risk in `docs/decisions/`.
4. Keep secrets and environment-specific values outside git.

## Data ownership

- Database owns posts, terms, users, options, menus, and plugin settings.
- `uploads/` owns media files and should be backed up, but not committed by default.
- Theme repository owns custom theme code and project documentation.
- `docs/content-system/` owns editorial system documentation and assets.
