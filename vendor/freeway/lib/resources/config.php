<?
class config
{
	private $config;
	public $environment;
	public $db_settings;
	
	public function __construct($filename)
	{
		$this->config = parse_ini_file($filename, TRUE);
		$this->environment = $this->config['general']['environment'];
		$this->db_settings = $this->config[$this->environment];
	}
}
?>