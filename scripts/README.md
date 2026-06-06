# SpanishNova Upload Script

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

Actualiza:

```txt
sin HTML → not-created
con HTML pero sin post → planned
post draft → draft
post published → published
```

Este comando es útil, pero debe usarse con cuidado.

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