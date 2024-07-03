<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass, ReflectionNamedType;

class Container
{
  private array $definitions = [];
  private array $resolved = [];

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

      echo "<br>";
      var_dump($name);
      echo "<br><br>";
      echo "<br>";
      var_dump($type);
      echo "<br><br>";
      echo "<br>";
      var_dump($type->getName());
      echo "<br><br>";

      if (!$type) {
        throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
      }

      if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
        throw new ContainerException("Failed to resolve class {$className} bacause invalid param name.");
      }
      $dependencies[] = $this->get($type->getName());
    }

    echo "<br> --Dependencies::: ";
    var_dump($dependencies);
    echo "<br><br>";

    return $reflectionClass->newInstanceArgs($dependencies);
  }

  public function get(string $id)
  {
    if (!array_key_exists($id, $this->definitions)) {
      throw new ContainerException("Class {$id} does not exist in container.");
    }

    if (array_key_exists($id, $this->resolved)) {
      return $this->resolved[$id];
    }

    $factory = $this->definitions[$id];
    $dependency = $factory();

    $this->resolved[$id] = $dependency;

    echo "<br> --Dependency:::";
    var_dump($dependency);
    echo "<br><br>";

    return $dependency;
  }
}
