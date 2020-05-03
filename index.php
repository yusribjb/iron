<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
system('clear');
function guidv4($data)
{assert(strlen($data) == 16);
$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));}
$ip=exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
$IPJson = file_get_contents("http://ip-api.com/json/$ip");
$IPTrack = json_decode($IPJson);
?>
<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$VideosSuccess = 0; $VideosError = 0; $TaskSuccess = 0; $TaskError = 0; $incentVideoSuccess = 0; $customEndCardSuccess = 0; $playableAdSuccess = 0; $CPVISuccess = 0;
for ($i = 0; $i <= 999999; $i++) {
// Iron Source Config Data
$IronSource = file_get_contents("iron_config.json");
$input = json_decode($IronSource);
// Random Data
$timestamp = round(microtime(true) * 1000);
$randNumber = substr(str_shuffle("012345678901234567890123456789"), 0, 16);
$disk = rand(5000,25000)*2;
$battery = rand(1,20)*5;
// Proxy Config
$Luminati = file_get_contents("https://raw.githubusercontent.com/eyuswap/applovin/master/Luminati.json");
$arr = json_decode($Luminati, true);
$RandServer = $arr[rand(0,count($arr)-1)];
$RandProxy = json_decode(json_encode($RandServer));
$proxy = $RandProxy->PROXY_HOSTPORT;
$proxyauth = $RandProxy->PROXY_USERPASS;
// UserAgent Random
$UAJson = file_get_contents("ua.json");
$arr = json_decode($UAJson, true);
$RandUA = $arr[rand(0,count($arr)-1)];
$RandUAJSON = json_decode(json_encode($RandUA));
$UA = $RandUAJSON->ua;
$model = preg_replace('/\s+/', '+', $RandUAJSON->model);
$idfa = guidv4(openssl_random_pseudo_bytes(16));
//============================================================
$url = "https://rv-gateway.supersonicads.com/gateway/sdk/request?v=3.0.52&controllerId=$timestamp"."_0.$randNumber&applicationKey=$input->Apikey&applicationUserId=$idfa&debug=0&appOrientation=none&mobileCarrier=&connectionType=wifi&deviceOs=android&SDKVersion=5.78&deviceOSVersion=$RandUAJSON->os&deviceOEM=$RandUAJSON->brand&deviceVolume=0&immersiveMode=false&deviceIds%5BAID%5D=$idfa&displaySizeWidth=1080&displaySizeHeight=1920&deviceWidthDP=360&deviceHeightDP=640&deviceScreenScale=3.0&deviceLanguage=EN&deviceModel=$model&diskFreeSize=$disk&country=&isLimitAdTrackingEnabled=false&bundleId=$input->PkgNme&jb=true&gpi=true&mcc=510&mnc=10&appVersion=$input->AppVrs&batteryLevel=$battery&localTime=$timestamp&timezoneOffset=-420&androidIsVersion=1450198800000&sdkAbName=0&deviceApiLevel=21&demandSource=SupersonicAds&impressionsCount=0&instanceImpressions=0&totalRVSessionImpressions=0&totalISSessionImpressions=0&fpf=46&integrationType=A&retryCounter=0&abt=A&omidEnabled=false&moatSupported=false&_=$timestamp";
$h = [
    "accept: */*",
	"x-requested-with: $input->PkgNme",
	"sec-fetch-site: cross-site",
	"sec-fetch-mode: no-cors",
	"sec-fetch-dest: script",
	"accept-encoding: gzip, deflate",
    "accept-language: en-US,en;q=0.9",
    "connection: keep-alive",
    "content-type: application/json; charset=utf-8",
    "cache-control: no-cache",
	"host: rv-gateway.supersonicads.com",
    "user-agent: $UA",
     ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_URL,$url);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$x = curl_exec($ch);
curl_close($ch);
$JSONData = json_decode($x);
$r_a = rand(1,10); $r_b = rand(1,10); $r_c = rand(1,10);
$adType_a = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'type'};
$adType_b = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'type'};
$adType_c = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'type'};
//1.============================================================
if(preg_match("/incentVideo/", $adType_a)) {$incentVideoSuccess++; $urlImp_a = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}[0]->{'vastImpression'}[0]->{'url'}; $adType = "incentVideo";}
if(preg_match("/customEndCard/", $adType_a)) {$customEndCardSuccess++; $urlImp_a = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "customEndCard";}
if(preg_match("/playableAd/", $adType_a)) {$playableAdSuccess++; $urlImp_a = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "playableAd";}
if(preg_match("/CPVI/", $adType_a)) {$CPVISuccess++; $urlImp_a = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "CPVI";}
$h = [
    "accept: */*",
	"x-requested-with: $input->PkgNme",
	"sec-fetch-site: cross-site",
	"sec-fetch-mode: no-cors",
	"sec-fetch-dest: script",
	"accept-encoding: gzip, deflate",
    "accept-language: en-US,en;q=0.9",
    "connection: keep-alive",
    "content-type: application/json; charset=utf-8",
    "cache-control: no-cache",
	"host: outcome-cdn.supersonicads.com",
    "user-agent: $UA",
     ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_URL, $urlImp_a);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$xImp_a = curl_exec($ch);
curl_close($ch);
//2.============================================================
if(preg_match("/incentVideo/", $adType_b)) {$incentVideoSuccess++; $urlImp_b = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}[0]->{'vastImpression'}[0]->{'url'}; $adType = "incentVideo";}
if(preg_match("/customEndCard/", $adType_b)) {$customEndCardSuccess++; $urlImp_b = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "customEndCard";}
if(preg_match("/playableAd/", $adType_b)) {$playableAdSuccess++; $urlImp_b = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "playableAd";}
if(preg_match("/CPVI/", $adType_b)) {$CPVISuccess++; $urlImp_b = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "CPVI";}
$h = [
    "accept: */*",
	"x-requested-with: $input->PkgNme",
	"sec-fetch-site: cross-site",
	"sec-fetch-mode: no-cors",
	"sec-fetch-dest: script",
	"accept-encoding: gzip, deflate",
    "accept-language: en-US,en;q=0.9",
    "connection: keep-alive",
    "content-type: application/json; charset=utf-8",
    "cache-control: no-cache",
	"host: outcome-cdn.supersonicads.com",
    "user-agent: $UA",
     ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_URL, $urlImp_b);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$xImp_b = curl_exec($ch);
curl_close($ch);
//3.============================================================
if(preg_match("/incentVideo/", $adType_c)) {$incentVideoSuccess++; $urlImp_c = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}[0]->{'vastImpression'}[0]->{'url'}; $adType = "incentVideo";}
if(preg_match("/customEndCard/", $adType_c)) {$customEndCardSuccess++; $urlImp_c = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "customEndCard";}
if(preg_match("/playableAd/", $adType_c)) {$playableAdSuccess++; $urlImp_c = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "playableAd";}
if(preg_match("/CPVI/", $adType_c)) {$CPVISuccess++; $urlImp_c = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}->{'impressions'}[0]->{'url'}; $adType = "CPVI";}
$h = [
    "accept: */*",
	"x-requested-with: $input->PkgNme",
	"sec-fetch-site: cross-site",
	"sec-fetch-mode: no-cors",
	"sec-fetch-dest: script",
	"accept-encoding: gzip, deflate",
    "accept-language: en-US,en;q=0.9",
    "connection: keep-alive",
    "content-type: application/json; charset=utf-8",
    "cache-control: no-cache",
	"host: outcome-cdn.supersonicads.com",
    "user-agent: $UA",
     ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_URL, $urlImp_c);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$xImp_c = curl_exec($ch);
curl_close($ch);
//============================================================
if(preg_match("/incentVideo/", "$adType_a")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}[0]->{'90'}[0]->{'url'};}
if(preg_match("/incentVideo/", "$adType_b")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}[0]->{'90'}[0]->{'url'};}
if(preg_match("/incentVideo/", "$adType_c")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}[0]->{'90'}[0]->{'url'};}
if(preg_match("/playableAd/", "$adType_a")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_a]->{'ext'}->{'callbacks'}->{'29'}[0]->{'url'};}
if(preg_match("/playableAd/", "$adType_b")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_b]->{'ext'}->{'callbacks'}->{'29'}[0]->{'url'};}
if(preg_match("/playableAd/", "$adType_c")) {$UrlClick = $JSONData->{'seatbid'}->bid[$r_c]->{'ext'}->{'callbacks'}->{'29'}[0]->{'url'};}
$h = [
    "accept: */*",
	"x-requested-with: $input->PkgNme",
	"sec-fetch-site: cross-site",
	"sec-fetch-mode: no-cors",
	"sec-fetch-dest: script",
	"accept-encoding: gzip, deflate",
    "accept-language: en-US,en;q=0.9",
    "connection: keep-alive",
    "content-type: application/json; charset=utf-8",
    "cache-control: no-cache",
	"host: pixel-tracking.sonic-us.supersonicads.com",
    "user-agent: $UA",
     ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_URL,$UrlClick);
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$xClick = curl_exec($ch);
curl_close($ch);
if(preg_match("/pixel-tracking/", "$UrlClick")) {$TaskSuccess++; }
//============================================================
if(preg_match("/(incentVideo|customEndCard|playableAd|CPVI)/", "($adType_a|$adType_b|$adType_c)")) 
{
$VideosSuccess++;
echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;35mn\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;32m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35mn\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;30mDATE/TIME     : "; usleep(200000); echo date('d-m-Y - H:i:s')."\033[0m\n"; usleep(200000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mN\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;36mN\033[0m"; usleep(25000); echo"\033[1;31mm\033[0m"; usleep(25000); echo"\033[1;32mm\033[0m"; usleep(25000); echo"\033[1;31mm\033[0m"; usleep(25000); echo"\033[1;36mN\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;30mIP ADDRESS    : "; usleep(200000); echo "$ip - "; echo mb_strimwidth("$IPTrack->isp", 0, 15); echo "\033[0m\n"; usleep(200000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;36mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36mM\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mA\033[0m"; usleep(30000); echo "\033[1;32mD\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;33mT\033[0m"; usleep(30000); echo "\033[1;34mY\033[0m"; usleep(30000); echo "\033[1;35mP\033[0m"; usleep(30000); echo "\033[1;36mE\033[0m"; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[1;41m[▸]\033[0m \033[33;5m$adType\033[0m"; usleep(30000); echo "    \033[44m$IPTrack->countryCode\033[0m\n"; usleep(30000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mD\033[0m"; usleep(30000); echo "\033[1;32mE\033[0m"; usleep(30000); echo "\033[1;33mV\033[0m"; usleep(30000); echo "\033[1;34mI\033[0m"; usleep(30000); echo "\033[1;35mC\033[0m"; usleep(30000); echo "\033[1;36mE\033[0m"; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[33;5m[ $RandUAJSON->brand ]-[ $RandUAJSON->model ]-[ $RandUAJSON->os ]\033[0m\n";  usleep(30000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mR\033[0m"; usleep(30000); echo "\033[1;32mE\033[0m"; usleep(30000); echo "\033[1;33mW\033[0m"; usleep(30000); echo "\033[1;34mA\033[0m"; usleep(30000); echo "\033[1;35mR\033[0m"; usleep(30000); echo "\033[1;36mD\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;31mV\033[0m"; usleep(30000); echo "\033[1;32mI\033[0m"; usleep(30000); echo "\033[1;33mD\033[0m"; usleep(30000); echo "\033[1;34mE\033[0m"; usleep(30000); echo "\033[1;35mO\033[0m"; usleep(30000);  echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[42m✔\033[0m "; usleep(30000); echo "\033[33;5mS"; usleep(30000); echo "u"; usleep(30000); echo "c"; usleep(30000); echo "c"; usleep(30000); echo "e"; usleep(30000); echo "s"; usleep(30000); echo "s\033[0m➠"; usleep(30000);
}
else
{
$VideosError++;
echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;35mn\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;32m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35mn\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;30mDATE/TIME     : "; usleep(200000); echo date('d-m-Y - H:i:s')."\033[0m\n"; usleep(200000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mN\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;36mN\033[0m"; usleep(25000); echo"\033[1;31mm\033[0m"; usleep(25000); echo"\033[1;32mm\033[0m"; usleep(25000); echo"\033[1;31mm\033[0m"; usleep(25000); echo"\033[1;36mN\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;30mIP ADDRESS    : "; usleep(200000); echo "$ip - "; echo mb_strimwidth("$IPTrack->isp", 0, 15); echo "\033[0m\n"; usleep(200000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;36mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36mM\033[0m"; usleep(25000); echo"\033[1;35mM\033[0m"; usleep(25000); echo"\033[1;34mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mA\033[0m"; usleep(30000); echo "\033[1;32mD\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;33mT\033[0m"; usleep(30000); echo "\033[1;34mY\033[0m"; usleep(30000); echo "\033[1;35mP\033[0m"; usleep(30000); echo "\033[1;36mE\033[0m"; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[1;41m[▸]\033[0m \033[33;5m$adType\033[0m"; usleep(30000); echo "    \033[44m$IPTrack->countryCode\033[0m\n"; usleep(30000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mD\033[0m"; usleep(30000); echo "\033[1;32mE\033[0m"; usleep(30000); echo "\033[1;33mV\033[0m"; usleep(30000); echo "\033[1;34mI\033[0m"; usleep(30000); echo "\033[1;35mC\033[0m"; usleep(30000); echo "\033[1;36mE\033[0m"; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[33;5m[ $RandUAJSON->brand ]-[ $RandUAJSON->model ]-[ $RandUAJSON->os ]\033[0m\n";  usleep(30000); echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mN\033[0m "; usleep(25000); echo "➤\033[1;31mR\033[0m"; usleep(30000); echo "\033[1;32mE\033[0m"; usleep(30000); echo "\033[1;33mW\033[0m"; usleep(30000); echo "\033[1;34mA\033[0m"; usleep(30000); echo "\033[1;35mR\033[0m"; usleep(30000); echo "\033[1;36mD\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;31mV\033[0m"; usleep(30000); echo "\033[1;32mI\033[0m"; usleep(30000); echo "\033[1;33mD\033[0m"; usleep(30000); echo "\033[1;34mE\033[0m"; usleep(30000); echo "\033[1;35mO\033[0m"; usleep(30000);  echo " "; usleep(30000); echo " "; usleep(30000); echo "\033[1;31m: \033[0m"; usleep(30000); echo "\033[41m✖\033[0m "; usleep(30000); echo "\033[33;5mF"; usleep(30000); echo "a"; usleep(30000); echo "i"; usleep(30000); echo "l"; usleep(30000); echo "e"; usleep(30000); echo "d\033[0m➠"; usleep(30000);
}
if(preg_match("/(incentVideo|customEndCard|playableAd|CPVI)/", "($adType_a|$adType_b|$adType_c)")) 
{
$VideosSuccess++;
echo "\033[42m✔\033[0m "; usleep(30000); echo "\033[33;5mS"; usleep(30000); echo "u"; usleep(30000); echo "c"; usleep(30000); echo "c"; usleep(30000); echo "e"; usleep(30000); echo "s"; usleep(30000); echo "s\033[0m➠"; usleep(30000);
}
else
{
$VideosError++;
echo "\033[41m✖\033[0m "; usleep(30000); echo "\033[33;5mF"; usleep(30000); echo "a"; usleep(30000); echo "i"; usleep(30000); echo "l"; usleep(30000); echo "e"; usleep(30000); echo "d\033[0m➠"; usleep(30000);
}
if(preg_match("/(incentVideo|customEndCard|playableAd|CPVI)/", "($adType_a|$adType_b|$adType_c)")) 
{
$VideosSuccess++;
echo "\033[42m✔\033[0m "; usleep(30000); echo "\033[33;5mS"; usleep(30000); echo "u"; usleep(30000); echo "c"; usleep(30000); echo "c"; usleep(30000); echo "e"; usleep(30000); echo "s"; usleep(30000); echo "s\033[0m\n"; usleep(30000);
}
else
{
$VideosError++;
echo "\033[41m✖\033[0m "; usleep(30000); echo "\033[33;5mF"; usleep(30000); echo "a"; usleep(30000); echo "i"; usleep(30000); echo "l"; usleep(30000); echo "e"; usleep(30000); echo "d\033[0m\n"; usleep(30000);
}
$i++;
echo"       \033[1;31mN\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;32m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32mM\033[0m"; usleep(25000); echo"\033[1;31mM\033[0m "; usleep(25000); echo "➤\033[1;30mTOTAL         :\033[0m "; usleep(200000); echo "\033[44m$VideosSuccess\033[0m"; echo " \033[33;5mR"; usleep(30000); echo "e"; usleep(30000); echo "w"; usleep(30000); echo "a"; usleep(30000); echo "r"; usleep(30000); echo "d"; usleep(30000); echo " "; usleep(30000); echo "V"; usleep(30000); echo "i"; usleep(30000); echo "d"; usleep(30000); echo "e"; usleep(30000); echo "o"; usleep(30000); echo " "; usleep(30000); echo "L"; usleep(30000); echo "o"; usleep(30000); echo "a"; usleep(30000); echo "d"; usleep(30000); echo "e"; usleep(30000); echo "d\033[0m\n"; usleep(30000); echo"       \033[1;31m \033[0m"; usleep(25000); echo"\033[1;32m?\033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;32m \033[0m"; usleep(25000); echo"\033[1;31m \033[0m"; usleep(25000); echo"\033[1;36m \033[0m"; usleep(25000); echo"\033[1;35m \033[0m"; usleep(25000); echo"\033[1;34m \033[0m"; usleep(25000); echo"\033[1;33mM\033[0m"; usleep(25000); echo"\033[1;32m?\033[0m"; usleep(25000); echo"\033[1;31m \033[0m "; usleep(25000); echo "\033[33;5m=="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "\033[1;31mS\033[0m"; usleep(30000); echo "\033[1;32mc\033[0m"; usleep(30000); echo "\033[1;33mr\033[0m"; usleep(30000); echo "\033[1;34mi\033[0m"; usleep(30000); echo "\033[1;35mp\033[0m"; usleep(30000); echo "\033[1;36mt\033[0m"; usleep(30000); echo " "; usleep(30000);  echo "\033[1;32mB\033[0m"; usleep(30000); echo "\033[1;33my\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;35mE\033[0m"; usleep(30000); echo "\033[1;36my\033[0m"; usleep(30000); echo "\033[1;31mu\033[0m"; usleep(30000); echo "\033[1;32ms\033[0m"; usleep(30000); echo " "; usleep(30000);  echo "\033[1;34mP\033[0m"; usleep(30000); echo "\033[1;35mr\033[0m"; usleep(30000); echo "\033[1;36mo\033[0m"; usleep(30000); echo "\033[1;31mj\033[0m"; usleep(30000); echo "\033[1;32me\033[0m"; usleep(30000); echo "\033[1;33mc\033[0m"; usleep(30000); echo "\033[1;34mt\033[0m"; usleep(30000); echo " "; usleep(30000); echo "\033[1;36m©\033[0m"; usleep(30000); echo "\033[1;33m2\033[0m"; usleep(30000); echo "\033[1;34m0\033[0m"; usleep(30000); echo "\033[1;35m2\033[0m"; usleep(30000); echo "\033[1;36m0\033[0m"; usleep(30000); echo "\033[33;5m="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "="; usleep(30000); echo "==\033[0m\n"; usleep(30000); echo"\033[1;30m +---------------+---------------+---------------+---------------+---------------+\n"; usleep(40000); echo" | Ad Type       | incentVideo   | customEndCard | playableAd    | CPVI          |\n"; usleep(100000); echo" +---------------+---------------+---------------+---------------+---------------+\n"; usleep(100000); echo" | Success       |".mb_strimwidth(" $incentVideoSuccess Imprs       ", 0, 15)."|".mb_strimwidth(" $customEndCardSuccess Imprs       ", 0, 15)."|".mb_strimwidth(" $playableAdSuccess Imprs       ", 0, 15)."|".mb_strimwidth(" $CPVISuccess Imprs       ", 0, 15)."|\n"; usleep(100000); echo" | Completions   |".mb_strimwidth(" $TaskSuccess Done        ", 0, 15)."| null          | null          | null          |\n"; usleep(100000); echo" +---------------+---------------+---------------+---------------+---------------+\033[0m\n"; usleep(100000);
sleep(10);
}