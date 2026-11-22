# HTML de las lecciones

Este directorio guarda **contenido de lecciones** para WordPress. Los modelos visuales se guardan fuera de `generated/`, en `docs/content-system/grammar-lesson-types/`.

## Rutas para contenido nuevo

```text
generated/html/
├── grammar/
│   ├── verbs/         # lesson_type = verb-usage
│   ├── tenses/        # lesson_type = tense
│   └── particles/     # lesson_type = particle-set
├── practice/
├── vocabulary/
├── readings/
├── conversations/
└── resources/
```

Cada archivo HTML debe llevar el `base_slug` del roadmap correspondiente, por ejemplo `grammar/verbs/ser-presente.html`. No duplicar `base_slug` dentro del mismo CPT.

## Flujo actual

- El generador `scripts/render_content.py` admite hoy **grammar** y **vocabulary**. Grammar usa `lesson_type` del `grammar-roadmap.csv` para elegir la carpeta HTML de verbos, tiempos o partículas.
- El publicador `scripts/upload_posts.py` encuentra el HTML en la carpeta correspondiente y mantiene una búsqueda de respaldo en `generated/html/grammar/[slug].html` para contenido heredado.
- Los tipos `structure` y `comparison` que figuran en el roadmap siguen usando la raíz `generated/html/grammar/` hasta que exista una decisión de organización para ellos. No representan modelos visuales nuevos.
- Los HTML existentes en la raíz de cada CPT permanecen en su lugar: moverlos sin necesidad puede romper otras referencias. Los archivos nuevos del generador se guardarán en su carpeta.
- Los directorios de Practice, Readings, Conversations y Resources se prepararon, pero **el generador todavía no implementa** esos tipos. No confundir la carpeta existente con soporte de generación o publicación.
- Los JSON editables actuales siguen en `generated/json/[cpt]/[slug].json`; el renderizador genera el HTML y Markdown. El roadmap identifica cada lección; `route-plan/route-inventory.csv` organiza las que pertenecen a rutas.

## Verificación

Antes de publicar, comprobar la fila del roadmap, el slug y la ruta del HTML. La organización de archivos no publica contenido en WordPress ni altera el diseño de las lecciones.
