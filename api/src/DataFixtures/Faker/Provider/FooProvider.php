<?php
namespace App\DataFixtures\Faker\Provider;

class FooProvider
{
   public static function foo($str)
   {
       return 'test '. $str;
   }
}
