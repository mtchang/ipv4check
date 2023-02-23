<?php
/*
可以用 apnic 資訊取得所需要國別的設定
https://ftp.apnic.net/stats/apnic/	delegated-apnic-latest

// 產生所屬國家的 ip
$ wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/TW\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./tw_ip.list
$ wc tw_ip.list -l
872 tw_ip.list

wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/JP\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./jp_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/VN\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./vn_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/PH\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./ph_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/SG\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./sg_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/HK\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./hk_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/CN\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./cn_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/ID\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./id_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/TH\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./th_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/IN\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./in_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/US\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./us_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/MY\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./my_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/AU\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./au_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/KR\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./kr_ip.list
wget -O- 'http://ftp.apnic.net/apnic/stats/apnic/delegated-apnic-latest' | awk -F\| '/BD\|ipv4/ { printf("%s/%d\n", $4, 32-log($5)/log(2)) }' > ./bd_ip.list

ref: http://jameshclai.blogspot.com/2017/05/apnicip.html
ref:https://github.com/cloudflarearchive/Cloudflare-Tools/blob/master/cloudflare/ip_in_range.php


// 美國麻省理工
$ host mit.edu
mit.edu has address 23.2.130.241
mit.edu has IPv6 address 2600:140b:10:28e::255e
mit.edu has IPv6 address 2600:140b:10:2a0::255e
mit.edu mail is handled by 100 mit-edu.mail.protection.outlook.com.

// 台灣教育部
$ host www.edu.tw
www.edu.tw has address 140.111.14.180
www.edu.tw has IPv6 address 2001:288:0:14::180

update: 2023/02/24 by mtchang.tw@gmail.com
*/


echo "在台灣的判斷 \n";
$ipv4 = '140.111.14.180';
$ipv6 = '2001:288:0:14::180';
$rr = checkipv4($ipv4, "tw_ip.list");
var_dump($rr);

echo "不在台灣的判斷 \n";
$ipv4 = '23.2.130.241';
$ipv6 = '2600:140b:10:28e::255e';
$rr = checkipv4($ipv4, "tw_ip.list");
var_dump($rr);


// ---------------------------------------------------------------
// 判斷ipv4
function checkipv4($ipv4, $apnic_country_file){
    $r['ipv4']=$ipv4;
    $r['apnic_country_file']=$apnic_country_file;
    $r['ipincountry'] = false;

    $i=0;
    $handle = fopen("$apnic_country_file", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $i++;       
            // 逐行判斷
            if(ipv4_in_range( $ipv4, $line)){
                $r['detail'] = "[$i] $line";    
                $r['ipincountry'] = true;
                break;
            }
        }
        fclose($handle);
    }

    return($r);
}

// ---------------------------------------------------------------

/*
 * ip_in_range.php - Function to determine if an IP is located in a
 *                   specific range as specified via several alternative
 *                   formats.
 *
 * Network ranges can be specified as:
 * 1. Wildcard format:     1.2.3.*
 * 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
 * 3. Start-End IP format: 1.2.3.0-1.2.3.255
 *
 * Return value BOOLEAN : ip_in_range($ip, $range);
 *
 * Copyright 2008: Paul Gregg <pgregg@pgregg.com>
 * 10 January 2008
 * Version: 1.2
 *
 * Source website: http://www.pgregg.com/projects/php/ip_in_range/
 * Version 1.2
 *
 * This software is Donationware - if you feel you have benefited from
 * the use of this tool then please consider a donation. The value of
 * which is entirely left up to your discretion.
 * http://www.pgregg.com/donate/
 *
 * Please do not remove this header, or source attibution from this file.
 */

/*
* Modified by James Greene <james@cloudflare.com> to include IPV6 support
* (original version only supported IPV4).
* 21 May 2012 
*/


// decbin32
// In order to simplify working with IP addresses (in binary) and their
// netmasks, it is easier to ensure that the binary strings are padded
// with zeros out to 32 characters - IP addresses are 32 bit numbers
function decbin32 ($dec) {
    return str_pad(decbin($dec), 32, '0', STR_PAD_LEFT);
}

// ipv4_in_range
// This function takes 2 arguments, an IP address and a "range" in several
// different formats.
// Network ranges can be specified as:
// 1. Wildcard format:     1.2.3.*
// 2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
// 3. Start-End IP format: 1.2.3.0-1.2.3.255
// The function will return true if the supplied IP is within the range.
// Note little validation is done on the range inputs - it expects you to
// use one of the above 3 formats.
function ipv4_in_range($ip, $range) {
    if (strpos($range, '/') !== false) {
        // $range is in IP/NETMASK format
        list($range, $netmask) = explode('/', $range, 2);
        if (strpos($netmask, '.') !== false) {
            // $netmask is a 255.255.0.0 format
            $netmask = str_replace('*', '0', $netmask);
            $netmask_dec = ip2long($netmask);
            return ( (ip2long($ip) & $netmask_dec) == (ip2long($range) & $netmask_dec) );
        } else {
            // $netmask is a CIDR size block
            // fix the range argument
            $x = explode('.', $range);
            while(count($x)<4) $x[] = '0';
            list($a,$b,$c,$d) = $x;
            $range = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
            $range_dec = ip2long($range);
            $ip_dec = ip2long($ip);
            
            # Strategy 1 - Create the netmask with 'netmask' 1s and then fill it to 32 with 0s
            #$netmask_dec = bindec(str_pad('', $netmask, '1') . str_pad('', 32-$netmask, '0'));
            //var_dump($netmask);
            # Strategy 2 - Use math to create it
            $wildcard_dec = pow(2, (32- (int)$netmask)) - 1;
            $netmask_dec = ~ $wildcard_dec;
            
            return (($ip_dec & $netmask_dec) == ($range_dec & $netmask_dec));
        }
    } else {
        // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
        if (strpos($range, '*') !==false) { // a.b.*.* format
            // Just convert to A-B format by setting * to 0 for A and 255 for B
            $lower = str_replace('*', '0', $range);
            $upper = str_replace('*', '255', $range);
            $range = "$lower-$upper";
        }
        
        if (strpos($range, '-')!==false) { // A-B format
            list($lower, $upper) = explode('-', $range, 2);
            $lower_dec = (float)sprintf("%u",ip2long($lower));
            $upper_dec = (float)sprintf("%u",ip2long($upper));
            $ip_dec = (float)sprintf("%u",ip2long($ip));
            return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
        }
        return false;
    } 
}

function ip2long6($ip) {
    if (substr_count($ip, '::')) { 
        $ip = str_replace('::', str_repeat(':0000', 8 - substr_count($ip, ':')) . ':', $ip); 
    } 
        
    $ip = explode(':', $ip);
    $r_ip = ''; 
    foreach ($ip as $v) {
        $r_ip .= str_pad(base_convert($v, 16, 2), 16, 0, STR_PAD_LEFT); 
    } 
        
    return base_convert($r_ip, 2, 10); 
} 




?>