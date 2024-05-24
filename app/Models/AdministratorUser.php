<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdministratorUser extends Model implements Authenticatable
{
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
        try {
            // Ambil data user berdasarkan email
            $user = self::where('email', $email)->first();

            // Jika user tidak ditemukan, return false
            if (!$user) {
                return false;
            }

            // Verifikasi password
            if (Hash::check($password, $user->password)) {

                // Login user
                $token = rand(1111,9999);
                $user->token = $token;
                $user->ip_login = $ip;
                $user->save();
                return $token;
            }

            return false; // Password tidak cocok
        } catch (\Exception $e) {
            // Tangani kesalahan
            return false;
        }
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

    public static function reset($email) {
        return self::where('email', $email)
                ->update(['condition' => false]);
    }
}
