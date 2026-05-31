# Script futuro

## Objetivo

Crear un script de Python para sincronizar el estado de los roadmaps CSV con los archivos generados.

## Contexto

Los roadmaps usan la columna `status` para indicar el estado editorial de cada entrada.

Estados propuestos:

```txt
planned
markdown_ready
html_ready
published
deleted
```

## Regla inicial

El script no debe generar contenido. Solo debe revisar archivos existentes y actualizar `status`.

## Flujo esperado

```txt
roadmap CSV
├─ construye la ruta Markdown esperada: output_folder + base_slug + .md
├─ construye la ruta HTML esperada: reemplaza generated-markdown-posts por generated-html-posts y usa base_slug + .html
└─ actualiza status
```

## Criterio tentativo

```txt
Si no existe Markdown:
status = planned

Si existe Markdown:
status = markdown_ready

Si existe HTML:
status = html_ready

Si WordPress confirma publicación:
status = published

Si status es published y se eliminó el archivo fuente generado:
status = deleted
```

## Alcance futuro

- Aplicar a Vocabulary, Reading, Conversation y Grammar.
- Evitar que los agentes gasten tokens editando CSV.
- Mantener la base de datos limpia.
- No mezclar creación de contenido con sincronización de estados.
- Los agentes de contenido no deben actualizar estados en los CSV.
