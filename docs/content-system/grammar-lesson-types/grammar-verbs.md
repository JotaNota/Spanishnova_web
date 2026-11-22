# Grammar lesson type: Verbs

## Modelo visual vigente

- Modelo HTML proporcionado: [grammar-verbs.html](./grammar-verbs.html) (lección **SER**).
- Contenedor obligatorio en el cuerpo de la lección: `<div class="sn-grammar-verb-lesson">…</div>`.
- Estilos: `assets/css/grammar-lesson-types/grammar-verbs.css`.
- Interacciones: `assets/js/grammar-lesson-types/grammar-verbs.js`.
- Página de WordPress: `single-grammar.php`. Los estilos compartidos permanecen en `assets/css/singles.css` y `assets/css/grammar-lesson.css`.

Este archivo describe el **modelo visual ya utilizado**. El HTML adjunto se conserva como referencia de sus clases y componentes; no se convierte en una plantilla obligatoria de contenido para otros formatos.

## Componentes presentes en el modelo SER

- `grammar-lesson-hero` y `grammar-lesson-question`: pregunta inicial con opciones y respuesta.
- `grammar-lesson-module`: explicación y secciones por uso.
- `grammar-lesson-cards` y `grammar-lesson-card`: ejemplos.
- `grammar-lesson-note`: nota.
- `grammar-lesson-table`: conjugación, práctica y resumen.
- `data-grammar-checkable`, `data-grammar-check`, `data-grammar-reset`: comprobación interactiva de conjugaciones.
- `grammar-lesson-practice`, `grammar-lesson-exercise` y `grammar-lesson-reveal`: ejercicios con respuesta desplegable.
- `grammar-lesson-wrap-up`: resumen.

La fuente adjunta incluye ejemplos, respuestas y contenidos propios de SER. No deben copiarse automáticamente a otras lecciones.

## Dónde guardar las lecciones

El repositorio ya establece este flujo para **Grammar**:

```text
docs/content-system/
├── roadmaps/grammar-roadmap.csv
├── schemas/grammar-content-data.schema.json
├── generated/
│   ├── json/grammar/[slug].json       ← fuente editable
│   ├── html/grammar/[slug].html       ← HTML generado para WordPress
│   └── markdown/grammar/[slug].md     ← versión para revisión
└── grammar-lesson-types/
    ├── grammar-verbs.md               ← documentación del formato
    └── grammar-verbs.html             ← modelo visual SER
```

No editar el HTML generado como fuente de verdad si la lección se produce mediante `scripts/render_content.py`: cambiar el JSON y regenerar.

**Importante:** el modelo SER adjunto es HTML elaborado manualmente, y no se ha convertido a un JSON compatible con el renderizador. Mantenerlo como referencia HTML hasta definir una migración de contenido.
