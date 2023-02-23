# ipv4check
* 判斷指定 ipv4 是否在國家 ip 列表內, 以台灣 ip 為例子
```
$ php check_ip_country.php 
在台灣的判斷 
array(4) {
  ["ipv4"]=>
  string(14) "140.111.14.180"
  ["apnic_country_file"]=>
  string(10) "tw_ip.list"
  ["ipincountry"]=>
  bool(true)
  ["detail"]=>
  string(21) "[540] 140.111.0.0/16
"
}
不在台灣的判斷 
array(3) {
  ["ipv4"]=>
  string(12) "23.2.130.241"
  ["apnic_country_file"]=>
  string(10) "tw_ip.list"
  ["ipincountry"]=>
  bool(false)
}
```


* 可以用 apnic 資訊取得所需要國別的設定
* 資料來源 https://ftp.apnic.net/stats/apnic/	delegated-apnic-latest

```
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
```

```
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
```

* update: 2023/02/24 by mtchang.tw@gmail.com
