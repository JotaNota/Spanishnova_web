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
```

## Regla inicial

El script no debe generar contenido. Solo debe revisar archivos existentes y actualizar `status`.

## Flujo esperado

```txt
roadmap CSV
├─ revisa output_path del Markdown
├─ revisa HTML correspondiente si aplica
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
```

## Alcance futuro

- Aplicar a Vocabulary, Reading, Conversation y Grammar.
- Evitar que los agentes gasten tokens editando CSV.
- Mantener la base de datos limpia.
- No mezclar creación de contenido con sincronización de estados.
