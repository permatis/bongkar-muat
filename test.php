<?php
// if( !session_id() ) session_start();

// include_once 'class.messages.php';
// $msg = new Messages();

// $kata = 1;
// if($kata <= 1){
// 	$msg->add('s', 'Bener Boz', 'test2.php');
// }else{
// 	$msg->add('e', 'Salah Boz');
// }

// require_once('test2.php');
// 
include_once 'lib/Kriptografi.php';
include_once 'lib/Database.php';

// $test = new mcrypt();
// $encrypted = $test->encryptIt( 'phpindonesia');
// $decrypted = $test->decryptIt( $encrypted );
// 
// $encrypted = Kriptografi::hash('admin');
// $decrypted = Kriptografi::check('admin');
// $data = [
// 	'nama'		=> 'admin',
// 	'password'	=> 'admin'
// ];

// var_dump(Kriptografi::check($data));

//print_r(in_array(Kriptografi::hash($encrypted), $pwd));

/// echo 'Hasil dari enkripsi <b>'.$encrypted. '</b>'; //s3LoYeqTzibHCGybqG90xPlMvMHJnWgpQKzB0YJJBQM=
//echo '<br /> Hasil dari dekripsi<b> ' .$decrypted.'</b>'; //phpindonesia
$id = 1;
$data = Database::get('relasi_anggota', ['id'])->where('bongkarmuat_id', '=', $id)->result();
$input1 = array(
        array(
        	'anggota_id' => 3,
        )
);

$input2 = array(
	        array(
	        	'anggota_id' => 5,
	        ),
	        array(
	        	'anggota_id' => 3,
	        ),
	        array(
	        	'anggota_id' => 1,
	        ),
	        array(
	        	'anggota_id' => 4,
	        )
	    );


var_dump($data);
// var_dump($input1);
// var_dump($input2);

function updateRelasi($id_keys, $id_values, $input, $database)
{
	if(count($input) == count($database)){
		for ($i=0; $i < count($input); $i++) { 
			$input_data[] = array_merge($database[$i], $input[$i]);
		}

		$id_database = array_keys($database[0])[0];
		$inputs 	 = array_keys($input[0])[0];
		
		foreach ($input_data as $keys => $values) {
			$q[] = "WHEN id = ".$values[$id_database]." THEN ". $values[$inputs];
			$v[] = $values[$id_database];
		}
		
		$query = "UPDATE relasi_anggota SET anggota_id = CASE ".implode(" ", $q)." END WHERE id IN(".implode(",", $v).")";
		Database::query($query)->result();

	}else{

		$inputs_value = implode(", ", array_values($input[0]));
		$inputs_key =  array_keys($input[0])[0];

		foreach ($input as $key => $value) {
			$values[] = "(".$id_values.",".$value[$inputs_key].")";
		}

		$delete = "DELETE FROM relasi_anggota WHERE ".$id_keys." = ".$id_values." AND ".$inputs_key." NOT IN (".$inputs_value.")";
		Database::query($delete)->result();
		
		$insert	= "INSERT INTO relasi_anggota (".$id_keys.", ".$inputs_key.") VALUES ".implode(", ", $values)." ON DUPLICATE KEY UPDATE ".$id_keys."=".$id_keys;
		
		Database::query($insert)->result();
	}
}

updateRelasi('bongkarmuat_id', $id, $input2, $data);


/**
 * Table relasi harus unique antara kolom1 dengan kolom2
 * ALTER TABLE `votes` ADD UNIQUE `unique_index`(`user`, `email`, `address`);
 * 
 */


// function arrayRecursiveDiff($aArray1, $aArray2) {
//   $aReturn = array();

//   foreach ($aArray1 as $mKey => $mValue) {
  	
//     if (array_key_exists($mKey, $aArray2)) {
//       if (is_array($mValue)) {
//         $aRecursiveDiff = arrayRecursiveDiff($mValue, $aArray2[$mKey]);
//         if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
//       } else {
//         if ($mValue != $aArray2[$mKey]) {
//           $aReturn[$mKey] = $mValue;
//         }
//       }
//     } else {
//       $aReturn[$mKey] = $mValue;
//     }
//   }
//   return $aReturn;
// } 
//  
  // $arr1 = arrayRecursiveDiff($data, $input2);
  // var_dump($arr1);
  // 

function $testimonials()
{
	return 'hello';
}

echo $this->
?>

<?php