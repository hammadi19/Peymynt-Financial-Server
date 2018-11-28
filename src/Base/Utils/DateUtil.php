<?php

namespace App\Base\Utils;


/**
 * Handle Date related logic
 *
 * Class DateUtil
 * @package ATMedics\SystemBundle\Base\Utils
 */
class DateUtil
{

    public static function getTargetRange($targetDate){
        $rangeArray = array();
        switch($targetDate->format('D')){
            case 'Mon':
                $rangeArray['m'] = $targetDate->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
            case 'Tue':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P1D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
            case 'Wed':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P2D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
            case 'Thu':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P3D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
            case 'Fri':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P4D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->format('Y-m-d');
                break;
            case 'Sat':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P5D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
            case 'Sun':
                $rangeArray['m'] = $targetDate->sub(new \DateInterval('P6D'))->format('Y-m-d');
                $rangeArray['f'] = $targetDate->add(new \DateInterval('P4D'))->format('Y-m-d');
                break;
        }
        return $rangeArray;
    }

    /**
     * Get One month weeks rang
     *
     * @param $year
     * @param $month
     * @return array
     */
    public static function getWorkingWeeksByMonths($year,$month){
        $day_count = cal_days_in_month(CAL_GREGORIAN, $month , $year);
        $counter = 1;
        $outputArray = array();
        for($i = 1; $i <= $day_count; $i++) {
            $dateString = $year.'-'.$month.'-'.$i; //format date
            $dateObject = new \DateTime($dateString);
            $dayName = $dateObject->format('D');
            if($dayName == 'Mon'){
                $outputArray[$counter] = array();
                array_push($outputArray[$counter],
                    array(
                        'week' => $counter ,
                        'start_date' => $dateObject->format('Y-m-d')
                    )
                );
            }
            if($dayName == 'Fri'){
                if(array_key_exists($counter, $outputArray) && 'array' === gettype($outputArray[$counter])){
                    array_push($outputArray[$counter],
                        array(
                            'week' => $counter ,
                            'end_date' => $dateObject->format('Y-m-d')
                        )
                    );
                    $counter++;
                }
            }
        }
        return $outputArray;
    }

    /**
     * Match Target date with current date
     *
     * @param $targetDateString
     * @return bool
     */
    public static function matchDates($targetDateString){
        $targetDate = new \DateTime($targetDateString);
        $nowDate = new \DateTime('now');
        //$nowDate = new \DateTime('2016-11-08');
        return ($nowDate >= $targetDate);
    }


    /**
     * Get working week sequence
     *
     * @param $startDateObject
     * @param $endDateObject
     * @return array
     */
    public static function generateDateSequence($startDateObject, $endDateObject){
        $rangeArray = array();
        array_push($rangeArray, $startDateObject->format('Y-m-d'));
        for($i=1; $i < 5; $i++){
            $limitString = $startDateObject->add(new \DateInterval('P1D'))->format('Y-m-d');
            array_push($rangeArray, $limitString);
        }
        return $rangeArray;
    }


    /**
     * Is Working Week?
     *
     * @param $startDate
     * @param $endDate
     * @return bool
     */
    public static function isCurrentWorkingWeek($startDate,$endDate){
        $nowDate = new \DateTime('now');
        if ($nowDate->getTimestamp() > $startDate->getTimestamp() &&
            $nowDate->getTimestamp() < $endDate->getTimestamp()){
            return TRUE;
        }
        return FALSE;
    }


    /**
     * Get last date of given month
     *
     * @param $year
     * @param $month
     * @return int
     */
    public static function getLastDayOfMonth($year,$month){
        return cal_days_in_month(CAL_GREGORIAN, $month , $year);
    }


}//@