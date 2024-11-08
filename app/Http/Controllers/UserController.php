<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {

            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $c_password = $request->c_password;
            $roleId = $request->role_id;
            $companyId = $request->company_id;

            // Validate if password and c_password match
            if (!empty($password) && !empty($c_password) && $password !== $c_password) {
                return redirect()->back()->with('error', 'Passwords and Confirm Password do not match.');
            }

            $client = new Client();
            $res = $client->request('POST', env('API_URL') . '/api/v1/user/create',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ],
                'verify' => false,
                'multipart' => [
                    [
                        'name'     => 'name',
                        'contents' => $name,
                    ],
                    [
                        'name'     => 'email',
                        'contents' => $email,
                    ],
                    [
                        'name'     => 'password',
                        'contents' => $password,
                    ],
                    [
                        'name'     => 'c_password',
                        'contents' => $c_password,
                    ],
                    [
                        'name'     => 'roles_id',
                        'contents' => $roleId,
                    ],
                    [
                        'name'     => 'company_id',
                        'contents' => $companyId,
                    ]
                ],
            ]);

            return redirect()->route('user');
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
            $email = $request->email;
            $password = $request->password;
            $c_password = $request->c_password;
            $roleId = $request->role_id;
            $companyId = $request->company_id;

            // Validate if password and c_password match
            if (!empty($password) && !empty($c_password) && $password !== $c_password) {
                return redirect()->back()->with('error', 'Passwords and Confirm Password do not match.');
            }
            $client = new Client();

            // Initialize an empty array for multipart data
            $multipartData = [];

            // Add parameters only if they are not null or empty
            $multipartData[] =
            [
                'name'     => 'id',
                'contents' => $id,
            ];

             // Add parameters only if they are not null or empty
             $multipartData[] =
             [
                'name'     => 'company_id',
                'contents' => $companyId,
            ];

            if (!empty($name)) {
                $multipartData[] = [
                    'name'     => 'name',
                    'contents' => $name,
                ];
            }
            if (!empty($email)) {
                $multipartData[] = [
                    'name'     => 'email',
                    'contents' => $email,
                ];
            }
            if (!empty($password)) {
                $multipartData[] = [
                    'name'     => 'password',
                    'contents' => $password,
                ];
            }
            if (!empty($c_password)) {
                $multipartData[] = [
                    'name'     => 'c_password',
                    'contents' => $c_password,
                ];
            }
            if (!empty($roleId)) {
                $multipartData[] = [
                    'name'     => 'roles_id',
                    'contents' => $roleId,
                ];
            }


            // Make the request
            $res = $client->request('POST', env('API_URL') . '/api/v1/user/update',  [
                'headers' => [
                    'Authorization' => 'Bearer ' . Session::get('token'),
                ],
                'verify' => false,
                'multipart' => $multipartData,
            ]);

            return redirect()->route('user');
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



    public function showUser()
    {
        try {
            $token = Session::get('token');

            // panggil controller CompanyController
            $companyController = new CompanyController();
            $companyCheckData = $companyController->companyCheck();

            if($companyCheckData->json('success') == false) {
                return redirect()->route('company-not-found')->with('error', $companyCheckData->json('message'));
            } else {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get(env('API_URL') . '/api/v1/user/get');

                if ($response->successful() && $response->json('success')) {
                    $data = $response->json('data');
                    $data = json_decode($response);
                    $user = $data->data;
                    // dd($user);
                    return view("pages.user.index", compact("user"));
                }
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

    public function index()
    {
        try {
            $token = Session::get('token');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get(env('API_URL') . '/api/v1/user/get');

            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                $data = json_decode($response);
                $user = $data->data;
                // dd($user);
                return view("pages.index", compact("user"));
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

            // panggil controller CompanyController
            $companyController = new CompanyController();
            $companyCheckData = $companyController->companyCheck();

            if($companyCheckData->json('success') == false) {
                return redirect()->route('company-not-found')->with('error', $companyCheckData->json('message'));
            }
            else {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/roles/get');

                if ($response->successful() && $response->json('success')) {
                    $data = $response->json('data');
                    $f_role = json_decode(json_encode($data)); // Mengonversi array menjadi objek jika dibutuhin

                    $dataCompany  = $companyCheckData->json('data');
                    $company      = json_decode(json_encode($dataCompany)); // Mengonversi array menjadi objek jika dibutuhin

                    return view("pages.user.add.index", compact("f_role", "company"));
                } else {
                    return redirect()->back()->with('error', $response->json('message'));
                }
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


    public function showDetailUser($id)
    {
        try {
            $token = Session::get('token');

              // panggil controller CompanyController
              $companyController = new CompanyController();
              $companyCheckData = $companyController->companyCheck();

              if($companyCheckData->json('success') == false) {
                  return redirect()->route('company-not-found')->with('error', $companyCheckData->json('message'));
              }
              else {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/user/view', [
                    'id' => $id,
                ]);

                if ($response->successful() && $response->json('success')) {
                    $data = $response->json('data');
                    $data = json_decode($response);
                    $d_user = $data->data;

                    return view("pages.user.detail.index", compact("d_user"));
                } else {
                    $data = $response->json('message');

                    return view("pages.user.detail.index", compact("data"));
                }
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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $token = Session::get('token');
            if (!$token) {
                return redirect()->back()->with('error', 'Token tidak ditemukan.');
            }



              // panggil controller CompanyController
              $companyController = new CompanyController();
              $companyCheckData = $companyController->companyCheck();

              if($companyCheckData->json('success') == false) {
                  return redirect()->route('company-not-found')->with('error', $companyCheckData->json('message'));
              }
              else {
                $response_user_detail = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/user/view', [
                    'id' => $id,
                ]);

                $response_role = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/roles/get');

                if ($response_role->successful() && $response_role->json('success') && $response_user_detail->successful() && $response_user_detail->json('success')) {
                    $data_role = $response_role->json('data');
                    $f_role = json_decode(json_encode($data_role));

                    $data_user_detail = $response_user_detail->json('data');
                    $f_user_detail = json_decode(json_encode($data_user_detail));


                    $dataCompany  = $companyCheckData->json('data');
                    $company      = json_decode(json_encode($dataCompany));

                    return view("pages.user.edit.index", compact("f_role", "f_user_detail", "company"));
                } else {
                    return redirect()->back()->with('error', $response_role->json('message'));
                }
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



    public function profile($id)
    {
        try {
            $token = Session::get('token');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->post(env('API_URL') . '/api/v1/user/view', [
                'id' => $id,
            ]);



            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                $data = json_decode($response);
                $d_user = $data->data;


                $response_detail_role = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/roles/view', [
                    'id' => $d_user->role_id
                ]);

                $response_list_function = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/functions/get');

                if ($response_detail_role->successful() && $response_detail_role->json('success') && $response_list_function->successful() && $response_list_function->json('success')) {
                    $data_detail_role = $response_detail_role->json('data');
                    $d_role = json_decode(json_encode($data_detail_role));

                    $data_list_function = $response_list_function->json('data');
                    $d_list_function = json_decode(json_encode($data_list_function));

                    return view("pages.profile.index", compact("d_user", "d_role", "d_list_function"));
                } else {
                    $data = $response->json('message');

                    return view("pages.profile.index", compact("data"));
                }
            } else {
                $data = $response->json('message');

                return view("pages.profile.index", compact("data"));
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



    public function destroy(string $id)
    {
        //
    }
}
