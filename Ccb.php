<?php

class Ccb
{
	function __construct($params)
	{
		$this->base_url = $params['ccb_base_url'];
		$this->username = $params['ccb_username'];
		$this->password = $params['ccb_password'];
		$this->timeout  = isset( $params['ccb_timeout'] ) ? $params['ccb_timeout'] : 120;
	}

	public function get($srv, $args=FALSE)
	{
		$args || $args = array();
		$args['srv'] = $srv;
		$args && ($args = '?' . http_build_query($args)) || $args = '';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->base_url . $args);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);

		$data = curl_exec($ch);
		$info = curl_getinfo($ch);

		curl_close($ch);

		$object = $this->xml_to_object($data);

		return $object;
	}

	public function xml_to_object($xml)
	{
		try
		{
			$xml_object = new SimpleXMLElement($xml);

			if ($xml_object == FALSE)
			{
				return FALSE;
			}
		}
		catch (Exception $e)
		{
			return FALSE;
		}

		return $xml_object;
	}
}
