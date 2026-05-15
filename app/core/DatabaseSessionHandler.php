<?php
// app/core/DatabaseSessionHandler.php

class DatabaseSessionHandler implements SessionHandlerInterface
{
    public function open($path, $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string|false
    {
        try {
            $row = DB::fetchOne("SELECT data FROM sessions WHERE id = ? AND last_accessed > DATE_SUB(NOW(), INTERVAL 1 DAY)", [$id]);
            if ($row) {
                return (string)$row['data'];
            }
        } catch (Exception $e) {
            // Jika tabel belum dibuat, jangan crash
        }
        return '';
    }

    public function write($id, $data): bool
    {
        try {
            $pdo = DB::pdo();
            // Gunakan REPLACE INTO agar otomatis insert atau update
            $stmt = $pdo->prepare("REPLACE INTO sessions (id, data, last_accessed) VALUES (?, ?, NOW())");
            $stmt->execute([$id, $data]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy($id): bool
    {
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->prepare("DELETE FROM sessions WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function gc($max_lifetime): int|false
    {
        try {
            $pdo = DB::pdo();
            $stmt = $pdo->prepare("DELETE FROM sessions WHERE last_accessed < DATE_SUB(NOW(), INTERVAL ? SECOND)");
            $stmt->execute([$max_lifetime]);
            return true; // Berhasil hapus
        } catch (Exception $e) {
            return false;
        }
    }
}
