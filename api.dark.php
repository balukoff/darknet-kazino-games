<?
 /***************************************/
 /*  Single class for api darknet games */
 /*  @2019 kasino @balukoff             */
 /***************************************/
 
 define('HALL', '1808062');
 define('KEY', 'kaziman98');
 define('LOGIN', $login);
 
class DarkAPI{
 
 function __construct($request){
  $this->filter_category = array('novomatic_classic', 'igrosoft', 'sport_betting'); 
  $this->filter_games = array('Sizzling Hot Quattro', 'Power Stars', 'Book of Ra Deluxe', 'Lord of the Ocean', 'Plenty on Twenty'.
							  'Sizzling Hot Deluxe', 'Xtra Hot', 'Katana', 'Lucky Ladys Charm', 'Dolphins Pearl', 'Just Jewels',
							  'Secret Elixir', 'Mystic Secret', 'Fruit Sensation', 'Fairy Queen', 'Columbus Deluxe', 'Magic Princess',
							  'Ramses II', 'Quest for Gold', 'Alchemist', 'Mermaids Pearl', 'Pharaohs Gold II', 'Rex', 'Indian Spirit',
							  'Beetle Mania', 'Gorilla', 'Flame Dancer', 'Pharaohs Tomb', 'Pharaohs Ring', 'Re+B1el King', 'Book of Ra Classic',
							  'Dolphins Pearl Classic', 'Polar Fox', 'Bananas Go Bahamas', 'Dinasty of Ming', 'Royal Treasures', 'Pharaohs Gold III',
							  'Banana Splash', 'Santa Strikes Back', 'Secret Forest', 'Rudolph Revenge', 'Return Of Rudolph', 'Coyote Cash',
							  'Wonderful Flute', 'The Money Game', 'Cold Spell', 'Hannibal of Carthago', 'Unicorn Magic', 'Lucky Lady Charm Classic',
							  'Just Jewels Classic', 'Beetle Mania Classic', 'Sizzling Hot Classic', 'Emperors of China', 'Attila', 'Griphons Gold',
							  'The Bee Bop', 'Columbus Classic', 'Silver Fox', 'Gold Craze', 'Hot Targets', 'Hot Volee', 'Egyptian Experience',
							  'The Ming Dinasty', 'Orca', 'Golden Ark', 'Big Catch', 'Chicago', 'Mega Joker', 'Red Lady', 'Fruitilicious', 'Hoffmeister',
							  'Flamenco Roses', 'Book of Ra 6', 'Spinata Grenada', 'King of Slots', 'South Park', 'Dracula', 'Glow', 'When Pigs Fly', 
							  'Nrvna', 'Fruit Spin', 'Archangels Salvation', 'Twin Spin Deluxe', 'Lost Relics', 'Witchcraft Academy', 'Wild Bazaar',
							  'Halloween Jack', 'Double Stacks', 'Coins of Egypt', 'Berryburst MAX', 'Berryburst', 'Fairytale Legends: Mirror Mirror', 
							  'eisha', 'Lucky Count', 'Sun and Moon', '50 Dragons', '50 Lions', 'Queen of the Nile', 'Double Happiness', 'Big Red', 
							  'Big Ben', 'Buffalo', 'Miss Kitty', 'Queen of the Nile II', 'Tiki Torch', '5 Dragons', 'Jaguar Mist', 'Pelican Pete',
							  'Dolphins Treasure', 'Moon Festival', 'Werewolf Wild', 'African Simba', 'Dazzling Diamonds', 'Sizzling 6');

  if (empty($request)) $request = 'gamesList';
  $this->url  = 'http://tbs2api.dark-a.com/API/';
  $this->hall = '1808062';
  $this->cmd = $request;
  $this->post_data = array (
    "hall" => $this->hall,
    "cmd"  => $this->cmd,
    "key"  => 'kaziman98'
 ); 
 }
 
 private function getGameParams(){}
 public  function getSingleName(){}
 
 public function openGame(){
  // меняем url для метода
  $this->url = 'http://tbs2api.dark-a.com/API/openGame/';
  $this->cmd = 'openGame';
  $this->post_data = array(
    "hall" => $this->hall,
    "cmd" => $this->cmd, 
    "key" => 'kaziman98',
    "login" => LOGIN,
    "gameId" => (int)$_GET['game_id'],
    "demo"   => (int)$_GET['mode'],
    "continent" => "eur",
    "iframe"    => 0,
    "domain" => "kaziman.win"
 );
    
  $data = $this->sendRequest();
  return $data;
 }
 
 private function getRequest(){
   if(isset($_REQUEST['q'])) 
   return trim($_REQUEST['q']);
   else return false;
 }
 /// с поиском
 public function getGames(){
   $data = $this->sendRequest();
   $games = array();
   $q = $this->getRequest();
   foreach($data->content->gameList as $game){
	if(in_array($game->name, $this->filter_games)) continue;
	if ($q !== false){
	 $pos = strpos(strtoupper($game->name), strtoupper($q));
	 if($pos !== false)
	  $games[] = array('id'=>$game->id, 'name'=>$game->name, 'img' => $game->img); 
	}else
	if(isset($_GET['game_id'])){
	 $game_id = (int)$_GET['game_id'];
	 if($game->id == $game_id){
	  $games[] = array('id'=>$game->id, 'name'=>$game->name, 'img' => $game->img);
	  break;
	 }
	}else
	$games[] = array('id'=>(int)$game->id, 'name'=>$game->name, 'img' => $game->img);
   }
   return $this->applyDoublesFilter($games);
 }
 
 private function gameExists($games, $gameName){
  $counter = 0;
  foreach($games as $game){
   if($game['name'] == $gameName){
    $counter++;
   }
  }
  return $counter;
 }
 
 private function applyDoublesFilter($games){
   $filtered_games = array();
   foreach($games as $game){
    $times = $this->gameExists($filtered_games, $game['name']);
	if($times == 0){
	 $filtered_games[] = $game;
	}
   }  
   return $filtered_games;
 }
 
 public function getFilterGames(){
   $data = $this->sendRequest();
   $games = array();
   $q = trim($_GET['category_name']);
   foreach($data->content->gameList as $game){
	$category = str_replace('_html5', '', $game->label);
	if(in_array($game->name, $this->filter_games)) continue;
	if ($q !== false){
	 $pos = strpos(strtoupper($category), strtoupper($q));
	 if($pos !== false)
	  $games[] = array('id'=>$game->id, 'name'=>$game->name, 'img' => $game->img); 
	}
   }
   return $this->applyDoublesFilter($games);
 }
 
 public function getCategories(){
  $data = $this->sendRequest();
  $categories = [];
  foreach($data->content->gameList as $game){
	$category = str_replace('_html5', '', $game->label);
	if ((!in_array($category, $categories))&&(!in_array($category, $this->filter_category))){
	 $categories[] = $category;
	}
  }
  return $categories;
 }
 
 private function sendRequest(){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $this->url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_data);
  $output = curl_exec($ch);
  curl_close($ch);
  return json_decode($output);
 } 
 }
?>