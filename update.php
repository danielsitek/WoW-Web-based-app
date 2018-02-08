<?php

require_once( 'config.php' );


function load_auction_api_json() {

	$data = file_get_contents( 'https://'. REALM_REGION .'.api.battle.net/wow/auction/data/'. REALM_NAME . '?locale=' . LOCALE . '&apikey=' . BATTLE_API_KEY );
	$res_json = json_decode( $data, true );

	return (object) array(
		'last_modified' => $res_json['files'][0]['lastModified'],
		'url' => $res_json['files'][0]['url'],
	);
}


function update_status_db( $json, $conn ) {

	$sql_status_update = "INSERT INTO status (realm) VALUES(" . $json->last_modified . ");";
	$update_status = $conn->query($sql_status_update);

	if ( !$update_status ) {
		printf("Errormessage: %s\n", $conn->error);
		var_dump( $update_status );
		die();
	}
}



$conn = new mysqli( SERVER_NAME, DB_USER_NAME, DB_USER_PASS, DB_SCHEMA );
$conn->set_charset('utf8');

if ( $conn->connect_error ) {
	printf("Connection failed: %s\n", $conn->connect_error);
	die();
}


$auction_json = load_auction_api_json();

$check_status = "SELECT realm FROM status WHERE realm=(SELECT max(realm) FROM status);";
$res_status = $conn->query($check_status);

if ( !$res_status ) {
	printf("Errormessage: %s\n", $conn->error);
	die();
}


// output data of each row

$row = $res_status->fetch_assoc();

if ( $res_status->num_rows <= 0 || $row["realm"] < $auction_json->last_modified ) {

    $conn->query("TRUNCATE TABLE auctions");
    $conn->query("TRUNCATE TABLE status");

    update_status_db( $auction_json, $conn );

    $auctionsFile = file_get_contents( $auction_json->url );
    $auctionsArray = json_decode($auctionsFile, true)['auctions'];

    foreach ($auctionsArray as $auction) {
	    $sql_insert = "INSERT INTO auctions (auc, item, owner, buyout, quantity) VALUES(" . $auction['auc'].",". $auction['item'].",'".$auction['owner']."',".$auction['buyout'].",".$auction['quantity'].");";
	    $inser_items = $conn->query($sql_insert);

	    if ( !$inser_items ) {
		    printf("Errormessage: %s\n", $conn->error);
		    var_dump( $auction );
	    }
    }

    $conn->query("DELETE FROM auctions WHERE buyout=0");

    print( "DB Updated.<br>" );
    return;

}

print( "Already up to date.<br>" );
return;
