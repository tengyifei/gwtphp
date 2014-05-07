<?php
/*
 * GWTPHP is a port to PHP of the GWT RPC package.
 * 
 * <p>This framework is based on GWT (see {@link http://code.google.com/webtoolkit/ gwt-webtoolkit} for details).</p>
 * <p>Design, strategies and part of the methods documentation are developed by Google Team  </p>
 * 
 * <p>PHP port, extensions and modifications by Rafal M.Malinowski. All rights reserved.<br>
 * Additional modifications, GWT generators and linkers by Yifei Teng. All rights reserved.<br>
 * For more information, please see {@link https://github.com/tengyifei/gwtphp}</p>
 * 
 * 
 * <p>Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at</p>
 * 
 * {@link http://www.apache.org/licenses/LICENSE-2.0 http://www.apache.org/licenses/LICENSE-2.0}
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * @package gwtphp.maps.java.util
 */
class HashMap {
	/**
	 * @var array
	 */
	protected $keys = array();
	
	/**
	 * @var array
	 */
	protected $keyObjects = array();

	public function toArray() {
		$arr = array();
		foreach ($this->keyObjects as $key){
			$arr[$key->stringValue()] = $this->keys[$key->hashCode()]->stringValue();
		}
		return $arr;
	}
	
	public function getKeySet() {
		return $this->keyObjects;
	}
	/**
	 *
	 * @param Object $key
	 * @param Object $value
	 */
	public function put($key,$value) {
		$this->keys[$key->hashCode()] = $value;
		$this->keyObjects[] = $key;
	}

	/**
	 * 
	 *
	 */
	public function clear() {
		$this->keys = array();
		$this->keyObjects = array();
	}

	/**
	 * 
	 *
	 * @return integer
	 */
	public function size() {
		return count($this->keys);
	}

	/**
	 * 
	 *
	 * @param object $key
	 * @return object
	 */
	public function get($key) {
		$hash = $key->hashCode();
		return isset($this->keys[$hash]) ? $this->keys[$hash] : null;
	}
	
	/**
	 * 
	 *
	 * @param Object $key
	 * @return boolean
	 */
	public function containsKey($key) {
		return isset($this->keys[$key->hashCode()]) ? true : false;
	}


}


?>