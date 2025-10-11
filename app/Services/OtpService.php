<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Services;

use App\Models\OtpToken;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OtpService
{
    /**
     * Generate OTP code (6 digit)
     *
     * @return int
     */
    public function generateOtpCode(): int
    {
        return random_int(100000, 999999);
    }

    /**
     * Generate and save OTP token
     *
     * @param  User  $user
     * @param  string  $channel (email|telegram)
     * @param  string  $identifier
     * @param  string  $purpose (activation|login)
     * @return array ['token' => OtpToken, 'otp' => int]
     */
    public function generateAndSave(User $user, string $channel, string $identifier, string $purpose = 'login'): array
    {
        // Generate OTP code
        $otp = $this->generateOtpCode();

        // Hash the OTP
        $tokenHash = Hash::make($otp);

        // Delete old tokens for this user and purpose
        OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->delete();

        // Create new token
        $expiryMinutes = config('otp.expiry_minutes', 5);
        $token = OtpToken::create([
            'user_id' => $user->id,
            'token_hash' => $tokenHash,
            'channel' => $channel,
            'identifier' => $identifier,
            'purpose' => $purpose,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
            'attempts' => 0,
        ]);

        return [
            'token' => $token,
            'otp' => $otp,
        ];
    }

    /**
     * Send OTP via email
     *
     * @param  string  $email
     * @param  int  $otp
     * @param  string  $purpose
     * @return bool
     */
    public function sendViaEmail(string $email, int $otp, string $purpose = 'login'): bool
    {
        try {
            Mail::to($email)->send(new OtpMail($otp, $purpose));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send OTP via Telegram
     *
     * @param  string  $chatId
     * @param  int  $otp
     * @param  string  $purpose
     * @return bool
     */
    public function sendViaTelegram(string $chatId, int $otp, string $purpose = 'login'): bool
    {
        try {
            $botToken = config('otp.telegram_bot_token');

            if (empty($botToken)) {
                Log::warning('Telegram bot token not configured');
                return false;
            }

            $message = $this->formatTelegramMessage($otp, $purpose);

            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to send OTP via Telegram: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format Telegram message
     *
     * @param  int  $otp
     * @param  string  $purpose
     * @return string
     */
    private function formatTelegramMessage(int $otp, string $purpose): string
    {
        $appName = config('app.name', 'OpenDK');

        switch ($purpose) {
            case 'activation':
                $purposeText = 'Aktivasi OTP';
                break;
            case '2fa_activation':
                $purposeText = 'Aktivasi 2FA';
                break;
            default:
                $purposeText = 'Login';
                break;
        }

        return "🔐 <b>{$appName} - {$purposeText}</b>\n\n" .
            "Kode OTP Anda: <code>{$otp}</code>\n\n" .
            "⏰ Berlaku selama " . config('otp.expiry_minutes', 5) . " menit\n" .
            "🔒 Jangan bagikan kode ini kepada siapa pun\n\n" .
            "<i>Jika Anda tidak meminta kode ini, abaikan pesan ini.</i>";
    }

    /**
     * Generate and send OTP
     *
     * @param  User  $user
     * @param  string  $channel
     * @param  string  $identifier
     * @param  string  $purpose
     * @return array
     */
    public function generateAndSend(User $user, string $channel, string $identifier, string $purpose = 'login'): array
    {
        $result = $this->generateAndSave($user, $channel, $identifier, $purpose);
        $otp = $result['otp'];

        $sent = false;
        if ($channel === 'email') {
            $sent = $this->sendViaEmail($identifier, $otp, $purpose);
        } elseif ($channel === 'telegram') {
            $sent = $this->sendViaTelegram($identifier, $otp, $purpose);
        }

        return [
            'token' => $result['token'],
            'sent' => $sent,
        ];
    }

    /**
     * Verify OTP
     *
     * @param  User  $user
     * @param  string  $otp
     * @param  string  $purpose
     * @return array
     */
    public function verify(User $user, string $otp, string $purpose = 'login'): array
    {
        // First check if token exists at all (including expired/max attempts)
        $token = OtpToken::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->first();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ];
        }

        // Check if expired
        if ($token->isExpired()) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ];
        }

        // Check max attempts
        if ($token->hasMaxAttempts()) {
            return [
                'success' => false,
                'message' => 'Maksimal percobaan telah tercapai. Silakan minta kode baru.',
            ];
        }

        if (!Hash::check($otp, $token->token_hash)) {
            $token->incrementAttempts();
            $remainingAttempts = 3 - $token->attempts;

            return [
                'success' => false,
                'message' => "Kode OTP salah. Sisa percobaan: {$remainingAttempts}",
            ];
        }

        // OTP is valid, delete the token
        $token->delete();

        return [
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi',
        ];
    }

    /**
     * Cleanup expired tokens
     *
     * @return int Number of deleted tokens
     */
    public function cleanupExpired(): int
    {
        return OtpToken::where('expires_at', '<', now())->delete();
    }

    /**
     * Verify Telegram chat ID
     *
     * @param  string  $chatId
     * @return bool
     */
    public function verifyTelegramChatId(string $chatId): bool
    {
        try {
            $botToken = config('otp.telegram_bot_token');

            if (empty($botToken)) {
                return false;
            }

            $response = Http::post("https://api.telegram.org/bot{$botToken}/getChat", [
                'chat_id' => $chatId,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Failed to verify Telegram chat ID: ' . $e->getMessage());
            return false;
        }
    }
}
