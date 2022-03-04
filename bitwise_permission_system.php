<?php
// bitwise permissions example

// this would be a drop down menu in some sort of CMS or user management system
$roles = [
  1 => 'SUPER DUPER LEET',
  2 => 'ADMIN',
  4 => 'ACCOUNT APPROVER',
  8 => 'ORDER MANAGER',
  16 => 'SLACK EMOJI MAKER',
  32 => 'ALGORITHM SHMOOZER',
  64 => 'DATABASE EXCELORIZER',
  128 => 'END USER',
  256 => 'LOWEST ON THE TOTEM POLE',
  512 => 'JUST KIDDING BANNED',
];

// use the sum of the keys for each role.
// for example, if a user is an "END USER" and also "ALGORITHM SHMOOZER", the sum of the keys would be 128+32 = 160
$userRoles = 160;

function checkRole(int $role, int $userRoles): bool {
  return ($role & $userRoles);
}

foreach($roles as $bits => $role) {
  echo "user has permission " . $role . ": " . (checkRole($bits, $userRoles) ? "true":"false") . "\n";
}

/** output:
 user has permission SUPER DUPER LEET: false
 user has permission ADMIN: false
 user has permission ACCOUNT APPROVER: false
 user has permission ORDER MANAGER: false
 user has permission SLACK EMOJI MAKER: false
 user has permission ALGORITHM SHMOOZER: true
 user has permission DATABASE EXCELORIZER: false
 user has permission END USER: true
 user has permission LOWEST ON THE TOTEM POLE: false
 user has permission JUST KIDDING BANNED: false
*/
