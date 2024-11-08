<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use GuzzleHttp\Client;

class CompanyController extends Controller
{
    public function companyCheck()
    {
        try {
            $token = Session::get('token');

            $response = Http::withHeaders([])->post(env('API_URL') . '/api/v1/company/check', [
                'url'=> request()->root() . "/",
            ]);
            return $response;

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

    public function companyNotFound () {
        return view('pages.company_not_found.index');
    }


     public function showCompany()
        {
            try {
               // panggil controller CompanyController
               $companyController = new CompanyController();
               $companyCheckData = $companyController->companyCheck();

               $data_company = $companyCheckData->json('data');


               Session::put('logo_url', $data_company['logo_url']);

               $token = Session::get('token');


               $response = Http::withHeaders([
                   'Authorization' => 'Bearer ' . $token,
               ])->post(env('API_URL') . '/api/admin/company/get');
//                   dd($response);
               if ($response->successful() && $response->json('success')) {
                   $data = $response->json('data');
                   $data = json_decode($response);
                   $company = $data->data;

                   return view("pages.company.index", compact("company"));
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


         public function showDetailCompany($id)
            {
                try {
                    $token = Session::get('token');

                    $response_detail_company = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])->post(env('API_URL') . '/api/admin/company/view', [
                        'id' => $id,
                    ]);

                    if ($response_detail_company->successful() && $response_detail_company->json('success')) {
                        $data_detail_company = $response_detail_company->json('data');
                        $d_company = json_decode(json_encode($data_detail_company));
                        return view("pages.company.detail.index", compact("d_company"));
                    } else {
                        //$data = $response->json('message');

                        return view("pages.company.detail.index", compact("d_company",));
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



       public function store(Request $request)
       {
           try {
               // Validate and upload files
               $logoPath = null;
               $backgroundPath = null;

               if ($request->hasFile('logo')) {
                   $logo = $request->file('logo');
                   $logoPath = $logo->store('logos', 'public'); // Store in the 'public/logos' directory
               }

               if ($request->hasFile('background_image')) {
                   $backgroundImage = $request->file('background_image');
                   $backgroundPath = $backgroundImage->store('backgrounds', 'public'); // Store in the 'public/backgrounds' directory
               }

               $company_name = $request->company_name;
               $is_active = $request->is_active;
               $url = $request->url;

               $client = new Client();
//                $res = $client->request('POST', env('API_URL') . '/api/admin/company/create', [
//                    'headers' => [
//                        'Authorization' => 'Bearer ' . Session::get('token'),
//                    ],
//                    'verify' => false,
//                    'json' => [
//                        'company_name' => $company_name,
//                        'is_active' => $is_active ? true : false,
//                        'url' => $url,
//                        'logo' => $logoPath ? url('storage/' . $logoPath) : null, // URL to the uploaded logo
//                        'background_image' => $backgroundPath ? url('storage/' . $backgroundPath) : null, // URL to the uploaded background image
//                    ],
//                ]);

                $res = $client->request('POST', env('API_URL') . '/api/admin/company/create', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . Session::get('token'),
                    ],
                    'verify' => false,
                    'multipart' => [
                        [
                            'name' => 'company_name',
                            'contents' => $company_name,
                        ],
                        [
                            'name' => 'is_active',
                            'contents' => $is_active ? true : false,
                        ],
                        [
                            'name' => 'url',
                            'contents' => $url,
                        ],
                        [
                            'name' => 'logo',
                            'contents' => $logoPath ? fopen(storage_path('app/public/' . $logoPath), 'r') : null,
                            'filename' => $logo ? $logo->getClientOriginalName() : null,
                        ],
                        [
                            'name' => 'background_image',
                            'contents' => $backgroundPath ? fopen(storage_path('app/public/' . $backgroundPath), 'r') : null,
                            'filename' => $backgroundImage ? $backgroundImage->getClientOriginalName() : null,
                        ],
                    ],
                ]);


               return redirect()->route('company');

           } catch (\GuzzleHttp\Exception\ClientException $e) {
               $response = $e->getResponse();
               $responseBody = json_decode($response->getBody()->getContents(), true);
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
               return view("pages.company.add.index");
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
                    $response_detail_company = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])->post(env('API_URL') . '/api/admin/company/view', [
                        'id' => $id,
                    ]);

                    if ($response_detail_company->successful() && $response_detail_company->json('success') ) {
                        $data_detail_company = $response_detail_company->json('data');
                        $f_company = json_decode(json_encode($data_detail_company));



                        return view("pages.company.edit.index", compact("f_company",));
                    } else {
                        //$data = $response->json('message');
                       $f_company = [];

                        return view("pages.company.edit.index", compact("f_company"));
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

            public function update(Request $request)
            {
                try {
                    $id = $request->id;
                    $company_name = $request->company_name;
                    $is_active = $request->is_active; // Get active status from checkbox
                    $url = $request->url; // Get the URL

                    // Initialize file paths to null
                    $logoPath = null;
                    $backgroundPath = null;

                    // Check if a logo file is uploaded and store it
                    if ($request->hasFile('logo')) {
                        $logo = $request->file('logo');
                        $logoPath = $logo->store('logos', 'public'); // Store logo in the 'public/logos' directory
                    }

                    // Check if a background image file is uploaded and store it
                    if ($request->hasFile('background_image')) {
                        $backgroundImage = $request->file('background_image');
                        $backgroundPath = $backgroundImage->store('backgrounds', 'public'); // Store background image in the 'public/backgrounds' directory
                    }

                    // Prepare API client
                    $client = new Client();

                    // Send a request to the update API
                    $res = $client->request('POST', env('API_URL') . '/api/admin/company/update',  [
                        'headers' => [
                            'Authorization' => 'Bearer ' . Session::get('token'),
                        ],
                        'verify' => false,
                        'multipart' => [
                            [
                                'name' => 'id',
                                'contents' => $id,
                            ],
                            [
                                'name' => 'company_name',
                                'contents' => $company_name,
                            ],
                            [
                                'name' => 'is_active',
                                'contents' => $is_active ? true : false,
                            ],
                            [
                                'name' => 'url',
                                'contents' => $url,
                            ],
                            // Attach the logo file if available
                            [
                                'name' => 'logo',
                                'contents' => $logoPath ? fopen(storage_path('app/public/' . $logoPath), 'r') : null,
                                'filename' => $logo ? $logo->getClientOriginalName() : null,
                            ],
                            // Attach the background image file if available
                            [
                                'name' => 'background_image',
                                'contents' => $backgroundPath ? fopen(storage_path('app/public/' . $backgroundPath), 'r') : null,
                                'filename' => $backgroundImage ? $backgroundImage->getClientOriginalName() : null,
                            ],
                        ],
                    ]);

                    // Redirect back to the company page after successful update
                    return redirect()->route('company');

                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    // Catch API exception and handle the error
                    $response = $e->getResponse();
                    $responseBody = json_decode($response->getBody()->getContents(), true);
                    $errorMessage = $responseBody['message'] ?? 'Something went wrong.';
                    return redirect()->back()->with('error', $errorMessage);
                } catch (\Throwable $th) {
                    // Catch any other exceptions
                    return redirect()->back()->with('error', $th->getMessage());
                }
            }


}
