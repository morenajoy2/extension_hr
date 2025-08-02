<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\Department;
use App\Models\DepartmentTeam;
use App\Models\Position;
use App\Models\EmploymentType;
use App\Models\Group;
use App\Models\User;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'roles' => Role::all(),
            'departments' => Department::all(),
            'departmentTeams' => DepartmentTeam::all(),
            'positions' => Position::all(),
            'employmentTypes' => EmploymentType::all(),
            'groups' => Group::all(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, User $user)
    {
        $formType = $request->input('form_type');

        if ($formType === 'personal_info') {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_of_date' => 'required|date',
                'contact_number' => 'required|string|max:20',
                'gender' => 'required|in:Male,Female',
                'address' => 'required|string|max:255',
                'school' => 'required|string|max:255',
                'school_address' => 'required|string|max:255',
                'email' => ['nullable','email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);

            $user->update($validated);
        }

        elseif ($formType === 'employment_info') {
            $validated = $request->validate([
                'employee_id' => 'required|numeric',
                'employment_type_id' => 'required|exists:employment_types,id',
                'department_id' => 'required|exists:departments,id',
                'department_team_id' => 'required|exists:department_teams,id',
                'position_id' => 'required|exists:positions,id',
            ]);

            $user->update($validated);
        }

        return redirect()->back()->with('success', 'Profile Information updated successfully!');
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

    public function showProfile()
    {
        $user = Auth::user(); 
        return view('profile.view', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:102400',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Target: public/5/profile_photo/
            $folder = $user->id . '/profile_photo';
            $path = $file->storeAs($folder, $filename, 'public');
            
            $user->photo = $path;

            if (!$path) {
                return back()->withErrors(['photo' => 'Photo upload failed.']);
            }
            
            $user->save();
        }

        return back()->with('success', 'Profile photo updated successfully!');
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->photo = null;
        $user->save();

        return back()->with('success', 'Profile photo deleted successfully!');
    }
}
