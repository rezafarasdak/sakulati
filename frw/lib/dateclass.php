<?php

# date class
# coded by Alessandro Rosa
# e-mail : zandor_zz@yahoo.it
# site : http://malilla.supereva.it

# last update and bug fixed at : Jan 24th 2007

# Copyright (C) 2007  Alessandro Rosa

# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 3 of the License, or
# any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software Foundation,
# Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA

# Compiled with PHP 4.4.0

$lastday_months = array( 1 => 31, 
                         2 => 28,
                         3 => 31,
                         4 => 30,
                         5 => 31,
                         6 => 30,
                         7 => 31,
                         8 => 31,
                         9 => 30,
                         10 => 31,
                         11 => 30,
                         12 => 31 ) ;

$lastday_months2 = array( 1 => 31, 
                         2 => 29,
                         3 => 31,
                         4 => 30,
                         5 => 31,
                         6 => 30,
                         7 => 31,
                         8 => 31,
                         9 => 30,
                         10 => 31,
                         11 => 30,
                         12 => 31 ) ;

// fill this array with the months name in your output language

$name_months = array( "01" => January,
                      "02" => February,
                      "03" => March,
                      "04" => April,
                      "05" => May,
                      "06" => June,
                      "07" => July,
                      "08" => August,
                      "09" => September,
                      "10" => October,
                      "11" => November,
                      "12" => December
                    ) ;

// fill this array with the week days name in your output language

$name_days = array( "Sunday",
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday"
              ) ;

$short_name_days = array( "Sun",
                    "Mon",
                    "Tue",
                    "Wed",
                    "Thu",
                    "Fri",
                    "Sat"
              ) ;


class date
{
      function date()
      {
          $this->start_date = 0 ;
      }

      function isbisextile( $year )
      {
          if ( ( $year % 4 == 0 ) && ( $year % 100 != 0 ) && ( $year % 1000 != 0 ) ) return true ;
          else if ( $year % 400 == 0 ) return true ;
          else if ( ( $year % 1000 == 0 ) && ( $year % 4000 != 0 ) ) return true ;
          else return false ;
      }
      
      function set_start_date( $d, $m, $y )
      {
          $this->start_day = $d + 0; // + 0 forces each entry string to turn into a number
          $this->start_month = $m + 0;
          $this->start_year = $y + 0;
      
          $this->days_of_the_year = $this->isbisextile( $y ) ? 366 : 365 ;
      }

      function daysfrombegin()
      {
            $tmp_days = 0 ;
            
            $a = array();
            $a = $this->isbisextile( $this->start_year ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;

            $tmp_days += $this->start_day ;

            for ( $i = 1; $i <= ( intval( $this->start_month ) - 1 ); $i++ ) $tmp_days += $a{$i} ;
            
            return --$tmp_days ;
      }

      function daystoend()
      {
            $tmp_days = 0 ;
            
            $a = array();
            $a = $this->isbisextile( $this->start_year ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;

            $m = $this->start_month ; // zero-based index of the input month
            $tmp_days += ( $a{$m} - $this->start_day ) ;
            
            for ( $i = ( intval( $this->start_month ) + 1 ) ; $i <= 12; $i++ ) $tmp_days += $a{$i} ;
            
            return $tmp_days ;
      }

      function to_months( $months )
      {
            $tmp_d = $this->start_day ;
            $tmp_m = $this->start_month ;
            $tmp_y = $this->start_year ;

            $y = floor( $months / 12.0 ) ;
            $m = $months % 12 ;
            
            $tmp_m += $m ;
            $tmp_y += $y ;

            $this->end_day = ( intval( $tmp_d ) < 10 ) ? "0$tmp_d" : $tmp_d ;
            $this->end_month = ( intval( $tmp_m ) < 10 ) ? "0$tmp_m" : $tmp_m ;
            $this->end_year = ( intval( $tmp_y ) < 10 ) ? "0$tmp_y" : $tmp_y ;
      }

      function to_years( $years )
      {
            $tmp_d = $this->start_day ;
            $tmp_m = $this->start_month ;
            $tmp_y = $this->start_year ;

            $tmp_y += $years ;

            $this->end_day = ( intval( $tmp_d ) < 10 ) ? "0$tmp_d" : $tmp_d ;
            $this->end_month = ( intval( $tmp_m ) < 10 ) ? "0$tmp_m" : $tmp_m ;
            $this->end_year = ( intval( $tmp_y ) < 10 ) ? "0$tmp_y" : $tmp_y ;
      }

      function to_date( $days )
      {
            $tmp_d = $this->start_day ;
            $tmp_m = $this->start_month ;
            $tmp_y = $this->start_year ;
            
            $unit_d = ( intval( $days < 0 ) ) ? -1 : +1;
            $unit_m = ( intval( $days < 0 ) ) ? -1 : +1;
            $unit_y = ( intval( $days < 0 ) ) ? -1 : +1;
            
            $a = array();
            $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;

            $days = abs( $days );

            while( intval( $days ) > 0 )
            {
                  $tmp_d += $unit_d ;
                  $days-- ;
                  
                  if ( intval( $tmp_d ) == 0 ) // when $unit_d is -1
                  {
                        $tmp_m-- ;
                        if ( intval( $tmp_m ) == 0 )
                        {
                           $tmp_m = 12 ;
                           $tmp_y-- ;
    
                           $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
                        }
                  
                        while( intval( $days ) > $a{ intval( $tmp_m ) } )
                        {
                            $days -= $a{ intval( $tmp_m ) } ;
                            $tmp_m-- ;
 
                            if ( intval( $tmp_m ) == 0 )
                            {
                               $tmp_m = 12 ;
                               $tmp_y-- ;
    
                               $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
                            }
                       }
                        
                        $tmp_d = $a{ intval( $tmp_m ) } ;
                  }
                  else if ( $tmp_d == ( $a{ intval( $tmp_m ) } + 1 ) )  // when $unit_d is +1
                  {
                        // first we check whether the next month is January,
                        // therefore the year is incremented by one and checked if bisextile.
                                      
                        if ( ( intval( $tmp_m ) + 1 ) == 13 )
                        {
                            $tmp_m = 1 ;    $tmp_y++ ;
                            $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
                        }
                        else $tmp_m++ ;

                        while( intval( $days ) > $a{ intval( $tmp_m ) } ) // looks at the days of the next month
                        {
                            $tmp_d = $a{ intval( $tmp_m ) } ;
                            $days -= $tmp_d ;

                            if ( ( intval( $tmp_m ) + 1 ) == 13 )
                            {
                                $tmp_m = 1 ;    $tmp_y++ ;
                                $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
                            }
                            else $tmp_m++ ;
                        }

                        $tmp_d = 1 ;
                  }
                  //////////////////////////////////////////////
            }

            $this->end_day = ( intval( $tmp_d ) < 10 ) ? "0$tmp_d" : $tmp_d ;
            $this->end_month = ( intval( $tmp_m ) < 10 ) ? "0$tmp_m" : $tmp_m ;
            $this->end_year = ( intval( $tmp_y ) < 10 ) ? "0$tmp_y" : $tmp_y ;
      }

      function daysbetween( $sd, $sm, $sy, $ed, $em, $ey, &$bSwap )
      {
            $days = 0 ;

            $bSwap = false ;

            if ( intval( $ey ) < intval( $sy ) )
            {
                $this->swap( $sd, $ed ) ;
                $this->swap( $sm, $em ) ;
                $this->swap( $sy, $ey ) ;

                $bSwap = true ;
            }
            else if ( intval( $em ) < intval( $sm ) && intval( $ey ) == intval( $sy ) )
            {
                $this->swap( $sd, $ed ) ;
                $this->swap( $sm, $em ) ;
                $this->swap( $sy, $ey ) ;

                $bSwap = true ;
            }
            else if ( intval( $ed ) < intval( $sd ) && intval( $em ) == intval( $sm ) && intval( $ey ) == intval( $sy ) )
            {
                $this->swap( $sd, $ed ) ;
                $this->swap( $sm, $em ) ;
                $this->swap( $sy, $ey ) ;

                $bSwap = true ;
            }
      
            
      
            $a = array();
            $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;

            $days = abs( $days );

            $tmp_d = $sd ; $tmp_m = $sm ; $tmp_y = $sy ;

            while( true )
            {
                  if ( $tmp_d == $ed && $tmp_m == $em && $tmp_y == $ey ) break;

                  $tmp_d++ ;
                  
                  if ( intval( $tmp_d ) == ( $a{ intval( $tmp_m ) } + 1 ) )
                  {
                        $tmp_d = 1 ;
                        $tmp_m++ ;

                        if ( intval( $tmp_m ) == 13 )
                        {
                           $tmp_m = 1 ;    $tmp_y++ ;
                           $a = $this->isbisextile( $tmp_y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
                        }
                  }

                  //////////////////////////////////////////////
                  $days++ ; // loops to exit
            }
      
            return ( $bSwap ) ? "-$days" : $days ;
      }

      function timebetween( $sh, $sm, $ss, $eh, $em, $es, &$bOneDayOut )
      {
            $a = array() ;

            if ( ( $ss < 0 || $ss > 59 ) ||
                 ( $eh < 0 || $eh > 59 ) ||
                 ( $sm < 0 || $sm > 59 ) ||
                 ( $em < 0 || $em > 59 ) ||
                 ( $sh < 0 || $sh > 23 ) ||
                 ( $eh < 0 || $eh > 23 ) )
            {
                $a['seconds'] = -1 ;
                $a['minutes'] = -1 ;
                $a['hours'] = -1 ;
                
                return $a ;
            }

if ( $ss > $es || $sm > $em || $sh > $eh )
{
    $bOneDayOut = true ;

    $this->swap( $ss, $es );
    $this->swap( $sm, $em );
    $this->swap( $sh, $eh );
}

            $seconds = $ss - $es ;
            $minutes = $sm - $em ;
            $hours = $sh - $eh ;

            $bSECS = false ;
            $bMINS = false ;
            $bHOURS = false ;

            if ( $seconds < 0 )
            {
                $seconds = 60 - abs( $seconds ) ;  $bSECS = true ;
            }

            if ( $minutes < 0 )
            {
                $minutes = 60 - abs( $minutes ) ; $bMINS = true ;
            }

            if ( $hours < 0 )
            {
                $hours = 24 - abs( $hours ) ; $bHOURS = true ;
            }
      
            if ( $bSECS ) $minutes-- ;
            if ( $bMINS ) $hours-- ;
      
            $a['seconds'] = $seconds ;
            $a['minutes'] = $minutes ;
            $a['hours'] = $hours ;

            return $a ;
      }

      function to_time( $sHOURS, $sMINUTES, $sSECONDS, $withinHOURS, $withinMINUTES, $withinSECONDS )
      {
              if ( !( $this->check_time( $sHOURS, $sMINUTES, $sSECONDS ) ) ) return false ;
              
              if ( $withinHOURS < 0 || $withinMINUTES < 0 || $withinSECONDS < 0 )
              {
                  $withinHOURS = 0 - abs( $withinHOURS );
                  $withinMINUTES = 0 - abs( $withinMINUTES );
                  $withinSECONDS = 0- abs( $withinSECONDS );
              }
              
              $sSECONDS += $withinSECONDS ;
              $addONminutes = floor( $sSECONDS / 60 );
              $sSECONDS = ( $sSECONDS < 0 ) ? 60 - abs( $sSECONDS ) % 60 : $sSECONDS % 60 ;
              ///////////////////////////////
              $sMINUTES += $withinMINUTES + $addONminutes ;
              $addONhours = floor( $sMINUTES / 60 );
              $sMINUTES = ( $sMINUTES < 0 ) ? 60 - abs( $sMINUTES ) % 60 : $sMINUTES % 60 ;
              ///////////////////////////////
              if ( $sHOURS == 0 ) $sHOURS = 24 ;
              $sHOURS += $withinHOURS + $addONhours ;
              $sHOURS = ( $sHOURS < 0 ) ? 24 - abs( $sHOURS ) % 24 : $sHOURS % 24 ;
              
              $a = array();
              $a['seconds'] = abs( $sSECONDS );
              $a['minutes'] = abs( $sMINUTES );
              $a['hours'] = abs( $sHOURS );
              
              return $a ;
              
      }


      function get_start_all_date() { $a = array( $this->start_day, $this->start_month, $this->start_year ); return $a ; }
      function get_end_all_date() { $a = array( $this->end_day, $this->end_month, $this->end_year ); return $a ; }
      function get_end_all_date_ref( &$m, &$d, &$y ) { $d = $this->end_day ; $m = $this->end_month ; $y = $this->end_year; }

      function get_start_day()   { return $this->start_day ;   }
      function get_start_month() { return $this->start_month ; }
      function get_start_year()  { return $this->start_year ;  }
      function get_end_day()     { return $this->end_day ;     }
      function get_end_month()   { return $this->end_month ;   }
      function get_end_year()    { return $this->end_year ;    }

      function get_start_day_name() {
            $this->start_day += 0 ;
            $this->start_month += 0 ;
            
            $date = getdate( mktime( 0, 0, 0, $this->start_month, $this->start_day, $this->start_year ) );
              
            return $GLOBALS['name_days'][$date['wday']] ;
      }

      function get_end_day_name() {
            $this->end_day += 0 ;
            $this->end_month += 0 ;
            
            $date = getdate( mktime( 0, 0, 0, $this->end_month, $this->end_day, $this->end_year ) );
            
            return $GLOBALS['name_days'][$date['wday']] ;
      }

      function get_start_day_index() {
            $this->start_day += 0 ;
            $this->start_month += 0 ;
            
            $date = getdate( mktime( 0, 0, 0, $this->start_month, $this->start_day, $this->start_year ) );
              
            return ( $date['wday'] == 0 ) ? 7 : $date['wday'] ;
      }

      function get_end_day_index() {
            $this->end_day += 0 ;
            $this->end_month += 0 ;
            
            $date = getdate( mktime( 0, 0, 0, $this->end_month, $this->end_day, $this->end_year ) );
            
            return ( $date['wday'] == 0 ) ? 7 : $date['wday'] ;
      }

      function get_start_month_name() { $this->start_month += 0 ; $m = ( $this->start_month < 10 ) ? "0$this->start_month" : $this->start_month ; return $GLOBALS['name_months'][$m] ; }
      function get_end_month_name()   { $this->end_month += 0 ; $m = ( $this->end_month < 10 ) ? "0$this->end_month" : $this->end_month ; return $GLOBALS['name_months'][$m] ; }

      function get_month_days( $month, $year )
      { 
          $a = $this->isbisextile( $year ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;
          
          if ( $this->isbisextile( $year ) ) return $a[$month] ;
          else return $a[$month] ;
      }

      function swap( &$a, &$b ) { $t = $a ; $a = $b ; $b = $t ; }

      function isnumeric( $sText )
      {
          $re = "^[0-9]{1,2}$" ;
          return eregi( $re, $sText ) ;
      }

      function check_date( $d, $m, $y )
      {
            if ( !( $this->isnumeric( $d ) ) ) return false ;
            if ( !( $this->isnumeric( $m ) ) ) return false ;
            if ( !( $this->isnumeric( $d ) ) ) return false ;
            
            $a = array();
            $a = $this->isbisextile( $y ) ? $GLOBALS['lastday_months2'] : $GLOBALS['lastday_months'] ;

            if ( $m < 1 || $m > 12 ) return false ;     
            if ( $d < 1 || $d > $a{$m} ) return false ;
            
            return true ;
      }

      function check_time( $hours, $minutes, $seconds )
      {
            if ( !( $this->isnumeric( $hours ) ) ) return false ;
            if ( !( $this->isnumeric( $minutes ) ) ) return false ;
            if ( !( $this->isnumeric( $seconds ) ) ) return false ;
            
            if ( abs( $seconds ) >= 60 ) return false ;     
            if ( abs( $minutes ) >= 60 ) return false ;     
            if ( abs( $hours ) >= 24 ) return false ;     
            
            return true ;
      }
      
      function today( &$m, &$d, &$y )
      {
          $today = getdate();
          $m = $today['mon'];     $d = $today['mday'];      $y = $today['year'];
      }

      function format_today( $fmt, $sep )
      {
          if ( strlen( $fmt ) < 5 || strlen( $fmt ) > 8 ) $fmt = "mmddyyyy" ;
          if ( strlen( $sep ) == 0 ) $sep = "/" ;
          
          $today = getdate();
          $m = $today['mon'];     $d = $today['mday'];      $y = $today['year'];
          $d += 0 ;     $m += 0 ;     $y += 0 ;

          $d = ( $d < 10 ) ? "0$d" : $d ;
          $m = ( $m < 10 ) ? "0$m" : $m ;
      
          switch( $fmt )
          {
              case "ddmmyy":
                  $y = substr( $y, -2 ) ;
                  return "$d$sep$m$sep$y" ;
              break;
              case "mmddyy":
                  $y = substr( $y, -2 ) ;
                  return "$m$sep$d$sep$y" ;
              break;
              case "ddmmyyyy":
                  return "$d$sep$m$sep$y" ;
              break;
              case "mmddyyyy":
                  return "$m$sep$d$sep$y" ;
              break;
              default:
                  return "$m$sep$d$sep$y" ;
              break;
          }
      }

      function format_date( $m, $d, $y, $fmt, $sep )
      {
          if ( !isset( $fmt ) || strlen( $fmt ) < 5 || strlen( $fmt ) > 8 ) $fmt = "mmddyyyy" ;
          if ( !isset( $sep ) || strlen( $sep ) == 0 ) $sep = "/" ;

          $d += 0 ;     $m += 0 ;     $y += 0 ;

          $d = ( $d < 10 ) ? "0$d" : $d ;
          $m = ( $m < 10 ) ? "0$m" : $m ;
      
          switch( $fmt )
          {
              case "ddmmyy":
                  $y = substr( $y, -2 ) ;
                  return "$d$sep$m$sep$y" ;
              break;
              case "mmddyy":
                  $y = substr( $y, -2 ) ;
                  return "$m$sep$d$sep$y" ;
              break;
              case "ddmmyyyy":
                  return "$d$sep$m$sep$y" ;
              break;
              case "mmddyyyy":
                  return "$m$sep$d$sep$y" ;
              break;
              default:
                  return "$m$sep$d$sep$y" ;
              break;
          }
      }

      function format_time( $hours, $mins, $seconds, $fmt, $sep )
      {
          if ( !isset( $fmt ) || strlen( $fmt ) < 5 || strlen( $fmt ) > 8 ) $fmt = "hhmmss" ;
          if ( !isset( $sep ) || strlen( $sep ) == 0 ) $sep = ":" ;

          $hours += 0 ;     $mins += 0 ;     $seconds += 0 ;

          $prefix = ( $hours < 0 || $mins < 0 || $seconds < 0 ) ? "-" : "" ;

          $hours = abs( $hours );   $mins = abs( $mins );   $seconds = abs( $seconds );

          $hours = ( $hours < 10 ) ? "0$hours" : $hours ;
          $mins = ( $mins < 10 ) ? "0$mins" : $mins ;
          $seconds = ( $seconds < 10 ) ? "0$seconds" : $seconds ;
      
          switch( $fmt )
          {
              case "hhmmss":
                  return "$prefix$hours$sep$mins$sep$seconds" ;
              break;
          }
      }

      function isbefore( $sm, $sd, $sy, $em, $ed, $ey )
      {
          if ( $ey > $sy ) return true ;
          else if ( $ey < $sy ) return false ;
          else
          {
                if ( $em > $sm ) return true ;
                else if ( $em < $sm ) return false ;
                else
                {
                    if ( $ed > $sd ) return true ;
                    else if ( $ed < $sd ) return false ;
                    else return false ;
                }
          }
      }

      function isafter( $sm, $sd, $sy, $em, $ed, $ey )
      {
            if ( $this->isbefore( $sm, $sd, $sy, $em, $ed, $ey ) ) return false ;
            else return true ;
      }

      var $start_day ;
      var $start_month ;
      var $start_year ;
      var $days_of_the_year ;

      var $end_day ;
      var $end_month ;
      var $end_year ;

      var $lastday_months;
      var $err_no ;
}

?>
