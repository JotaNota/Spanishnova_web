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
├─ taxonomía nueva: routes
├─ término route: beginner
├─ término route: intermediate
├─ término route: advance
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

Cambios que probablemente pertenecen a WordPress admin:

```txt
├─ crear taxonomía routes
├─ crear términos beginner, intermediate, advance
├─ revisar asociaciones de posts o páginas con routes
├─ revisar /route/beginner/
├─ revisar /level/beginner/
└─ revisar enlaces de la sección niveles en frontpage
```

Cambios que probablemente pertenecen al tema o código:

```txt
├─ templates de archive/taxonomy si existen
├─ templates de level si fueron modificados
├─ frontpage o bloque de niveles si está en el tema
├─ CSS si cambió la presentación
└─ funciones PHP si se registró la taxonomía routes en código
```

Pendiente:

```txt
1. Revisar archivos modificados con Git.
2. Identificar dónde se registró la taxonomía routes.
3. Identificar qué archivo controla la frontpage.
4. Identificar qué archivo controla /level/beginner/.
5. Identificar qué archivo controla /route/beginner/.
6. Definir qué se replica online desde archivos.
7. Definir qué se replica online desde WordPress admin.
8. No importar el tema completo.
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
