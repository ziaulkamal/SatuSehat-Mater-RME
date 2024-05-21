<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdministratorUser;
use App\Models\TransactionPayment;
use App\Models\UserBillingPayment;
use App\Models\UserClientCredential;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DefaultController extends BaseController
{



    public function gatewayApiRequest(Request $request)
    {
        $json = $request->getContent();
        $data = json_decode($json, true);

        try {
            $const_users    = $data['const_users'];
            $organisasi_id  = $data['id'];

            $client = UserClientCredential::where('organisasi_id', $organisasi_id)->count();
            $validate = UserClientCredential::getStatusClient($const_users);

            if ( $client == 1) {
                if ($validate == true) {
                    return $this->token($const_users);
                }else {
                    return response()->json([
                        'due' => true,
                        'message' => 'Maaf Const ID anda mati, silahkan hubungi vendor untuk lebih lanjut !'
                    ], 403);
                }
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID Tidak dikenal'
                ], 400);
            }

        } catch (\Throwable $e) {
           return response()->json([
                'status' => 'error',
                'message' => 'Invalid JSON data'
            ], 500);
        }

    }

    public function fasyankes(Request $request) {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'organisasi_id' => 'required|string|unique:user_client_credential',
            'client_id' => 'required|string|unique:user_client_credential',
            'secret_id' => 'required|string|unique:user_client_credential',
        ]);

        // Jika validasi gagal, kembalikan respon dengan error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buat entri baru dalam tabel user_client_credential
            $userClientCredential = UserClientCredential::create([
                'organisasi_id' => $request->organisasi_id,
                'client_id' => $request->client_id,
                'secret_id' => $request->secret_id,
            ]);

            // Kembalikan respon sukses jika data berhasil disimpan
            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data'    => $userClientCredential
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data failed to save',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function v_fasyankes($const_users) {
        $token      = $this->token($const_users);

        if ($token === 'Const ID Tidak Ditemukan') {
            return $this->d_fasyankes($const_users, false);
        }

        $data       = UserClientCredential::data($const_users);
        $id         = $data->organisasi_id;
        $resources  = 'Organization';
        [ $response, $statusCode ] = $this->getFHIR($id, $resources, $token);
        try {
            $data->nama_fasyankes = $response->name;
            $data->validasi = true;
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Data successfully updated',
                'data'    => [$data->nama_fasyankes, $data->validasi, $data->status]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data failed to Fetching',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function g_fasyankes() {
        $perPage = 10; // Jumlah item per halaman
        $data = UserClientCredential::getAll($perPage);

        return response()->json($data);
    }

    public function d_fasyankes($const_users, $n = true) {
        $data = UserClientCredential::data($const_users);
        if ($n == true) {
            $data->delete();
            return response()->json([
                    'success' => true,
                    'message' => 'Data successfully deleted',
                    'data'    => [$data->nama_fasyankes, 'deleted']
                ], 201);
        }else {
            $data->forceDelete();
            return response()->json([
                    'success' => true,
                    'message' => 'Data successfully permanent deleted',
                    'data'    => [$data->nama_fasyankes, 'permanent']
                ], 201);
        }
    }

    public function c_billing(Request $request) {
        $tanggal_mulai = Carbon::createFromFormat('d-m-Y', $request->tanggal_mulai);

        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'const_users'       => 'required|string|unique:billing_payment_due',
            'tanggal_mulai'     => 'required',
            'harga_awal'        => 'required',  // Ubah ke numeric jika 'total_bayar' adalah angka
            'harga_langganan'   => 'required',  // Ubah ke numeric jika 'total_bayar' adalah angka
            'jenis_fasyankes'   => 'required|numeric',  // Ubah ke numeric jika 'total_bayar' adalah angka

        ]);

        // Jika validasi gagal, kembalikan respon dengan error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Buat entri baru dalam tabel user_billing_payment
            $userBillingPayment = UserBillingPayment::create([
                'const_users'       => $request->const_users,
                'tanggal_mulai'     => $tanggal_mulai,
                'jenis_fasyankes'   => $request->jenis_fasyankes,
                'harga_awal'        => (int) str_replace(['Rp.', '.', ','], '', $request->harga_awal),
                'harga_langganan'   => (int) str_replace(['Rp.', '.', ','], '', $request->harga_langganan),
                'total_bayar'       => 0,
                'status'            => 'active',
                'jatuh_tempo'       => $tanggal_mulai->copy()->addMonths(1)->endOfMonth()->startOfDay(),
            ]);

            $datas = [
                'const_users' => $request->const_users,
                'total_bayar' => (int) str_replace(['Rp.', '.', ','], '', $request->harga_awal),
            ];

            $this->c_payment('first', $request->const_users);
            // Kembalikan respon sukses jika data berhasil disimpan
            return response()->json([
                'success' => true,
                'message' => 'Data successfully saved',
                'data' => $userBillingPayment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data failed to save',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function gb_fasyankes() {
        $data = UserClientCredential::getActive();
        return response()->json($data);
    }

    public function g_billing() {
        $perPage = 10; // Jumlah item per halaman
        $data = UserBillingPayment::getAll($perPage);

        return response()->json($data);
    }

    public function c_payment($conditional, $const_users) {
        $data = UserBillingPayment::where('const_users', $const_users)->first();
        try {
            if ($conditional == 'first') {
                return TransactionPayment::create([
                    'transaction_id' => 'TRX-' . Str::uuid(date('d-m-Y H:i:s')),
                    'const_users'    => $const_users,
                    'total_bayar'    => $data->harga_awal,
                    'status'         => 0
                ]);
            }elseif($conditional == date('Y-m-d')) {
                $data->jatuh_tempo = Carbon::parse($data->jatuh_tempo)->addMonths(1)->format('Y-m-d');
                $data->status = 'active';
                $data->total_bayar += 1;
                $con = $data->save();
                if ($con) {
                    TransactionPayment::create([
                        'transaction_id' => 'TRX-' . Str::uuid(date('d-m-Y H:i:s')),
                        'const_users'    => $const_users,
                        'total_bayar'    => $data->harga_langganan,
                        'status'         => 0
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data successfully saved',
                    'data' => $data,
                    'con'   => $con
                ], 201);
            }else {
                return response()->json([
                'success' => false,
                'message' => 'Parameter salah !',
            ], 499);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data failed to save',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function d_payment($const_users) {
        $list       = TransactionPayment::detail($const_users);
        $profile    = UserBillingPayment::detail($const_users);
        try {
            $fasyankes = $list[0]['client']->nama_fasyankes;
            return response()->json([
                'fasyankes' => $fasyankes,
                'profile'   => $profile,
                'data'      => $list
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data failed to get',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function u_status($const_users, $status) {
        $condition = UserBillingPayment::actionStatus($const_users, $status);
        $message = $status != 'active' ? 'Di Tangguhkan' : 'Di Aktivasi';
        try {
            return response()->json([
                'success' => true,
                'message' => 'License Telah '. $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Const ID tidak ditemukan !',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:administrator_user,email',
            'password' => 'required|min:6',
            'secret_key' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $secret_key = 'codepad@usa.com';
        if ($request->input('secret_key') !== $secret_key) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid secret key',
            ], 403);
        }

        try {
            $data = [
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ];

            $result = AdministratorUser::createAccount($data);

            return response()->json([
                'success' => true,
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function v_login(Request $request) {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        try {
            $ip_address = file_get_contents("http://ipecho.net/plain");
            $user_agent = $request->header('User-Agent');
            $token = AdministratorUser::tryLogin($request->email, $request->password, $ip_address);
            $data = AdministratorUser::validateOne($request->email);
            if ($token) {
                if ($data->condition == true) {
                    $data->token = null;
                    $data->save();

                    return response()->json([
                        'success'   => false,
                        'message'   => 'Akun Anda sedang di akses di perangkat lain'
                    ], 201);

                }
                $data->condition = true;
                $data->save();
                session([
                    'user_id'    => $data->id,
                    'user_email' => $data->email,
                    'condition'  => $data->condition,
                    'time'       => Carbon::now()
                ]);
                // return response()->json([
                //     'success'   => true,
                //     'message'   => 'Silahkan masukan token untuk login',
                // ], 200);
                return response()->json([
                    'success'   => true,
                    'message'   => 'Akses di kenali, Selamat Datang Admin',
                    'redirect'  => route('dashboard')
                ], 200);
            }else {
               return response()->json([
                    'success'   => false,
                    'message'   => 'Access Denied'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => 'Access Denied'
            ], 500);
        }
    }

    public function v_token(Request $request) {
        $users = AdministratorUser::tokenizer($request->userInput);
        if ($users) {


            session([
                'user_id'    => $users->id,
                'user_email' => $users->email,
                'condition'  => $users->condition,
                'time'       => Carbon::now()
            ]);

            // $message = "[LOGIN]\n\n$users";
            // $this->telegramService->sendMessage($message);
            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'redirect' => route('dashboard'),
            ]);
        }
    }

    public function v_destroy() {
        $id = session('user_id');
        $data = AdministratorUser::destroy($id);
        // $message = "[END]\n\n$data";
        // $this->telegramService->sendMessage($message);
        session()->flush();

        return response()->json([
            'success' => true,
            'message' => 'Anda telah logout',
            'redirect' => route('login_page')
        ]);
    }

    public function a_destroy($id) {
        $data = AdministratorUser::destroy($id);
        // $message = "[END]\n\n$data";
        // $this->telegramService->sendMessage($message);
        session()->flush();

        return response()->json([
            'success' => true,
            'message' => 'Sesi Anda Telah Berakhir',
            'redirect' => route('login_page')
        ]);
    }

}
