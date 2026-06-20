# Método elegido para importar cambios a WordPress online

## Decisión

Usar importación por piezas.

```txt
Archivos del tema
→ subir solo archivos modificados por SFTP, File Manager o el método de archivos del hosting

Contenido y estructura WordPress
→ sincronizar o recrear solo lo necesario desde WordPress admin o script controlado

Plugins, Site Kit, Analytics y configuración online
→ no importar, no reemplazar, no tocar
```

## Método descartado

No usar migración completa.
No importar tema completo desde WordPress.
No copiar `wp-content` completo.
No importar base de datos completa.

Motivo: ese flujo puede pisar plugins y configuración viva del sitio online.

## Método para el caso routes

```txt
1. Subir archivos PHP/CSS del tema modificados.
2. Confirmar que WordPress online registra `route_tax`.
3. Confirmar que existen los términos `beginner`, `intermediate`, `advanced`.
4. Sincronizar posts con `route_tax`, `route_block`, `route_step`.
5. Revisar URLs de route y level.
6. Revisar home.
7. Revisar Site Kit y Analytics.
```

## Archivos del tema que deben pasar online en este caso

```txt
front-page.php
taxonomy-level_tax.php
taxonomy-route_tax.php
inc/enqueue.php
inc/taxonomies.php
inc/taxonomy-seeds.php
assets/css/level-route.css
```

## Archivos de soporte que deben quedar en el repo

```txt
scripts/upload_posts.py
scripts/spanishnova_upload/uploader.py
scripts/sync_roadmap_from_wp.py
docs/content-system/content-plan/grammar-roadmap.csv
```

Estos archivos no necesariamente se suben al servidor público como parte del tema. Sirven para controlar y sincronizar el contenido.

## Regla práctica

```txt
Lo que afecta el frontend público
→ va al tema online

Lo que controla contenido, roadmap o scripts
→ queda en repo y se usa desde entorno local/controlado

Lo que pertenece a plugins/configuración online
→ queda intacto en online
```

## Validación mínima online

```txt
/route/beginner/
/route/intermediate/
/route/advanced/
/level/beginner/
/
```

Revisar:

```txt
[ ] home apunta a /route/beginner/
[ ] home apunta a /route/intermediate/
[ ] home apunta a /route/advanced/
[ ] /route/beginner/ muestra bloques
[ ] /level/beginner/ muestra contenido general
[ ] CSS de rutas carga
[ ] Site Kit sigue conectado
[ ] Analytics sigue activo
```
