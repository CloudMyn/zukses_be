<?php

namespace App\Services;

use App\Models\Verification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengelola pengiriman OTP
 *
 * Class ini menangai pengiriman OTP melalui email atau WhatsApp via Fonnte
 */
class OtpService
{
    /**
     * Kirim OTP melalui email menggunakan Gmail
     *
     * @param string $email
     * @param string $otpCode
     * @return bool
     */
    public function sendOtpViaEmail(string $email, string $otpCode): bool
    {
        try {
            // Create HTML email content with modern green theme
            $htmlContent = $this->generateOtpEmailTemplate($otpCode);

            // Send email using Laravel's Mail facade
            Mail::send([], [], function ($message) use ($email, $htmlContent, $otpCode) {
                $message->to($email)
                        ->subject('Kode OTP Verifikasi - ZUKSES')
                        ->setBody($htmlContent, 'text/html');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP via email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate HTML email template for OTP
     *
     * @param string $otpCode
     * @return string
     */
    private function generateOtpEmailTemplate(string $otpCode): string
    {
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Kode OTP Verifikasi</title>
            <style>
                /* Reset dan styling dasar */
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: #f5f7fa;
                    padding: 20px 0;
                }

                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                }

                /* Header dengan tema hijau */
                .email-header {
                    background: linear-gradient(135deg, #22c55e, #16a34a);
                    padding: 30px;
                    text-align: center;
                    color: white;
                }

                .logo {
                    font-size: 28px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    letter-spacing: 1px;
                }

                .header-title {
                    font-size: 22px;
                    font-weight: 600;
                    margin-bottom: 5px;
                }

                .header-subtitle {
                    font-size: 16px;
                    opacity: 0.9;
                    font-weight: 300;
                }

                /* Konten utama */
                .email-content {
                    padding: 40px 30px;
                    text-align: center;
                }

                .greeting {
                    font-size: 20px;
                    color: #1f2937;
                    margin-bottom: 20px;
                    font-weight: 600;
                }

                .message {
                    font-size: 16px;
                    color: #4b5563;
                    line-height: 1.6;
                    margin-bottom: 30px;
                }

                /* Kotak OTP */
                .otp-box {
                    background-color: #f0fdf4;
                    border: 2px dashed #22c55e;
                    border-radius: 12px;
                    padding: 25px;
                    margin: 25px 0;
                    display: inline-block;
                }

                .otp-code {
                    font-size: 32px;
                    font-weight: 700;
                    color: #16a34a;
                    letter-spacing: 5px;
                    font-family: 'Courier New', monospace;
                }

                .otp-label {
                    font-size: 14px;
                    color: #6b7280;
                    margin-top: 10px;
                    font-weight: 500;
                }

                /* Tombol aksi */
                .action-button {
                    display: inline-block;
                    background-color: #22c55e;
                    color: white !important;
                    text-decoration: none;
                    padding: 14px 30px;
                    border-radius: 8px;
                    font-size: 16px;
                    font-weight: 600;
                    margin: 25px 0;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
                }

                .action-button:hover {
                    background-color: #16a34a;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
                }

                /* Informasi tambahan */
                .info-box {
                    background-color: #f9fafb;
                    border-radius: 8px;
                    padding: 20px;
                    margin: 25px 0;
                    text-align: left;
                    border-left: 4px solid #22c55e;
                }

                .info-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #111827;
                    margin-bottom: 10px;
                }

                .info-list {
                    list-style: none;
                }

                .info-list li {
                    padding: 5px 0;
                    color: #4b5563;
                    font-size: 14px;
                }

                .info-list li::before {
                    content: '• ';
                    color: #22c55e;
                    font-weight: bold;
                }

                /* Footer */
                .email-footer {
                    background-color: #f9fafb;
                    padding: 25px 30px;
                    text-align: center;
                    border-top: 1px solid #e5e7eb;
                }

                .footer-text {
                    color: #6b7280;
                    font-size: 14px;
                    line-height: 1.5;
                }

                .company-name {
                    font-weight: 600;
                    color: #16a34a;
                    margin-top: 10px;
                    display: block;
                }

                /* Responsive */
                @media (max-width: 600px) {
                    .email-container {
                        margin: 0 15px;
                    }

                    .email-header {
                        padding: 25px 15px;
                    }

                    .email-content {
                        padding: 30px 20px;
                    }

                    .greeting {
                        font-size: 18px;
                    }

                    .otp-code {
                        font-size: 28px;
                    }
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <!-- Header -->
                <div class='email-header'>
                    <div class='logo'>ZUKSES</div>
                    <div class='header-title'>Verifikasi Akun Anda</div>
                    <div class='header-subtitle'>Keamanan adalah prioritas utama kami</div>
                </div>

                <!-- Konten Utama -->
                <div class='email-content'>
                    <div class='greeting'>Halo,</div>
                    <div class='message'>Kode verifikasi OTP Anda untuk masuk ke akun ZUKSES. Kode ini hanya berlaku selama 10 menit.</div>

                    <!-- Kotak OTP -->
                    <div class='otp-box'>
                        <div class='otp-code'>{$otpCode}</div>
                        <div class='otp-label'>Kode Verifikasi OTP</div>
                    </div>

                    <div class='message'>Masukkan kode di atas untuk menyelesaikan proses verifikasi Anda. Kode ini hanya berlaku satu kali dan akan kedaluwarsa dalam 10 menit.</div>

                    <!-- Tombol Aksi -->
                    <a href='#' class='action-button'>Verifikasi Sekarang</a>

                    <!-- Informasi Tambahan -->
                    <div class='info-box'>
                        <div class='info-title'>Petunjuk Penggunaan:</div>
                        <ul class='info-list'>
                            <li>Jangan bagikan kode ini ke siapa pun</li>
                            <li>Kode hanya berlaku selama 10 menit</li>
                            <li>Kode hanya dapat digunakan satu kali</li>
                            <li>Jika Anda tidak meminta kode ini, harap abaikan email ini</li>
                        </ul>
                    </div>
                </div>

                <!-- Footer -->
                <div class='email-footer'>
                    <div class='footer-text'>
                        Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                        Jika Anda memiliki pertanyaan, silakan hubungi tim dukungan kami.
                    </div>
                    <span class='company-name'>© " . date('Y') . " ZUKSES. Semua Hak Dilindungi.</span>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Kirim OTP melalui SMS menggunakan layanan Fonnte WhatsApp Blast
     *
     * @param string $phoneNumber
     * @param string $otpCode
     * @return bool
     */
    public function sendOtpViaSms(string $phoneNumber, string $otpCode): bool
    {
        try {
            // Format nomor telepon untuk digunakan dengan API Fonnte
            // Hilangkan karakter non-digit dan pastikan formatnya benar
            $formattedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

            // Jika nomor dimulai dengan '0', ganti dengan '62'
            if (substr($formattedPhone, 0, 1) === '0') {
                $formattedPhone = '62' . substr($formattedPhone, 1);
            }
            // Jika nomor dimulai dengan '62', biarkan apa adanya
            elseif (substr($formattedPhone, 0, 2) !== '62') {
                // Jika nomor tidak dimulai dengan '62' atau '0', asumsikan nomor lokal tanpa kode negara
                $formattedPhone = '62' . $formattedPhone;
            }

            // Pesan OTP
            $message = "Kode OTP Anda adalah: *$otpCode*. Kode ini berlaku selama 10 menit. Jangan berikan kode ini ke siapa pun.";

            // Gunakan API Fonnte untuk mengirim pesan WhatsApp
            $response = Http::withHeaders([
                'Authorization' => config('services.fonnte.auth_token'),
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            // Log aktivitas pengiriman OTP
            Log::info("Mengirim OTP ke: $formattedPhone, response: " . $response->body());

            // Cek apakah permintaan berhasil
            if ($response->successful()) {
                $responseData = $response->json();

                // Cek apakah pesan berhasil dikirim (biasanya Fonnte mengembalikan status tertentu)
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim OTP via Fonnte WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate kode OTP acak
     *
     * @param int $length
     * @return string
     */
    public function generateOtp(int $length = 6): string
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }

        return $otp;
    }

    /**
     * Validasi apakah kode OTP valid
     *
     * @param string $verificationValue
     * @param string $otpCode
     * @param string $verificationType
     * @return Verification|null
     */
    public function validateOtp(string $verificationValue, string $otpCode, string $verificationType = 'EMAIL'): ?Verification
    {
        return Verification::where('nilai_verifikasi', $verificationValue)
            ->where('jenis_verifikasi', $verificationType)
            ->where('kode_verifikasi', $otpCode)
            ->where('telah_digunakan', false)
            ->where('kedaluwarsa_pada', '>', now())
            ->first();
    }

    /**
     * Menentukan apakah nilai verifikasi adalah email atau nomor telepon
     *
     * @param string $value
     * @return string 'EMAIL' or 'TELEPON'
     */
    public function determineVerificationType(string $value): string
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? 'EMAIL' : 'TELEPON';
    }
}
