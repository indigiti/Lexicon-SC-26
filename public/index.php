<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';
$path=path_only(); $method=$_SERVER['REQUEST_METHOD'] ?? 'GET';
header('X-Content-Type-Options: nosniff'); header('Referrer-Policy: strict-origin-when-cross-origin'); header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
if ($path==='/api/health' && $method==='GET') json_response(['ok'=>true,'version'=>'0.4.0','runtime'=>'php','database'=>false,'redis'=>false]);
if ($path==='/api/content/site' && $method==='GET') json_response(public_urls(['site'=>content('site'),'schools'=>content('schools')]));
if ($method==='GET' && preg_match('~^/api/calendar/(wagholi|kalyani-nagar|hadapsar)\.ics$~',$path,$calendarMatch)) {
    $slug=$calendarMatch[1]; $data=content('academics'); $campus=$data['campuses'][$slug]??[];
    $escapeIcs=static function(string $value): string { return str_replace(['\\',';',',',"\n","\r"],['\\\\','\\;','\\,','\\n',''],$value); };
    header('Content-Type: text/calendar; charset=utf-8'); header('Content-Disposition: attachment; filename="lexicon-'.$slug.'-academic-calendar-2026-27.ics"');
    echo "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//The Lexicon Schools//Academic Calendar 2026-27//EN\r\nCALSCALE:GREGORIAN\r\nMETHOD:PUBLISH\r\nX-WR-CALNAME:".$escapeIcs((string)($campus['name']??'Lexicon Campus'))." 2026-27\r\n";
    foreach(($campus['events']??[]) as $event){ $date=(string)($event['date']??''); if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)) continue; $start=str_replace('-','',$date); $end=(new DateTimeImmutable($date))->modify('+1 day')->format('Ymd'); $uid=$slug.'-'.$start.'-'.substr(sha1((string)($event['title']??'')),0,10).'@lexicon.digiti.in'; echo "BEGIN:VEVENT\r\nUID:$uid\r\nDTSTAMP:".gmdate('Ymd\THis\Z')."\r\nDTSTART;VALUE=DATE:$start\r\nDTEND;VALUE=DATE:$end\r\nSUMMARY:".$escapeIcs((string)($event['title']??'Lexicon event'))."\r\nDESCRIPTION:".$escapeIcs((string)($event['description']??''))."\r\nEND:VEVENT\r\n"; }
    echo "END:VCALENDAR\r\n"; exit;
}
if ($path==='/api/enquiry' && $method==='POST') {
    $raw=(string)file_get_contents('php://input'); $input=json_decode($raw,true); if(!is_array($input)) $input=$_POST;
    $name=trim((string)($input['parent_name']??$input['name']??'')); $phone=trim((string)($input['phone']??'')); $email=trim((string)($input['email']??''));
    if($name===''||$phone==='') json_response(['ok'=>false,'error'=>'Parent name and phone are required.'],422);
    if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL)) json_response(['ok'=>false,'error'=>'Enter a valid email address.'],422);
    $record=['id'=>'ENQ-'.gmdate('YmdHis').'-'.bin2hex(random_bytes(2)),'created_at'=>gmdate('c'),'parent_name'=>$name,'phone'=>$phone,'email'=>$email,'campus'=>(string)($input['campus']??''),'grade'=>(string)($input['grade']??''),'reason'=>(string)($input['reason']??''),'form_type'=>(string)($input['form_type']??'enquiry'),'child_name'=>(string)($input['child_name']??''),'contact_preference'=>(string)($input['contact_preference']??''),'preferred_date'=>(string)($input['preferred_date']??''),'preferred_time'=>(string)($input['preferred_time']??''),'visitors'=>(string)($input['visitors']??''),'interest'=>(string)($input['interest']??''),'message'=>(function(string $v): string { return function_exists('mb_substr') ? mb_substr($v,0,2000) : substr($v,0,2000); })(trim((string)($input['message']??'')))];
    $store=BASE_PATH.'/storage/enquiries.ndjson'; file_put_contents($store,json_encode($record,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL,FILE_APPEND|LOCK_EX);
    json_response(['ok'=>true,'id'=>$record['id']],201);
}
$map=[
 '/'=>['home','home-page'], '/lex360'=>['lex360','page page-lex360'], '/admissions'=>['admissions','page page-admissions'], '/schools/wagholi/admissions'=>['wagholi-admissions','page page-wagholi-admissions'], '/schools/wagholi/admissions/class-xi'=>['wagholi-class-xi','page page-wagholi-class-xi'], '/schools/kalyani-nagar/admissions'=>['campus-admissions','page page-campus-admissions page-kalyani-admissions'], '/schools/kalyani-nagar/admissions/class-xi'=>['campus-class-xi','page page-campus-class-xi page-kalyani-class-xi'], '/schools/hadapsar/admissions'=>['campus-admissions','page page-campus-admissions page-hadapsar-admissions'], '/schools/hadapsar/admissions/class-xi'=>['campus-class-xi','page page-campus-class-xi page-hadapsar-class-xi'], '/about'=>['about','page page-about'], '/achievements'=>['achievements','page page-achievements'], '/contact'=>['contact','page page-contact'], '/alumni'=>['alumni','page page-alumni'], '/careers'=>['careers','page page-careers'], '/legal/privacy'=>['privacy','page page-legal page-privacy'], '/legal/terms'=>['terms','page page-legal page-terms'], '/legal/disclosure'=>['disclosure','page page-legal page-disclosure'], '/campus-tour'=>['campus-tour','page page-campus-tour'], '/virtual-tour'=>['virtual-tour','page page-virtual-tour'], '/apply-now'=>['apply-now','page page-apply-now'], '/enquire'=>['enquire','page page-enquire'], '/book-a-visit'=>['book-visit','page page-book-visit'], '/transport'=>['transport','page page-transport page-transport-group']
];
if(isset($map[$path])) {
  $extra=['pageClass'=>$map[$path][1]];
  if (str_starts_with($path,'/schools/kalyani-nagar/admissions')) $extra['contentKey']='kalyani-admissions';
  if (str_starts_with($path,'/schools/hadapsar/admissions')) $extra['contentKey']='hadapsar-admissions';
  render($map[$path][0],$extra);
}
foreach (['wagholi','kalyani-nagar','hadapsar'] as $transportCampus) {
  if ($path === '/schools/' . $transportCampus . '/transport') render('transport',['pageClass'=>'page page-transport page-transport-campus page-transport-' . $transportCampus,'schoolSlug'=>$transportCampus]);
}
foreach (['wagholi','kalyani-nagar','hadapsar'] as $academicCampus) {
  $academicBase='/schools/'.$academicCampus.'/academics';
  if ($path===$academicBase) render('academics',['pageClass'=>'page page-academics page-academics-hub page-academics-'.$academicCampus,'schoolSlug'=>$academicCampus,'academicsMode'=>'hub']);
  if ($path===$academicBase.'/calendar') render('academics',['pageClass'=>'page page-academics page-academics-calendar page-academics-'.$academicCampus,'schoolSlug'=>$academicCampus,'academicsMode'=>'calendar']);
  if ($path===$academicBase.'/assessment') render('academics',['pageClass'=>'page page-academics page-academics-assessment page-academics-'.$academicCampus,'schoolSlug'=>$academicCampus,'academicsMode'=>'assessment']);
  if ($path===$academicBase.'/holidays') render('academics',['pageClass'=>'page page-academics page-academics-holidays page-academics-'.$academicCampus,'schoolSlug'=>$academicCampus,'academicsMode'=>'holidays']);
}
$schoolRoutes=[
 '/schools/wagholi'=>'wagholi',
 '/schools/kalyani-nagar'=>'kalyani-nagar',
 '/schools/hadapsar'=>'hadapsar',
];
if(isset($schoolRoutes[$path])) render('school',['pageClass'=>'page page-school page-school-'.$schoolRoutes[$path],'schoolSlug'=>$schoolRoutes[$path]]);
http_response_code(404); render('generic',['pageTitle'=>'Page not found — The Lexicon Schools','heading'=>'Page not found','intro'=>'The page you requested could not be found.','eyebrow'=>'404','cards'=>[['title'=>'Return Home','text'=>'Continue to The Lexicon Schools homepage.','href'=>'/','label'=>'Home']]]);
