<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Verification;
use App\Models\Device;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

/**
 * Controller untuk manajemen autentikasi pengguna dan OTP
 *
 * Controller ini menangani proses registrasi, login, update data pengguna,
 * serta pengiriman dan verifikasi kode OTP.
 */
class AuthController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Register pengguna baru
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validasi input - memerlukan contact (email atau nomor telepon)
            $request->validate([
                'contact' => 'required|string'
            ]);

            // Tentukan apakah kontak adalah email atau nomor telepon
            $contact = $request->contact;
            $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);

            $email = null;
            $nomorTelepon = null;
            $contactType = '';

            if ($isEmail) {
                // Contact adalah email
                $email = $contact;
                $contactType = 'EMAIL';

                // Cek apakah email sudah digunakan
                if (User::where('email', $email)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email sudah terdaftar'
                    ], 400);
                }
            } else {
                // Contact adalah nomor telepon
                $nomorTelepon = $contact;
                $contactType = 'TELEPON';

                // Cek apakah nomor telepon sudah digunakan
                if (User::where('nomor_telepon', $nomorTelepon)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nomor telepon sudah terdaftar'
                    ], 400);
                }
            }

            // Generate random password
            $randomPassword = Str::random(12);

            // Generate unique username
            $username = $this->generateUniqueUsername("ZX");

            // Generate nama_lengkap dari contact
            if ($isEmail) {
                $namaLengkap = "USER#" . substr($email, 0, 3) . random_int(1000, 9999);
            } else {
                $namaLengkap = "USER#" . substr($nomorTelepon, -3) . random_int(1000, 9999);
            }

            // Buat pengguna baru
            $user = User::create([
                'username' => $username,
                'email' => $email,
                'nomor_telepon' => $nomorTelepon,
                'kata_sandi' => Hash::make($randomPassword),
                'tipe_user' => 'PELANGGAN',
                'status' => 'AKTIF',
                'nama_lengkap' => $namaLengkap,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ]);

            // Send password via WhatsApp (priority) or email
            $this->sendPasswordToUser($user, $randomPassword);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil. Password telah dikirim ke ' . ($isEmail ? 'email' : 'nomor telepon') . ' Anda.',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $user->createToken('auth_token')->plainTextToken,
                    'contact_type' => $contactType
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login pengguna
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validasi input - hanya contact yang wajib
            $request->validate([
                'contact' => 'required|string',
                'password' => 'nullable|string',
                'otp_code' => 'nullable|string|size:6',
                'device_id' => 'nullable|string',
                'device_name' => 'nullable|string',
                'operating_system' => 'nullable|string',
            ]);

            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);

            // Cari pengguna berdasarkan kontak
            if ($isEmail) {
                $user = User::where('email', $request->contact)->first();
            } else {
                $user = User::where('nomor_telepon', $request->contact)->first();
            }

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }

            $loginSuccess = false;

            // Jika otp_code disediakan, lakukan verifikasi OTP
            if ($request->has('otp_code') && !empty($request->otp_code)) {
                // Cek apakah OTP valid
                $verificationType = $isEmail ? 'EMAIL' : 'TELEPON';
                $verification = Verification::where('nilai_verifikasi', $request->contact)
                    ->where('jenis_verifikasi', $verificationType)
                    ->where('kode_verifikasi', $request->otp_code)
                    ->where('telah_digunakan', false)
                    ->where('kedaluwarsa_pada', '>', now())
                    ->first();

                if ($verification) {
                    // Tandai OTP sudah digunakan
                    $verification->update(['telah_digunakan' => true]);
                    $loginSuccess = true;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
                    ], 400);
                }
            }
            // Jika password disediakan, lakukan verifikasi password
            elseif ($request->has('password') && !empty($request->password)) {
                if (Hash::check($request->password, $user->kata_sandi)) {
                    $loginSuccess = true;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password salah'
                    ], 400);
                }
            }
            // Jika tidak ada OTP maupun password disediakan
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password atau kode OTP diperlukan'
                ], 400);
            }

            if (!$loginSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Autentikasi gagal'
                ], 400);
            }

            // Perbarui status login terakhir
            $user->update(['terakhir_login_pada' => now()]);

            // Buat token autentikasi
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan informasi perangkat jika device_id disediakan
            if ($request->has('device_id')) {
                $device = Device::updateOrCreate(
                    ['device_id' => $request->device_id],
                    [
                        'id_user' => $user->id,
                        'device_name' => $request->device_name ?? 'Unknown Device',
                        'operating_system' => $request->operating_system ?? 'Unknown OS',
                        'app_version' => $request->app_version ?? null,
                        'push_token' => $request->push_token ?? null,
                        'adalah_device_terpercaya' => true,
                        'terakhir_aktif_pada' => now(),
                        'dibuat_pada' => now(),
                        'diperbarui_pada' => now(),
                    ]
                );
            } else {
                $device = null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'device' => $device ? new DeviceResource($device) : null
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kirim kode OTP ke email atau nomor telepon pengguna
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'contact' => 'required|string',
            'type' => 'nullable|in:registration,login,another'
        ]);

        try {
            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);
            $verificationType = $isEmail ? 'EMAIL' : 'TELEPON';

            // Dapatkan tipe OTP (default: another)
            $otpType = $request->type ?? 'another';

            // Cek apakah pengguna sudah terdaftar
            $user = $isEmail ? User::where('email', $request->contact)->first() : User::where('nomor_telepon', $request->contact)->first();

            // Validasi berdasarkan tipe OTP
            if ($otpType === 'registration') {
                // Untuk registrasi, pastikan kontak belum terdaftar
                if ($user) {
                    return response()->json([
                        'success' => false,
                        'message' => $isEmail ? 'Email sudah terdaftar' : 'Nomor telepon sudah terdaftar'
                    ], 400);
                }
            } elseif ($otpType === 'login') {
                // Untuk login, pastikan pengguna sudah terdaftar
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun tidak ditemukan'
                    ], 404);
                }
            }

            $nilai_verifikasi = $request->contact;

            // Set PHP execution time limit to prevent timeout
            set_time_limit(30);

            // Hapus OTP lama yang belum digunakan
            Verification::where('nilai_verifikasi', $nilai_verifikasi)
                ->where('jenis_verifikasi', $verificationType)
                ->where('telah_digunakan', false)
                ->where('kedaluwarsa_pada', '>', now())
                ->delete();

            // Generate kode OTP baru
            $otpCode = 999999;
            // $otpCode = $this->otpService->generateOtp();

            // Simpan kode OTP
            $verification = Verification::create([
                'id_user' => $user ? $user->id : null,
                'jenis_verifikasi' => $verificationType,
                'nilai_verifikasi' => $nilai_verifikasi,
                'kode_verifikasi' => $otpCode,
                'kedaluwarsa_pada' => now()->addMinutes(10), // OTP kadaluarsa dalam 10 menit
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ]);

            // Kirim OTP berdasarkan jenis verifikasi (fire and forget approach)
            try {
                if ($verificationType === 'EMAIL') {
                    // Send email asynchronously to avoid timeout
                    $this->otpService->sendOtpViaEmail($nilai_verifikasi, $otpCode);
                } else {
                    // Jika bukan email, asumsikan sebagai nomor telepon
                    $this->otpService->sendOtpViaSms($nilai_verifikasi, $otpCode);
                }
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan - user bisa request OTP lagi
                Log::warning('Pengiriman OTP gagal: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke ' . ($verificationType === 'EMAIL' ? 'email' : 'nomor telepon') . ' : ' . $otpCode,
                'data' => [
                    'verification_id' => $verification->id,
                    'expires_at' => $verification->kedaluwarsa_pada,
                    'verification_type' => $verificationType,
                    'otp_type' => $otpType
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Logout pengguna
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dapatkan data pengguna saat ini
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lupa password - kirim OTP ke email atau nomor telepon
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'contact' => 'required|string'
        ]);

        try {
            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);

            // Cek apakah pengguna terdaftar
            if ($isEmail) {
                $user = User::where('email', $request->contact)->first();
            } else {
                $user = User::where('nomor_telepon', $request->contact)->first();
            }

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }

            $verificationType = $isEmail ? 'EMAIL' : 'TELEPON';
            $nilai_verifikasi = $request->contact;

            // Hapus OTP lama yang belum digunakan
            Verification::where('nilai_verifikasi', $nilai_verifikasi)
                ->where('jenis_verifikasi', $verificationType)
                ->where('telah_digunakan', false)
                ->where('kedaluwarsa_pada', '>', now())
                ->delete();

            // Generate kode OTP baru
            $otpCode = $this->otpService->generateOtp();

            // Simpan kode OTP
            $verification = Verification::create([
                'id_user' => $user->id,
                'jenis_verifikasi' => $verificationType,
                'nilai_verifikasi' => $nilai_verifikasi,
                'kode_verifikasi' => $otpCode,
                'kedaluwarsa_pada' => now()->addMinutes(10), // OTP kadaluarsa dalam 10 menit
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ]);

            // Kirim OTP berdasarkan jenis verifikasi (fire and forget approach)
            try {
                if ($verificationType === 'EMAIL') {
                    // Send email asynchronously to avoid timeout
                    $this->otpService->sendOtpViaEmail($nilai_verifikasi, $otpCode);
                } else {
                    // Jika bukan email, asumsikan sebagai nomor telepon
                    $this->otpService->sendOtpViaSms($nilai_verifikasi, $otpCode);
                }
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan - user bisa request OTP lagi
                Log::warning('Pengiriman OTP gagal: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim untuk reset password',
                'data' => [
                    'verification_id' => $verification->id,
                    'expires_at' => $verification->kedaluwarsa_pada,
                    'verification_type' => $verificationType
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim OTP untuk reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi OTP untuk reset password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'contact' => 'required|string',
            'otp_code' => 'required|string|size:6'
        ]);

        try {
            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);
            $verificationType = $isEmail ? 'EMAIL' : 'TELEPON';

            // Validasi apakah kode OTP valid
            $verification = $this->otpService->validateOtp($request->contact, $request->otp_code, $verificationType);

            if (!$verification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
                ], 400);
            }

            // Tandai OTP sudah digunakan
            // $verification->update(['telah_digunakan' => true]);

            return response()->json([
                'success' => true,
                'message' => 'OTP terverifikasi, silakan atur password baru',
                'data' => [
                    'user_id' => $verification->id_user
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password setelah OTP terverifikasi
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // Update password pengguna
            $user->update([
                'kata_sandi' => Hash::make($request->new_password),
                'diperbarui_pada' => now(),
            ]);

            // Hapus semua token Sanctum agar pengguna harus login ulang
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mereset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Redirect ke Google untuk autentikasi
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleGoogleCallback(Request $request): JsonResponse
    {
        try {
            // Mendapatkan user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar berdasarkan Google ID
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Jika user sudah ada, perbarui informasi dari Google
                $user->update([
                    'nama_depan' => $googleUser->user['given_name'] ?? $user->nama_depan,
                    'nama_belakang' => $googleUser->user['family_name'] ?? $user->nama_belakang,
                    'url_foto_profil' => $googleUser->avatar,
                    'email_terverifikasi_pada' => $user->email_terverifikasi_pada ?? now(),
                    'diperbarui_pada' => now(),
                ]);
            } else {
                // Jika user belum ada, buat user baru
                $user = User::create([
                    'username' => $this->generateUniqueUsername($googleUser->user['given_name']),
                    'email' => $googleUser->email,
                    'kata_sandi' => Hash::make(Str::random(16)), // Buat password acak
                    'tipe_user' => 'PELANGGAN',
                    'status' => 'AKTIF',
                    'nama_depan' => $googleUser->user['given_name'],
                    'nama_belakang' => $googleUser->user['family_name'] ?? null,
                    'nama_lengkap' => $googleUser->name,
                    'url_foto_profil' => $googleUser->avatar,
                    'email_terverifikasi_pada' => now(),
                    'dibuat_pada' => now(),
                    'diperbarui_pada' => now(),
                ]);
            }

            // Buat token autentikasi
            $token = $user->createToken('google_auth_token')->plainTextToken;

            // Simpan informasi perangkat jika disediakan
            if ($request->has('device_id')) {
                $device = Device::updateOrCreate(
                    ['device_id' => $request->device_id],
                    [
                        'id_user' => $user->id,
                        'device_name' => $request->device_name ?? 'Google Login',
                        'operating_system' => $request->operating_system ?? 'Web',
                        'app_version' => $request->app_version ?? null,
                        'push_token' => $request->push_token ?? null,
                        'adalah_device_terpercaya' => true,
                        'terakhir_aktif_pada' => now(),
                        'dibuat_pada' => now(),
                        'diperbarui_pada' => now(),
                    ]
                );
            } else {
                $device = null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Login dengan Google berhasil',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'device' => $device ? new DeviceResource($device) : null
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate username unik dengan pola konvensional 6 karakter
     *
     * @param string $baseName
     * @return string
     */
    private function generateUniqueUsername(string $baseName): string
    {
        $baseSlug = Str::slug(substr($baseName, 0, 3)); // Ambil 3 karakter pertama dari nama depan
        $randomPart = Str::random(3); // Tambahkan 3 karakter acak
        $username = $baseSlug . $randomPart;

        // Pastikan username unik
        while (User::where('username', $username)->exists()) {
            $randomPart = Str::random(3);
            $username = $baseSlug . $randomPart;
        }

        return $username;
    }

    /**
     * Send password to user via WhatsApp (priority) or email
     *
     * @param User $user
     * @param string $password
     * @return void
     */
    private function sendPasswordToUser(User $user, string $password): void
    {
        // In a real implementation, you would integrate with a WhatsApp API service
        // For now, we'll use a placeholder implementation

        $contact = $user->nomor_telepon ?? ''; // WhatsApp priority
        $message = "Halo {$user->nama_lengkap}, password Anda untuk akun {$user->email} adalah: {$password}. Silakan login dan segera ganti password Anda.";

        // Try to send via WhatsApp first (placeholder implementation)
        $whatsappSent = $this->sendViaWhatsApp($contact, $message);

        // If WhatsApp sending fails, send via email
        if (!$whatsappSent) {
            $this->sendViaEmail($user->email, $message);
        }
    }

    /**
     * Send message via WhatsApp
     *
     * @param string $contact
     * @param string $message
     * @return bool
     */
    private function sendViaWhatsApp(string $contact, string $message): bool
    {
        // This is a placeholder implementation
        // In a real application, integrate with WhatsApp Business API or similar
        try {
            // Log the attempt (in real implementation, call WhatsApp API here)
            Log::info("Attempting to send WhatsApp message to: {$contact}");
            Log::info("Message: {$message}");

            // Simulate WhatsApp sending
            // In real implementation, you would use a service like Twilio, WhatsApp Business API, etc.
            return true; // Assuming success for now

        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message via email
     *
     * @param string $email
     * @param string $message
     * @return void
     */
    private function sendViaEmail(string $email, string $message): void
    {
        // This is a placeholder implementation
        // In a real application, use Laravel's Mail facade
        try {
            Log::info("Sending password email to: {$email}");
            Log::info("Message: {$message}");

            // In real implementation, send actual email using Laravel Mail
            // Mail::to($email)->send(new PasswordMail($message));

        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
        }
    }
}
