<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UploadController extends Controller
{
    public function showUpload()
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
            } else {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->post(env('API_URL') . '/api/v1/file/get', [
                    'company_id'=> Session::get('user.company_id'),
                ]);

                if ($response->successful() && $response->json('success')) {
                    $data = $response->json('data');
                    $file = json_decode(json_encode($data)); // Mengonversi array menjadi objek jika dibutuhkan

                    return view("pages.upload.index", compact("file"));
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

    // public function store(Request $request)
    // {
    //     try {
    //         $upload = $request->file('upload');

    //         $client = new Client();
    //         $res = $client->request('POST', env('API_URL') . '/api/v1/file/upload-excel',  [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . Session::get('token'),
    //             ],
    //             'verify' => false,
    //             'multipart' => [
    //                 [
    //                     'name'     => 'excel_file',
    //                     'contents' => file_get_contents($upload->getPathname()),
    //                     'filename' => 'upload.' . $upload->getClientOriginalExtension(),
    //                 ],
    //             ],
    //         ]);

    //         return redirect()->route('pages.upload.index');
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', $th->getMessage());
    //     }
    // }

//     public function store(Request $request)
// {
//     try {
//         // Validate the file upload
//         $request->validate([
//             'upload' => 'required|file|mimes:xls,xlsx|max:2048', // Adjust max size as needed
//         ]);

//         $upload = $request->file('upload');

//         $client = new Client();
//         $res = $client->request('POST', env('API_URL') . '/api/v1/file/upload-excel',  [
//             'headers' => [
//                 'Authorization' => 'Bearer ' . Session::get('token'),
//             ],
//             'verify' => false,
//             'multipart' => [
//                 [
//                     'name'     => 'excel_file',
//                     'contents' => file_get_contents($upload->getPathname()),
//                     'filename' => 'upload.' . $upload->getClientOriginalExtension(),
//                 ],
//             ],
//         ]);

//         // Check if the response status is 200 OK
//         if ($res->getStatusCode() === 200) {
//              $data = response()->json([
//                 'success' => true,
//                 'message' => 'File uploaded successfully!',
//             ]);

//             return redirect()->route('upload',compact('data'));
//         }
//         else {

//             $data = response()->json([
//                 'success' => false,
//                 'message' => 'Failed to upload file to the external API.',
//             ], 400);

//             return redirect()->route('upload',compact('data'));
//         }


//     } catch (\Throwable $th) {
//         // Return a JSON error response
//         return response()->json([
//             'success' => false,
//             'message' => 'Error: ' . $th->getMessage(),
//         ], 500);
//     }
// }


public function store(Request $request)
{
    try {
        $upload = $request->file('upload');

        $client = new Client();
        $res = $client->request('POST', env('API_URL') . '/api/v1/file/upload-excel',  [
            'headers' => [
                'Authorization' => 'Bearer ' . Session::get('token'),
            ],
            'verify' => false,
            'multipart' => [
                [
                    'name'     => 'excel_file',
                    'contents' => file_get_contents($upload->getPathname()),
                    'filename' => 'upload.' . $upload->getClientOriginalExtension(),
                ],
            ],
        ]);

        return response()->json(['message' => 'File uploaded successfully!']);
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
