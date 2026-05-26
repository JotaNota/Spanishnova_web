# Nota: Inicializar y publicar el repositorio

Esta nota resume los pasos que debes ejecutar más tarde para finalizar la inicialización del repo `Spanishnova_web` desde la carpeta del tema.

Preparación (en la máquina local dentro de la carpeta del tema):

1. Verificar estado del repo y ramas:
   - `git status`
   - `git branch --show-current`

2. Asegurar remoto correcto:
   - `git remote -v` (debe apuntar a https://github.com/JotaNota/Spanishnova_web.git)
   - Si es necesario: `git remote add origin <url>` o `git remote set-url origin <url>`

3. Hacer commit inicial (si faltara):
   - `git add .`
   - `git commit -m "Initial commit: tema Spanishnova"`

4. Sincronizar con remoto existente:
   - `git pull --rebase origin main` (resolver conflictos si aparecen)

5. Push al remoto:
   - `git push -u origin main`


Tareas opcionales (hacer después):

- Estas tareas son opcionales y se planificarán y ejecutarán en una fase posterior:
   - Crear un tag de versión: `git tag -a v1.0.0 -m "v1.0.0"` y `git push origin v1.0.0`
   - Añadir `CODEOWNERS`, `.gitattributes` y protección de ramas en GitHub.
   - Añadir CI minimal con GitHub Actions para ejecutar linters o comprobaciones de PHP/WordPress.
   - Considerar firmar commits (`git commit -S`) si se requiere.

Notas:

- Esta nota solo documenta los pasos; no ejecuta cambios.
- Ruta del archivo dentro del tema: `docs/initialize-repo.md`
