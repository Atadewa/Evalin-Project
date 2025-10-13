<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update data teks
        $user->fill($validated);

        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Upload foto profil
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama kalau ada
            if ($user->photo_profil_path && Storage::disk('public')->exists($user->photo_profil_path)) {
                Storage::disk('public')->delete($user->photo_profil_path);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile', 'public');
            $user->photo_profil_path = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo_profil_path) {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($user->photo_profil_path)) {
                Storage::disk('public')->delete($user->photo_profil_path);
            }

            // Reset kolom di database
            $user->photo_profil_path = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-photo-deleted');
    }
}
