<?php

/**
 * Classe TGeoCode
 * Faz requisições de localização utilizando a API Google Geocoding.
 *
 * @version    1.0
 * @package    libs/geocode
 * @author     Guilherme Faht (GURUX)
 */
class TGeoCode {

	const URL = 'http://maps.googleapis.com/maps/api/geocode/json';
	
	private $address;
	private $formatted_address;
	private $lat;
	private $lng;
	private $status;
	
	/**
	 * Método Construtor
	 * @param string $address Endereço que se deseja obter as coordenadas.
	 */
	public function __construct($address) {
		
		$this->address = $address;
	}

	/**
	 * Método getAddress()
	 * Retorna o endereço a ser enviado na requisição.
	 * 
	 * @return string.
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * Método setAddress()
	 * Seta o endereço a ser enviado na requisição.
	 * 
	 * @param string $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}
	
	/**
	 * Método getFormattedAddress()
	 * Retorna o endereço formatado após a requisição.
	 * 
	 * @return string
	 */
	public function getFormattedAddress() {
		return $this->formatted_address;
	}
	
	/**
	 * Método getLat()
	 * Retorna a latitude após a requisição. A latitude está formatada em graus decimais.
	 * 
	 * @return string
	 */
	public function getLat() {
		return $this->lat;
	}
	
	/**
	 * Método getLng()
	 * Retorna a longitude após a requisição. A longitude está formatada em graus decimais.
	 *
	 * @return string
	 */
	public function getLng() {
		return $this->lng;
	}
	
	/**
	 * Método getStatus()
	 * Retorna o status da requisição. 'OK' significa que foi executado com sucesso.
	 * 
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}
	
	
	/**
	 * Método prepareURL()
	 * Prepara a URL para requisição.
	 * 
	 * @param string $address
	 * @return string Retorna a URL formatada.
	 */
	private function prepareURL($address) {
		
		// Paramâmetros enviados na requisição.
		$params = array(
				'address' => $address,
				'sensor'  => FALSE
		);
		
		return self::URL . '?' .http_build_query($params);
	}
		
	/**
	 * Método request()
	 * Faz a requisição ao serviço de Geocoding.
	 */
	public function request() {
		
		// Faz a requisição e decodifica o retorno.
		$result = file_get_contents($this->prepareURL($this->address));
		$this->decode(json_decode($result,TRUE));
	}
	
	/**
	 * Método decodeStatus()
	 * Faz a decodificação do status.
	 * 
	 * @param array $array Array de retorno do geocoding.
	 * @return String contendo o status.
	 */
	private function decodeStatus($array) {
		
		if(count($array) > 0) {
			foreach ($array as $key => $value) {			
				if($key == 'status') {
					return $value;
				}
			}
		}
	}

	/**
	 * Método findMatches()
	 * Responsável por localizar dados no retorno do geocoding.
	 * 
	 * @param array $array Array de retorno do geocoding.
	 * @param string $value Valor a ser procurado.
	 * @return string
	 */
	private function findMatches($array, $value) {
		$found = '';
		array_walk_recursive($array,
			function ($item, $key) use ($value, &$found) {
				if ($value === $key) {
					$found = $item;
				}
			}
		);
		return $found;
	}
	
	/**
	 * Método decode()
	 * Faz a decodificação do resultado.
	 */
	private function decode($array) {
		
		$this->status = $this->decodeStatus($array);
		$this->formatted_address = $this->findMatches($array, 'formatted_address');
		$this->lat = $this->findMatches($array, 'lat');
		$this->lng = $this->findMatches($array, 'lng');
	}
}
?>

