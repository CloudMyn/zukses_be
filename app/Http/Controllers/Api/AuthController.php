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
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
            // Validasi input
            $request->validate([
                'email' => 'required|string', // Changed from email to string to accept both email and phone
                'kata_sandi' => 'required|string|min:8|confirmed',
                'nama_depan' => 'required|string|max:255',
                'nama_belakang' => 'required|string|max:255'
            ]);

            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->email, FILTER_VALIDATE_EMAIL);

            // Validate that email field contains either valid email or phone number
            if (empty(trim($request->email))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau nomor telepon wajib diisi',
                    'errors' => ['email' => ['Email atau nomor telepon wajib diisi']]
                ], 422);
            }

            // Cek apakah email atau nomor telepon sudah digunakan
            if ($isEmail) {
                if (User::where('email', $request->email)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email sudah terdaftar'
                    ], 400);
                }
            } else {
                if (User::where('nomor_telepon', $request->email)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Nomor telepon sudah terdaftar'
                    ], 400);
                }
            }

            // Generate unique username
            $username = $this->generateUniqueUsername($request->nama_depan);

            // Buat pengguna baru
            $user = User::create([
                'username' => $username,
                'email' => $isEmail ? $request->email : null,
                'nomor_telepon' => $isEmail ? null : $request->email, // Jika bukan email, simpan sebagai nomor telepon
                'kata_sandi' => Hash::make($request->kata_sandi),
                'tipe_user' => 'PELANGGAN',
                'status' => 'AKTIF',
                'nama_depan' => $request->nama_depan ?? null,
                'nama_belakang' => $request->nama_belakang ?? null,
                'nama_lengkap' => $request->nama_depan . ' ' . $request->nama_belakang,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $user->createToken('auth_token')->plainTextToken
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
            // Validasi input
            $request->validate([
                'contact' => 'required|string',
                'password' => 'required|string',
                'device_id' => 'required|string',
                'device_name' => 'required|string',
                'operating_system' => 'required|string',
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

            // Validasi password
            if (!Hash::check($request->password, $user->kata_sandi)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 400);
            }

            // Perbarui status login terakhir
            $user->update(['terakhir_login_pada' => now()]);

            // Buat token autentikasi
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan informasi perangkat
            $device = Device::updateOrCreate(
                ['device_id' => $request->device_id],
                [
                    'id_user' => $user->id,
                    'device_name' => $request->device_name,
                    'operating_system' => $request->operating_system,
                    'app_version' => $request->app_version ?? null,
                    'push_token' => $request->push_token ?? null,
                    'adalah_device_terpercaya' => true,
                    'terakhir_aktif_pada' => now(),
                    'dibuat_pada' => now(),
                    'diperbarui_pada' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'device' => new DeviceResource($device)
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
            'contact' => 'required|string'
        ]);

        try {
            // Tentukan apakah kontak adalah email atau nomor telepon
            $isEmail = filter_var($request->contact, FILTER_VALIDATE_EMAIL);
            $verificationType = $isEmail ? 'EMAIL' : 'TELEPON';

            // Cek apakah pengguna sudah terdaftar (jika ini untuk login)
            $user = $isEmail ? User::where('email', $request->contact)->first() : User::where('nomor_telepon', $request->contact)->first();

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
            $otpCode = $this->otpService->generateOtp();

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
                'message' => 'Kode OTP telah dikirim ke ' . ($verificationType === 'EMAIL' ? 'email' : 'nomor telepon'),
                'data' => [
                    'verification_id' => $verification->id,
                    'expires_at' => $verification->kedaluwarsa_pada,
                    'verification_type' => $verificationType
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
            $verification->update(['telah_digunakan' => true]);

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
     * Generate username unik jika username dari Google sudah digunakan
     *
     * @param string $baseName
     * @return string
     */
    private function generateUniqueUsername(string $baseName): string
    {
        $username = Str::slug($baseName);
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = Str::slug($baseName) . $counter;
            $counter++;
        }

        return $username;
    }
}
