<?php
// Route hierarchy and explicit slugs are maintained in route-inventory.csv.
if (!defined('ABSPATH')) { exit; }
function spanishnova_level_curriculum() {
  $file = get_template_directory() . '/docs/content-system/content-plan/route-inventory.csv';
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
  while (($row = fgetcsv($handle)) !== false) {
    $v = static function($k) use($row,$cols) { return trim((string)($row[$cols[$k]] ?? '')); };
    $level = strpos($v('Nivel'),'A1') === 0 ? 'starter' : (strpos($v('Nivel'),'A2') === 0 ? 'beginner' : '');
    $num = (int)$v('Módulo'); $module = $v('Nombre del módulo'); $unit = $v('Unidad');
    if (!$level || $num < 1 || !$module || !$unit) continue;
    if (!isset($routes[$level][$num])) $routes[$level][$num] = ['label'=>$module,'number'=>$num,'units'=>[]];
    if (!isset($routes[$level][$num]['units'][$unit])) $routes[$level][$num]['units'][$unit] = ['title'=>$unit,'lessons'=>[]];
    if ($v('Tipo') === 'Lección' && $v('Lección') !== '')
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
