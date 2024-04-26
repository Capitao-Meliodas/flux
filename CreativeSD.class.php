<?php
/*.---------------------------------------------------------------.
  .   ____                          __                            .
  .  /\  _`\                       /\ \__  __                     .
  .  \ \ \/\_\  _ __    __     __  \ \ ,_\/\_\  __  __     __     .
  .   \ \ \/_/_/\`'__\/'__`\ /'__`\ \ \ \/\/\ \/\ \/\ \  /'__`\   .
  .    \ \ \s\ \ \ \//\  __//\ \s\.\_\ \ \_\ \ \ \ \_/ |/\  __/   .
  .     \ \____/\ \_\\ \____\ \__/.\_\\ \__\\ \_\ \___/ \ \____\  .
  .      \/___/  \/_/ \/____/\/__/\/_/ \/__/ \/_/\/__/   \/____/  .
  .                                                               .
  .         2014~2018 © Creative Services and Development         .
  .                     www.creativesd.com.br                     .
  .---------------------------------------------------------------.
  . Autor: Romulo SM (sbk_)                          Versão: 1.0  .
  .---------------------------------------------------------------.
  .                    CreativeSD FluxCP Class                    .
  *---------------------------------------------------------------*/
class CreativeSD {
	private $flux = null;
	private $server = null;
	public $Cfg = array();
	
	public function __construct($config, $flux, $server) {
		$this->flux = $flux;
		$this->server = $server;
		$this->Cfg = $config;
	}
	
	public function getNews($init = 0, $end = null)
	{
		if( $end === null )
			$end = $this->Cfg['News']['home_rows'];
		
		$tbl = Flux::config('FluxTables.CMSNewsTable');
		$sql = "SELECT id, title, type, body, link, author, created, modified FROM {$this->server->loginDatabase}.$tbl ORDER BY id DESC LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$news = $sth->fetchAll();
		return $news;
	}
	
	public function getPvPRank($type = 0, $init = 0, $end = null)
	{
		if( $end === null )
			$end = $this->Cfg['rank_home_rows'];
		
		$sql  = "`rank`.`char_id` AS `char_id`, `rank`.`kill` AS `kill`, `rank`.`die` AS `die`, (`rank`.`kill`-`rank`.`die`) AS ration,  `rank`.`last_update` AS `last_update`, ";
		$sql .= "`ch`.`name` AS `name`, `ch`.`base_level`, `ch`.`job_level`, `ch`.`class` AS `job`, `ch`.`sex`, ";
		$sql .= "`g`.`guild_id` AS `guild_id`, `g`.`name` AS `guild_name`, `g`.`emblem_len` AS `emblem_len` ";
		$sql .= "FROM `{$this->server->charMapDatabase}`.`pvp_flux_csd` AS `rank` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`char` AS `ch` ON `ch`.`char_id`=`rank`.`char_id` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`guild` AS `g` ON `ch`.`guild_id`=`g`.`guild_id` ";
		$sql .= "WHERE `rank`.`type`=? ";
		$sql = "SELECT " . $sql . " ORDER BY ration DESC, last_update DESC LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($type));
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function getGBRank($init = 0, $end = null)
	{
		if( $end === null )
			$end = $this->Cfg['rank_home_rows'];
		
		$sql  = "`rank`.`guild_id` AS `guild_id`, `rank`.`break` AS `break`, `rank`.`last_update` AS `last_update`, ";
		$sql .= "`g`.`name` AS `name`, `g`.`emblem_len` AS `emblem_len`, `ch`.`name` AS `master` ";
		$sql .= "FROM `{$this->server->charMapDatabase}`.`guild_flux_csd` AS `rank` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`guild` AS `g` ON `rank`.`guild_id`=`g`.`guild_id` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`char` AS `ch` ON `g`.`char_id`=`ch`.`char_id` ";
		$sql .= "WHERE 1=1 ";
		$sql = "SELECT " . $sql . " ORDER BY break DESC, last_update DESC LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function getPBRank($init = 0, $end = null)
	{
		if( $end === null )
			$end = $this->Cfg['rank_home_rows'];
		
		$sql  = "`rank`.`char_id` AS `char_id`, `rank`.`break` AS `break`, `rank`.`last_update` AS `last_update`, ";
		$sql .= "`ch`.`name` AS `name`, `ch`.`base_level`, `ch`.`job_level`, `ch`.`class` AS `job`, `ch`.`sex`, ";
		$sql .= "`g`.`guild_id` AS `guild_id`, `g`.`name` AS `guild_name`, `g`.`emblem_len` AS `emblem_len` ";
		$sql .= "FROM `{$this->server->charMapDatabase}`.`pguild_flux_csd` AS `rank` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`char` AS `ch` ON `ch`.`char_id`=`rank`.`char_id` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`guild` AS `g` ON `ch`.`guild_id`=`g`.`guild_id` ";
		$sql .= "WHERE 1=1";
		$sql = "SELECT " . $sql . " ORDER BY break DESC, last_update DESC LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute(array($type));
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function getMvPRank($init = 0, $end = null)
	{
		if( $end === null )
			$end = $this->Cfg['rank_home_rows'];
		
		$sql  = "`rank`.`char_id` AS `char_id`, `rank`.`kill` AS `kill`, `rank`.`last_update` AS `last_update` ";
		$sql .= "`ch`.`name` AS `name`, `ch`.`guild_id` AS `guild_id`, ";
		$sql .= "`g`.`name` AS `guild_name`, `g`.`emblem_len` AS `emblem_len` ";
		$sql .= "FROM `{$this->server->charMapDatabase}`.`mvp_flux_csd` AS `rank` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`char` AS `ch` ON `ch`.`char_id`=`rank`.`char_id` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`guild` AS `g` ON `ch`.`guild_id`=`g`.`guild_id` ";
		$sql .= "WHERE 1=1 ";
		$sql = "SELECT " . $sql . " ORDER BY kill DESC, last_update LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function getGuildCastle($guild_id)
	{
		if( !$guild_id )
			return array();
		
		$sql = "SELECT `gd`.`castle_id` AS `castle_id` FROM `{$this->server->charMapDatabase}`.`guild_castle` AS `gd` WHERE `gd`.`guild_id`=?";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute((array)$guild_id);
		$result = $sth->fetchAll();
		return $result;
	}

	public function getGuildMembers($guild_id)
	{
		$sth = $this->server->connection->getStatement("SELECT `ch`.`name` FROM {$server->charMapDatabase}.`char` AS `ch` WHERE `ch`.`guild_id`=?");
		$sth->execute((array)$guild_id);
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function countGuildMember($guild_id)
	{
		if( !$guild_id )
			return array();
		
		$sql = "SELECT COUNT(*) AS value FROM `guild_member` WHERE `guild_id`=?";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute((array)$guild_id);
		$value = $sth->fetch()->value;
		return $value ? $value : 1;
	}
	
	public function getCastleData($arr = true)
	{
		$sql  = "`c`.`castle_id` AS `id`, `c`.`guild_id` AS `guild_id`, ";
		$sql .= "`g`.`name` AS `guild_name`, `g`.`emblem_len` AS `emblem_len` ";
		$sql .= "FROM `{$this->server->charMapDatabase}`.`guild_castle` AS `c` ";
		$sql .= "LEFT JOIN `{$this->server->charMapDatabase}`.`guild` AS `g` ON `c`.`guild_id`=`g`.`guild_id` ";
		$sql .= "WHERE 1=1 ";
		$sql = "SELECT " . $sql . " ORDER BY id DESC";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$tmp = $sth->fetchAll();
		$result = array();
		foreach( $tmp as $value ) {
			$result[$value->id]['guild_id'] = $value->guild_id;
			$result[$value->id]['guild_name'] = $value->guild_name;
			$result[$value->id]['emblem_len'] = $value->emblem_len;
		}
		return $result;
	}
	
	public function getCastleName($castle_id)
	{
		foreach( $this->Cfg['Castles'] as $value ) {
			if( isset($value['list'][$castle_id]) )
				return $value['list'][$castle_id];
		}
		return null;
	}
	
	public function getCashShop($init = 0, $end = null)
	{
		if( $end == null )
			$end = $this->Cfg['cashshop_home_rows'];
		
		$fromTables1 = array("{$this->server->charMapDatabase}.item_cash_db", "{$this->server->charMapDatabase}.item_cash_db2");
		$tempTable1 = new Flux_TemporaryTable($this->server->connection, 'items_cash', $fromTables1);
		
		if($this->server->isRenewal) {
			$fromTables2 = array("{$this->server->charMapDatabase}.item_db_re", "{$this->server->charMapDatabase}.item_db2_re");
		} else {
			$fromTables2 = array("{$this->server->charMapDatabase}.item_db", "{$this->server->charMapDatabase}.item_db2");
		}

		$tempTable2 = new Flux_TemporaryTable($this->server->connection, 'items', $fromTables2);
		
		$sql = "SELECT cash.item_id AS item_id, cash.price AS price, items.name_japanese AS item_name ";
		$sql .= "FROM {$this->server->charMapDatabase}.`items_cash` AS `cash`";
		$sql .= "LEFT OUTER JOIN {$this->server->charMapDatabase}.items ON items.id = cash.item_id ";
		$sql .= "WHERE 1=1 ORDER BY cash.date DESC LIMIT {$init},{$end}";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		return $result;
	}
	
	public function ReadCache($file) {
		$status = array();
		if( file_exists($file) ) {
			$contents = file_get_contents($file);
			$status = (array)json_decode($contents);
		}
		
		if( !is_writable($file) || count($status) <= 0 ||
			!isset($status['expire']) || time() >= $status['expire'] ||
			!isset($status['login']) || !isset($status['char']) || !isset($status['map'])
		)
			return null;
		
		return $status;
	}
	
	public function RequestInfo($log_ip, $log_port, $char_ip, $char_port, $map_ip, $map_port)
	{
		$login = @fsockopen($log_ip, $log_port, $errno, $errstr, 1);
        $char = @fsockopen($char_ip, $char_port, $errno, $errstr, 1);
        $map = @fsockopen($map_ip, $map_port, $errno, $errstr, 1);
		$status = array('login' => $login ? true : false, 'char' => $char ? true : false, 'map' => $map ? true : false);
		return $status;
	}
	
	public function SaveCache($path, $time, $status)
	{
		$status['expire'] = $time;
		$set = json_encode($status);
		return file_put_contents($path, $set) OR trigger_error('Não foi possível criar o arquivo de cache.', E_USER_ERROR);
	}
	
	public function getURL()
	{
		$tmpURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$tmpURL = explode("?",$tmpURL);
		return $tmpURL[0];
	}
	
	public function getPlayersOn() {
		$sql = "SELECT COUNT(*) AS value FROM {$this->server->charMapDatabase}.char WHERE online='1'";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		return $sth->fetch()->value;
	}
	
	public function getWarOn() {
		$sql = "SELECT COUNT(*) AS value FROM {$this->server->charMapDatabase}.server_info WHERE `name` LIKE '%woe_%' AND `value`='1'";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		return $sth->fetch()->value;
	}
	
	public function getPeak()
	{
		$sql = "SELECT `value` FROM {$this->server->charMapDatabase}.server_info LIMIT 1";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute();
		$peak = $sth->fetch()->value;
		return $peak;
	}
	
	public function getCashPoints($account_id)
	{
		$sql = "SELECT `value` FROM `acc_reg_num` WHERE `account_id`=?";
		$sth = $this->server->connection->getStatement($sql);
		$sth->execute((array)$account_id);
		$value = $sth->fetch()->value;
		return $value;
	}
}
?>