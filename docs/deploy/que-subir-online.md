# Qué subir online

## Objetivo

Definir un método para pasar cambios desde WordPress local hacia WordPress online sin importar todo el sitio ni pisar configuración viva del online.

## Problema

Importar el tema completo o copiar todo `wp-content` puede afectar plugins, Site Kit, Analytics y configuraciones que existen solo en el WordPress online.

El objetivo no es mover todo. El objetivo es identificar qué cambió y pasar solo esas piezas.

## Separación de cambios

```txt
Cambios detectables por Git
├─ archivos del tema
├─ CSS
├─ JS
├─ PHP
├─ JSON
├─ CSV
└─ HTML generado

Cambios no detectables por Git
├─ páginas editadas desde WordPress admin
├─ posts editados desde WordPress admin
├─ taxonomías creadas o modificadas
├─ categorías
├─ tags
├─ menús
├─ página principal
└─ ajustes de WordPress
```

## Regla principal

No importar WordPress completo.
No importar base de datos completa.
No copiar `wp-content` completo.
No reemplazar plugins del online.
No tocar Site Kit, Analytics ni configuración del sitio online.

## Método de trabajo

Antes de tocar el WordPress online:

1. Ver qué archivos cambió Git.
2. Anotar qué cambios se hicieron desde WordPress admin.
3. Separar los cambios entre archivos, contenido y estructura WordPress.
4. Pasar al online solo las piezas necesarias.
5. Revisar que el sitio online siga funcionando.

## Qué hacer según el tipo de cambio

```txt
Si cambió un archivo del tema
→ subir solo ese archivo

Si cambió una página
→ actualizar solo esa página online

Si cambió un post
→ actualizar solo ese post online

Si cambió una taxonomía
→ crearla o ajustarla online de forma manual o con script controlado

Si cambió la página principal
→ actualizar solo la página principal online

Si cambió un menú
→ ajustar solo ese menú online
```

## Control para cada lote de cambios

```txt
Fecha:
Rama:

Cambios hechos en local:
- 

Archivos detectados por Git:
- 

Páginas a actualizar online:
- 

Posts a actualizar online:
- 

Taxonomías a crear o ajustar online:
- 

Menús o ajustes visuales:
- 

No tocar:
[ ] plugins
[ ] Site Kit
[ ] Analytics
[ ] usuarios
[ ] configuración general
[ ] base de datos completa
[ ] wp-content completo

Revisión online:
[ ] home carga bien
[ ] páginas modificadas cargan bien
[ ] posts modificados cargan bien
[ ] taxonomías funcionan
[ ] menú funciona
[ ] Site Kit sigue conectado
[ ] Analytics sigue activo
```

## Caso actual

Cambios hechos hasta ahora:

```txt
Cambios locales
├─ taxonomía nueva: route_tax / routes
├─ término route: beginner
├─ término route: intermediate
├─ término route: advanced
├─ cambio en frontpage porque “niveles” ahora apunta a rutas nuevas
├─ creación/modificación de /route/beginner/
├─ modificación de /level/beginner/
└─ sección de niveles redirige a tres páginas nuevas de route
```

URLs locales mencionadas:

```txt
http://spanishnova-localwp-full-import.local/route/beginner/
http://spanishnova-localwp-full-import.local/level/beginner/
```

## Aclaración clave

`/route/beginner/` no debe importarse como una página de WordPress.

Esa URL sale de:

```txt
route_tax
├─ term: beginner
└─ template: taxonomy-route_tax.php
```

`/level/beginner/` tampoco es una página normal. Sale de:

```txt
level_tax
├─ term: beginner
└─ template: taxonomy-level_tax.php
```

La importación correcta no es crear páginas manuales para esas URLs. La importación correcta es subir el código, registrar la taxonomía, crear/sincronizar términos y asociar posts.

## Archivos/capas detectadas en ramas o PRs anteriores

La PR relacionada con presentación de rutas muestra que esto no es solo contenido de WordPress admin. Incluye cambios de tema, CSS, PHP, taxonomías, scripts y roadmap.

```txt
Tema / front
├─ front-page.php
├─ taxonomy-level_tax.php
├─ taxonomy-route_tax.php
├─ inc/enqueue.php
├─ inc/taxonomies.php
├─ inc/taxonomy-seeds.php
└─ assets/css/level-route.css

Scripts / sincronización
├─ scripts/upload_posts.py
├─ scripts/spanishnova_upload/uploader.py
├─ scripts/sync_roadmap_from_wp.py
└─ scripts/README.md

Roadmap / documentación
├─ docs/content-system/content-plan/grammar-roadmap.csv
├─ docs/content-system/content-plan/README.md
├─ docs/content-system/content-plan/beginner-route-skeleton.md
├─ docs/content-system/content-plan/intermediate-route-skeleton.md
├─ docs/content-system/content-plan/advanced-route-skeleton.md
└─ docs/decisions/route-intention.md
```

## Cómo importar este cambio online

```txt
1. Subir solo archivos del tema modificados
   ├─ front-page.php
   ├─ taxonomy-level_tax.php
   ├─ taxonomy-route_tax.php
   ├─ inc/enqueue.php
   ├─ inc/taxonomies.php
   ├─ inc/taxonomy-seeds.php
   └─ assets/css/level-route.css

2. Subir solo scripts y roadmaps si se van a usar para sincronizar
   ├─ scripts/upload_posts.py
   ├─ scripts/spanishnova_upload/uploader.py
   ├─ scripts/sync_roadmap_from_wp.py
   └─ docs/content-system/content-plan/grammar-roadmap.csv

3. Entrar al WordPress online
   ├─ visitar admin para que WordPress cargue el tema actualizado
   ├─ revisar que exista la taxonomía Routes / route_tax
   ├─ revisar que existan beginner, intermediate y advanced
   └─ guardar enlaces permanentes para refrescar reglas de URLs

4. Sincronizar relación de posts con rutas
   ├─ route_tax
   ├─ route_block
   └─ route_step

5. Revisar URLs online
   ├─ /route/beginner/
   ├─ /route/intermediate/
   ├─ /route/advanced/
   └─ /level/beginner/

6. Revisar home online
   ├─ enlaces de Beginner → /route/beginner/
   ├─ enlaces de Intermediate → /route/intermediate/
   └─ enlaces de Advanced → /route/advanced/

7. Revisar que no se tocó configuración viva
   ├─ plugins
   ├─ Site Kit
   ├─ Analytics
   ├─ usuarios
   └─ configuración general
```

## Qué NO hacer para este caso

```txt
No crear /route/beginner/ como página manual.
No importar base de datos completa.
No importar tema completo desde WordPress si eso pisa plugins/configuración.
No copiar wp-content completo.
No borrar plugins del online.
No reemplazar Site Kit.
```

## Qué implica para pasar esto online

```txt
No alcanza con crear páginas manualmente.
También hay que llevar archivos del tema.
También hay que registrar la taxonomía route_tax.
También hay que verificar que el CSS level-route.css esté cargando.
También hay que verificar que /route/beginner/ use taxonomy-route_tax.php.
También hay que verificar que /level/beginner/ use taxonomy-level_tax.php.
También hay que sincronizar route_tax, route_block y route_step en los posts que pertenecen a la ruta.
```

## Cambios que probablemente pertenecen a WordPress admin

```txt
├─ revisar que existan los términos beginner, intermediate, advanced en route_tax
├─ revisar asociaciones de posts con route_tax
├─ revisar metadatos route_block y route_step en posts de grammar
├─ revisar /route/beginner/
├─ revisar /level/beginner/
└─ revisar enlaces de la sección niveles en frontpage
```

## Cambios que probablemente pertenecen al tema o código

```txt
├─ registrar route_tax en inc/taxonomies.php
├─ sembrar términos route_tax en inc/taxonomy-seeds.php
├─ cargar level-route.css desde inc/enqueue.php
├─ modificar enlaces de niveles en front-page.php
├─ crear template taxonomy-route_tax.php
├─ modificar template taxonomy-level_tax.php
└─ subir assets/css/level-route.css
```

## Pendiente

```txt
1. Revisar archivos modificados con Git en la rama local actual.
2. Comparar contra PR #29 si esos cambios ya están en remoto.
3. Identificar si los cambios locales nuevos son continuación o corrección de esa rama.
4. Identificar si route_tax está registrada en el online.
5. Identificar si los términos beginner, intermediate y advanced existen online.
6. Identificar qué posts tienen route_tax, route_block y route_step.
7. Verificar /route/beginner/ online.
8. Verificar /level/beginner/ online.
9. No importar el tema completo.
```

## Ramas o PRs recientes relacionadas

```txt
#29 Work/grammar route presentation
#28 Add local WordPress vocabulary pull script
#27 Work/vocabulary roadmap posts
#26 Add WordPress-to-repo sync workflow
#25 Work/content roadmaps routes
#24 Publish posts and organize taxonomies
#23 Arch/mod content system
```
