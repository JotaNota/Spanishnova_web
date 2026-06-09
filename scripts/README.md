# SpanishNova Upload Script

## Flujo lineal de grammar

```text
roadmap CSV -> content-data JSON -> render -> generated Markdown/HTML -> upload -> WordPress draft
```

1. Elegir una fila en `docs/content-system/content-plan/grammar-roadmap.csv`.
2. Crear o editar `docs/content-system/content-data/grammar/[slug].json`.
3. Renderizar con `python scripts/render_content.py --type grammar --slug [slug]`.
4. Revisar `docs/content-system/generated/generated-markdown-posts/grammar/[slug].md`.
5. Subir el HTML generado con `python scripts/upload_posts.py --upload-one --slug [slug]`.

El render genera un fragmento HTML compatible con el upload existente en
`docs/content-system/generated/generated-html-posts/grammar/[slug].html`.

## Render de contenido estructurado

Ruta del script:

`scripts/render_content.py`

Ejemplo piloto:

```bash
python scripts/render_content.py --type grammar --slug poder-presente
```

El render lee el roadmap, el JSON de content-data y el renderer interno de
`scripts/spanishnova_render/`. Valida que el slug exista, que el JSON exista, que
la fila use `cpt=grammar`, que los campos requeridos existan, y crea las carpetas
de salida si hacen falta.

El HTML generado es solo un fragmento para el cuerpo del post de WordPress. No
incluye `html`, `head`, `body`, `style`, `script` ni comentarios Gutenberg.

Este script sirve para subir posts generados desde el repositorio local a WordPress Local.

El script se corre desde la terminal.

Ruta del script:

`scripts/upload_posts.py`

---

## Qué hace el script

El script toma dos fuentes:

`docs/content-system/content-plan/*.csv`

Usa los roadmaps para leer:

- PK
- slug
- título
- status
- CPT
- taxonomías
- tags

`docs/content-system/generated/generated-html-posts/`

Usa los archivos HTML como contenido visible del post.

El HTML es lo que se sube al cuerpo del post en WordPress.

Los metadatos del roadmap no aparecen dentro del post.

---

## Archivo local necesario

El script necesita un archivo local:

`.env.local`

Ese archivo contiene los datos para conectar con WordPress Local.

Ejemplo:

```txt
WP_BASE_URL=http://spanishnova-localwp-full-import.local
WP_USERNAME=your_wordpress_username
WP_APP_PASSWORD=your_wordpress_application_password
```

`.env.local` no debe subirse a GitHub.

---

## Status del roadmap

El script usa la columna `status` para decidir qué puede hacer.

### `not-created`

Existe en el roadmap, pero todavía no existe el HTML generado.

El script no lo sube.

### `planned`

Existe el HTML y está listo para subir.

El script puede subirlo como draft.

### `draft`

Ya fue subido a WordPress como borrador.

El script no lo vuelve a subir.

### `published`

Ya está publicado.

El script no lo toca.

---

## Regla principal

Solo se sube lo que está en:

`planned`

Después de subirlo, el script cambia el status:

`planned → draft`

Eso evita duplicados.

---

## Roadmaps y PK

Cada roadmap tiene su propia PK.

```txt
grammar-roadmap.csv      → pk_grammar
vocabulary-roadmap.csv   → pk_vocabulary
conversation-roadmap.csv → pk_conversation
reading-roadmap.csv      → pk_reading
```

Ejemplo en grammar:

```csv
pk_grammar,base_slug,status
001,ser-presente,draft
002,estar-presente,draft
003,ir-presente,draft
004,hacer-presente,planned
```

La PK identifica la fila.

La PK no debe cambiar.

El `base_slug` puede usarse para encontrar el HTML.

---

# Comandos actuales

## Revisar conexión con WordPress

```bash
python scripts/upload_posts.py --check-auth
```

Sirve para confirmar que `.env.local` funciona y WordPress responde.

---

## Ver un post sin subirlo

```bash
python scripts/upload_posts.py --dry-run --slug hacer-presente
```

Sirve para revisar qué datos usaría el script.

No crea nada en WordPress.

---

## Subir un post por slug

```bash
python scripts/upload_posts.py --upload-one --slug hacer-presente
```

Solo funciona si ese post tiene:

`status = planned`

Si el post está en `draft`, el script se detiene.

Ejemplo:

```txt
Cannot upload ir-presente: status is draft. Only planned posts can be uploaded.
```

---

## Sincronizar status

```bash
python scripts/upload_posts.py --sync-status
```

Compara el roadmap con WordPress y con los HTML generados.

WordPress Local es la fuente principal para saber si un post ya existe. El
script consulta primero por `slug` con `status=any`. Si WordPress devuelve un
post, usa ese status aunque no exista HTML generado local.

Actualiza:

```txt
post draft → draft
post published → published
otro status de WordPress → ese status
sin post en WordPress y con HTML → planned
sin post en WordPress y sin HTML → not-created
```

Este comando es útil, pero debe usarse con cuidado.

---

## Auditar grammar sin modificar nada

```bash
python scripts/upload_posts.py --audit-grammar
```

Genera un reporte de solo lectura para reconciliar:

- `docs/content-system/content-plan/grammar-roadmap.csv`
- `docs/content-system/generated/generated-html-posts/grammar/`
- WordPress Local, CPT `grammar`

El reporte muestra:

- En roadmap pero no en WordPress
- En WordPress pero no en roadmap
- En generated HTML pero no en roadmap
- En roadmap sin generated HTML pero sí en WordPress
- Duplicados o slugs sospechosos, si se detectan

No modifica el CSV y no crea ni actualiza posts en WordPress.

---

## Ver cambios de orden de grammar sin aplicarlos

```bash
python scripts/upload_posts.py --dry-run-grammar-order
```

Lee `docs/content-system/content-plan/grammar-roadmap.csv` y compara la columna
`priority` con el `menu_order` actual de cada post del CPT `grammar` en
WordPress Local.

`priority` mantiene el orden pedagogico del roadmap. WordPress Local recibe el
orden invertido para que los posts mas nuevos o ultimos del roadmap aparezcan
arriba en el admin.

El reporte muestra los posts que cambiarian con:

```txt
slug=ser-presente priority=1 current_menu_order=0 target_menu_order=22
```

Tambien reporta filas `missing` cuando el slug no existe en WordPress e
`invalid` cuando `priority` esta vacio o no es numerico.

Las filas con `status=not-created` se ignoran.

No modifica WordPress y no modifica archivos.

---

## Sincronizar orden de grammar

```bash
python scripts/upload_posts.py --sync-grammar-order
```

Lee `docs/content-system/content-plan/grammar-roadmap.csv` y actualiza
`menu_order` de los posts existentes del CPT `grammar` usando el orden invertido:

```txt
menu_order = max_priority + 1 - priority
```

`priority` mantiene el orden pedagogico del roadmap. WordPress Local se ordena
invertido para que los posts mas nuevos o ultimos del roadmap aparezcan arriba
en el admin.

Este comando busca cada post por `slug` con `status=any`, no crea posts, no
cambia titulo, slug, contenido, status ni taxonomias. Si un post no existe, lo
reporta como `missing` y sigue. Si `priority` esta vacio o no es numerico, lo
reporta como `invalid` y sigue.

Las filas con `status=not-created` se ignoran.

---

# Comandos que queremos agregar

Estos comandos todavía no existen.

Son el diseño del flujo futuro.

---

## Subir por PK

```bash
python scripts/upload_posts.py --upload-pk --type grammar --pk 005
```

Significa:

```txt
Usar grammar-roadmap.csv
Buscar pk_grammar = 005
Subir solo si status = planned
Cambiar status a draft después de subir
```

---

## Subir rango de PK

```bash
python scripts/upload_posts.py --upload-range --type grammar --from 005 --to 010
```

Significa:

```txt
Usar grammar-roadmap.csv
Buscar pk_grammar desde 005 hasta 010
Subir solo filas con status = planned
Cambiar cada fila subida a draft
No tocar draft, published ni not-created
```

---

## Subir los próximos 5 de grammar

```bash
python scripts/upload_posts.py --upload-next --type grammar --limit 5
```

Significa:

```txt
Usar grammar-roadmap.csv
Buscar los primeros 5 posts con status = planned
Subirlos como drafts
Cambiar cada fila subida a draft
```

---

## Subir 1 de cada CPT

```bash
python scripts/upload_posts.py --upload-next-each --limit 1
```

Significa:

```txt
Subir 1 grammar planned
Subir 1 vocabulary planned
Subir 1 conversation planned
Subir 1 reading planned
Cambiar cada fila subida a draft
```

---

## Subir 2 de cada CPT

```bash
python scripts/upload_posts.py --upload-next-each --limit 2
```

Significa:

```txt
Subir 2 grammar planned
Subir 2 vocabulary planned
Subir 2 conversation planned
Subir 2 reading planned
Cambiar cada fila subida a draft
```

---

# Mapeo futuro del script

```txt
grammar
├─ roadmap: docs/content-system/content-plan/grammar-roadmap.csv
├─ pk: pk_grammar
├─ cpt: grammar
└─ html folder: docs/content-system/generated/generated-html-posts/grammar/

vocabulary
├─ roadmap: docs/content-system/content-plan/vocabulary-roadmap.csv
├─ pk: pk_vocabulary
├─ cpt: vocabulary
└─ html folder: docs/content-system/generated/generated-html-posts/vocabulary/

conversations
├─ roadmap: docs/content-system/content-plan/conversation-roadmap.csv
├─ pk: pk_conversation
├─ cpt: conversations
└─ html folder: docs/content-system/generated/generated-html-posts/conversations/

readings
├─ roadmap: docs/content-system/content-plan/reading-roadmap.csv
├─ pk: pk_reading
├─ cpt: readings
└─ html folder: docs/content-system/generated/generated-html-posts/readings/
```

---

# Flujo recomendado

1. Generar HTML.
2. Revisar roadmap.
3. Confirmar que el post esté en `planned`.
4. Correr `--dry-run`.
5. Correr `--upload-one`.
6. Verificar en WordPress.
7. Confirmar que el roadmap cambió a `draft`.

Ejemplo:

```bash
python scripts/upload_posts.py --dry-run --slug hacer-presente
python scripts/upload_posts.py --upload-one --slug hacer-presente
```

---

# Seguridad

* El script solo sube drafts.
* El script no publica posts.
* El script no debe subir `.env.local`.
* No pegar credenciales en el chat.
* No pegar credenciales en GitHub.
* No correr comandos masivos antes de probar con un solo post.
