<?php

function getUserPreference($key = null)
{
    $userId = session()->get('id_user');
    if (!$userId) return $key ? 0 : ['tampilkan_varian' => 0];

    $userModel = new \App\Models\M_profil_user();
    $user = $userModel->find($userId);

    $preferences = [
        'tampilkan_varian' => $user['tampilkan_varian'] ?? 0,
    ];

    return $key ? ($preferences[$key] ?? 0) : $preferences;
}
