<?php

//
// given an array of strings, find the first occurrence where one letter of one string is in the same position as the same letter in another string for example:
//
// given ["abc", "def", "cba"], the algorithm should return [0, 2, 1], meaning that there was a match for the first string and last string, and that match was letter b 
// because they are the same letter in the same position
//
// if there is no match, [] should be returned
//
// assumptions are that all letters are lower case between a-z
//

$stringsArray = [
  ["abc", "def", "cba"],
  ["ggg", "hhh", "iii"],
  ["lambda", "grandpa", "grandma"],
  ["zhjkl", "yilkm", "leet"],
];

function solve(array $strings): array {

  $length = count($strings);

  for($i=0; $i<$length; $i++) {
    
    $current = str_split($test[$i]);
    $currentLength = count($current);
    
    for($j=$i+1; $j<$length; $j++) {
      
      $compare = str_split($test[$j]);
      $result = array_intersect_assoc($current, $compare);
      
      if (count($result) > 0) {
       return [$i, $j, array_key_first($result)];
      }
    }
  }

  return [];
}

$assertions = [
  [0, 2, 1], // abc, cba, b
  [], // no matches
  [1, 2, 2], // grandpa, grandma, a
  [0, 1, 3], // zhjkl, yilkm, k
];

foreach($stringsArray as $key => $stringArray) {
  var_dump(assert($assertions[$key] === solve($stringArray)));
}
