<?php



///////////////// Méthodes sur les dates et jeures /////////

/**
 * fait le nombre de mois de différence entre deux
 * dates (rappel: peut être negatif) prend en compte 
 * les années bissextiles !
 * @param DateTime $date1 periode 1
 * @param DateTime $date2 periode 2
 * @return int signé (donc le nombre de mois d'écart)
 */
function diffMonthsBissextile($date1, $date2) {
    $start = clone $date1;
    $end = clone $date2;
    $totalMonths = 0;
    if ($end < $start) {
        $temp = $start;
        $start = $end;
        $end = $temp;
    }
    $daysInMonth = [
        1 => 31, 2 => 28, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
        7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31
    ];
    while ($start->format('Y-m') != $end->format('Y-m')) {
        $daysInStartMonth = $daysInMonth[$start->format('n')];
        $totalMonths += ($daysInStartMonth - $start->format('j')) / $daysInStartMonth;
        $start->modify('+1 month');
        $start->setDate($start->format('Y'), $start->format('m'), 1);
    }
    $totalMonths += $end->format('n') - $start->format('n');
    return round($totalMonths);
}

/**
 * renvoie un double en partant d'une date
 */
function date2double($str="") { 
    $a = new DateTime($str);
    $b = (double)$a->format("U")+(double)($a->format("u")/1e6);
    return $b;
};

/**
 * transformation des localtime européen en americain
 * format: {Y}{Y}{Y}{Y}-{mo}{mo}-{d}{d}T{h}{h}:{mi}{mi} (le T est un caractère)
 * @param string|DateTime $date suit un format cohérent de timestamp ou un objet php DateTime
 */
function eur2EN($date){
    $res="";
    if(strtolower(gettype($date)) == "object" && get_class($date)=="DateTime")
        $res=$date->format("Y-m-d\TH:i");
    else
        $res=(new DateTime($date))->format("Y-m-d\TH:i");
    return $res;     
}


/**
 * transformation des localtime européen en americain
 * @param string|DateTime $date suit un format cohérent de timestamp ou obj php DateTime
 * @return strFormat {Y}{Y}{Y}{Y}-{mo}{mo}-{d}{d}T{h}{h}:{mi}{mi}:{s}{s} 
 * precisions : (le T est un caractère et il n'y a pas de crochets ils font juste office de séparateurs)
 */
function eur2EN_Etendu($date){
    $res="";
    if(strtolower(gettype($date)) == "object" && get_class($date)=="DateTime")
        $res=$date->format("Y-m-d\TH:i:s");
    else
        $res=(new DateTime($date))->format("Y-m-d\TH:i:s");
    return $res;  
}


