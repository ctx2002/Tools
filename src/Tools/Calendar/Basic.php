<?php
namespace Tools\Calendar;

class Basic {
    
    public function monthList($year, $month)
    {
        $list = array();
        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);          
            if (date('m', $time)==$month) {
                $list[]=date('Y-m-d', $time);
            }
        }
        return $list;
    }
    
    private function firstDayOf($monthList)
    {
        $str = $monthList[0];
        //zero is sunday
        return date('w', strtotime( $str));
    }
    
    public function spaceFill($year, $month)
    {
        $monthList = $this->monthList($year, $month);
            //var_dump($monthList);
        $firstWeekDay = $this->firstDayOf($monthList);
        for($i = 0; $i < $firstWeekDay; $i++) {
                    array_unshift($monthList, '');	
                }
        return $monthList; 	
    }
    
    public function month($year, $month)
    {
        $monthList = $this->spaceFill($year, $month);

        $weeks = 5;//5 week
        $days  = 7; // 7 days a week
        $list = array();

        for($i = 0; $i < $weeks; $i++) {
            $list[$i] = array();
            for($j = 0; $j < $days; $j++) {
			    $p = ($i * $days) + $j;
                if (isset($monthList[$p])) {
                    $str = $monthList[$p];
                    if ($str == '') {
                        $list[$i][$j] = array();	
                    } else {
                        $list[$i][$j] = array('date' => $str,
                                    'weekDayLong' => date('l', strtotime($str)),
                                    'weekDayShort' => date('D', strtotime($str))
                                    );	
                    }
		}				
	    }
	}
		
	return $list;
    }
}
