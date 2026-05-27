# Requirements

Status: Draft

## Functional requirements

| ID | Requirement | Priority | Status | Notes |
|---|---|---:|---|---|
| FR-001 | Register structured content types for grammar, vocabulary, readings, conversations, practice, and resources. | High | Draft | Current CPTs are definitive and scalable. |
| FR-002 | Register `level` and `topic` taxonomies across structured content types. | High | Draft | Current taxonomies are definitive and scalable. |
| FR-003 | Provide archive and single templates for each structured content type. | High | Draft | Implemented in theme templates. |
| FR-004 | Support AI-assisted editorial production through existing `docs/content-system/`. | High | Draft | Keep current content system intact. |
| FR-005 | Support SEO-first publishing workflows. | High | Draft | Editorial rules TBD. |
| FR-006 | Support newsletter/signups. | Medium | Draft | Integration TBD. |
| FR-007 | Support future monetization readiness. | Medium | Draft | Model TBD. |

## Non-functional requirements

| ID | Requirement | Target | Status | Notes |
|---|---|---|---|---|
| NFR-001 | SEO performance | Indexable content architecture and internal linking coverage | Draft | Targets TBD. |
| NFR-002 | Accessibility | WCAG 2.2 AA target unless changed | Draft | Audit TBD. |
| NFR-003 | Performance | Fast theme output and cache-compatible templates | Draft | Budget TBD. |
| NFR-004 | Editorial maintainability | Reusable templates and clear content rules | Draft | Governed by docs and agents. |
| NFR-005 | Security | WordPress escaping, least-privilege admin, plugin hygiene | Draft | Policy TBD. |
| NFR-006 | Backup/recovery | TBD RPO/RTO | Draft | Hosting TBD. |

## Open questions

- Marketing ownership.
- Operations ownership.
- Newsletter/signups integration.
- Analytics stack.
- Monetization model.
- Internal linking rules.
- Publishing cadence.
