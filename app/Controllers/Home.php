<?php

namespace App\Controllers;
// Panggil JWT
use \Firebase\JWT\JWT;
// panggil class Auht
use App\Controllers\Auth;
// panggil restful api codeigniter 4
use CodeIgniter\RESTful\ResourceController;

class Home extends ResourceController
{
	public function __construct()
	{
		// inisialisasi class Auth dengan $this->protect
		$this->protect = new Auth();
	}

	public function index()
	{
		// ambil dari controller auth function public private key
		$secret_key = $this->protect->privateKey();

		$token = null;

		$authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

		$arr = explode(" ", $authHeader);

		$token = $arr[1];

		if ($token) {

			try {

				$decoded = JWT::decode($token, $secret_key, array('HS256'));

				// Access is granted. Add code of the operation here 
				if ($decoded) {
					// response true
					$output = [
						'message' => 'Anda Sudah Masuk Gan'
					];
					return $this->respond($output, 200);
				}
			} catch (\Exception $e) {

				$output = [
					'message' => 'Sorry Gan Anda Tidak Bisa Masuk',
					"error" => $e->getMessage()
				];

				return $this->respond($output, 401);
			}
		}
	}
}
