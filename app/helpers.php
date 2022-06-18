<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

//Site Name (get from site owner dashboard)
define('site', '//change here');

//Database Name (get from site owner dashboard)
define('dbname', '//change here');

function hasSite($user)
{

   $user_count = DB::table('users')->where('email', $user)->count();
   if ($user_count > 0) {

      $user_refid = DB::table('users')->where('email', $user)->value('refid');

      $site_count = DB::table('assignment')->where('user', $user_refid)->where('site', site)->count();
      if ($site_count > 0) {
         return true;
      }else{
         return false;
      }
   }else{
      return false;
   }
}

function site()
{
   return site;
}

function dbname()
{
   return dbname;
}

function convert($iSeconds)
{
   $time_convert = [];

  $hour = intval($iSeconds / 3600);
   $min = intval($iSeconds / 60);

   $minafter = intval(($iSeconds / 60) % 60);
   $secafter = intval($iSeconds % 60);
   return $time_convert = array([
      'hour' => $hour,
      'min' => $min,
      'minafter' => $minafter,
      'secafter' => $secafter,
   ]);
}

function spellOut($num = false)
{
    $num = str_replace(array(',', ''), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '' );
        } elseif ($tens >= 20) {
            $tens = (int)($tens / 10);
            $tens = ' and ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    $words = implode(' ',  $words);
    $words = preg_replace('/^\s\b(and)/', '', $words );
    $words = trim($words);
    //$words = ucfirst($words);
    $words = $words;
    return $words;
}

function myArrayContainsWord(array $myArray, $key, $word) {
    foreach ($myArray as $element) {
        if ($element[0]->$key == $word){
            return true;
        }
    }
    return false;
}