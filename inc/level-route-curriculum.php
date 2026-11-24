<?php
// Route hierarchy and explicit slugs are maintained in route-inventory.csv.
if (!defined('ABSPATH')) { exit; }
function spanishnova_level_curriculum() {
  $file = get_template_directory() . '/docs/content-system/route-plan/route-inventory.csv';
  $routes = ['starter' => [], 'beginner' => []];
  if (!is_readable($file)) return $routes;
  $handle = fopen($file, 'r');
  if (!$handle) return $routes;
  $headers = fgetcsv($handle);
  if (!$headers) { fclose($handle); return $routes; }
  $headers[0] = preg_replace('/^\\xEF\\xBB\\xBF/', '', $headers[0]);
  $cols = array_flip($headers);
  foreach (['Nivel','Módulo','Nombre del módulo','Unidad','Tipo','Lección','CPT','Slug'] as $key) {
    if (!isset($cols[$key])) { fclose($handle); return $routes; }
  }
  $current_unit = ['starter' => [], 'beginner' => []];
  while (($row = fgetcsv($handle)) !== false) {
    $v = static function($k) use($row,$cols) { return trim((string)($row[$cols[$k]] ?? '')); };
    $level = strpos($v('Nivel'),'A1') === 0 ? 'starter' : (strpos($v('Nivel'),'A2') === 0 ? 'beginner' : '');
    $num = (int)$v('Módulo');
    $module = $v('Nombre del módulo');
    $unit = $v('Unidad');
    $kind = $v('Tipo');
    if (!$level || $num < 1 || !$module) continue;
    if (!isset($routes[$level][$num])) $routes[$level][$num] = ['label'=>$module,'number'=>$num,'units'=>[]];
    if ($kind === 'Unidad') {
      if ($unit === '') continue;
      if (!isset($routes[$level][$num]['units'][$unit]))
        $routes[$level][$num]['units'][$unit] = ['title'=>$unit,'lessons'=>[]];
      $current_unit[$level][$num] = $unit;
      continue;
    }
    if ($kind !== 'Lección' || $v('Lección') === '') continue;
    // Starter uses the last declared unit; legacy Beginner rows may repeat the unit name.
    if ($unit === '') $unit = $current_unit[$level][$num] ?? '';
    if ($unit === '') continue;
    if (!isset($routes[$level][$num]['units'][$unit]))
      $routes[$level][$num]['units'][$unit] = ['title'=>$unit,'lessons'=>[]];
    $routes[$level][$num]['units'][$unit]['lessons'][] = ['title'=>$v('Lección'),'type'=>$v('CPT'),'slug'=>$v('Slug')];
  }
  fclose($handle);
  foreach ($routes as &$modules) {
    ksort($modules,SORT_NUMERIC); $modules = array_values($modules);
    foreach ($modules as &$module) $module['units'] = array_values($module['units']);
    unset($module);
  }
  unset($modules);
  return $routes;
}
