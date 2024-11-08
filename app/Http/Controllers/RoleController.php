<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        try {
            $name = $request->name;
            $is_active = $request->is_active; // Ambil status aktif dari checkbox
            $roles = $request->roles; // Ambil array ID role dari checkbox

            $client = new Client();
            $res = $client->request('POST', env('API_URL') . '/api/v1/roles/create',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ],
                'verify' => false,
                'json' => [ // Gunakan 'json' untuk mengirimkan data sebagai JSON
                    'name' => $name,
                    'is_active' => $is_active ? true : false, // Set true/false sesuai checkbox
                    'functions_id' => $roles ? $roles : [], // Ambil id role yang di ceklis
                ],
            ]);

            return redirect()->route('role');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $id = $request->id;
            $name = $request->name;
            $is_active = $request->is_active; // Ambil status aktif dari checkbox
            $function = $request->functions; // Ambil array ID role dari checkbox

            $client = new Client();
            $res = $client->request('POST', env('API_URL') . '/api/v1/roles/update',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ],
                'verify' => false,
                'json' => [ // Gunakan 'json' untuk mengirimkan data sebagai JSON
                    'id' => $id,
                    'name' => $name,
                    'is_active' => $is_active ? true : false, // Set true/false sesuai checkbox
                    'functions_id' => $function ? $function : [], // Ambil id role yang di ceklis
                ],
            ]);

            return redirect()->route('role');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function showRole()
    {
        try {
            $token = Session::get('token');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/roles/get');

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                $role = json_decode(json_encode($data)); // Mengonversi array menjadi objek jika dibutuhkan

                return view("pages.role.index", compact("role"));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data role.');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    public function create()
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->back()->with('error', 'Token tidak ditemukan.');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/functions/get');

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                $f_role = json_decode(json_encode($data)); // Mengonversi array menjadi objek jika dibutuhin

                return view("pages.role.add.index", compact("f_role"));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data function role.');
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function showDetailRole($id)
    {
        try {
            $token = Session::get('token');
            $response_detail_role = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/roles/view', [
                'id' => $id,
            ]);

            $response_list_function = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/functions/get');

            if ($response_detail_role->successful() && $response_detail_role->json('success') && $response_list_function->successful() && $response_list_function->json('success')) {
                $data_detail_role = $response_detail_role->json('data');
                $d_role = json_decode(json_encode($data_detail_role));

                $data_list_function = $response_list_function->json('data');
                $d_list_function = json_decode(json_encode($data_list_function));

                return view("pages.role.detail.index", compact("d_role", "d_list_function"));
            } else {
                //$data = $response->json('message');
                $d_role = [];
                $d_list_function = [];
                return view("pages.user.detail.index", compact("d_role","d_list_function"));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $token = Session::get('token');
            $response_detail_role = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/roles/view', [
                'id' => $id,
            ]);

            $response_list_function = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/functions/get');

            if ($response_detail_role->successful() && $response_detail_role->json('success') && $response_list_function->successful() && $response_list_function->json('success')) {
                $data_detail_role = $response_detail_role->json('data');
                $d_role = json_decode(json_encode($data_detail_role));

                $data_list_function = $response_list_function->json('data');
                $d_list_function = json_decode(json_encode($data_list_function));

                return view("pages.role.edit.index", compact("d_role", "d_list_function"));
            } else {
                //$data = $response->json('message');
                $d_role = [];
                $d_list_function = [];
                return view("pages.user.edit.index", compact("d_role","d_list_function"));
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Get the response and decode JSON
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Extract error message and redirect back with the message
            $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
