<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TokenStorageService
{
    protected $storageDisk;
    protected $basePath;

    public function __construct()
    {
        $this->storageDisk = 'local';
        $this->basePath = 'tokens';
    }

    /**
     * Save token to storage
     */
    public function saveToken($userId, $token, $userData = null): bool
    {
        try {
            $tokenData = [
                'token' => $token,
                'user_id' => $userId,
                'user_data' => $userData,
                'created_at' => now()->toISOString(),
                'expires_at' => now()->addDays(30)->toISOString(), // 30 days expiry
            ];

            $filename = $this->getTokenFilename($userId);
            $content = json_encode($tokenData, JSON_PRETTY_PRINT);

            return Storage::disk($this->storageDisk)->put($filename, $content);
        } catch (\Exception $e) {
            Log::error('Error saving token to storage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get token from storage
     */
    public function getToken($userId): ?array
    {
        try {
            $filename = $this->getTokenFilename($userId);
            
            if (!Storage::disk($this->storageDisk)->exists($filename)) {
                return null;
            }

            $content = Storage::disk($this->storageDisk)->get($filename);
            $tokenData = json_decode($content, true);

            // Check if token is expired
            if (isset($tokenData['expires_at']) && now()->gt($tokenData['expires_at'])) {
                $this->deleteToken($userId);
                return null;
            }

            return $tokenData;
        } catch (\Exception $e) {
            Log::error('Error getting token from storage: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete token from storage
     */
    public function deleteToken($userId): bool
    {
        try {
            $filename = $this->getTokenFilename($userId);
            
            if (Storage::disk($this->storageDisk)->exists($filename)) {
                return Storage::disk($this->storageDisk)->delete($filename);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting token from storage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all tokens (for admin purposes)
     */
    public function getAllTokens(): array
    {
        try {
            $tokens = [];
            $files = Storage::disk($this->storageDisk)->files($this->basePath);

            foreach ($files as $file) {
                if (Str::endsWith($file, '.json')) {
                    $content = Storage::disk($this->storageDisk)->get($file);
                    $tokenData = json_decode($content, true);
                    
                    // Check expiry
                    if (isset($tokenData['expires_at']) && now()->gt($tokenData['expires_at'])) {
                        continue;
                    }
                    
                    $tokens[] = $tokenData;
                }
            }

            return $tokens;
        } catch (\Exception $e) {
            Log::error('Error getting all tokens: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clean expired tokens
     */
    public function cleanExpiredTokens(): int
    {
        try {
            $deletedCount = 0;
            $files = Storage::disk($this->storageDisk)->files($this->basePath);

            foreach ($files as $file) {
                if (Str::endsWith($file, '.json')) {
                    $content = Storage::disk($this->storageDisk)->get($file);
                    $tokenData = json_decode($content, true);
                    
                    if (isset($tokenData['expires_at']) && now()->gt($tokenData['expires_at'])) {
                        if (Storage::disk($this->storageDisk)->delete($file)) {
                            $deletedCount++;
                        }
                    }
                }
            }

            return $deletedCount;
        } catch (\Exception $e) {
            Log::error('Error cleaning expired tokens: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get token filename
     */
    protected function getTokenFilename($userId): string
    {
        return $this->basePath . '/user_' . $userId . '_token.json';
    }

    /**
     * Verify token validity
     */
    public function verifyToken($userId, $token): bool
    {
        $tokenData = $this->getToken($userId);
        
        if (!$tokenData) {
            return false;
        }

        return $tokenData['token'] === $token;
    }
}