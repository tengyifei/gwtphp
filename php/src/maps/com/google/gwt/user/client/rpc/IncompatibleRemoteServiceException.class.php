<?PHP
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
 * @package gwtphp.maps.com.google.gwt.user.client.rpc
 */
class IncompatibleRemoteServiceException extends Exception implements IsSerializable {
	
	/**
	 *
	 * @var Exception
	 */
	private $couse;
	
	const DEFAULT_MESSAGE = "This application is out of date, please click the refresh button on your browser.";
	
	/**
	 *
	 * @param string $message
	 * @param Exception $couse
	 */
	public function __construct($message,Exception $couse = null) {		
		parent::__construct(self::CONSTANT . "(" . $message . ")",0);
		$this->couse = $couse;
	}
}

?>
