<?php

// example factory pattern which is quite a bit paraphrased from a real application that I wrote this for
// in the real world this is 1 step in a shipping calculator stack

interface Box {
  public function getPrice(): int;
}

class LargeBox implements Box {
  public function getPrice(): int {
    return 500;
  }
}

class MediumBox implements Box {
  public function getPrice(): int {
    return 250;
  }
}

class SmallBox implements Box {
  public function getPrice(): int {
    return 100;
  }
}

class ContainerFactory 
{
  public static function create($type) {
    switch($type) {
      case "large":
        return new LargeBox;
        break;
      case "medium":
        return new MediumBox;
        break;
      case "small":
        return new SmallBox;
        break;
      default:
        return new MediumBox;
    }
  }
}

$container = ContainerFactory::create("large");
var_dump(assert($container->getPrice() === 500));

$container = ContainerFactory::create("medium");
var_dump(assert($container->getPrice() === 250));

$container = ContainerFactory::create("small");
var_dump(assert($container->getPrice() === 100));

$container = ContainerFactory::create("does not exist");
var_dump(assert($container->getPrice() === 250));
