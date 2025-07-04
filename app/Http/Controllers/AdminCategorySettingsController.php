<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminCategorySettingsController extends Controller
{
    /**
     * Menampilkan daftar semua user dan kategori yang dimiliki.
     */
    public function index()
    {
        // Ambil semua user
        $users = User::all();

        // Ambil relasi kategori dari tabel pivot
        $userCategories = DB::table('category_user')
            ->join('categories', 'category_user.category_id', '=', 'categories.id')
            ->select('category_user.user_id', 'categories.name as category_name')
            ->get()
            ->groupBy('user_id');

        return view('adminRole.categorySettings.index', compact('users', 'userCategories'));
    }

    /**
     * Menampilkan form untuk mengedit kategori akses user tertentu.
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $allCategories = DB::table('categories')->get();

        $assignedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id')
            ->toArray();

        return view('adminRole.categorySettings.create', compact('user', 'allCategories', 'assignedCategoryIds'));
    }

    /**
     * Menyimpan perubahan hak akses kategori user.
     */
    public function updatePermissions(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $categoryIds = $request->input('categories', []);

        DB::table('category_user')->where('user_id', $user->id)->delete();

        $dataToInsert = [];
        foreach ($categoryIds as $categoryId) {
            $dataToInsert[] = [
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($dataToInsert)) {
            DB::table('category_user')->insert($dataToInsert);
        }

        return redirect()->route('admin.users.categories.index')->with('success', 'Hak akses kategori untuk ' . $user->name . ' berhasil diperbarui.');
    }
}
