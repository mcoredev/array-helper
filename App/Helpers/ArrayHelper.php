<?php

namespace App\Helpers;

use Closure;

final class ArrayHelper
{
  public static function map(array $data, string|Closure $from, string|Closure $to, string|Closure|null $group = null): array
  {
      return self::processMapping($data, $from, $to, $group, false);
  }

  public static function mapToString(array $data, string|Closure $from, string|Closure $to, string|Closure|null $group = null, string $separate = ','): array
  {
      return self::processMapping($data, $from, $to, $group, true, $separate);
  }

  private static function processMapping(array $data, string|Closure $from, string|Closure $to, string|Closure|null $group, bool $isString, string $separate = ','): array
  {
      $results = [];
      foreach ($data as $keyIndex => $element) {
          $key = ($from === '_') ? $keyIndex : self::getNestedValue($element, $from);

          $group_key = self::resolveGroup($group, $element);

          $value = self::resolveValue($to, $element, $isString, $separate);

          if ($group_key === null) {
              $results[$key] = $value;
          } elseif ($group_key) {
              $results[$group_key][$key] = $value;
          }
      }

      return $results;
  }

  private static function resolveGroup(string|Closure|null $group, $element): string|null
  {
      if ($group instanceof Closure) {
          return $group($element);
      }

      return $group ? self::getNestedValue($element, $group) : null;
  }

  private static function resolveValue(string|Closure $to, $element, bool $isString, string $separate): string|array
  {
      if ($to instanceof Closure) {
          return $to($element);
      }

      if (strpos($to, ',') !== false) {
          return self::resolveMultipleValues($to, $element, $isString, $separate);
      }

      if ($to === "__item__") {
          return $element;
      }

      return self::getNestedValue($element, $to);
  }

  private static function resolveMultipleValues(string $to, $element, bool $isString, string $separate): string|array
  {
      $value = [];
      foreach (explode(',', $to) as $item) {
          $keyItem = trim($item);
          if (strpos($item, '.') !== false) {
              $keys = explode('.', $item);
              if (count($keys) > 1) {
                  $keyItem = end($keys);
              }
          }

          if ($keyItem) {
              $valueItem = self::getNestedValue($element, trim($item));
              $value[$keyItem] = $valueItem;
          }
      }

      return $isString ? implode($separate, $value) : $value;
  }

  public static function getNestedValue(array|object $element, string $path): string|array|null
  {
      if (!$path) {
          return null;
      }
      $keys = explode('.', $path);

      foreach ($keys as $key) {
          if (is_array($element) && isset($element[$key])) {
              $element = $element[$key];
          } elseif (is_object($element) && isset($element->{$key})) {
              $element = $element->{$key};
          } else {
              return null;
          }
      }

      return $element;
  }
}
