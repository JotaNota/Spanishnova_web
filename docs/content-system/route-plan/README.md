# Route plan

La ruta de aprendizaje es una selección ordenada de lecciones registradas en los roadmaps de sus respectivos CPT.

## Archivos

- `route-inventory.csv`: inventario de niveles, módulos, unidades y lecciones. Lo lee `inc/level-route-curriculum.php`.
- `a1-starter-a2-beginner-routes.md`: planificación editorial; no es un listado de publicaciones verificadas.

## Relación con los roadmaps

- `pk_route_item`: identificador único y estable de la fila del inventario, incluyendo agrupadores de unidad.
- `CPT`: tipo de contenido al que pertenece la lección.
- `source_pk`: clave del elemento en el roadmap correspondiente (por ejemplo, `pk_grammar` para Grammar). La pareja `CPT + source_pk` identifica una lección; el valor de `source_pk` no es global.
- `Slug`: referencia editorial y de WordPress. Se conserva para compatibilidad con el lector de rutas.
- Una lección puede estar en un roadmap sin aparecer en una ruta.
- Una entrada de tipo `Lección` debe resolverse contra su roadmap antes de considerarse vinculada. Las entradas de tipo `Unidad` son agrupadores y no requieren `source_pk`.

### Estado de la vinculación (2026-11-22)

Las filas tienen `pk_route_item`, y 13 de las 54 entradas de lección tienen `source_pk` verificado por coincidencia única de `Slug` con `base_slug`/`wp_slug` del roadmap. Otras 41 requieren conciliación; el campo queda vacío para evitar atribuciones falsas. Las filas se conservan por completo.

**No considerar `source_pk` vacío como una relación válida**. Antes de imponer integridad referencial se deben completar los roadmaps restantes y vincular las lecciones de la ruta.

## HTML y modelos visuales

```text
docs/content-system/
├── grammar-lesson-types/
│   └── grammar-verbs.html           # Modelo visual; no es una lección de producción
├── generated/html/
│   ├── grammar/                     # Salida actual del renderizador
│   ├── practice/
│   ├── vocabulary/
│   ├── readings/
│   ├── conversations/
│   └── resources/
├── roadmaps/
│   └── grammar-roadmap.csv
└── route-plan/
    ├── README.md
    ├── route-inventory.csv
    └── a1-starter-a2-beginner-routes.md
```

Cuando el renderizador y los scripts de publicación admitan rutas por subtipo, Grammar podrá organizarse bajo `generated/html/grammar/verbs/`, etc. **No mover los HTML generados sin actualizar los consumidores**. La clasificación se determina por el roadmap, y la presentación por el modelo visual.
