 <?php
function isPlural($string) {
	return ($string > 1) ? 's' : '';
}

function ordinal($num) {
	if (!in_array(($num % 100),array(11,12,13))){
	  switch ($num % 10) {
	    // Handle 1st, 2nd, 3rd
	    case 1:  return $num.'st';
	    case 2:  return $num.'nd';
	    case 3:  return $num.'rd';
	  }
	}
	return $num.'th';
}


function getYears($val ='') {
 
	$current_year = date("Y");
	$years = range('2015', $current_year);

	foreach($years as $y) {
		$data[$y] = $y;
	}

	return ($val) ? $data[$val] : $data;
} 

function getMonths($val ='') {
 
	$data = array(
		"01" => "January",
		"02" => "February",
		"03" => "March",
		"04" => "April",
		"05" => "May",
		"07" => "July",
		"08" => "August",
		"09" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December"
	);

	return ($val) ? $data[$val] : $data;
} 

function getDates() {
	foreach (range(1, 23) as $date) {
		$data[$date] = ordinal($date);
	}
	return $data;
}

function getHours() {
	$data['00'] = '00';
	foreach (range(1, 23) as $hour) {
		$h = sprintf("%02d", $hour);
		$data[$h] = $h;
	}
	return $data;
}

function limit_length_of_stay($month) {
	foreach (range(1, $month) as $m) {
    	$data[$m.'m'] = $m.' month'.isPlural($m);
	}

	return $data;
}


function user_roles($val ='') {

	$data = array(
		'admin' => 'Administrator',
		'chemist' => 'Chemist',
		'user'  => 'User',
	);

	return ($val) ? $data[$val] : $data;
}

function length_of_stay($val ='') {

	$data = array(
		'1m'  => '1 month',
		'2m'  => '2 months',
		'3m'  => '3 months',
		'4m'  => '4 months',
		'5m'  => '5 months',
		'6m'  => '6 months',
		'7m'  => '7 months',
		'8m'  => '8 months',
		'9m'  => '9 months',
		'10m' => '10 months',
		'11m' => '11 months',
		'12m' => '1 Year',
	);

	return ($val) ? $data[$val] : $data;
}

function mode_of_payment($val ='') {

	$data = array(
		'cash' => 'Cash',
		'bank' => 'Bank Deposit: Cash',
		'check' => 'Check',
	);
	
	return ($val) ? $data[$val] : $data;
}

function get_course_year($val ='') {
	foreach (range(1,5) as $year) {
		$y = ordinal($year);
		$data[$y] = $y;
	}
	return ($val) ? $data[$val] : $data;
}


function get_civil_status($val ='') {

	$data = array(
		'single'  => 'Single',
		'married' => 'Married',
	);
	
	return ($val) ? $data[$val] : $data;
}

function get_status($val ='') {

if(Auth::user()->role != 'user')
	$data['']        = 'Bulk Actions';
	
	if(Auth::User()->role == 'admin') {
		$data['approve'] = 'Approve to Publish';
		$data['pending'] = 'Needing Review';
	}

	$data['trash']   = 'Move to Trash';
	$data['draft']   = 'Mark as Draft';

	unset($data[Input::get('status')]);
	
	return ($val) ? $data[$val] : $data;
}

function post_status($val ='') {

	$data = array(
		'approve' => '<span class="badge badge-roundless badge-primary">Approved</span>',
		'draft'   => '<span class="badge badge-roundless badge-success">Drafted</span>',
		'pending' => '<span class="badge badge-roundless badge-danger">Needing Review</span>',
		'trash'   => '<span class="badge badge-roundless badge-default">Trashed</span>',
	);
	
	return ($val) ? $data[$val] : $data;
}

function getMins() {
	$data['00'] = '00';
	foreach (range(1, 59) as $mins) {
		$m = sprintf("%02d", $mins);
		$data[$m] = $m;
	}
	return $data;
}

function get_sortable($val ='') {

	$data = array(
			'created_at-desc'   => 'Date: Newest',
			'created_at-asc'  => 'Date: Oldest',
			'price-asc'  => 'Price: Cheapest',
			'price-desc' => 'Price: Most Expensive',
		);

	return ($val) ? $data[$val] : $data;
}

function getDays($val ='') {

	$data = array(
			'sundays'    => 'Sunday',
			'mondays'    => 'Monday',
			'tuesdays'   => 'Tuesday',
			'wednesdays' => 'Wednesday',
			'thursdays'  => 'Thursday',
			'fridays'    => 'Friday',
			'saturdays'  => 'Saturday',
		);

	return ($val) ? $data[$val] : $data;
}

function db_backup_dir() {
	return 'mysql-db-backups';
}

function format_size($size) {
    $mod = 1024;
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }
    return round($size, 2) . ' ' . $units[$i];
}

function db_backup_link($domain ='', $file='') {
	return 'http://'.$domain.'/'.db_backup_dir().'/'.$file;
}

function dbBackupDetails($dbs = array(), $dbname ='', $file ='') {
	$data = ''; 
	$db = @$dbs[$dbname];
	if($db) {
		if($file != '') {	
			if($file == 'date') {			
				return date('M d, Y h:i:s A', $db->ctime);
			}
			$data = $db->$file;
		}
	}
	
	return $data;
}

function editFile($file, $key, $value, $seperator ='=') {
	$fh = fopen($file,'r+');
	// string to put username and passwords
	$i=1;
	$contents = '';
	while(!feof($fh)) {
		$user = explode($seperator,fgets($fh));

		// take-off old "\r\n"
		$file_key = @trim($user[0]);
		$file_val = @trim($user[1]);
		// check for empty indexes

		if($i == 1) {
			$contents .= "TIMEZONE" . '=' . "$value\n";	    	
		}

		if ($file_key == $key) {
		    $file_val = "";	
		}

		if (!empty($file_key) AND !empty($file_val)) {
		    $contents .= $file_key . $seperator . $file_val;
		    $contents .= "\n";
		}
		if($file_key == '') {
		    $contents .= "\n";
		}
		$i++;
	 }

	// using file_put_contents() instead of fwrite()
	file_put_contents($file, $contents);
	fclose($fh);	
}

function protocols($val ='') {

	$data['https'] = 'https';
	$data['http'] = 'http';

	return ($val) ? $data[$val] : $data;
}

function selected($val, $post) {
	return ($val == $post) ? 'selected="selected"' : '';	
}
function actived($val, $post) {
	return ($val == $post) ? 'active' : '';	
}

function checked_in_array($val, $array = array()) {
	if (in_array($val, $array)) {
		return 'checked="checked"';
	}
}

function checked($val, $post) {
	return ($val == $post) ? 'checked="checked"' : '';	
}

function status($val ='') {
	return ($val == 1) ? 'Active' : 'Inactive';	
}

function status_ico($val) {
	$data[0] = '<span class="fa fa-circle-o font-red"></span>';	
	$data[1] = '<span class="fa fa-check-square-o font-green"></span>';

	$data['Paid'] = '<span class="lbl label-primary">Paid</span>';
	$data['Unpaid'] = '<span class="lbl label-danger">Unpaid</span>';
	$data['Void'] = '<span class="lbl label-default">Void</span>';

	$data['occupied'] = '<span class="lbl lbl-info">Occupied</span>';
	$data['available'] = '<span class="lbl lbl-warning">Available</span>';

	echo $data[$val];
}


function genders($val ='') {

	$data['M'] = 'Male';
	$data['F'] = 'Female';

	return ($val) ? $data[$val] : $data;
}

//----------------------------------------------------------------

function getTimezones() {
	$timezone = array(
		"Africa" => 
		array(
			"Africa/Abidjan" => "Abidjan",
			"Africa/Accra" => "Accra",
			"Africa/Addis_Ababa" => "Addis Ababa",
			"Africa/Algiers" => "Algiers",
			"Africa/Asmara" => "Asmara",
			"Africa/Bamako" => "Bamako",
			"Africa/Bangui" => "Bangui",
			"Africa/Banjul" => "Banjul",
			"Africa/Bissau" => "Bissau",
			"Africa/Blantyre" => "Blantyre",
			"Africa/Brazzaville" => "Brazzaville",
			"Africa/Bujumbura" => "Bujumbura",
			"Africa/Cairo" => "Cairo",
			"Africa/Casablanca" => "Casablanca",
			"Africa/Ceuta" => "Ceuta",
			"Africa/Conakry" => "Conakry",
			"Africa/Dakar" => "Dakar",
			"Africa/Dar_es_Salaam" => "Dar es Salaam",
			"Africa/Djibouti" => "Djibouti",
			"Africa/Douala" => "Douala",
			"Africa/El_Aaiun" => "El Aaiun",
			"Africa/Freetown" => "Freetown",
			"Africa/Gaborone" => "Gaborone",
			"Africa/Harare" => "Harare",
			"Africa/Johannesburg" => "Johannesburg",
			"Africa/Juba" => "Juba",
			"Africa/Kampala" => "Kampala",
			"Africa/Khartoum" => "Khartoum",
			"Africa/Kigali" => "Kigali",
			"Africa/Kinshasa" => "Kinshasa",
			"Africa/Lagos" => "Lagos",
			"Africa/Libreville" => "Libreville",
			"Africa/Lome" => "Lome",
			"Africa/Luanda" => "Luanda",
			"Africa/Lubumbashi" => "Lubumbashi",
			"Africa/Lusaka" => "Lusaka",
			"Africa/Malabo" => "Malabo",
			"Africa/Maputo" => "Maputo",
			"Africa/Maseru" => "Maseru",
			"Africa/Mbabane" => "Mbabane",
			"Africa/Mogadishu" => "Mogadishu",
			"Africa/Monrovia" => "Monrovia",
			"Africa/Nairobi" => "Nairobi",
			"Africa/Ndjamena" => "Ndjamena",
			"Africa/Niamey" => "Niamey",
			"Africa/Nouakchott" => "Nouakchott",
			"Africa/Ouagadougou" => "Ouagadougou",
			"Africa/Porto-Novo" => "Porto-Novo",
			"Africa/Sao_Tome" => "Sao Tome",
			"Africa/Tripoli" => "Tripoli",
			"Africa/Tunis" => "Tunis",
			"Africa/Windhoek" => "Windhoek"
		),
		"America" => array(
			"America/Adak" => "Adak",
			"America/Anchorage" => "Anchorage",
			"America/Anguilla" => "Anguilla",
			"America/Antigua" => "Antigua",
			"America/Araguaina" => "Araguaina",
			"America/Argentina/Buenos_Aires" => "Argentina - Buenos Aires",
			"America/Argentina/Catamarca" => "Argentina - Catamarca",
			"America/Argentina/Cordoba" => "Argentina - Cordoba",
			"America/Argentina/Jujuy" => "Argentina - Jujuy",
			"America/Argentina/La_Rioja" => "Argentina - La Rioja",
			"America/Argentina/Mendoza" => "Argentina - Mendoza",
			"America/Argentina/Rio_Gallegos" => "Argentina - Rio Gallegos",
			"America/Argentina/Salta" => "Argentina - Salta",
			"America/Argentina/San_Juan" => "Argentina - San Juan",
			"America/Argentina/San_Luis" => "Argentina - San Luis",
			"America/Argentina/Tucuman" => "Argentina - Tucuman",
			"America/Argentina/Ushuaia" => "Argentina - Ushuaia",
			"America/Aruba" => "Aruba",
			"America/Asuncion" => "Asuncion",
			"America/Atikokan" => "Atikokan",
			"America/Bahia" => "Bahia",
			"America/Bahia_Banderas" => "Bahia Banderas",
			"America/Barbados" => "Barbados",
			"America/Belem" => "Belem",
			"America/Belize" => "Belize",
			"America/Blanc-Sablon" => "Blanc-Sablon",
			"America/Boa_Vista" => "Boa Vista",
			"America/Bogota" => "Bogota",
			"America/Boise" => "Boise",
			"America/Cambridge_Bay" => "Cambridge Bay",
			"America/Campo_Grande" => "Campo Grande",
			"America/Cancun" => "Cancun",
			"America/Caracas" => "Caracas",
			"America/Cayenne" => "Cayenne",
			"America/Cayman" => "Cayman",
			"America/Chicago" => "Chicago",
			"America/Chihuahua" => "Chihuahua",
			"America/Costa_Rica" => "Costa Rica",
			"America/Creston" => "Creston",
			"America/Cuiaba" => "Cuiaba",
			"America/Curacao" => "Curacao",
			"America/Danmarkshavn" => "Danmarkshavn",
			"America/Dawson" => "Dawson",
			"America/Dawson_Creek" => "Dawson Creek",
			"America/Denver" => "Denver",
			"America/Detroit" => "Detroit",
			"America/Dominica" => "Dominica",
			"America/Edmonton" => "Edmonton",
			"America/Eirunepe" => "Eirunepe",
			"America/El_Salvador" => "El Salvador",
			"America/Fortaleza" => "Fortaleza",
			"America/Glace_Bay" => "Glace Bay",
			"America/Godthab" => "Godthab",
			"America/Goose_Bay" => "Goose Bay",
			"America/Grand_Turk" => "Grand Turk",
			"America/Grenada" => "Grenada",
			"America/Guadeloupe" => "Guadeloupe",
			"America/Guatemala" => "Guatemala",
			"America/Guayaquil" => "Guayaquil",
			"America/Guyana" => "Guyana",
			"America/Halifax" => "Halifax",
			"America/Havana" => "Havana",
			"America/Hermosillo" => "Hermosillo",
			"America/Indiana/Indianapolis" => "Indiana - Indianapolis",
			"America/Indiana/Knox" => "Indiana - Knox",
			"America/Indiana/Marengo" => "Indiana - Marengo",
			"America/Indiana/Petersburg" => "Indiana - Petersburg",
			"America/Indiana/Tell_City" => "Indiana - Tell City",
			"America/Indiana/Vevay" => "Indiana - Vevay",
			"America/Indiana/Vincennes" => "Indiana - Vincennes",
			"America/Indiana/Winamac" => "Indiana - Winamac",
			"America/Inuvik" => "Inuvik",
			"America/Iqaluit" => "Iqaluit",
			"America/Jamaica" => "Jamaica",
			"America/Juneau" => "Juneau",
			"America/Kentucky/Louisville" => "Kentucky - Louisville",
			"America/Kentucky/Monticello" => "Kentucky - Monticello",
			"America/Kralendijk" => "Kralendijk",
			"America/La_Paz" => "La Paz",
			"America/Lima" => "Lima",
			"America/Los_Angeles" => "Los Angeles",
			"America/Lower_Princes" => "Lower Princes",
			"America/Maceio" => "Maceio",
			"America/Managua" => "Managua",
			"America/Manaus" => "Manaus",
			"America/Marigot" => "Marigot",
			"America/Martinique" => "Martinique",
			"America/Matamoros" => "Matamoros",
			"America/Mazatlan" => "Mazatlan",
			"America/Menominee" => "Menominee",
			"America/Merida" => "Merida",
			"America/Metlakatla" => "Metlakatla",
			"America/Mexico_City" => "Mexico City",
			"America/Miquelon" => "Miquelon",
			"America/Moncton" => "Moncton",
			"America/Monterrey" => "Monterrey",
			"America/Montevideo" => "Montevideo",
			"America/Montreal" => "Montreal",
			"America/Montserrat" => "Montserrat",
			"America/Nassau" => "Nassau",
			"America/New_York" => "New York",
			"America/Nipigon" => "Nipigon",
			"America/Nome" => "Nome",
			"America/Noronha" => "Noronha",
			"America/North_Dakota/Beulah" => "North Dakota - Beulah",
			"America/North_Dakota/Center" => "North Dakota - Center",
			"America/North_Dakota/New_Salem" => "North Dakota - New Salem",
			"America/Ojinaga" => "Ojinaga",
			"America/Panama" => "Panama",
			"America/Pangnirtung" => "Pangnirtung",
			"America/Paramaribo" => "Paramaribo",
			"America/Phoenix" => "Phoenix",
			"America/Port-au-Prince" => "Port-au-Prince",
			"America/Port_of_Spain" => "Port of Spain",
			"America/Porto_Velho" => "Porto Velho",
			"America/Puerto_Rico" => "Puerto Rico",
			"America/Rainy_River" => "Rainy River",
			"America/Rankin_Inlet" => "Rankin Inlet",
			"America/Recife" => "Recife",
			"America/Regina" => "Regina",
			"America/Resolute" => "Resolute",
			"America/Rio_Branco" => "Rio Branco",
			"America/Santa_Isabel" => "Santa Isabel",
			"America/Santarem" => "Santarem",
			"America/Santiago" => "Santiago",
			"America/Santo_Domingo" => "Santo Domingo",
			"America/Sao_Paulo" => "Sao Paulo",
			"America/Scoresbysund" => "Scoresbysund",
			"America/Shiprock" => "Shiprock",
			"America/Sitka" => "Sitka",
			"America/St_Barthelemy" => "St Barthelemy",
			"America/St_Johns" => "St Johns",
			"America/St_Kitts" => "St Kitts",
			"America/St_Lucia" => "St Lucia",
			"America/St_Thomas" => "St Thomas",
			"America/St_Vincent" => "St Vincent",
			"America/Swift_Current" => "Swift Current",
			"America/Tegucigalpa" => "Tegucigalpa",
			"America/Thule" => "Thule",
			"America/Thunder_Bay" => "Thunder Bay",
			"America/Tijuana" => "Tijuana",
			"America/Toronto" => "Toronto",
			"America/Tortola" => "Tortola",
			"America/Vancouver" => "Vancouver",
			"America/Whitehorse" => "Whitehorse",
			"America/Winnipeg" => "Winnipeg",
			"America/Yakutat" => "Yakutat",
			"America/Yellowknife" => "Yellowknife"
		),
		"Antarctica" => array(
			"Antarctica/Casey" => "Casey",
			"Antarctica/Davis" => "Davis",
			"Antarctica/DumontDUrville" => "DumontDUrville",
			"Antarctica/Macquarie" => "Macquarie",
			"Antarctica/Mawson" => "Mawson",
			"Antarctica/McMurdo" => "McMurdo",
			"Antarctica/Palmer" => "Palmer",
			"Antarctica/Rothera" => "Rothera",
			"Antarctica/South_Pole" => "South Pole",
			"Antarctica/Syowa" => "Syowa",
			"Antarctica/Vostok" => "Vostok"
		),
		"Arctic" => array(
			"Arctic/Longyearbyen" => "Longyearbyen",
		),
		"Asia" => array(
			"Asia/Aden" => "Aden",
			"Asia/Almaty" => "Almaty",
			"Asia/Amman" => "Amman",
			"Asia/Anadyr" => "Anadyr",
			"Asia/Aqtau" => "Aqtau",
			"Asia/Aqtobe" => "Aqtobe",
			"Asia/Ashgabat" => "Ashgabat",
			"Asia/Baghdad" => "Baghdad",
			"Asia/Bahrain" => "Bahrain",
			"Asia/Baku" => "Baku",
			"Asia/Bangkok" => "Bangkok",
			"Asia/Beirut" => "Beirut",
			"Asia/Bishkek" => "Bishkek",
			"Asia/Brunei" => "Brunei",
			"Asia/Choibalsan" => "Choibalsan",
			"Asia/Chongqing" => "Chongqing",
			"Asia/Colombo" => "Colombo",
			"Asia/Damascus" => "Damascus",
			"Asia/Dhaka" => "Dhaka",
			"Asia/Dili" => "Dili",
			"Asia/Dubai" => "Dubai",
			"Asia/Dushanbe" => "Dushanbe",
			"Asia/Gaza" => "Gaza",
			"Asia/Harbin" => "Harbin",
			"Asia/Hebron" => "Hebron",
			"Asia/Ho_Chi_Minh" => "Ho Chi Minh",
			"Asia/Hong_Kong" => "Hong Kong",
			"Asia/Hovd" => "Hovd",
			"Asia/Irkutsk" => "Irkutsk",
			"Asia/Jakarta" => "Jakarta",
			"Asia/Jayapura" => "Jayapura",
			"Asia/Jerusalem" => "Jerusalem",
			"Asia/Kabul" => "Kabul",
			"Asia/Kamchatka" => "Kamchatka",
			"Asia/Karachi" => "Karachi",
			"Asia/Kashgar" => "Kashgar",
			"Asia/Kathmandu" => "Kathmandu",
			"Asia/Khandyga" => "Khandyga",
			"Asia/Kolkata" => "Kolkata",
			"Asia/Krasnoyarsk" => "Krasnoyarsk",
			"Asia/Kuala_Lumpur" => "Kuala Lumpur",
			"Asia/Kuching" => "Kuching",
			"Asia/Kuwait" => "Kuwait",
			"Asia/Macau" => "Macau",
			"Asia/Magadan" => "Magadan",
			"Asia/Makassar" => "Makassar",
			"Asia/Manila" => "Manila",
			"Asia/Muscat" => "Muscat",
			"Asia/Nicosia" => "Nicosia",
			"Asia/Novokuznetsk" => "Novokuznetsk",
			"Asia/Novosibirsk" => "Novosibirsk",
			"Asia/Omsk" => "Omsk",
			"Asia/Oral" => "Oral",
			"Asia/Phnom_Penh" => "Phnom Penh",
			"Asia/Pontianak" => "Pontianak",
			"Asia/Pyongyang" => "Pyongyang",
			"Asia/Qatar" => "Qatar",
			"Asia/Qyzylorda" => "Qyzylorda",
			"Asia/Rangoon" => "Rangoon",
			"Asia/Riyadh" => "Riyadh",
			"Asia/Sakhalin" => "Sakhalin",
			"Asia/Samarkand" => "Samarkand",
			"Asia/Seoul" => "Seoul",
			"Asia/Shanghai" => "Shanghai",
			"Asia/Singapore" => "Singapore",
			"Asia/Taipei" => "Taipei",
			"Asia/Tashkent" => "Tashkent",
			"Asia/Tbilisi" => "Tbilisi",
			"Asia/Tehran" => "Tehran",
			"Asia/Thimphu" => "Thimphu",
			"Asia/Tokyo" => "Tokyo",
			"Asia/Ulaanbaatar" => "Ulaanbaatar",
			"Asia/Urumqi" => "Urumqi",
			"Asia/Ust-Nera" => "Ust-Nera",
			"Asia/Vientiane" => "Vientiane",
			"Asia/Vladivostok" => "Vladivostok",
			"Asia/Yakutsk" => "Yakutsk",
			"Asia/Yekaterinburg" => "Yekaterinburg",
			"Asia/Yerevan" => "Yerevan",
		),
		"Atlantic" => array(
			"Atlantic/Azores" => "Azores",
			"Atlantic/Bermuda" => "Bermuda",
			"Atlantic/Canary" => "Canary",
			"Atlantic/Cape_Verde" => "Cape Verde",
			"Atlantic/Faroe" => "Faroe",
			"Atlantic/Madeira" => "Madeira",
			"Atlantic/Reykjavik" => "Reykjavik",
			"Atlantic/South_Georgia" => "South Georgia",
			"Atlantic/Stanley" => "Stanley",
			"Atlantic/St_Helena" => "St Helena",
		),
		"Australia" => array(
			"Australia/Adelaide" => "Adelaide",
			"Australia/Brisbane" => "Brisbane",
			"Australia/Broken_Hill" => "Broken Hill",
			"Australia/Currie" => "Currie",
			"Australia/Darwin" => "Darwin",
			"Australia/Eucla" => "Eucla",
			"Australia/Hobart" => "Hobart",
			"Australia/Lindeman" => "Lindeman",
			"Australia/Lord_Howe" => "Lord Howe",
			"Australia/Melbourne" => "Melbourne",
			"Australia/Perth" => "Perth",
			"Australia/Sydney" => "Sydney",
		),
		"Europe" => array(
			"Europe/Amsterdam" => "Amsterdam",
			"Europe/Andorra" => "Andorra",
			"Europe/Athens" => "Athens",
			"Europe/Belgrade" => "Belgrade",
			"Europe/Berlin" => "Berlin",
			"Europe/Bratislava" => "Bratislava",
			"Europe/Brussels" => "Brussels",
			"Europe/Bucharest" => "Bucharest",
			"Europe/Budapest" => "Budapest",
			"Europe/Busingen" => "Busingen",
			"Europe/Chisinau" => "Chisinau",
			"Europe/Copenhagen" => "Copenhagen",
			"Europe/Dublin" => "Dublin",
			"Europe/Gibraltar" => "Gibraltar",
			"Europe/Guernsey" => "Guernsey",
			"Europe/Helsinki" => "Helsinki",
			"Europe/Isle_of_Man" => "Isle of Man",
			"Europe/Istanbul" => "Istanbul",
			"Europe/Jersey" => "Jersey",
			"Europe/Kaliningrad" => "Kaliningrad",
			"Europe/Kiev" => "Kiev",
			"Europe/Lisbon" => "Lisbon",
			"Europe/Ljubljana" => "Ljubljana",
			"Europe/London" => "London",
			"Europe/Luxembourg" => "Luxembourg",
			"Europe/Madrid" => "Madrid",
			"Europe/Malta" => "Malta",
			"Europe/Mariehamn" => "Mariehamn",
			"Europe/Minsk" => "Minsk",
			"Europe/Monaco" => "Monaco",
			"Europe/Moscow" => "Moscow",
			"Europe/Oslo" => "Oslo",
			"Europe/Paris" => "Paris",
			"Europe/Podgorica" => "Podgorica",
			"Europe/Prague" => "Prague",
			"Europe/Riga" => "Riga",
			"Europe/Rome" => "Rome",
			"Europe/Samara" => "Samara",
			"Europe/San_Marino" => "San Marino",
			"Europe/Sarajevo" => "Sarajevo",
			"Europe/Simferopol" => "Simferopol",
			"Europe/Skopje" => "Skopje",
			"Europe/Sofia" => "Sofia",
			"Europe/Stockholm" => "Stockholm",
			"Europe/Tallinn" => "Tallinn",
			"Europe/Tirane" => "Tirane",
			"Europe/Uzhgorod" => "Uzhgorod",
			"Europe/Vaduz" => "Vaduz",
			"Europe/Vatican" => "Vatican",
			"Europe/Vienna" => "Vienna",
			"Europe/Vilnius" => "Vilnius",
			"Europe/Volgograd" => "Volgograd",
			"Europe/Warsaw" => "Warsaw",
			"Europe/Zagreb" => "Zagreb",
			"Europe/Zaporozhye" => "Zaporozhye",
			"Europe/Zurich" => "Zurich",
		),
		"Indian" => array(
			"Indian/Antananarivo" => "Antananarivo",
			"Indian/Chagos" => "Chagos",
			"Indian/Christmas" => "Christmas",
			"Indian/Cocos" => "Cocos",
			"Indian/Comoro" => "Comoro",
			"Indian/Kerguelen" => "Kerguelen",
			"Indian/Mahe" => "Mahe",
			"Indian/Maldives" => "Maldives",
			"Indian/Mauritius" => "Mauritius",
			"Indian/Mayotte" => "Mayotte",
			"Indian/Reunion" => "Reunion",
		),
		"Pacific" => array(
			"Pacific/Apia" => "Apia",
			"Pacific/Auckland" => "Auckland",
			"Pacific/Chatham" => "Chatham",
			"Pacific/Chuuk" => "Chuuk",
			"Pacific/Easter" => "Easter",
			"Pacific/Efate" => "Efate",
			"Pacific/Enderbury" => "Enderbury",
			"Pacific/Fakaofo" => "Fakaofo",
			"Pacific/Fiji" => "Fiji",
			"Pacific/Funafuti" => "Funafuti",
			"Pacific/Galapagos" => "Galapagos",
			"Pacific/Gambier" => "Gambier",
			"Pacific/Guadalcanal" => "Guadalcanal",
			"Pacific/Guam" => "Guam",
			"Pacific/Honolulu" => "Honolulu",
			"Pacific/Johnston" => "Johnston",
			"Pacific/Kiritimati" => "Kiritimati",
			"Pacific/Kosrae" => "Kosrae",
			"Pacific/Kwajalein" => "Kwajalein",
			"Pacific/Majuro" => "Majuro",
			"Pacific/Marquesas" => "Marquesas",
			"Pacific/Midway" => "Midway",
			"Pacific/Nauru" => "Nauru",
			"Pacific/Niue" => "Niue",
			"Pacific/Norfolk" => "Norfolk",
			"Pacific/Noumea" => "Noumea",
			"Pacific/Pago_Pago" => "Pago Pago",
			"Pacific/Palau" => "Palau",
			"Pacific/Pitcairn" => "Pitcairn",
			"Pacific/Pohnpei" => "Pohnpei",
			"Pacific/Port_Moresby" => "Port Moresby",
			"Pacific/Rarotonga" => "Rarotonga",
			"Pacific/Saipan" => "Saipan",
			"Pacific/Tahiti" => "Tahiti",
			"Pacific/Tarawa" => "Tarawa",
			"Pacific/Tongatapu" => "Tongatapu",
			"Pacific/Wake" => "Wake",
			"Pacific/Wallis" => "Wallis",
		),
		"UTC" => array(
			"UTC" => "UTC",
		)
	);

	return $timezone;
}

function form_vars($query ='') {

    $qs = $_SERVER['QUERY_STRING'];
    $vars = array();

    $strings = explode('&', $qs);

	if($query == '') $query = array();

    foreach ($strings as $key => $value) {    	
    	list($k, $v) = explode('=', $value);
        $vars[$k] = $v;
        if($v == '0') unset($vars[$k]);
    }

    foreach ($query as $key => $value) {    	
    	list($k, $v) = explode('=', $value);
        $vars[$k] = $v;
    	if($v == '0') unset($vars[$k]);
    }

    foreach ($vars as $k => $v) {    	
        echo '<input type="text" name="'.$k.'" value="'.$v.'">';
    }
}

function query_vars($query ='') {

    $qs = $_SERVER['QUERY_STRING'];
    $vars = array();
	if($query == '') return $qs;

    parse_str($_SERVER['QUERY_STRING'], $qs);
    
    foreach ($qs as $key => $value) {    	
		$vars[$key] = $value;

		if($value == '0') {
			unset($vars[$key]);		
		}
    }
 
    parse_str($query, $queries);
    
    foreach ($queries as $key => $value) {    	
		$vars[$key] = $value;

		if($value == '0') {
			unset($vars[$key]);		
		}
    }

    return $vars;
}


function mail_security($val ='') {

	$data['none'] = 'NONE';
	$data['ssl']  = 'SSL';
	$data['tls']  = 'TLS';

	return ($val) ? $data[$val] : $data;
}





function timeOut($val ='') {
	$data['300']  = '5 minutes';
	$data['600']  = '10 minutes';
	$data['900']  = '15 minutes';
	$data['1200'] = '20 minutes';
	$data['1500'] = '25 minutes';
	$data['1800'] = '30 minutes';
	$data['3600'] = '60 minutes';
	$data['10']   = '10 seconds (Test mode)';

	return ($val) ? $data[$val] : $data;
}

function control_no($id=1, $mode = 'date') {

	if($mode == 'date') {
		$a = date('ym-d');
	}
	if($mode == 'rand') {
		$a = str_random(4);
	}

	return $a.'-'.sprintf('%06d', $id);
}

function dateFormat($date ='', $format ='F d, Y') {
	if($date)
	return date($format, strtotime($date));
}

function getFacilities($facilities) {
	if($facilities) {
		foreach(json_decode($facilities) as $key => $item) {
			echo '<li>'.strtoupper($item->facility).'</li>';
		}
	}
}

function sort_array_multidim(array $array, $order_by)
{
    //TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
    if(!is_array($array[0]))
        throw new Exception('$array must be a multidimensional array!',E_USER_ERROR);
    $columns = explode(',',$order_by);
    foreach ($columns as $col_dir)
    {
        if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
        {
            if(!array_key_exists(trim($matches[1]),$array[0]))
                trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
            else
            {
                if(isset($sorts[trim($matches[1])]))
                    trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));
                $sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
            }
        }
        else
        {
            throw new Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
        }
    }
    //TODO -c optimization -o tufanbarisyildirim : use array_* functions.
    $colarr = array();
    foreach ($sorts as $col => $order)
    {
        $colarr[$col] = array();
        foreach ($array as $k => $row)
        {
            $colarr[$col]['_'.$k] = strtolower($row[$col]);
        }
    }
   
    $multi_params = array();
    foreach ($sorts as $col => $order)
    {
        $multi_params[] = '$colarr[\'' . $col .'\']';
        $multi_params[] = $order;
    }
    $rum_params = implode(',',$multi_params);
    eval("array_multisort({$rum_params});");
    $sorted_array = array();
    foreach ($colarr as $col => $arr)
    {
        foreach ($arr as $k => $v)
        {
            $k = substr($k,1);
            if (!isset($sorted_array[$k]))
                $sorted_array[$k] = $array[$k];
            $sorted_array[$k][$col] = $array[$k][$col];
        }
    }
    return array_values($sorted_array);
}

function get_calendar() {

	$events = array();

	$dormers = Dorm\Dormer::get();
	$reservations = Dorm\Reservation::where('status', 'reserved')->get();

	$unPaids = $endContracts = array();
	$endCon = '';
	
	foreach ($dormers as $dormer) {

	    // Get ending contracts
	    $contract_id = $dormer->contract_id;
	    $contract = Dorm\Contract::find($contract_id);
	   

	    if(@$contract->date_end)  {
	    	 $endCon = $contract->date_end;
		    $endContracts[$endCon][] = $dormer->id;
	    }
	
	    // Get Unpaids
	    $billing_month = date('F');
	    $isPaid = Dorm\Billing::where('type_of_billing', 'rental')
	                          ->where('dormer_id', $dormer->id)
	                          ->where('details', 'LIKE', '%'.$billing_month.'%')->count();

/*	    if($isPaid == 0 && $endCon) {
	        $billing_date = date('Y-').date('m-d', strtotime($endCon));
	        $unPaids[$billing_date][] = $dormer->id;
	    }*/

		$rental = Dorm\Billing::where('dormer_id', $dormer->id)
		->where('date_charge', '<=', date('Y-m-d'))
		->where('status', 'Unpaid')
		->first();

		if($rental) {
			$unPaids[$rental->date_charge][] = $dormer->id;
		}

	    // Get Birtdays
	    $bday = strtotime($dormer->birthday);
	    $bdayNow = date('m-d', $bday);
	    $bdays[$bdayNow][] = $dormer->id;
	}

	$events = array();
	foreach ($bdays as $bday => $bd) {
	    $bdayNow = date('Y-').$bday;
	    $events[] = array(
	        'title'           => count($bd).' BD',
	        'start'           => $bdayNow,
	        'end'             => $bdayNow,
	        'backgroundColor' => '#F3565D',
	        'url'             => URL::route('backend.dormers.index', ['birthday' => $bday]),
	    );
	}

	foreach ($endContracts as $endContract => $ec) {
	    $events[] = array(
	        'title'           => count($ec).' EC',
	        'start'           => $endContract,
	        'end'             => $endContract,
	        'backgroundColor' => '#1caf9a',
	        'url'             => URL::route('backend.dormers.index', ['date-end' => $endContract]),
	    );
	}

	foreach ($unPaids as $unPaid => $up) {
	    $events[] = array(
	        'title'           => count($up).' UP',
	        'start'           => $unPaid,
	        'end'             => $unPaid,
	        'backgroundColor' => '#dfba49',
	        'url'             => URL::route('backend.dormers.index', ['date-charge' => $unPaid]),
	    );
	}

	$reserves = array();

	foreach ($reservations as $reserve) {
	    // Get reservations
	    $eda = $reserve->estimated_date_arrival;
	    $reserves[$eda][] = $reserve->dormer_id;
	}


	foreach ($reserves as $res => $rs) {
	    $events[] = array(
	        'title'           => count($rs).' RS',
	        'start'           => $res,
	        'end'             => $res,
	        'backgroundColor' => '#8775a7',
	        'url'             => URL::route('backend.reservations.index', ['date-reserve' => $res]),
	    );
	}

	return $events;
}


function get_ad_photo($path, $index = 0, $size = 'medium', $default = true) {

	if($size == 'thumbnail')
		$path = @json_decode($path)->thumbnail[$index];

	if($size == 'medium')
		$path = @json_decode($path)->medium[$index];

	if($size == 'large')
		$path = @json_decode($path)->large[$index];

	if($path) {
		if( file_exists($path) ) {
			return asset($path);		
		}
	}

	// Default
	if($default) return false;

	return asset('uploads/images/ads/pabilepo.png');
}

function ad_primary_photo($path ='') {

	$path = @json_decode($path)->medium[0];

	if($path) {
		if( file_exists($path) ) {
			return asset($path);		
		}
	}
	// Default
	return asset('uploads/images/ads/pabilepo.png');
}

function ad_photo($path ='', $size ='medium') {

	$path = @json_decode($path)->$size[0];

	if($path) {
		if( file_exists($path) ) {
			return asset($path);		
		}
	}
	// Default
	return asset('uploads/images/ads/pabilepo.png');
}

function hasPhoto($path ='') {
	if($path) {
		if( file_exists($path) ) {
			return asset($path);		
		}
	}
	// Default
	return asset('uploads/images/avatar.png');
}

function date_formatted($date ='') {	
	if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
	if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
	    return date('d-M-Y', strtotime($date));
	} else {
	    return date('Y-m-d', strtotime($date));
	}
}

function name_formatted($dormer_id, $format = 'l, f m.') {	
	$d = App\User::find($dormer_id);

	$split_format = str_split($format);
	$name ='';
	foreach ($split_format as $char) {
		
		if (preg_match('/[a-zA-Z]/', $char)) {
			$n = $char.'name';
			$name .= $d->$n;
		} else {
			$name .= $char;
		}
	}
	
	return ucwords($name);
}


function get_setting($value ='site_name') {
	return App\Setting::getInfo($value);
}

function amount_formatted($amount) {
	return currency_symbol('PHP').' '.number_format($amount, 2);
}

function currency_symbol( $currency = '' ) {
  if ( ! $currency ) {
    $currency = get_woocommerce_currency();
  }

  switch ( $currency ) {
    case 'AED' :
      $currency_symbol = 'Ø¯.Ø¥';
      break;
    case 'AUD' :
    case 'ARS' :
    case 'CAD' :
    case 'CLP' :
    case 'COP' :
    case 'HKD' :
    case 'MXN' :
    case 'NZD' :
    case 'SGD' :
    case 'USD' :
      $currency_symbol = '$';
      break;
    case 'BDT':
      $currency_symbol = 'à§³&nbsp;';
      break;
    case 'BGN' :
      $currency_symbol = 'Ð»Ð².';
      break;
    case 'BRL' :
      $currency_symbol = 'R$';
      break;
    case 'CHF' :
      $currency_symbol = 'CHF';
      break;
    case 'CNY' :
    case 'JPY' :
    case 'RMB' :
      $currency_symbol = '&yen;';
      break;
    case 'CZK' :
      $currency_symbol = 'KÄ';
      break;
    case 'DKK' :
      $currency_symbol = 'DKK';
      break;
    case 'DOP' :
      $currency_symbol = 'RD$';
      break;
    case 'EGP' :
      $currency_symbol = 'EGP';
      break;
    case 'EUR' :
      $currency_symbol = '&euro;';
      break;
    case 'GBP' :
      $currency_symbol = '&pound;';
      break;
    case 'HRK' :
      $currency_symbol = 'Kn';
      break;
    case 'HUF' :
      $currency_symbol = 'Ft';
      break;
    case 'IDR' :
      $currency_symbol = 'Rp';
      break;
    case 'ILS' :
      $currency_symbol = 'âª';
      break;
    case 'INR' :
      $currency_symbol = 'Rs.';
      break;
    case 'ISK' :
      $currency_symbol = 'Kr.';
      break;
    case 'KIP' :
      $currency_symbol = 'â­';
      break;
    case 'KRW' :
      $currency_symbol = 'â©';
      break;
    case 'MYR' :
      $currency_symbol = 'RM';
      break;
    case 'NGN' :
      $currency_symbol = 'â¦';
      break;
    case 'NOK' :
      $currency_symbol = 'kr';
      break;
    case 'NPR' :
      $currency_symbol = 'Rs.';
      break;
    case 'PHP' :
      $currency_symbol = 'â±';
      break;
    case 'PLN' :
      $currency_symbol = 'zÅ';
      break;
    case 'PYG' :
      $currency_symbol = 'â²';
  break;
    case 'RON' :
      $currency_symbol = 'lei';
      break;
    case 'RUB' :
      $currency_symbol = 'ÑÑÐ±.';
      break;
    case 'SEK' :
      $currency_symbol = 'kr';
      break;
    case 'THB' :
      $currency_symbol = 'à¸¿';
      break;
    case 'TRY' :
      $currency_symbol = 'âº';
      break;
    case 'TWD' :
      $currency_symbol = 'NT$';
      break;
    case 'UAH' :
      $currency_symbol = 'â´';
      break;
    case 'VND' :
      $currency_symbol = 'â«';
      break;
    case 'ZAR' :
      $currency_symbol = 'R';
      break;
    default :
      $currency_symbol = '';
      break;
  }

  return $currency_symbol;
}

function generate_soa($id ='') {
	return date('Y-').sprintf('%05d', $id);
}


//----------------------------------------------------------------

function xTimeAgo ($oldTime, $newTime) {
    $timeCalc = strtotime($newTime) - strtotime($oldTime);     

	if ($timeCalc > (60*60*60*24)) { $timeCalc = date('M m, Y', strtotime($oldTime)); }
	else if ($timeCalc > (60*60*24)) {
		$time = round($timeCalc/60/60/24);
		$timeCalc = $time." day".isPlural($time)." ago";
	} else if ($timeCalc > (60*60)) {
		$time = round($timeCalc/60/60);
		$timeCalc = $time." hr".isPlural($time)." ago";
	} else if ($timeCalc > 60) {
		$time = round($timeCalc/60);
		$timeCalc = $time." min".isPlural($time)." ago";
	} else if ($timeCalc >= 0) {$timeCalc = "a few seconds ago";}
	
	return $timeCalc;

}

//----------------------------------------------------------------

function txt_to_slug($str) {
	return str_replace(' ', '-', strtolower($str));
}

//----------------------------------------------------------------

function price_formatted($amount = '') {
	if($amount == '0') {
		return 'Please contact seller for pricing.';
	} 

	return currency_symbol('PHP').' '.number_format($amount, 2);
}

//----------------------------------------------------------------

function breadcrumbs() {
	$data = '';


	if (Request::is('news')) {		
		$data .= '<span class="breadcrumb_last">News</span>';
	}

	if (Request::is('account')) {		
		$data .= '<span class="breadcrumb_last">My Account</span>';
	}
	if (Request::is('edit-profile')) {		
		$data .= '<a href="'.url('account').'">My Account</a>';	
		$data .= '<span class="breadcrumb_last">Edit Profile</span>';
	}

	if (Request::is('edit-ad/*')) {		
		$data .= '<span class="breadcrumb_last">Edit Ad</span>';
	}
	if (Request::is('posting')) {		
		$data .= '<span class="breadcrumb_last">Post Ad</span>';
	}
	if (Request::is('account/register')) {		
		$data .= '<span class="breadcrumb_last">Create an Account</span>';
	}
	if (Request::is('account/signin')) {		
		$data .= '<span class="breadcrumb_last">Sign In</span>';
	}
	if (Request::is('forgot-password')) {		
		$data .= '<span class="breadcrumb_last">Forgot Password</span>';
	}


	if( Request::is('news/*') ) {
		$data .= '<a href="'.url('news').'">News</a>';		
		$data .= '<span class="breadcrumb_last">'.str_replace('-', ' ', $title).'</span>';
	}

	$current_category = $province = $search = '';
	$category_segments = array();

	$segment2 = Request::segment(2);
	$segment3 = Request::segment(3);
	$segment4 = Request::segment(4);
	$segment5 = Request::segment(5);
	$segment6 = Request::segment(6);

	$segments = array_filter([$segment2, $segment3, $segment4, $segment5, $segment6]);
	foreach ($segments as $segment) {
	    if( str_contains($segment, 'ph-') ) {
	       $province = str_replace('ph-', '', $segment);
	    } elseif ( str_contains($segment, 'sq-') ) {
	       $search = str_replace('sq-', '', $segment);
	    } else {
	       $category_segments[] = $segment;
	    }
	}

	if(request()->route()) {
		if ( request()->route()->getName() == 'frontend.ads.index') {	

			if( count($category_segments) == 0) {
				$data .= '<a href="'.URL::route('frontend.ads.index').'">All Categories</a>';			    	
			}

			foreach ($segments as $segment) {
			    if( str_contains($segment, 'ph-') ) {
					$province_s = str_replace(['Ph-', '-'], ' ', ucwords($segment));	
					$ad_url = implode('/', remove_from_array($segments, $segment));
					$data .= '<a href="'.URL::route('frontend.ads.index').'/'.$ad_url.'">'.ucwords(str_replace('-', ' ', $province_s)).'</a>';
			    } elseif ( str_contains($segment, 'sq-') ) {
					$search_s = str_replace('sq-', ' ', $segment);	
					$ad_url = implode('/', remove_from_array($segments, $segment));
					$data .= '<a href="'.URL::route('frontend.ads.index').'/'.$ad_url.'">'.ucwords(str_replace('-', ' ', urldecode($search_s))).'</a>';
			    } else {
					$ad_url = implode('/', remove_from_array($segments, $segment));
					$data .= '<a href="'.URL::route('frontend.ads.index').'/'.$ad_url.'">'.ucwords(str_replace('-', ' ', $segment)).'</a>';
			    }
			}
		}	
	}

	if (Request::is('ad/*/*')) {		
		$ad_id = Request::segment('2');	
		$title = Request::segment('3');

		$ad = App\Ad::find($ad_id);
		$cat = App\Category::find($ad->category_id);

        $s1=$s2=$s3=0;
        $cc[] = $s0 = $ad->category_id;
        $cc[] = $s1 = App\Category::find($s0)->parent;
        if($s1 !== 0)  $cc[] = $s2 = @App\Category::find($s1)->parent;
        if($s2 !== 0)  $cc[] = $s3 = @App\Category::find($s2)->parent;
        if($s3 !== 0)  $cc[] = @App\Category::find($s3)->parent;

        $cat_ids = array_diff($cc, [0]);

        $cat_rows = App\Category::whereIn('id', $cat_ids)
					            ->get();

        $cat_rows_array = App\Category::whereIn('id', $cat_ids)
							          ->lists('slug')
							          ->toArray();

        foreach ($cat_rows as $cat_row) {	 	
	 		$ad_url = implode('/', remove_from_array($cat_rows_array, $cat_row->slug));
			$data .= '<a href="'.URL::route('frontend.ads.index').'/'.$ad_url.'">'.$cat_row->name.'</a>';
        }

		$data .= '<span class="breadcrumb_last">'.ucwords($ad->title).'</span>';

	}	

	if (Request::is('user/*')) {		
		$title = Request::segment('2');
		$data .= '<a>User</a>';
		$data .= '<span class="breadcrumb_last">'.str_replace('-', ' ', $title).'</span>';
	}	

	if (Request::is('policy')) {		
		$data .= '<span class="breadcrumb_last">Policy</span>';
	}	
	if (Request::is('contact-us')) {		
		$data .= '<span class="breadcrumb_last">Contact Us</span>';
	}
	if (Request::is('about-us')) {		
		$data .= '<span class="breadcrumb_last">About Us</span>';
	}
	if (Request::is('faq')) {		
		$data .= '<span class="breadcrumb_last">FAQ</span>';
	}
	if (Request::is('terms-of-services')) {		
		$data .= '<span class="breadcrumb_last">Terms of Services</span>';
	}

	return $data;
}	

function remove_from_array_search($string) {
  return (strpos($string, 'sq-') === false && strpos($string, 'ph-') === false);
}

function get_search_value($val ='') {
	$data['current_category'] = $data['province'] =  $data['province_ph'] = $data['search'] = $data['search_sq'] = '';

	$segment2 = Request::segment(2);
	$segment3 = Request::segment(3);
	$segment4 = Request::segment(4);
	$segment5 = Request::segment(5);
	$segment6 = Request::segment(6);

	$segments = array_filter([$segment2, $segment3, $segment4, $segment5, $segment6]);
	foreach ($segments as $segment) {

	    if( strpos($segment, 'ph-') !== false ) {
	       $data['province'] = str_replace('ph-', '', $segment);
	       $data['province_ph'] = $segment;
	    }
	    if ( strpos($segment, 'sq-') !== false ) {
	       $data['search'] = str_replace('sq-', '', urldecode($segment));
	       $data['search_sq'] = $segment;
	    } 
	}

	$current_categories = array_filter($segments, 'remove_from_array_search');

	if(count($current_categories) >= 2)
	$data['current_category'] = end($current_categories);

	$data['categories'] = $current_categories;

	return $data[$val];
}

function get_search_segments($province = '') {
	$search = '';

	$segment2 = Request::segment(2);
	$segment3 = Request::segment(3);
	$segment4 = Request::segment(4);
	$segment5 = Request::segment(5);
	$segment6 = Request::segment(6);
	$province = ($province) ? 'ph-'.$province : '';
 	$search = (get_search_value('search')) ? 'sq-'.get_search_value('search') : '';

	$segments = array_filter([$segment2, $segment3, $segment4, $segment5, $segment6]);

	$current_categories = array_filter($segments, 'remove_from_array_search');
	$segments = array_unique($current_categories);
	
	$segments = array_merge($segments, [$province, $search]);

	return $segments;
}

function remove_from_array($array=array(), $remove ='') {

	foreach ($array as $arr) {
		 $data[] = $arr;
		 if($arr == $remove) break;
	}

	return $data;		
}

function maskEmail($email) {
 	$prefix = substr($email, 0, strrpos($email, '@'));
    $suffix = substr($email, strripos($email, '@'));
    $len  = floor(strlen($prefix)/2);

    return substr($prefix, 0, $len) . str_repeat('*', $len) . $suffix;
}

function mobileno_formatted($value='') {
	$in = sprintf('%011s', $value);
	return substr($in, 0, 4).'-'.substr($in, 4, 3).'-'.substr($in, 7, 4);
}

 function getRealUserIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
 }

function compress($source, $destination, $quality) {

	$info = getimagesize($source);

	if ($info['mime'] == 'image/jpeg') 
		$image = imagecreatefromjpeg($source);

	elseif ($info['mime'] == 'image/gif') 
		$image = imagecreatefromgif($source);

	elseif ($info['mime'] == 'image/png') 
		$image = imagecreatefrompng($source);

	imagejpeg($image, $destination, $quality);

	return $destination;
}

function delete_old_files($path, $hour=24) {
    if ( $handle = opendir( $path ) ) {
        while (false !== ($file = readdir($handle))) {
            $filelastmodified = filemtime($path.$file);
            if((time()-$filelastmodified) > $hour*3600 && is_file($path.$file))
            {
                 unlink($path.$file);
            }
        }
        closedir($handle);
    }	
}

function get_tds_forms() {

	$forms = array(
	    array(
	        'label' => 'Title',
	        'name' => 1,
	        'type' => 'text',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Sub Title',
	        'name' => 2,
	        'type' => 'text',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Description',
	        'name' => 3,
	        'type' => 'textarea',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Typical Properties',
	        'name' => 4,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Benefits',
	        'name' => 5,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Recommended Application',
	        'name' => 6,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Material Compatibility',
	        'name' => 7,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Shipping',
	        'name' => 8,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
		array(
		    'label' => 'Custom Field',
		    'name' => 9,
		    'type' => 'list_populate',
		    'rules' => '',
		   	'col' => '5',
		   	'populate' => true,
		    'inputs' => array(
		    	array(
		        	'id' => 9.1,
		        	'type' => 'text',
		        	'label' => 'Title',
		    	),
		    	array(
		        	'id' => 9.2,
		        	'type' => 'textarea',
		        	'label' => 'Description',
		    	),
		    )
		),
	);

	return $forms;
} 

function get_tds_v2_forms() {

	$forms = array(
	    array(
	        'label' => 'Product Information',
	        'name' => '',
	        'type' => 'title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Title',
	        'name' => 1,
	        'type' => 'text',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Sub Title',
	        'name' => 2,
	        'type' => 'text',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Description',
	        'name' => 3,
	        'type' => 'textarea',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Typical Properties',
	        'name' => 4,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Benefits',
	        'name' => 5,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Recommended Application',
	        'name' => 6,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Material Compatibility',
	        'name' => 7,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Shipping',
	        'name' => 8,
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Material Type',
	        'name' => 10,
	        'type' => 'text',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Colour',
	        'name' => 11,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	    
	    array(
	        'label' => 'Health & Safety',
	        'name' => 12,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Storage',
	        'name' => 13,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Surfaces',
	        'name' => 14,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
		array(
		    'label' => 'Custom Field',
		    'name' => 9,
		    'type' => 'list_populate',
		    'rules' => '',
		   	'col' => '5',
		   	'populate' => true,
		    'inputs' => array(
		    	array(
		        	'id' => 9.1,
		        	'type' => 'text',
		        	'label' => 'Title',
		    	),
		    	array(
		        	'id' => 9.2,
		        	'type' => 'textarea',
		        	'label' => 'Description',
		    	),
		    )
		),
	    array(
	        'label' => 'Application Information',
	        'name' => '',
	        'type' => 'title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Application',
	        'name' => 15,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Tack Free Drying time',
	        'name' => 16,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Coverage',
	        'name' => 17,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Removal',
	        'name' => 18,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'External Weather Resistance',
	        'name' => 19,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Clean Down',
	        'name' => 20,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Repair',
	        'name' => 21,
	        'type' => 'textarea',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Technical Information',
	        'name' => '',
	        'type' => 'title',
	        'rules' => ''
	    ),
		array(
		    'label' => 'Tensile Strength',
		    'name' => 22,
		    'type' => 'text',
		    'rules' => ''
		),	
		array(
		    'label' => 'Visosity @ 10rpm',
		    'name' => 23,
		    'type' => 'text',
		    'rules' => ''
		),		
		array(
		    'label' => 'Solids Content',
		    'name' => 24,
		    'type' => 'text',
		    'rules' => ''
		),	
		array(
		    'label' => 'Elongation',
		    'name' => 25,
		    'type' => 'text',
		    'rules' => ''
		),	
		array(
		    'label' => 'Flash Point',
		    'name' => 26,
		    'type' => 'text',
		    'rules' => ''
		),		
		array(
		    'label' => 'pH',
		    'name' => 27,
		    'type' => 'text',
		    'rules' => ''
		),			
		array(
		    'label' => 'Adhesion',
		    'name' => 28,
		    'type' => 'text',
		    'rules' => ''
		),	 
		array(
		    'label' => 'Density',
		    'name' => 29,
		    'type' => 'text',
		    'rules' => ''
		),	

		array(
		    'label' => 'Maximum VOC',
		    'name' => 30,
		    'type' => 'text',
		    'rules' => ''
		),	
	);

	return $forms;
} 

function get_sds_forms() {

	$forms = array(
	    array(
	        'label' => '1. Product and Company Identification',
	        'name' => 'input_1',
	        'type' => 'title',
	        'rules' => ''
	    ),
	    array(
	        'label' => '1.1 Product Identification',
	        'name' => 'input_2',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Product Identification',
	        'name' => '1',
	        'type' => 'text',
	        'rules' => 'required'
	    ),
	    array(
	        'label' => 'Product Code',
	        'name' => '3',
	        'type' => 'text',
	        'rules' => ''
	    ),	    
	    array(
	        'label' => '1.2 Relevant Identified Uses of the Substance of Mixture and Uses Advised Against',
	        'name' => 'input_5',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Use',
	        'name' => '9',
	        'type' => 'text',
	        'rules' => ''
	    ),	 
	    array(
	        'label' => '1.3. Details of the Supplier of the Safety Data Sheet',
	        'name' => 'input_7',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'Company Name',
	        'name' => '11_3',
	        'type' => 'text',
	        'rules' => ''
	    ),
/*	    array(
	        'label' => 'First Name',
	        'name' => 'input_9',
	        'type' => 'text',
	        'rules' => ''
	    ),	 
	    array(
	        'label' => 'Last Name',
	        'name' => 'input_10',
	        'type' => 'text',
	        'rules' => ''
	    ),	*/
	    array(
	        'label' => 'Company Address',
	        'name' => 'input_30',
	        'type' => 'list',
	        'rules' => '',
	        'col' => '6',
	        'inputs' => array(
	        	array(
		        	'id' => 12.1,
		        	'label' => 'Street Address',
	        	),
	        	array(
		        	'id' => 12.3,
		        	'label' => 'City',
	        	),
	        	array(
		        	'id' => 12.4,
		        	'label' => 'State / Province / Region',
	        	),
	        	array(
		        	'id' => 12.5,
		        	'label' => 'ZIP / Postal Code',
	        	),
	        	array(
		        	'id' => 12.6,
		        	'label' => 'Country',
	        	),
	        )
	    ),
	    array(
	        'label' => 'Contact Phone Number',
	        'name' => '13',
	        'type' => 'text',
	        'rules' => ''
	    ),	 
	    array(
	        'label' => 'Transportation (General Chemical Corp)',
	        'name' => '516',
	        'type' => 'text',
	        'rules' => ''
	    ),	
	    array(
	        'label' => '1.4 Emergency Telephone Number',
	        'name' => 'input_14',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Emergency Phone (Day or Night)',
	        'name' => '15',
	        'type' => 'text',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'Number (Call Collect from Outside U.S.A)',
	        'name' => '515',
	        'type' => 'text',
	        'rules' => ''
	    ),	
	    array(
	        'label' => '2. Hazard Identification',
	        'name' => 'input_18',
	        'type' => 'title',
	        'rules' => ''
	    ),	
	    array(
	        'label' => '2.1 Classification of the Substance or Mixture',
	        'name' => 'input_19',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),	
	    array(
	        'label' => 'GHS Hazard Categories',
	        'name' => 'input_20',
	        'type' => 'checkbox',
	        'rules' => '',
	        'inputs' => array(
				array('id' => '19.1', 'label' => 'Non-classified'),
				array('id' => '19.2', 'label' => 'Explosives (unstable)'),
				array('id' => '19.3', 'label' => 'Explosives (mass explosion)'),
				array('id' => '19.4', 'label' => 'Explosives (severe projection hazard)'),
				array('id' => '19.5', 'label' => 'Explosives (fire, blast hazard)'),
				array('id' => '19.6', 'label' => 'Explosives (fire/projection hazard)'),
				array('id' => '19.7', 'label' => 'Explosives (may mass explode in fire)'),
				array('id' => '19.8', 'label' => 'Flammable Gases Cat 1'),
				array('id' => '19.9', 'label' => 'Flammable Gases Cat 2'),
				array('id' => '19.10', 'label' => 'Aerosols Cat 1'),
				array('id' => '19.11', 'label' => 'Aerosols Cat 2'),
				array('id' => '19.13', 'label' => 'Flammable Liq Cat 1'),
				array('id' => '19.14', 'label' => 'Flammable Liq Cat 2'),
				array('id' => '19.15', 'label' => 'Flammable Liq Cat 3'),
				array('id' => '19.16', 'label' => 'Flammable Liq Cat 4'),
				array('id' => '19.17', 'label' => 'Flammable Solid'),
				array('id' => '19.18', 'label' => 'Aerosols'),
				array('id' => '19.19', 'label' => 'Flammable gases (chemically unstable Cat A)'),
				array('id' => '19.21', 'label' => 'Flammable gases (chemically unstable Cat B)'),
				array('id' => '19.22', 'label' => 'Self reactive substances and mixtures (Type A)'),
				array('id' => '19.23', 'label' => 'Self reactive substances and mixtures (Type B)'),
				array('id' => '19.24', 'label' => 'Self reactive substances and mixtures (Type C, D)'),
				array('id' => '19.25', 'label' => 'Pyrophoric Liq Cat 1'),
				array('id' => '19.26', 'label' => 'Self heating substances and mixtures Cat 1'),
				array('id' => '19.27', 'label' => 'Self heating substances and mixtures Cat 2'),
				array('id' => '19.28', 'label' => 'Substances and mixtures which, in contact with water, emit flammable gases Cat 1'),
				array('id' => '19.29', 'label' => 'Substances and mixtures which, in contact with water, emit flammable gases Cat 2'),
				array('id' => '19.20', 'label' => 'Oxidizing gases Cat 1'),
				array('id' => '19.31', 'label' => 'Oxidizer Cat 1'),
				array('id' => '19.32', 'label' => 'Oxidizer Cat 2'),
				array('id' => '19.34', 'label' => 'Gases under pressure'),
				array('id' => '19.33', 'label' => 'Gases under pressure'),
				array('id' => '19.35', 'label' => 'Corrosive to metals Cat 1'),
				array('id' => '19.36', 'label' => 'Acute tox, oral Cat 1/2'),
				array('id' => '19.37', 'label' => 'Acute tox, oral Cat 3'),
				array('id' => '19.39', 'label' => 'Acute tox, oral Cat 4'),
				array('id' => '19.41', 'label' => 'Acute tox, oral Cat 5'),
				array('id' => '19.42', 'label' => 'Aspiration hazard Cat 1'),
				array('id' => '19.43', 'label' => 'Aspiration hazard Cat 2'),
				array('id' => '19.44', 'label' => 'Acute tox, dermal Cat 1/2'),
				array('id' => '19.45', 'label' => 'Acute tox, dermal Cat 3'),
				array('id' => '19.46', 'label' => 'Acute tox, dermal Cat 4'),
				array('id' => '19.47', 'label' => 'Acute tox, dermal Cat 5'),
				array('id' => '19.48', 'label' => 'Skin corrosion/irritation Cat 1'),
				array('id' => '19.49', 'label' => 'Skin corrosion/irritation Cat 2'),
				array('id' => '19.51', 'label' => 'Skin corrosion/irritation Cat 3'),
				array('id' => '19.52', 'label' => 'Sensitization, skin Cat 1'),
				array('id' => '19.53', 'label' => 'Serious eye damage/eye irritation Cat 1'),
				array('id' => '19.54', 'label' => 'Serious eye damage/eye irritation Cat 2'),
				array('id' => '19.55', 'label' => 'Serious eye damage/eye irritation Cat 3'),
				array('id' => '19.56', 'label' => 'Acute tox, inh. Cat 1/2'),
				array('id' => '19.57', 'label' => 'Acute tox, inh. Cat 3'),
				array('id' => '19.58', 'label' => 'Acute tox, inh. Cat 4'),
				array('id' => '19.59', 'label' => 'Acute tox, inh. Cat 5'),
				array('id' => '19.61', 'label' => 'Sensitization, resp. Cat 1'),
				array('id' => '19.62', 'label' => 'Specific target organ tox, single exp. Cat 3 (resp irrit)'),
				array('id' => '19.63', 'label' => 'Specific target organ tox, single exp. Cat 3 (narcotic effects)'),
				array('id' => '19.64', 'label' => 'Germ cell mutagenicity Cat 1'),
				array('id' => '19.65', 'label' => 'Germ cell mutagenicity Cat 2'),
				array('id' => '19.66', 'label' => 'Carcinogenicity Cat 1'),
				array('id' => '19.67', 'label' => 'Carcinogenicity Cat 2'),
				array('id' => '19.68', 'label' => 'Reproductive tox Cat 1'),
				array('id' => '19.69', 'label' => 'Reproductive tox Cat 2'),
				array('id' => '19.72', 'label' => 'Reproductive tox (breastfeeding)'),
				array('id' => '19.73', 'label' => 'Specific target organ tox, single exp. Cat 1'),
				array('id' => '19.74', 'label' => 'Specific target organ tox, single exp. Cat 2'),
				array('id' => '19.75', 'label' => 'Specific target organ tox, repeat exp. Cat 1'),
				array('id' => '19.76', 'label' => 'Specific target organ tox, repeat exp. Cat 2'),
				array('id' => '19.77', 'label' => 'Hazard to aquatic environment, acute Cat 1'),
				array('id' => '19.78', 'label' => 'Hazard to aquatic environment, acute Cat 2'),
				array('id' => '19.79', 'label' => 'Hazard to aquatic environment, acute Cat 3'),
				array('id' => '19.81', 'label' => 'Hazard to aquatic environment, chronic Cat 1'),
				array('id' => '19.82', 'label' => 'Hazard to aquatic environment, chronic Cat 2'),
				array('id' => '19.83', 'label' => 'Hazard to aquatic environment, chronic Cat 3'),
				array('id' => '19.84', 'label' => 'Hazard to aquatic environment, chronic Cat 4'),
				array('id' => '19.85', 'label' => 'Hazardous to ozone layer, Cat 1'),
	        )
	    ),	
	    array(
	        'label' => '2.2 GHS Label Elements',
	        'name' => 'input_21',
	        'type' => 'title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'GHS Signal Word',
	        'name' => '21',
	        'type' => 'radio',
	        'rules' => '',
	        'inputs' => array(
				array('id' => '22.0', 'label' => 'Not Applicable'),
				array('id' => '22.1', 'label' => 'Danger'),
				array('id' => '22.2', 'label' => 'Warning'),
	        )
	    ),	
	    array(
	        'label' => 'GHS Pictogram',
	        'name' => 'input_23',
	        'type' => 'checkbox',
	        'rules' => '',
	        'col' => 4,
	        'inputs' => array(
				array('id' => '22.1', 'label' => 'Flame Over Circle', 'img' => 'uploads/images/ghs/Flame-Over-Circle.png'),
				array('id' => '22.2', 'label' => 'Flame', 'img' => 'uploads/images/ghs/Flame.png'),
				array('id' => '22.3', 'label' => 'Exploding Bomb', 'img' => 'uploads/images/ghs/Exploding-Bomb.png'),
				array('id' => '22.4', 'label' => 'Skull and Crossbones', 'img' => 'uploads/images/ghs/Skull-and-Crossbones.png'),
				array('id' => '22.5', 'label' => 'Corrosion', 'img' => 'uploads/images/ghs/Corrosion.png'),
				array('id' => '22.6', 'label' => 'Gas Cylinder', 'img' => 'uploads/images/ghs/Gas-Cylinder.png'),
				array('id' => '22.7', 'label' => 'Health Hazard', 'img' => 'uploads/images/ghs/Health-Hazard.png'),
				array('id' => '22.8', 'label' => 'Environment', 'img' => 'uploads/images/ghs/Environment.png'),
				array('id' => '22.9', 'label' => 'Exclamation Mark', 'img' => 'uploads/images/ghs/exclamation-mark.png')
	        )
	    ),	
		array(
		    'label' => 'GHS Hazard Statements',
		    'name' => 'input_426',
		    'type' => 'checkbox',
		    'rules' => '',
		    'inputs' => array(
				array('id' => '426', 'label' => 'Not Applicable'),
				array('id' => '299.1', 'label' => 'H200: Unstable Explosive'),
				array('id' => '297.1', 'label' => 'H201: Explosive; Mass Explosion hazard'),
				array('id' => '303.1', 'label' => 'H202: Explosive; severe projection hazard'),
				array('id' => '304.1', 'label' => 'H203: Explosive; fire, blast or projection hazard'),
				array('id' => '305.1', 'label' => 'H204: Fire or projection hazard'),
				array('id' => '306.1', 'label' => 'H205: May mass explode in fire'),
				array('id' => '307.1', 'label' => 'H220: Extremely flammable gas'),
				array('id' => '308.1', 'label' => 'H221: Flammable gas'),
				array('id' => '309.1', 'label' => 'H222: Extremely flammable aerosol'),
				array('id' => '310.1', 'label' => 'H223: Flammable aerosol'),
				array('id' => '311.1', 'label' => 'H224: Extremely flammable liquid and vapour'),
				array('id' => '312.1', 'label' => 'H225: Highly flammable liquid and vapour'),
				array('id' => '313.1', 'label' => 'H226: Flammable liquid and vapour'),
				array('id' => '314.1', 'label' => 'H227: Combustible liquid'),
				array('id' => '315.1', 'label' => 'H228: Flammable solid'),
				array('id' => '316.1', 'label' => 'H229: Pressurized container: may burst if heated'),
				array('id' => '317.1', 'label' => 'H230: May react explosively even in the absence of air'),
				array('id' => '318.1', 'label' => 'H231: May react explosively even in the absence of air at elevated pressure and/or temperature'),
				array('id' => '319.1', 'label' => 'H240: Heating may cause an explosion'),
				array('id' => '320.1', 'label' => 'H241: Heating may cause a fire or explosion'),
				array('id' => '321.1', 'label' => 'H242: Heating may cause a fire'),
				array('id' => '322.1', 'label' => 'H250: Catches fire spontaneously if exposed to air'),
				array('id' => '323.1', 'label' => 'H251: Self-heating; may catch fire'),
				array('id' => '324.1', 'label' => 'H252: Self-heating in large quantities; may catch fire'),
				array('id' => '325.1', 'label' => 'H260: In contact with water releases flammable gases which may ignite spontaneously'),
				array('id' => '326.1', 'label' => 'H261: In contact with water releases flammable gas'),
				array('id' => '327.1', 'label' => 'H270: May cause or intensify fire; oxidizer'),
				array('id' => '328.1', 'label' => 'H271: May cause fire or explosion; strong oxidizer'),
				array('id' => '329.1', 'label' => 'H272: May intensify fire; oxidizer'),
				array('id' => '330.1', 'label' => 'H280: Contains gas under pressure; may explode if heated'),
				array('id' => '331.1', 'label' => 'H281: Contains refrigerated gas; may cause cryogenic burns or injury'),
				array('id' => '332.1', 'label' => 'H290: May be corrosive to metals'),
				array('id' => '333.1', 'label' => 'H300: Fatal if swallowed'),
				array('id' => '334.1', 'label' => 'H301: Toxic if swallowed'),
				array('id' => '335.1', 'label' => 'H302: Harmful if swallowed'),
				array('id' => '336.1', 'label' => 'H303: May be harmful if swallowed'),
				array('id' => '337.1', 'label' => 'H304: May be fatal if swallowed and enters airways'),
				array('id' => '338.1', 'label' => 'H305: May be harmful if swallowed and enters airways'),
				array('id' => '339.1', 'label' => 'H310: Fatal in contact with skin'),
				array('id' => '340.1', 'label' => 'H311: Toxic in contact with skin'),
				array('id' => '341.1', 'label' => 'H312: Harmful in contact with skin'),
				array('id' => '342.1', 'label' => 'H313: May be harmful in contact with skin'),
				array('id' => '343.1', 'label' => 'H314: Causes severe skin burns and eye damage'),
				array('id' => '344.1', 'label' => 'H315: Causes skin irritation'),
				array('id' => '345.1', 'label' => 'H316: Causes mild skin irritation'),
				array('id' => '346.1', 'label' => 'H317: May cause an allergic skin reaction'),
				array('id' => '347.1', 'label' => 'H318: Causes serious eye damage'),
				array('id' => '348.1', 'label' => 'H319: Causes serious eye irritation'),
				array('id' => '349.1', 'label' => 'H320: Causes eye irritation'),
				array('id' => '350.1', 'label' => 'H330: Fatal if inhaled'),
				array('id' => '351.1', 'label' => 'H331: Toxic if inhaled'),
				array('id' => '352.1', 'label' => 'H332: Harmful if inhaled'),
				array('id' => '353.1', 'label' => 'H333: May be harmful if inhaled'),
				array('id' => '354.1', 'label' => 'H334: May cause allergy or asthma symptoms or breathing difficulties if inhaled'),
				array('id' => '355.1', 'label' => 'H335: May cause respiratory irritation'),
				array('id' => '356.1', 'label' => 'H336: May cause drowsiness or dizziness'),
				array('id' => '357.1', 'label' => 'H340: May cause genetic defects'),
				array('id' => '358.1', 'label' => 'H341: Suspected of causing genetic defects'),
				array('id' => '359.1', 'label' => 'H350: May cause cancer'),
				array('id' => '360.1', 'label' => 'H351: Suspected of causing cancer'),
				array('id' => '361.1', 'label' => 'H360: May damage fertility or the unborn child'),
				array('id' => '362.1', 'label' => 'H361: Suspected of damaging fertility or the unborn child'),
				array('id' => '363.1', 'label' => 'H361d: Suspected of damaging the unborn child'),
				array('id' => '364.1', 'label' => 'H362: May cause harm to breast-fed children'),
				array('id' => '365.1', 'label' => 'H370: Causes damage to organs'),
				array('id' => '366.1', 'label' => 'H371: May cause damage to organs'),
				array('id' => '367.1', 'label' => 'H372: Causes damage to organs through prolonged or repeated exposure'),
				array('id' => '368.1', 'label' => 'H373: May cause damage to organs through prolonged or repeated exposure'),
				array('id' => '369.1', 'label' => 'H400: Very toxic to aquatic life'),
				array('id' => '370.1', 'label' => 'H401: Toxic to aquatic life'),
				array('id' => '371.1', 'label' => 'H402: Harmful to aquatic life'),
				array('id' => '372.1', 'label' => 'H410: Very toxic to aquatic life with long lasting effects'),
				array('id' => '373.1', 'label' => 'H411: Toxic to aquatic life with long lasting effects'),
				array('id' => '374.1', 'label' => 'H412: Harmful to aquatic life with long lasting effects'),
				array('id' => '375.1', 'label' => 'H413: May cause long lasting harmful effects to aquatic life'),
				array('id' => '376.1', 'label' => 'H420: Harms public health and the environment by destroying ozone in the upper atmosphere'),
			)
		),
	    array(
	        'label' => 'GHS Precautionary Statements',
	        'name' => 'input_24',
	        'type' => 'checkbox',
	        'rules' => '',
	        'inputs' => array(
				array('id' => '24.1', 'label' => 'Not Applicable'),
				array('id' => '24.2', 'label' => 'P101: If medical advice is needed, have product container or label at hand'),
				array('id' => '24.3', 'label' => 'P102: Keep out of reach of children'),
				array('id' => '24.4', 'label' => 'P103: Read label before use'),
				array('id' => '24.5', 'label' => 'P201: Obtain special instructions before use'),
				array('id' => '24.6', 'label' => 'P202: Do not handle until all safety precautions have been read and understood'),
				array('id' => '24.7', 'label' => 'P210: Keep away from heat/sparks/open flames/hot surfaces â No smoking'),
				array('id' => '24.8', 'label' => 'P211: Do not spray on an open flame or other ignition source'),
				array('id' => '24.9', 'label' => 'P220: Keep/Store away from clothing/â¦/combustible materials'),
				array('id' => '24.11', 'label' => 'P221: Take any precaution to avoid mixing with combustibles'),
				array('id' => '24.12', 'label' => 'P222: Do not allow contact with air'),
				array('id' => '24.13', 'label' => 'P223: Keep away from any possible contact with water, because of violent reaction and possible flash fire'),
				array('id' => '24.14', 'label' => 'P230: Keep wetted with â¦'),
				array('id' => '24.15', 'label' => 'P231: Handle under inert gas'),
				array('id' => '24.16', 'label' => 'P232: Protect from moisture'),
				array('id' => '24.17', 'label' => 'P233: Keep container tightly closed'),
				array('id' => '24.18', 'label' => 'P234: Keep only in original container'),
				array('id' => '24.19', 'label' => 'P235: Keep cool'),
				array('id' => '24.21', 'label' => 'P240: Ground/bond container and receiving equipment'),
				array('id' => '24.22', 'label' => 'P241: Use explosion-proof electrical/ventilating/light/â¦/equipment'),
				array('id' => '24.23', 'label' => 'P242: Use only non-sparking tools'),
				array('id' => '24.24', 'label' => 'P243: Take precautionary measures against static discharge'),
				array('id' => '24.25', 'label' => 'P244: Keep reduction valves free from grease and oil'),
				array('id' => '24.26', 'label' => 'P250: Do not subject to grinding/shock/â¦/friction'),
				array('id' => '24.27', 'label' => 'P251: Pressurized container â Do not pierce or burn, even after use'),
				array('id' => '24.28', 'label' => 'P260: Do not breathe dust/fume/gas/mist/vapours/spray'),
				array('id' => '24.29', 'label' => 'P261: Avoid breathing dust/fume/gas/mist/vapours/spray'),
				array('id' => '24.31', 'label' => 'P262: Do not get in eyes, on skin, or on clothing'),
				array('id' => '24.30', 'label' => 'P263: Avoid contact during pregnancy/while nursing'),
				array('id' => '24.33', 'label' => 'P264: Wash thoroughly after handling'),
				array('id' => '24.34', 'label' => 'P270: Do not eat, drink or smoke when using this product'),
				array('id' => '24.35', 'label' => 'P271: Use only outdoors or in a well-ventilated area'),
				array('id' => '24.36', 'label' => 'P272: Contaminated work clothing should not be allowed out of the workplace'),
				array('id' => '24.37', 'label' => 'P273: Avoid release to the environment'),
				array('id' => '24.38', 'label' => 'P280: Wear protective gloves/protective clothing/eye protection/face protection'),
				array('id' => '24.39', 'label' => 'P281: Use personal protective equipment as required'),
				array('id' => '24.41', 'label' => 'P282: Wear cold insulating gloves/face shield/eye protection'),
				array('id' => '24.42', 'label' => 'P283: Wear fire/flame resistant/retardant clothing'),
				array('id' => '24.43', 'label' => 'P284: Wear respiratory protection'),
				array('id' => '24.44', 'label' => 'P285: In case of inadequate ventilation wear respiratory protection'),
				array('id' => '24.45', 'label' => 'P231+232: Handle under inert gas. Protect from moisture'),
				array('id' => '24.46', 'label' => 'P235+410: Keep cool. Protect from sunlight'),
				array('id' => '24.47', 'label' => 'P301: IF SWALLOWED:'),
				array('id' => '24.48', 'label' => 'P302: IF ON SKIN:'),
				array('id' => '24.49', 'label' => 'P303: IF ON SKIN (or hair):'),
				array('id' => '24.51', 'label' => 'P304: IF INHALED:'),
				array('id' => '24.52', 'label' => 'P305: IF IN EYES:'),
				array('id' => '24.53', 'label' => 'P306: IF ON CLOTHING:'),
				array('id' => '24.54', 'label' => 'P307: IF exposed:'),
				array('id' => '24.55', 'label' => 'P308: IF exposed or concerned:'),
				array('id' => '24.56', 'label' => 'P309: IF exposed or you feel unwell:'),
				array('id' => '24.57', 'label' => 'P310: Immediately call a POISON CENTER or doctor/physician'),
				array('id' => '24.58', 'label' => 'P311: Call a POISON CENTER or doctor/physician'),
				array('id' => '24.59', 'label' => 'P312: Call a POISON CENTER or doctor/physician if you feel unwell'),
				array('id' => '24.61', 'label' => 'P313: Get medical advice/attention'),
				array('id' => '24.62', 'label' => 'P314: Get Medical advice/attention if you feel unwell'),
				array('id' => '24.63', 'label' => 'P315: Get immediate medical advice/attention'),
				array('id' => '24.64', 'label' => 'P320: Specific treatment is urgent (see â¦ on this label)'),
				array('id' => '24.65', 'label' => 'P321: Specific treatment (see â¦ on this label)'),
				array('id' => '24.66', 'label' => 'P322: Specific measures (see â¦ on this label)'),
				array('id' => '24.67', 'label' => 'P330: Rinse mouth'),
				array('id' => '24.68', 'label' => 'P331: Do NOT induce vomiting'),
				array('id' => '24.69', 'label' => 'P332: If skin irritation occurs:'),
				array('id' => '24.70', 'label' => 'P333: If skin irritation or a rash occurs:'),
				array('id' => '24.71', 'label' => 'P334: Immerse in cool water/wrap in wet bandages'),
				array('id' => '24.72', 'label' => 'P335: Brush off loose particles from skin'),
				array('id' => '24.73', 'label' => 'P336: Thaw frosted parts with lukewarm water. Do not rub affected areas'),
				array('id' => '24.74', 'label' => 'P337: If eye irritation persists:'),
				array('id' => '24.75', 'label' => 'P338: Remove contact lenses if present and easy to do. continue rinsing'),
				array('id' => '24.77', 'label' => 'P340: Remove victim to fresh air and keep at rest in a position comfortable for breathing'),
				array('id' => '24.78', 'label' => 'P341: If breathing is difficult, remove victim to fresh air and keep at rest in a position comfortable for breathing'),
				array('id' => '24.79', 'label' => 'P342: If experiencing respiratory symptoms:'),
				array('id' => '24.80', 'label' => 'P350: Gently wash with plenty of soap and water'),
				array('id' => '24.81', 'label' => 'P351: Rinse cautiously with water for several minutes'),
				array('id' => '24.83', 'label' => 'P352: Wash with soap and water'),
				array('id' => '24.84', 'label' => 'P353: Rinse skin with water/shower'),
				array('id' => '24.85', 'label' => 'P360: Rinse immediately contaminated clothing and skin with plenty of water before removing clothes'),
				array('id' => '24.86', 'label' => 'P361: Remove/Take off immediately all contaminated clothing'),
				array('id' => '24.87', 'label' => 'P362: Take off contaminated clothing and wash before reuse'),
				array('id' => '24.88', 'label' => 'P363: Wash contaminated clothing before reuse'),
				array('id' => '24.89', 'label' => 'P370: In case of fire:'),
				array('id' => '24.90', 'label' => 'P371: In case of major fire and large quantities:'),
				array('id' => '24.91', 'label' => 'P372: Explosion risk in case of fire'),
				array('id' => '24.92', 'label' => 'P373: DO NOT fight fire when fire reaches explosives'),
				array('id' => '24.94', 'label' => 'P374: Fight fire with normal precautions from a reasonable distance'),
				array('id' => '24.95', 'label' => 'P375: Fight fire remotely due to the risk of explosion'),
				array('id' => '24.96', 'label' => 'P376: Stop leak if safe to do so'),
				array('id' => '24.97', 'label' => 'P377: Leaking gas fire â do not extinguish unless leak can be stopped safely'),
				array('id' => '24.98', 'label' => 'P378: Use foam or dry chemical for extinction'),
				array('id' => '24.99', 'label' => 'P380: Evacuate area'),
				array('id' => '24.101', 'label' => 'P381: Eliminate all ignition sources if safe to do so'),
				array('id' => '24.102', 'label' => 'P391: Collect spillage'),
				array('id' => '24.103', 'label' => 'P301+310: IF SWALLOWED: Immediately call a POISON CENTER or doctor/physician'),
				array('id' => '24.104', 'label' => 'P301+312: IF SWALLOWED: Call a POISON CENTER or doctor/physician if you feel unwell'),
				array('id' => '24.105', 'label' => 'P301+330+331: IF SWALLOWED: Rinse mouth. Do NOT induce vomiting'),
				array('id' => '24.106', 'label' => 'P302+334: IF ON SKIN: Immerse in cool water/wrap in wet bandages'),
				array('id' => '24.107', 'label' => 'P302+350: IF ON SKIN: Gently wash with plenty of soap and water'),
				array('id' => '24.108', 'label' => 'P302+352: IF ON SKIN: Wash with soap and water'),
				array('id' => '24.109', 'label' => 'P303+361+353: IF ON SKIN (or hair): Remove/Take off immediately all contaminated clothing. Rinse skin with water/shower'),
				array('id' => '24.111', 'label' => 'P304+312: IF INHALED: Call a POISON CENTER or doctor/physician if you feel unwell'),
				array('id' => '24.112', 'label' => 'P304+340: IF INHALED: Remove person to fresh air and keep at rest in a position comfortable for breathing'),
				array('id' => '24.113', 'label' => 'P304+341: IF INHALED: If breathing is difficult, remove victim to fresh air and keep at rest in a position comfortable for breathing'),
				array('id' => '24.114', 'label' => 'P305+351+338: IF IN EYES: Rinse cautiously with water for several minutes. Remove contact lenses if present and easy to do â continue rinsing'),
				array('id' => '24.115', 'label' => 'P306+360: IF ON CLOTHING: Rinse immediately contaminated clothing and skin with plenty of water before removing clothes'),
				array('id' => '24.116', 'label' => 'P307+311: IF exposed: Call a POISON CENTER or doctor/physician'),
				array('id' => '24.117', 'label' => 'P308+313: IF exposed or concerned: Get medical advice/attention'),
				array('id' => '24.118', 'label' => 'P309+311: IF exposed or you feel unwell: Call a POISON CENTER or doctor/physician'),
				array('id' => '24.119', 'label' => 'P332+313: If skin irritation occurs: Get medical advice/attention'),
				array('id' => '24.121', 'label' => 'P333+313: If skin irritation or a rash occurs: Get medical advice/attention'),
				array('id' => '24.122', 'label' => 'P335+334: Brush off loose particles from skin. Immerse in cool water/wrap in wet bandages'),
				array('id' => '24.123', 'label' => 'P337+313: If eye irritation persists get medical advice/attention'),
				array('id' => '24.124', 'label' => 'P342+311: If experiencing respiratory symptoms: Call a POISON CENTER or doctor/physician'),
				array('id' => '24.125', 'label' => 'P370+376: In case of fire: Stop leak if safe to do so'),
				array('id' => '24.126', 'label' => 'P370+378: In case of fire: Use foam or dry chemical for extinction'),
				array('id' => '24.127', 'label' => 'P370+380: In case of fire: Evacuate area'),
				array('id' => '24.128', 'label' => 'P370+380+375: In case of fire: Evacuate area. Fight fire remotely due to the risk of explosion'),
				array('id' => '24.129', 'label' => 'P371+380+375: In case of major fire and large quantities: Evacuate area. Fight fire remotely due to the risk of explosion'),
				array('id' => '24.131', 'label' => 'P401: Store â¦ (to manually edit)'),
				array('id' => '24.132', 'label' => 'P402: Store in a dry place'),
				array('id' => '24.133', 'label' => 'P403: Store in a well ventilated place'),
				array('id' => '24.134', 'label' => 'P404: Store in a closed container'),
				array('id' => '24.135', 'label' => 'P405: Store locked up'),
				array('id' => '24.136', 'label' => 'P406: Store in a corrosive resistant/â¦ container with a resistant inner liner'),
				array('id' => '24.137', 'label' => 'P407: Maintain air gap between stacks/pallets'),
				array('id' => '24.138', 'label' => 'P410: Protect from sunlight'),
				array('id' => '24.139', 'label' => 'P411: Store at temperatures not exceeding â¦ Â°C/â¦ Â°F (to manually edit)'),
				array('id' => '24.140', 'label' => 'P412: Do not expose to temperatures exceeding 50 Â°C/122 Â°F'),
				array('id' => '24.141', 'label' => 'P413: Store bulk masses greater than â¦ kg/â¦ lbs at temperatures not exceeding â¦ Â°C/â¦ Â°F (to manually edit)'),
				array('id' => '24.143', 'label' => 'P420: Store away from other materials'),
				array('id' => '24.144', 'label' => 'P422: Store contents under â¦ (to manually edit)'),
				array('id' => '24.145', 'label' => 'P402+404: Store in a dry place. Store in a closed container'),
				array('id' => '24.146', 'label' => 'P403+233: Store in a well ventilated place. Keep container tightly closed'),
				array('id' => '24.147', 'label' => 'P403+235: Store in a well ventilated place. Keep cool'),
				array('id' => '24.148', 'label' => 'P410+403: Protect from sunlight. Store in a well ventilated place'),
				array('id' => '24.149', 'label' => 'P410+412: Protect from sunlight. Do not expose to temperatures exceeding 50 Â°C/122 Â°F'),
				array('id' => '24.151', 'label' => 'P411+235: Store at temperatures not exceeding â¦ Â°C/â¦ Â°F. Keep cool (to manually edit)'),
				array('id' => '24.152', 'label' => 'P501: Dispose of contents/container in accordance with local/regional/national regulations'),
	        )
	    ),	
	    array(
	        'label' => '2.3 Other Hazards/Hazards Not Otherwise Classified',
	        'name' => 'title_26',
	        'type' => 'sub-title',
	        'rules' => ''
	    ),
	    array(
	        'label' => '',
	        'name' => '26',
	        'type' => 'text',
	        'rules' => ''
	    ),
	    array(
	        'label' => '',
	        'name' => '552',
	        'type' => 'textarea',
	        'rules' => ''
	    ),
	    array(
	        'label' => '3. Composition / Information on Ingredients',
	        'name' => 'input_28',
	        'type' => 'title',
	        'rules' => ''
	    ),
	    array(
	        'label' => 'How many hazardous chemicals will this contain?',
	        'name' => '38',
	        'type' => 'text',
	        'help' => 'Please enter a value between 0 and 15.',
	        'rules' => '',
	        'input' => 'number',
	    ),
	    array(
	        'label' => 'List',
	        'name' => '543',
	        'type' => 'list_populate',
	        'rules' => '',
	       	'col' => '2',
	       	'populate' => true,
	        'inputs' => array(
	        	array(
		        	'id' => 543.1,
		        	'label' => 'Chemical Name(s)',
	        	),
	        	array(
		        	'id' => 543.2,
		        	'label' => 'CAS Number',
	        	),
	        	array(
		        	'id' => 543.3,
		        	'label' => '% Weight',
	        	),
	        	array(
		        	'id' => 543.4,
		        	'label' => '',
	        	),
	        	array(
		        	'id' => 543.4,
		        	'label' => '',
	        	),
	        )
	    ),
	    array(
	        'label' => '1. Chemical Name',
	        'name' => '28',
	        'type' => 'text',
	        'rules' => '',
	        'col' => '2',
	    ),
	    array(
	        'label' => '1. CAS Number',
	        'name' => '33',
	        'type' => 'text',
	        'rules' => '',
	        'col' => '2',
	    ),
	    array(
	        'label' => '1. % Weight',
	        'name' => '31',
	        'type' => 'text',
	        'rules' => '',
	        'col' => '2',
	    ),
	    array(
	        'label' => 'How many GHS Hazard Categories for chemical 1?',
	        'name' => '378',
	        'type' => 'text',
	        'help' => 'Please enter a value between 0 and 5.',
	        'rules' => '',
	        'input' => 'number',
	    ),
	    array(
	        'label' => 'GHS Hazard Category (Chemical 1)',
	        'name' => '32',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_378',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '378',
				'operator' => '=',
				'value' => '1',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 1)',
	        'name' => '398',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_378',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '378',
				'operator' => '=',
				'value' => '2',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 1)',
	        'name' => '399',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_378',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '378',
				'operator' => '=',
				'value' => '3',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 1)',
	        'name' => '400',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_378',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '378',
				'operator' => '=',
				'value' => '4',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 1)',
	        'name' => '402',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_378',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '378',
				'operator' => '=',
				'value' => '5',
			)
	    ),	    
	    array(
	        'label' => '2. Chemical Name',
	        'name' => '34',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '2',
			)	 
	    ),
	    array(
	        'label' => '2. CAS Number',
	        'name' => '35',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '2',
			)	 
	    ),
	    array(
	        'label' => '2. % Weight',
	        'name' => '36',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '2',
			)	 
	    ),
	    array(
	        'label' => 'How many GHS Hazard Categories for chemical 2?',
	        'name' => '383',
	        'type' => 'text',
	        'help' => 'Please enter a value between 0 and 5.',
	        'rules' => '',
	        'input' => 'number',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '2',
			)	 
	    ),
	    array(
	        'label' => 'GHS Hazard Category (Chemical 2)',
	        'name' => '404',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
			'style' => 'display:none;',      
			'condition' => array(
				'field_id' => '383',
				'operator' => '=',
				'value' => '1',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 2)',
	        'name' => '405',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_383',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '383',
				'operator' => '=',
				'value' => '2',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 2)',
	        'name' => '407',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_383',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '383',
				'operator' => '=',
				'value' => '3',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 2)',
	        'name' => '408',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_383',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '383',
				'operator' => '=',
				'value' => '4',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 2)',
	        'name' => '406',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_383',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '383',
				'operator' => '=',
				'value' => '5',
			)
	    ),	
	    array(
	        'label' => '3. Chemical Name',
	        'name' => '68',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '3',
			)	 
	    ),
	    array(
	        'label' => '3. CAS Number',
	        'name' => '169',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '3',
			)
	    ),
	    array(
	        'label' => '3. % Weight',
	        'name' => '170',
	        'type' => 'text',
	        'rules' => '',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '3',
			)
	    ),
	    array(
	        'label' => 'How many GHS Hazard Categories for chemical 3?',
	        'name' => '384',
	        'type' => 'text',
	        'help' => 'Please enter a value between 0 and 5.',
	        'rules' => '',
	        'input' => 'number',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '3',
			)
	    ),
	    array(
	        'label' => 'GHS Hazard Category (Chemical 3)',
	        'name' => '409',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_348',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '384',
				'operator' => '=',
				'value' => '1',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 3)',
	        'name' => '410',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_348',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '384',
				'operator' => '=',
				'value' => '2',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 3)',
	        'name' => '411',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_348',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '384',
				'operator' => '=',
				'value' => '3',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 3)',
	        'name' => '428',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_348',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '384',
				'operator' => '=',
				'value' => '4',
			)
	    ),	    
	    array(
	        'label' => 'GHS Hazard Category (Chemical 3)',
	        'name' => '429',
	        'type' => 'select',
	        'rules' => '',
	        'inputs' => hazard_category_chemical(),
	        'class' => 'input_348',
	        'style' => 'display:none;',
			'condition' => array(
				'field_id' => '384',
				'operator' => '=',
				'value' => '5',
			)
	    ),	
		array(
		    'label' => '4. Chemical Name',
		    'name' => '168',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '4',
			)	 
		),
		array(
		    'label' => '4. CAS Number',
		    'name' => '71',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '4',
			)
		),
		array(
		    'label' => '4. % Weight',
		    'name' => '70',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '4',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for chemical 4?',
		    'name' => '385',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '4',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 4)',
		    'name' => '430',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '385',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 4)',
		    'name' => '431',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '385',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 4)',
		    'name' => '432',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '385',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 4)',
		    'name' => '433',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '385',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 4)',
		    'name' => '434',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '385',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '5. Chemical Name',
		    'name' => '173',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '5',
			)	 
		),
		array(
		    'label' => '5. CAS Number',
		    'name' => '174',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '5. % Weight',
		    'name' => '175',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 5?',
		    'name' => '386',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 5)',
		    'name' => '435',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '386',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 5)',
		    'name' => '436',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '386',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 5)',
		    'name' => '437',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '386',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 5)',
		    'name' => '4338',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '386',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 5)',
		    'name' => '439',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '386',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '6. Chemical Name',
		    'name' => '178',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '6',
			)	 
		),
		array(
		    'label' => '6. CAS Number',
		    'name' => '179',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '6',
			)
		),
		array(
		    'label' => '6. % Weight',
		    'name' => '180',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '6',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 6?',
		    'name' => '387',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '6',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 6)',
		    'name' => '440',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '387',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 6)',
		    'name' => '441',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '387',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 6)',
		    'name' => '442',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '387',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 6)',
		    'name' => '443',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '387',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 6)',
		    'name' => '444',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '387',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '7. Chemical Name',
		    'name' => '183',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '7',
			)	 
		),
		array(
		    'label' => '7. CAS Number',
		    'name' => '184',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '7',
			)
		),
		array(
		    'label' => '7. % Weight',
		    'name' => '185',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '7',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 7?',
		    'name' => '388',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '7',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 7)',
		    'name' => '445',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '388',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 7)',
		    'name' => '446',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '388',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 7)',
		    'name' => '447',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '388',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 7)',
		    'name' => '448',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '388',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 7)',
		    'name' => '449',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '388',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '8. Chemical Name',
		    'name' => '188',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '8',
			)	 
		),
		array(
		    'label' => '8. CAS Number',
		    'name' => '189',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '8',
			)
		),
		array(
		    'label' => '8. % Weight',
		    'name' => '190',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '8',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 8?',
		    'name' => '389',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '8',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 8)',
		    'name' => '450',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '389',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 8)',
		    'name' => '451',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '389',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 8)',
		    'name' => '452',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '389',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 8)',
		    'name' => '453',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '389',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 8)',
		    'name' => '454',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '389',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '9. Chemical Name',
		    'name' => '193',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '9',
			)	 
		),
		array(
		    'label' => '9. CAS Number',
		    'name' => '199',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '9',
			)
		),
		array(
		    'label' => '9. % Weight',
		    'name' => '195',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '9',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 9?',
		    'name' => '390',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '9',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 9)',
		    'name' => '455',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '390',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 9)',
		    'name' => '456',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '390',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 9)',
		    'name' => '457',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '390',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 9)',
		    'name' => '458',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '390',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 9)',
		    'name' => '459',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '390',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '10. Chemical Name',
		    'name' => '198',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '10',
			)	 
		),
		array(
		    'label' => '10. CAS Number',
		    'name' => '194',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '10',
			)
		),
		array(
		    'label' => '10. % Weight',
		    'name' => '205',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '10',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 10?',
		    'name' => '391',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '10',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 10)',
		    'name' => '460',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '391',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 10)',
		    'name' => '461',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '391',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 10)',
		    'name' => '462',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '391',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 10)',
		    'name' => '463',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '391',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 10)',
		    'name' => '464',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '391',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '11. Chemical Name',
		    'name' => '203',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '11',
			)	 
		),
		array(
		    'label' => '11. CAS Number',
		    'name' => '204',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '11',
			)
		),
		array(
		    'label' => '11. % Weight',
		    'name' => '200',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '11',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 11?',
		    'name' => '392',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '11',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 11)',
		    'name' => '465',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '392',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 11)',
		    'name' => '466',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '392',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 11)',
		    'name' => '467',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '392',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 11)',
		    'name' => '468',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '392',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 11)',
		    'name' => '469',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '392',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '12. Chemical Name',
		    'name' => '209',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '12',
			)	 
		),
		array(
		    'label' => '12. CAS Number',
		    'name' => '210',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '12',
			)
		),
		array(
		    'label' => '12. % Weight',
		    'name' => '211',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '12',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 12?',
		    'name' => '393',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '12',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 12)',
		    'name' => '470',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '393',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 12)',
		    'name' => '471',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '393',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 12)',
		    'name' => '472',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '393',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 12)',
		    'name' => '473',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '393',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 12)',
		    'name' => '474',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '393',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '13. Chemical Name',
		    'name' => '214',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '13',
			)	 
		),
		array(
		    'label' => '13. CAS Number',
		    'name' => '215',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '13',
			)
		),
		array(
		    'label' => '13. % Weight',
		    'name' => '216',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '13',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 13?',
		    'name' => '394',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '13',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 13)',
		    'name' => '475',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '394',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 13)',
		    'name' => '476',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '394',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 13)',
		    'name' => '477',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '394',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 13)',
		    'name' => '478',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '394',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 13)',
		    'name' => '479',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '394',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '14. Chemical Name',
		    'name' => '219',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '14',
			)	 
		),
		array(
		    'label' => '14. CAS Number',
		    'name' => '220',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '14',
			)
		),
		array(
		    'label' => '14. % Weight',
		    'name' => '221',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '14',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 14?',
		    'name' => '395',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '14',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 14)',
		    'name' => '480',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '395',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 14)',
		    'name' => '481',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '395',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 14)',
		    'name' => '482',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '395',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 14)',
		    'name' => '483',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '395',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 14)',
		    'name' => '489',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '395',
				'operator' => '=',
				'value' => '5',
			)
		),
		array(
		    'label' => '15. Chemical Name',
		    'name' => '224',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '15',
			)	 
		),
		array(
		    'label' => '15. CAS Number',
		    'name' => '225',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '15',
			)
		),
		array(
		    'label' => '15. % Weight',
		    'name' => '226',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '15',
			)
		),
		array(
		    'label' => 'How many GHS Hazard Categories for Chemical 15?',
		    'name' => '396',
		    'type' => 'text',
		    'help' => 'Please enter a value between 0 and 5.',
		    'rules' => '',
		    'input' => 'number',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '38',
				'operator' => '=',
				'value' => '15',
			)
		),
		array(
		    'label' => 'GHS Hazard Category (Chemical 15)',
		    'name' => '484',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '396',
				'operator' => '=',
				'value' => '1',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 15)',
		    'name' => '485',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '396',
				'operator' => '=',
				'value' => '2',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 15)',
		    'name' => '486',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '396',
				'operator' => '=',
				'value' => '3',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 15)',
		    'name' => '487',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '396',
				'operator' => '=',
				'value' => '4',
			)
		),	    
		array(
		    'label' => 'GHS Hazard Category (Chemical 15)',
		    'name' => '488',
		    'type' => 'select',
		    'rules' => '',
		    'inputs' => hazard_category_chemical(),
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '396',
				'operator' => '=',
				'value' => '5',
			)
		),
	    array(
	        'label' => 'Additional Information',
	        'name' => '397',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '4. First Aid Measures',
	        'name' => 'input_34',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '4.1. Description of First Aid Measures',
	        'name' => 'input_35',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Inhalation',
	        'name' => '42',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Skin Contact',
	        'name' => '43',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Eye Contact',
	        'name' => '44',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Ingestion',
	        'name' => '45',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '4.2. Most Important Symptoms and Effects, Acute and Delayed',
	        'name' => '72',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '4.3. Indication of Any Immediate Medical Attention and Special Treatment Needed',
	        'name' => '47',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '544',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '5. Firefighting Measures',
	        'name' => 'input_43',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '5.1 Extinguishing Media',
	        'name' => '51',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '5.2 Unusual Fire & Explosion Hazard',
	        'name' => '53',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '5.3 Advice for Firefighters',
	        'name' => '55',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '5.4 Further Information',
	        'name' => '73',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '518',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '6. Accidental Release Measures',
	        'name' => 'input_48',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '6.1 Personal Precautions, Protective Equipment and Emergency Procedures',
	        'name' => '62',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '6.2 Environmental Precautions',
	        'name' => '64',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '6.3 Methods for Materials for Containment and Cleanups',
	        'name' => '66',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '6.4 Reference to Other Sections',
	        'name' => '75',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '545',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '7. Handling and Storage',
	        'name' => 'input_53',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '7.1 Precautions for Safe Handling',
	        'name' => '79',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '7.2 Conditions for Safe Storage, Including Any Incompatibilities',
	        'name' => '82',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '7.3 Specific End Use Considerations',
	        'name' => '83',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '546',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '8. Exposure Control/Personal Protection',
	        'name' => 'input_59',
	        'type' => 'title',
	        'rules' => '',
	    ),	    
	    array(
	        'label' => '8.1 Components',
	        'name' => 'input_60',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'How Many Chemicals have exposure limits?',
	        'name' => '229',
	        'type' => 'text',
	        'rules' => '',
	        'help' => 'Please enter a value between 0 and 4.',
	        'input' => 'number'
	    ),
		array(
		    'label' => 'Chemical 1',
		    'name' => '230',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '1',
			),
		),
		array(
		    'label' => 'Chemical 1',
		    'name' => '228',
		    'type' => 'textarea',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '1',
			),
		),
		array(
		    'label' => 'Chemical 2',
		    'name' => '231',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '2',
			),
		),
		array(
		    'label' => 'Chemical 2',
		    'name' => '232',
		    'type' => 'textarea',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '2',
			),
		),
		array(
		    'label' => 'Chemical 3',
		    'name' => '234',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '3',
			),
		),
		array(
		    'label' => 'Chemical 3',
		    'name' => '233',
		    'type' => 'textarea',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '3',
			),
		),
		array(
		    'label' => 'Chemical 4',
		    'name' => '235',
		    'type' => 'text',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '4',
			),
		),
		array(
		    'label' => 'Chemical 4',
		    'name' => '236',
		    'type' => 'textarea',
		    'rules' => '',
		    'style' => 'display:none;',
			'condition' => array(
				'field_id' => '229',
				'operator' => '=',
				'value' => '4',
			),
		),
	    array(
	        'label' => '8.2 Exposure Controls',
	        'name' => 'input_64',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '8.2.1 Engineering Controls',
	        'name' => 'input_66',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Appropriate Engineering Controls',
	        'name' => '90',
	        'type' => 'text',
	        'rules' => '',
	    ),

	    array(
	        'label' => '8.2.2 Personal Protective Equipment',
	        'name' => 'input_67',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Eye Protection',
	        'name' => '92',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Skin and Body Protection',
	        'name' => '93',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Respiratory Protection',
	        'name' => '94',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '8.2.3 Environmental Exposure Controls',
	        'name' => 'input_71',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Hand Protection',
	        'name' => '96',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Hygiene Measures',
	        'name' => '500',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '501',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '502',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '503',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '547',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '8.2.4 Exhaust',
	        'name' => 'input_71',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Local Exhaust',
	        'name' => '503.1',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Mechanical Exhaust',
	        'name' => '503.2',
	        'type' => 'text',
	        'rules' => '',
	    ),

	    array(
	        'label' => '9. Physical and Chemical Properties',
	        'name' => 'input_78',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '9.1 Information on Basic Physical and Chemical Properties',
	        'name' => 'input_79',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),

	    array(
	        'label' => 'Reactivity in Water',
	        'name' => '237.2',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Analytical VOC (EPA method 24)',
	        'name' => '237.3',
	        'type' => 'text',
	        'rules' => '',
	    ),

	    array(
	        'label' => 'Appearance',
	        'name' => '237',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Specific Gravity (H20=1)',
	        'name' => '494',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '% volatile by volume',
	        'name' => '495',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '% solid by weight',
	        'name' => '496',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Weight per gallon',
	        'name' => '497',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Theoretical VOC',
	        'name' => '498',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Color',
	        'name' => '246',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Odor',
	        'name' => '245',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Odor Threshold',
	        'name' => '499',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'pH',
	        'name' => '244',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Melting Point',
	        'name' => '243',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Freezing Point',
	        'name' => '519',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Boiling Point',
	        'name' => '242',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Flash Point',
	        'name' => '241',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Flammability (liquid)',
	        'name' => '520',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Evap. Rate',
	        'name' => '240',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Upper Explosive Limit',
	        'name' => '239',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Lower Explosive Limit',
	        'name' => '238',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Vapor Pressure',
	        'name' => '248',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Vapor Density',
	        'name' => '252',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Density',
	        'name' => '251',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Water Solubility',
	        'name' => '250',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Partition Coeff (Oct/Water LogPow)',
	        'name' => '249',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Auto-Ignition Temp',
	        'name' => '253',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Decomp Temp',
	        'name' => '254',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Viscosity',
	        'name' => '255',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '9.2 Other Information',
	        'name' => '99',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '548',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10. Stability and Reactivity',
	        'name' => 'input_108',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.1 Reactivity Information',
	        'name' => '111',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.2 Chemical Stability',
	        'name' => '111.1',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.3 Possibility of Hazardous Reactions',
	        'name' => 'input_111',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Known Hazardous Reactions',
	        'name' => '113',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.4 Conditions to Avoid',
	        'name' => 'input_113',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Conditions to Avoid',
	        'name' => '114',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.5 Incompatible Materials',
	        'name' => 'input_115',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Incompatible Materials',
	        'name' => '115',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '10.6 Hazardous Decomposition Products',
	        'name' => 'input_117',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Hazardous Decomposition Products',
	        'name' => '116',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '527',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '11. Toxicological Information',
	        'name' => 'input_120',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '11.1 Information on Toxicological Effects',
	        'name' => 'input_121',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Toxicological Information (product):',
	        'name' => '413',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '256',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '517',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '514',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Likely Routes of Exposure:',
	        'name' => '414',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'How Many Chemicals with Tox. Information?',
	        'name' => 'input_127',
	        'type' => 'text',
	        'rules' => '',
	        'input' => 'number',
	    ),
	    array(
	        'label' => 'Chemical 1 Acute Toxicity (Oral)',
	        'name' => 'input_128',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Acute Toxicity (Dermal)',
	        'name' => 'input_129',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Skin Corrosion/Irritation',
	        'name' => 'input_130',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Eye Corrosion/Irritation',
	        'name' => 'input_131',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Sensitization',
	        'name' => 'input_132',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Additional Toxicological Information',
	        'name' => '125',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Carcinogenicity',
	        'name' => '521',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '528',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12. Ecological Information',
	        'name' => 'input_136',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '257',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '505',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => 'input_139',
	        'type' => '506',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Aquatic Toxicity (Product):',
	        'name' => '504',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'How Many Chemicals with Aquatic Toxicity Information?',
	        'name' => '415',
	        'type' => 'text',
	        'rules' => '',
	        'input' => 'number',
	    ),
	    array(
	        'label' => '12.1 Toxicity',
	        'name' => 'input_142',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Toxicity',
	        'name' => '134',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12.2 Persistence and Degradability',
	        'name' => 'input_144',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Persistence and Degradability',
	        'name' => '135',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12.3 Bioaccumulation Potential',
	        'name' => 'input_146',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Bioaccumulation Potential',
	        'name' => '136',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12.4 Mobility in Soil',
	        'name' => 'input_148',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Mobility Information',
	        'name' => '137',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12.5 Results of PBT and vPvB Assessment',
	        'name' => 'input_150',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 European Tox Assessment',
	        'name' => '138',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '12.6 Other Adverse Effects',
	        'name' => 'input_152',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Chemical 1 Other Adverse Effects',
	        'name' => '139',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '529',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '13. Disposal Considerations',
	        'name' => 'input_155',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '13.1 Waste Treatment Methods',
	        'name' => '143',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Product Disposal Considerations:',
	        'name' => '513',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Packaging Disposal Considerations:',
	        'name' => '144',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Miscellaneous Advice:',
	        'name' => '507',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'RCRA HAZARD CLASS',
	        'name' => '508',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '14. Transportation Information',
	        'name' => 'input_161',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'DOT Domestic Ground: Non-Bulk',
	        'name' => '147',
	        'type' => 'text',
	        'help' => '',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'DOT Domestic Ground: Bulk',
	        'name' => '147.1',
	        'type' => 'text',
	        'help' => '',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'DOT International',
	        'name' => '147.2',
	        'type' => 'text',
	        'help' => '',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Other Information:',
	        'name' => '148',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '509',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '15. Regulatory Information',
	        'name' => 'input_165',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '15.1 US Regulations',
	        'name' => 'input_166',
	        'type' => 'sub-title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'TSCA:',
	        'name' => '154',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'CERCLA:',
	        'name' => '417',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'SARA TITLE III (313):',
	        'name' => '418',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'SARA 311/312:',
	        'name' => '416',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Federal and State Regulations:',
	        'name' => '156',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '15.2 Chemical Safety Assessment',
	        'name' => '157',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '15.3 Other Regulatory Information',
	        'name' => '158',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'HMIS-Health:',
	        'name' => '162',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'HMIS-Fire:',
	        'name' => '419',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'HMIS-Reactivity:',
	        'name' => '420',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'HMIS-PPE:',
	        'name' => '421',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'NFPA-Health:',
	        'name' => '422',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'NFPA-Flammability:',
	        'name' => '423',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'NFPA-Reactivity:',
	        'name' => '424',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'NFPA-Special Notice:',
	        'name' => '425',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '523',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '526',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '525',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => '',
	        'name' => '549',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '16. Other Information',
	        'name' => 'input_190',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Other Information',
	        'name' => '522',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'SDS Revision:',
	        'name' => '550',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Date:',
	        'name' => '551',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'SDS Author:',
	        'name' => '165',
	        'type' => 'text',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Additional Information:',
	        'name' => 'input_195',
	        'type' => 'title',
	        'rules' => '',
	    ),
	    array(
	        'label' => '<p style="text-align:justify;">The development of this Safety Data Sheet (SDS) relies upon information provided to us by each of our raw material suppliers. This SDS will be updated as changes occur to their SDS(s). We believe the recommendations and technical information contained herein to be accurate. However, they are given without warranty or guarentee, expressed or implied, and we assume no responsibility for losses or damage, direct or indirect, as a result of their use.</p>',
	        'name' => 'input_196', 
	        'type' => 'html',
	        'rules' => '',
	    ),
	    array(
	        'label' => 'Disclaimer:',
	        'name' => '531',
	        'type' => 'textarea',
	        'rules' => '',
	    ),
	    array(
	        'label' => '<p style="text-align:justify;font-size:11px;">The development of this Safety Data Sheet (SDS) relies upon information provided to us by each of our raw material suppliers. This SDS will be updated as changes occur to their SDS(s). We believe the recommendations and technical information contained herein to be accurate. However, they are given without warranty or guarentee, expressed or implied, and we assume no responsibility for losses or damage, direct or indirect, as a result of their use.</p>',
	        'name' => 'input_199',
	        'type' => 'html',
	        'rules' => '',
	    ),
	);

	return $forms;
} 

function hazard_category_chemical() {
	return array(
		'Expl. (unstbl).',
		'Expl. (Div 1.1)',
		'Expl. (Div 1.2)',
		'Expl. (Div 1.3)',
		'Expl. (Div 1.4)',
		'Expl. (Div 1.5)',
		'Flam. Gas Cat 1',
		'Flam. Gas Cat 2',
		'Aer. Cat 1',
		'Aer. Cat 2',
		'Flam. Liq. Cat 1',
		'Flam. Liq. Cat 2',
		'Flam. Liq. Cat 3',
		'Flam. Liq. Cat 4',
		'Flam. Solid',
		'Aer. (press cont.)',
		'Flam. Gas (Cat A)',
		'Flam. Gas (Cat B)',
		'Self React (Type A)',
		'Self React (Type B)',
		'Self React (Type C/D)',
		'Pyr. Liq. Cat 1',
		'Self heat. Cat 1',
		'Self heat. Cat 2',
		'Water React. Emit Flam Gas Cat 1',
		'Water React. Emit Flam Gas Cat 2',
		'Ox Gas Cat 1',
		'Ox Cat 1',
		'Ox Cat 2',
		'Press Gas (expl if heated)',
		'Press Gas (cryo)',
		'Corr Met Cat 1',
		'Acute tox, oral Cat 1/2',
		'Acute tox, oral Cat 3',
		'Acute tox, oral Cat 4',
		'Acute tox, oral Cat 5',
		'Asp. Cat 1',
		'Asp. Cat 2',
		'Acute tox, dermal Cat 1/2',
		'Acute tox, dermal Cat 3',
		'Acute tox, dermal Cat 4',
		'Acute tox, dermal Cat 5',
		'Skin Corr/Irrit Cat 1',
		'Skin Corr/Irrit Cat 2',
		'Skin Corr/Irrit Cat 3',
		'Skin Sens Cat 1',
		'Eye Dam/Irrit Cat 1',
		'Eye Dam/Irrit Cat 2',
		'Eye Dam/Irrit Cat 3',
		'Acute tox, inh. Cat 1/2',
		'Acute tox, inh. Cat 3',
		'Acute tox, inh. Cat 4',
		'Acute tox, inh. Cat 5',
		'Resp Sens Cat 1',
		'STOT SE Cat 3 (resp irrit)',
		'STOT SE Cat 3 (narc)',
		'Muta. Cat 1',
		'Muta. Cat 2',
		'Carc. Cat 1',
		'Carc. Cat 2',
		'Repr Cat 1',
		'Repr Cat 2',
		'Repr Cat 2 (unborn child)',
		'Repr (breastfeed)',
		'STOT SE Cat 1',
		'STOT SE Cat 2',
		'STOT RE Cat 1',
		'STOT RE Cat 2',
		'Aquatic Acute Cat 1',
		'Aquatic Acute Cat 2',
		'Aquatic Acute Cat 3',
		'Aquatic Chr. Cat 1',
		'Aquatic Chr. Cat 2',
		'Aquatic Chr. Cat 3',
		'Aquatic Chr. Cat 4',
		'Ozone Haz Cat 1'
	);	
}

function sds_na() {
	return array(
		array('class' => 'input_20', 'name' => 'input_19.1'),
		array('class' => 'input_426', 'name' => 'input_426'),
		array('class' => 'input_24', 'name' => 'input_21'),
	);
}










