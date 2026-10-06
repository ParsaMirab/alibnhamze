<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RankEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $search = trim($request->input('search', ''));

        $perPage = (int) $request->input('per_page', 10);

        if (! in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $deputies = User::query()
            ->where('is_admin', true)
            ->where('rank', RankEnum::DEPUTY->value)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('national_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.admins.index', compact(
            'deputies',
            'search',
            'perPage'
        ));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.admins.create');
    }
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_code' => ['required', 'string', 'max:10', 'unique:users,national_code'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'national_code' => $validated['national_code'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'rank' => RankEnum::DEPUTY->value,
        ]);

        return redirect()
            ->route('admin.admin-management.index')
            ->with('success', 'معاون با موفقیت ایجاد شد.');
    }
    public function edit(User $deputy){
        $user = auth()->user();
        $this->authorize('update', $user);
        return view('admin.admins.edit', compact('deputy'));
    }
    public function update(Request $request, User $user){
        $user = auth()->user();
        $this->authorize('update', $user);
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_code' => ['required', 'string', 'max:10'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['string', 'min:8', 'confirmed','nullable'],
        ]);
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'national_code' => $validated['national_code'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()
            ->route('admin.admin-management.index')
            ->with('success', 'معاون با موفقیت ویرایش شد.');
    }
    public function destroy(User $deputy){
        $this->authorize('delete', $deputy);
        if ($deputy->rank === RankEnum::MANAGER->value) {
            return redirect()
                ->route('admin.admin-management.index')
                ->with('error', 'امکان حذف مدیر وجود ندارد.');
        }

        $deputy->delete();

        return redirect()
            ->route('admin.admin-management.index')
            ->with('success', 'معاون با موفقیت حذف شد.');
    }
}
