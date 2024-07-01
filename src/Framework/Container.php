<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass, ReflectionNamedType;

class Container
{
  private array $definitions = [];

  public function addDefinitions(array $newDefinitions)
  {
    $this->definitions = array_merge($this->definitions, $newDefinitions);
  }

  public function resolve(string $className)
  {
    $reflectionClass = new ReflectionClass($className);

    if (!$reflectionClass->isInstantiable()) {
      throw new ContainerException("Class {$className} is not instantiable");
    }

    $constructor = $reflectionClass->getConstructor();

    if (!$constructor) {
      return new $className;
    }

    $params = $constructor->getParameters();

    if (count($params) === 0) {
      return new $className;
    }

    $dependencies = [];

    foreach ($params as $param) {
      $name = $param->getName();
      $type = $param->getType();

      if (!$type) {
        throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
      }

      if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
        throw new ContainerException("Failed to resolve class {$className} bacause invalid param name.");
      }
    }

    dd($params);
  }
}
