<?php 

function date_now($format = 'Y-m-d H:i:s') 
{
    return date($format, now());
}

function dateadd($interval, $date = NULL, $format = 'Y-m-d H:i:s')
{
    $date_obj = NULL;
    
    if(isset($date))
        $date_obj = date_create($date);
    else
        $date_obj = date_create(date_now());
    
    date_add($date_obj, date_interval_create_from_date_string($interval));
    
    return date_format($date_obj, $format);
}

function datesub($interval, $date = NULL, $format = 'Y-m-d H:i:s')
{
    $date_obj = NULL;
    
    if(isset($date))
        $date_obj = date_create($date);
    else
        $date_obj = date_create(date_now());
    
    date_sub($date_obj, date_interval_create_from_date_string($interval));
    
    return date_format($date_obj, $format);
}

function date_id($date, $format = 'l, j F Y') 
{
    /*
        Format : 

        d - The day of the month (from 01 to 31)
        D - A textual representation of a day (three letters)
        j - The day of the month without leading zeros (1 to 31)
        l - (lowercase 'L') - A full textual representation of a day
        N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
        S - The English ordinal suffix for the day of the month (2 characters st, nd, rd or th. Works well with j)
        w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
        z - The day of the year (from 0 through 365)
        W - The ISO-8601 week number of year (weeks starting on Monday)
        F - A full textual representation of a month (January through December)
        m - A numeric representation of a month (from 01 to 12)
        M - A short textual representation of a month (three letters)
        n - A numeric representation of a month, without leading zeros (1 to 12)
        t - The number of days in the given month
        L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
        o - The ISO-8601 year number
        Y - A four digit representation of a year
        y - A two digit representation of a year
        a - Lowercase am or pm
        A - Uppercase AM or PM
        B - Swatch Internet time (000 to 999)
        g - 12-hour format of an hour (1 to 12)
        G - 24-hour format of an hour (0 to 23)
        h - 12-hour format of an hour (01 to 12)
        H - 24-hour format of an hour (00 to 23)
        i - Minutes with leading zeros (00 to 59)
        s - Seconds, with leading zeros (00 to 59)
        u - Microseconds (added in PHP 5.2.2)
        e - The timezone identifier (Examples: UTC, GMT, Atlantic/Azores)
        I - (capital i) - Whether the date is in daylights savings time (1 if Daylight Savings Time, 0 otherwise)
        O - Difference to Greenwich time (GMT) in hours (Example: +0100)
        P - Difference to Greenwich time (GMT) in hours:minutes (added in PHP 5.1.3)
        T - Timezone abbreviations (Examples: EST, MDT)
        Z - Timezone offset in seconds. The offset for timezones west of UTC is negative (-43200 to 50400)
        c - The ISO-8601 date (e.g. 2013-05-05T16:34:42+00:00)
        r - The RFC 2822 formatted date (e.g. Fri, 12 Apr 2013 12:01:05 +0200)
        U - The seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
    */

    $hari = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu'
    ];

    $bulan_M = [
        'Jan' => 'Jan',
        'Feb' => 'Feb',
        'Mar' => 'Mar',
        'Apr' => 'Apr',
        'May' => 'Mei',
        'Jun' => 'Jun',
        'Jul' => 'Jul',
        'Aug' => 'Agu',
        'Sep' => 'Sep',
        'Oct' => 'Okt',
        'Nov' => 'Nov',
        'Dec' => 'Des'
    ];

    $bulan_F = [
        'January'   => 'Januari',
        'February'  => 'Februari',
        'March'     => 'Maret',
        'April'     => 'April',
        'May'       => 'Mei',
        'June'      => 'Juni',
        'July'      => 'Juli',
        'August'    => 'Agustus',
        'September' => 'September',
        'October'   => 'Oktober',
        'November'  => 'November',
        'December'  => 'Desember'
    ];

    $strtotime = strtotime($date);

    // 1 Jan 2019
    if($format == 'j M Y') 
    { 
        $j = date('j', $strtotime);
        $M = date('M', $strtotime);
        $Y = date('Y', $strtotime);
        
        return $j.' '.$bulan_M[$M].' '.$Y;
    } 
    
    // 1 Januari 2019
    elseif($format == 'j F Y') 
    { 
        $j = date('j', $strtotime);
        $F = date('F', $strtotime);
        $Y = date('Y', $strtotime);
        
        return $j.' '.$bulan_F[$F].' '.$Y;
    } 

    // Selasa, 1 Jan 2019
    elseif($format == 'l, j M Y') 
    { 
        $l = date('l', $strtotime);
        $j = date('j', $strtotime);
        $M = date('M', $strtotime);
        $Y = date('Y', $strtotime);
        
        return $hari[$l].', '.$j.' '.$bulan_M[$M].' '.$Y;
    } 

    // Selasa, 1 Jan 2019 07:00
    elseif($format == 'l, j M Y H:i') 
    { 
        $l = date('l', $strtotime);
        $j = date('j', $strtotime);
        $M = date('M', $strtotime);
        $Y = date('Y', $strtotime);
        $time = date('H:i', $strtotime);
        
        return $hari[$l].', '.$j.' '.$bulan_M[$M].' '.$Y.' '.$time;
    } 

    // Selasa, 1 Januari 2019
    elseif($format == 'l, j F Y') 
    { 
        $l = date('l', $strtotime);
        $j = date('j', $strtotime);
        $F = date('F', $strtotime);
        $Y = date('Y', $strtotime);
        
        return $hari[$l].', '.$j.' '.$bulan_F[$F].' '.$Y;
    } 

    // Selasa, 1 Januari 2019 07:00
    elseif($format == 'l, j F Y H:i') 
    { 
        $l = date('l', $strtotime);
        $j = date('j', $strtotime);
        $F = date('F', $strtotime);
        $Y = date('Y', $strtotime);
        $time = date('H:i', $strtotime);
        
        return $hari[$l].', '.$j.' '.$bulan_F[$F].' '.$Y.' '.$time;
    } 
}
