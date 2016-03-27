<?php

class System 
{
	private $db = null;
	private $sysInfo = array();
	private static $instance;

	private function __construct ()
	{
		$this->db =& Database::getInstance();
		$this->loadSettings();
	}

	public static function &getInstance ()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	private function loadSettings()
	{
		$sql = "SELECT * FROM system";

		if ($this->db->query($sql)->num_rows() < 1)
			throw new TCenterException("Error Loading System Settings");

		while ($row = $this->db->fetch_assoc())
		{
			$this->sysInfo[$row['s_name']] = $row['s_val'];
		}
	}

	/*
	 * getAttrib
	 *
	 * return some user attribute
	 */
	public function getAttrib ($attrib)
	{
		if (!is_array($attrib))
		{
			if (!isset($this->sysInfo[$attrib]))
				throw new TCenterException("Invalid ".get_class($this)."Attribute - $attrib Does Not Exist");

			return $this->sysInfo[$attrib];
		}
		else
		{
			$ret = array();

			foreach ($attrib as $a)
			{
				if (!isset($this->sysInfo[$a]))
					throw new TCenterException("Invalid ".get_class($this)." Attribute Array - $a Does Not Exist");

				$ret[] = $this->sysInfo[$a];
			}

			return $ret;
		}
	}

	public function updateAttrib ($attrib, $val = null)
	{
		if (!is_array($attrib))
		{
			$this->sysInfo[$attrib] = $val;
		}
		else
		{
			foreach ($attrib as $k=>$v)
			{
				$this->sysInfo[$k] = $v;
			}
		}
	}

	public function save ()
	{
		foreach ($this->sysInfo as $k=>$v)
		{
			$sql = "UPDATE system SET s_val = '".$this->getAttrib($k)."' where s_name = '$k'";
			$this->db->query($sql);
		}
	}
}
