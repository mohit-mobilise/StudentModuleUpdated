<?php

$SchoolNameupper="Delhi Public School, RK Puram";
$SchoolName2="Delhi Public School, RK Puram";

$shortschoolname="DPSRKPUPRAM";


$HeaderBaseURL="https://schoolerpbeta.mobilisepro.com/Admin/Base.php";

$BaseURL="http://localhost/cursorai/Testing/studentportal/";
$BaseURL1="http://localhost/cursorai/Testing/studentportal/";


$SchoolAccountNo1="50592010056960";

$SchoolAccountNo2="916010055659937";

$MobileAppLink="https://play.google.com/store/apps/details?id=com.nkbpsrohini.app";

$SchoolName="Delhi Public School, RK Puram";
$SchoolName1="Delhi Public School, RK Puram";
$AdminApplicationName="School Information System [Admin Control Panel]";
$SchoolLogo="https://dpsrkpadmission.mobilisepro.com/Admin/images/logo.png";
$SchoolAddress="Delhi Public School R. K. Puram, Sector 12, RK Puram New Delhi-110022";
$SchoolAddress1="Sector 12, RK Puram New Delhi-110022";
//$SchoolPhoneNo="+91 011 49117700  +91-11-49116600";
$SchoolPhoneNo="+91-11-49115500";
$SchoolPhoneNoNew="0129-2280522, 2290522, 8744078558, 8744078548";
$TCMobile="+918744078548,+918744078558";
$SchoolEmailId="principal@dpsrkp.net";
$SchoolEmailIdTC="Info@dpsfsis.com";
$SchoolWebsite="www.dpsfaridabad.in";
$CommunicationEmailId="communication@dpsfsis.com";
$PrincipalEmailId="principal@dpsfsis.com";
$AdminEmailId="vikaspuri@dpsfsis.com";
$AccountsEmailId="accounts@dpsfsis.com";
$TransportEmailId="transport@dpsfsis.com";
$HeaderBaseURL="https://dpsfsis.com/Admin/Newlanding_page_menu.php";
$smsURL="http://mainadmin.dove-sms.com/sendsms.jsp?user=MALLLP&password=D@ve12&mobiles=";
$gcmURL="http://aravalisisgcm.in/school/SendGCM.php";
$AffiliationNo='530244';
$SchoolNo="4264";

// Load environment variables for API keys
if (!function_exists('load_env')) {
    require_once __DIR__ . '/Users/includes/env_loader.php';
}

// Get API keys from environment variables
$app_salt_key = $_ENV['APP_SALT_KEY'] ?? getenv('APP_SALT_KEY') ?? "wYCOfHnYvUbBbVJJRuOEQRoLBihkGbbP"; // dps rk puram
$app_merchant_key = $_ENV['APP_MERCHANT_KEY'] ?? getenv('APP_MERCHANT_KEY') ?? "I9Aiod"; // dps rk puram

$hostel_app_salt_key = $_ENV['HOSTEL_APP_SALT_KEY'] ?? getenv('HOSTEL_APP_SALT_KEY') ?? "wYCOfHnYvUbBbVJJRuOEQRoLBihkGbbP"; // hostel dps rk puram
$hostel_app_merchant_key = $_ENV['HOSTEL_APP_MERCHANT_KEY'] ?? getenv('HOSTEL_APP_MERCHANT_KEY') ?? "I9Aiod"; // hostel dps rk puram

$ChallanSchoolName="Delhi Public School, RK Puram";
 
$ChallanSchoolAddress="Sector 12, RK Puram New Delhi-110022";
 
$ChallanSchoolPhoneNo="011-49115500";
 
$ChallanSchoolEmailId="011-49115500";
 
$ChallanSchoolWebsite="011-49115500";

$lateFeeCalculationType = 'daywise'; // DPS Navi Mumbai
// $lateFeeCalculationType = 'datewise'; // DPS RKP

// ==================student_subject_check_by_head.php==================
    $excludedSubjects = [
                            '25' => ['S12', 'S16', 'S17','S3'],
                            '26' => ['S32']
                        ];
    $maxMatchLimits = [
                        '25' => 3, 
                        '26' => 1
                    ];
// ==================student_subject_check_by_head.php==================

$PaymentGateway = 'payu';
//$PaymentGateway = 'ccavenue';
 

?>