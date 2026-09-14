<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddressController extends Controller
{
    /**
     * Daftar alamat milik user yang sedang login.
     */
    public function index(): View
    {
        $addresses = auth()->user()->addresses()->get();

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Form tambah alamat baru.
     */
    public function create(Request $request): View
    {
        $returnTo = $request->query('return_to', '');

        return view('addresses.create', compact('returnTo'));
    }

    /**
     * Simpan alamat baru. Satu user hanya boleh punya satu default.
     */
    public function store(AddressRequest $request): RedirectResponse|JsonResponse
    {
        $userId = auth()->id();
        $data = $request->validated();

        $address = DB::transaction(function () use ($data, $userId) {
            $isFirst = Address::where('user_id', $userId)->count() === 0;
            $makeDefault = ($data['is_default'] ?? false) || $isFirst;

            if ($makeDefault) {
                Address::where('user_id', $userId)->update(['is_default' => false]);
            }

            return Address::create([
                ...$data,
                'user_id' => $userId,
                'is_default' => $makeDefault,
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan.',
                'address' => $address,
            ], 201);
        }

        if ($request->input('return_to') === 'checkout') {
            return redirect()->route('checkout', ['address_id' => $address->id])
                ->with('success', 'Alamat berhasil ditambahkan dan dipilih untuk checkout.');
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Form edit alamat (hanya milik sendiri).
     */
    public function edit(Request $request, Address $address): View
    {
        $this->authorizeOwner($address);
        $returnTo = $request->query('return_to', '');

        return view('addresses.edit', compact('address', 'returnTo'));
    }

    /**
     * Update alamat (hanya milik sendiri).
     */
    public function update(AddressRequest $request, Address $address): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($address);

        $data = $request->validated();

        DB::transaction(function () use ($address, $data) {
            // Jika dicentang "jadikan utama", nonaktifkan default lain.
            if (! empty($data['is_default'])) {
                Address::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
                $data['is_default'] = true;
            } else {
                // Jangan biarkan user tanpa alamat default: jika ini satu-satunya
                // alamat, paksa tetap default.
                $otherCount = Address::where('user_id', $address->user_id)
                    ->where('id', '!=', $address->id)
                    ->count();

                $data['is_default'] = $otherCount === 0 ? true : $address->is_default;
            }

            $address->update($data);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diperbarui.',
                'address' => $address->fresh(),
            ]);
        }

        if ($request->input('return_to') === 'checkout') {
            return redirect()->route('checkout', ['address_id' => $address->id])
                ->with('success', 'Alamat berhasil diperbarui.');
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Hapus alamat. Jika yang dihapus adalah default, pilih alamat lain
     * sebagai default baru bila masih tersedia.
     */
    public function destroy(Address $address): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($address);

        $wasDefault = (bool) $address->is_default;
        $userId = $address->user_id;

        DB::transaction(function () use ($address, $wasDefault, $userId) {
            $address->delete();

            if ($wasDefault) {
                $next = Address::where('user_id', $userId)->latest('id')->first();

                if ($next) {
                    $next->update(['is_default' => true]);
                }
            }
        });

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alamat berhasil dihapus.']);
        }

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Jadikan alamat sebagai alamat utama.
     */
    public function setDefault(Address $address): RedirectResponse|JsonResponse
    {
        $this->authorizeOwner($address);

        DB::transaction(function () use ($address) {
            $address->markAsDefault();
        });

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alamat utama berhasil diubah.']);
        }

        // Kembali ke halaman sebelumnya (mendukung alur checkout).
        return back()->with('success', 'Alamat utama berhasil diubah.');
    }

    /**
     * Pastikan alamat milik user yang sedang login.
     * Jangan pernah percaya user_id dari request — ambil dari auth().
     */
    private function authorizeOwner(Address $address): void
    {
        abort_unless($address->user_id === auth()->id(), 403);
    }
}
