<?php
/*
Ceres Control Panel

This is a control pannel program for Athena and Freya
Copyright (C) 2005 by Beowulf and Nightroad

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

To contact any of the authors about special permissions send
an e-mail to cerescp@gmail.com
*/

class ResultClass {
	var $result;
	var $row;

	function ResultClass($arg1) {
		$this->row = FALSE;
		$this->result = $arg1;
	}

	function fetch_row() {
		if ($this->result !== TRUE && $this->result !== FALSE)
			$this->row = mysqli_fetch_row($this->result);
		else
			$this->row = FALSE;
		return $this->row;
	}

	function fetch_assoc() {
		if ($this->result !== TRUE && $this->result !== FALSE)
			$this->row = mysqli_fetch_assoc($this->result);
		else
			$this->row = FALSE;
		return $this->row;
	}

	function count() {
		if ($this->result)
			return mysqli_num_rows($this->result);
		return 0;
	}

	function row($pos) {
		if (isset($this->row[$pos]))
			return $this->row[$pos];
		return FALSE;
	}

	function free() {
		if (empty($this->result))
			return;
		if ($this->result !== TRUE && $this->result !== FALSE)
			mysqli_free_result($this->result);
	}

}

class QueryClass {
	var $rag_link;
	var $cp_link;
	var $log_link;
	var $stmt;
	var $q, params;

	function QueryClass($rag_addr, $rag_username, $rag_password, $rag_db, $cp_addr, $cp_username, $cp_password, $cp_db, $log_db) {
		global $lang;

		$this->rag_link = mysqli_connect($rag_addr,$rag_username,$rag_password,$rag_db) or die($lang['DB_ERROR']);
		$this->cp_link = mysqli_connect($cp_addr,$cp_username,$cp_password,$cp_db) or die($lang['DB_ERROR']);
		$this->log_link = mysqli_connect($rag_addr,$rag_username,$rag_password,$log_db) or die($lang['DB_ERROR']);
	}

	function Prepare($query, $database) {
		switch ($database) {
			case 0:
				$stmt = $this->rag_link->prepare($query);
				break;
			case 1:
				$stmt = $this->cp_link->prepare($query);
				break;
			case 2:
				$stmt = $this->log_link->prepare($query);
				break;
		}
		
		$q = $query;  // save query
		
		if ($stmt) {
			//var_dump(func_get_args());
			if (func_num_args() >= 4) {  // are there parameters to bind?
				$this->params = array_slice(func_get_args(), 2);
				//array_unshift($params, $types);  // prepend $types
				//var_dump($params);
				call_user_func_array(array($stmt, "bind_param"), $this->params);
			}
		}
		
		return $stmt;
	}
	
	function Query($stmt) {
		$this->stmt = $stmt;  // copy or reference?
		if ($result = $stmt->execute())
			return $stmt->get_result();  // what is returned on a non-SELECT statement?

		return $result;  // FALSE because of failure
	}
	
	/**
	 * Replaces any parameter placeholders in a query with the value of that
	 * parameter. Useful for debugging. Assumes anonymous parameters from 
	 * $params are are in the same order as specified in $query
	 *
	 * @param string $query The sql query with parameter placeholders
	 * @param array $params The array of substitution parameters
	 * @return string The interpolated query
	 */
	function Interpolate() {
		if (!isset($this->q))
			return FALSE;
		$keys = array();

		# build a regular expression for each parameter
		foreach ($this->params as $key => $value) {
			if (is_string($key)) {
				$keys[] = '/:'.$key.'/';
			} else {
				$keys[] = '/[?]/';
			}
		}

		$limit = 1;
		$query = preg_replace($keys, $this->params, $this->q, $limit, $count);

		#trigger_error('replaced '.$count.' keys');

		return $query;
	}

	function finish() {
		if (empty($this->stmt))
			return;
		if ($this->stmt !== TRUE && $this->stmt !== FALSE)
			$this->stmt->close();
	}
}

?>
