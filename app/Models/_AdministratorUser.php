<?php

namespace App\Models;

use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdministratorUser extends Model implements Authenticatable
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    use AuthenticableTrait;
    protected $table = 'administrator_user';

    protected $fillable = [
        'email',
        'password',
        'ip_login',
        'token',
        'status',
        'condition'
    ];

    protected $hidden = [
        'password'
    ];

        /**
     * Create a new user account.
     *
     * @param array $data
     * @return \App\Models\AdministratorUser
     */
    public static function createAccount($data)
    {
        // Create new user account
        return self::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => $data['status'] ?? 'active',
            'condition' => false,
        ]);
    }

    public static function tryLogin($email, $password, $ip)
    {
        $user = self::where('email', $email)->first();
        // Jika user tidak ditemukan, return false
        if (!$user) {
            $response = [
                'code'      => 404,
                'message'   => "Email tidak terdaftar !"
            ];
            return $response;
        }

        if (Hash::check($password, $user->password)) {
            if ($user->condition == true) {

                $response = [
                    'code'      => 405,
                    'message'   => "Email Ini telah login di perangkat lain !"
                ];
                return $response;
            }

            if ($user->token != null && $user->condition == false) {
                $user->condition = false;
                $user->token = null;
                $user->save();

                $response = [
                    'code'      => 201,
                    'message'   => "Memperbaharui Sesi, silahkan coba kembali !"
                ];
                return $response;
            }

            if ($user->token == null && $user->condition == false) {

                $token = rand(1111,9999);
                $user->token = $token;
                $user->ip_login = $ip;
                $user->save();

                $message = "[INFO] IP $ip berupaya login ke akun anda.\n\n"
                        . "Jangan berikan token ini kepada siapapun. Token anda adalah \n\n"
                        . $token;
                $this->telegramService->sendMessage($message);

                $response = [
                    'code'      => 200,
                    'message'   => "Silahkan masukan Token Untuk Melanjutkan !"
                ];
                return $response;

            }
        }
            $response = [
                'code'      => 404,
                'message'   => "Email tidak terdaftar !"
            ];
            return $response;
    }

    public static function tokenizer($token) {
        $user = self::where('token', $token)->first();
        $user->token = null;
        $user->condition = true;
        $user->save();

        // Tambahkan informasi lain yang diperlukan ke dalam session
        return $user;
    }

    public static function destroy($id) {
        $data = self::find($id);
        $data->condition = false;
        $data->token = null;
        $data->save();
        return $data;
    }

    public static function validateOne($email) {
        $data = self::where('email', $email)
            ->first();
        return $data;
    }
}
