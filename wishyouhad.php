<?php
include('includes/bootloader.php');
include('includes/kernel.php');

//online friends that have achievements that you don't have

$api->output_headers();
$gamertag = (isset($_GET['gamertag']) && !empty($_GET['gamertag'])) ? trim($_GET['gamertag']) : null;
$gameid = (isset($_GET['gameid']) && !empty($_GET['gameid'])) ? trim($_GET['gameid']) : null;
$region   = (isset($_GET['region']) && !empty($_GET['region'])) ? $_GET['region'] : 'en-US';


if (!$api->logged_in) {
    echo $api->output_error(500);
} else {
    if (empty($gamertag)) {
        echo $api->output_error(301);
    } elseif ($api->check_culture($region) == false) {
        echo $api->output_error(305);
    } else {
    	$wishyouhad = array();

        $friendData = $api->fetch_friends($gamertag, $region);

        $friends = array();
        foreach($friendData['friends'] as $friend){
        	if($friend['online']){
        		$friends[] = $friend;
        	}
        }
			
		$hasData = $api->fetch_achievements($gamertag, $gameid, $region);
		$hasData = isset($hasData['achievements']) ? $hasData['achievements'] : false;
		if($hasData) {
			$has = array();
			foreach($hasData as $h){
				if($h['unlocked'] == "true"){
					$has[] = $h;
				}
			}
			foreach($friends as $key => $friend){
				//this friend has this game!
				//get friend's achievements for this game
				$achievements = $api->fetch_achievements($friend['gamertag'], $gameid, $region);
				if($achievements){
					$achievements = isset($achievements['achievements']) ? $achievements['achievements'] : false;
					
					foreach($achievements as $achievement){
						if($achievement['unlocked'] == "true"){
							$found = false;
							//do we have it?
							foreach($has as $a){
								if($achievement['id'] === $a['id']){
									$found = true;
								}
							}
							if($found == false){
								//we don't have this achievement.
								$wishyouhad[$achievement['title']]['description'] = $achievement['description'];
								$wishyouhad[$achievement['title']]['artwork'] = $achievement['artwork']['locked'];
								$wishyouhad[$achievement['title']]['friends'][] = $friend;
							}
						}
					}
					
					
				}
			}
		}
        
        if ($wishyouhad) {
            echo $api->output_payload($wishyouhad);
        } else {
            echo $api->output_error($api->error);
        }
    }
}
