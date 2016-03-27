<?php
/**
  * ldap.inc
  *
  * Ldap Plugin for authentication
  *
  * @author David Walker	(azrail@cshr.rit.edu)
  */

class LDAP extends Auth 
{
	/*
	 * ldapAuth
	 *
	 * A means of auth against an LDAP server
	 */
	protected function authenticate ($username, $password)
	{
		global $tcenter;
		global $l_auth;

		$ldapconfig['host'] = 'ldap://ldap.rit.edu';
		$ldapconfig['basedn'] = 'ou=People,dc=rit,dc=edu';

		$ds = ldap_connect($ldapconfig['host'], 639);
		$r = ldap_search( $ds, $ldapconfig['basedn'], 'uid=' . $username);
		if ($r) 
		{
			$result = @ldap_get_entries( $ds, $r);
			if ($result[0]) 
			{
				// Oddly we need to have this check here, since blank passwords passed into
				// ldap bind, return true.  WTF?!
				if (!isset($password) || empty($password))
					return;

				if (@ldap_bind( $ds, $result[0]['dn'], $password) )
				{
					$this->authenticated = true;
					$this->username = $username;
				}
			}
		}
	}
}
