<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = User::with('role')
            ->whereHas('role', function ($query) {
                $query->whereIn('name', ['kasir', 'gudang']);
            })
            ->latest()
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('name', ['kasir', 'gudang'])
            ->orderBy('name')
            ->get();

        return view('employees.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', ['kasir', 'gudang']),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        User::create($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Akun karyawan berhasil ditambahkan.');
    }

    public function edit(User $employee): View
    {
        $this->ensureEmployeeRole($employee);

        $roles = Role::whereIn('name', ['kasir', 'gudang'])
            ->orderBy('name')
            ->get();

        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        $this->ensureEmployeeRole($employee);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($employee->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->whereIn('name', ['kasir', 'gudang']),
            ],
            'is_active' => ['required', 'boolean'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $employee->update($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Akun karyawan berhasil diperbarui.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        $this->ensureEmployeeRole($employee);

        if (
            $employee->transactions()->exists() ||
            $employee->stockMovements()->exists()
        ) {
            return redirect()
                ->route('employees.index')
                ->with('error', 'Akun tidak dapat dihapus karena sudah memiliki riwayat transaksi atau pergerakan stok. Nonaktifkan akun tersebut sebagai gantinya.');
        }

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Akun karyawan berhasil dihapus.');
    }

    private function ensureEmployeeRole(User $employee): void
    {
        abort_unless(
            $employee->role && in_array(
                $employee->role->name,
                ['kasir', 'gudang'],
                true
            ),
            404
        );
    }
}