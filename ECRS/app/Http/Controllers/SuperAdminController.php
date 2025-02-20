<?php

namespace App\Http\Controllers;

use App\Mail\AdminAccountCredentials;
use App\Models\Admin;
use App\Models\Branch;
use App\Models\Login;
use App\Models\Programs;
use App\Models\Registration;
use App\Models\SuperAdmin;
use App\Notifications\BatchSuperAdminSendAdminCredentials;
use App\Notifications\SuperAdminSendAdminCredentials;
use Carbon\Carbon;
use App\Models\AuditTrail;
use App\Notifications\BatchFacultySendStudentCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


class SuperAdminController extends Controller
{


    public function superAdminAccountsPage()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('superadmin.superadmin-accounts', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }



    // public function getAdminAccData(Request $request)
    // {
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);
    //     $searchValue = strtolower($request->input('search.value', ''));
    //     $orderColumn = $request->input('order.0.column', '0');
    //     $orderDirection = $request->input('order.0.dir', 'asc');

    //     $columns = [
    //         null,
    //         'Fname',
    //         'Lname',
    //         'email',
    //         'isActive',
    //         'branchDetail.branchDescription',
    //     ];
    //     $orderColumnName = $columns[$orderColumn] ?? 'Lname';

    //     $admins = Admin::with(['login', 'branchDetail']);

    //     if (!empty($searchValue)) {
    //         $admins = $admins->get()->filter(function ($admin) use ($searchValue) {
    //             return stripos(strtolower($admin->Fname), $searchValue) !== false ||
    //                 stripos(strtolower($admin->Lname), $searchValue) !== false ||
    //                 stripos(strtolower($admin->login->email ?? ''), $searchValue) !== false ||
    //                 stripos(strtolower($admin->branchDetail->branchDescription ?? ''), $searchValue) !== false;
    //         });
    //     } else {
    //         $admins = $admins->get();
    //     }

    //     $admins = $admins->sortBy(function ($admin) use ($orderColumnName) {
    //         return strtolower(data_get($admin, $orderColumnName, ''));
    //     }, SORT_REGULAR, $orderDirection === 'desc');

    //     $total = $admins->count();

    //     $adminsData = $admins
    //         ->slice($start, $length)
    //         ->map(function ($admin) {
    //             return [
    //                 'id' => $admin->adminID,
    //                 'Fname' => $admin->Fname,
    //                 'Lname' => $admin->Lname,
    //                 'email' => $admin->login->email ?? 'N/A',
    //                 'status' => $admin->isActive ? 'Active' : 'Inactive',
    //                 'isSentCredentials' => $admin->isSentCredentials,
    //                 'salutation' => $admin->salutation,
    //                 'schoolIDNo' => $admin->schoolIDNo,
    //                 'Mname' => $admin->Mname,
    //                 'Sname' => $admin->Sname,
    //                 'branch' => $admin->branchDetail ? $admin->branchDetail->branchDescription : 'N/A',
    //             ];
    //         });

    //     return response()->json([
    //         'data' => $adminsData->values(),
    //         'recordsTotal' => Admin::count(),
    //         'recordsFiltered' => $total,
    //     ]);
    // }


    public function getAdminAccData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = [
            null,
            'Fname',
            'Lname',
            'login.email',
            'isActive',
            'branchDetail.branchDescription',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'Lname';

        $query = Admin::with(['login', 'branchDetail']);

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->whereRaw('LOWER(Fname) LIKE ?', ["%$searchValue%"])
                    ->orWhereRaw('LOWER(Lname) LIKE ?', ["%$searchValue%"])
                    ->orWhereHas('login', function ($q) use ($searchValue) {
                        $q->whereRaw('LOWER(email) LIKE ?', ["%$searchValue%"]);
                    })
                    ->orWhereHas('branchDetail', function ($q) use ($searchValue) {
                        $q->whereRaw('LOWER(branchDescription) LIKE ?', ["%$searchValue%"]);
                    });
            });
        }

        $totalFiltered = $query->count();

        if ($orderColumnName) {
            $query->orderBy($orderColumnName, $orderDirection);
        }

        $admins = $query->skip($start)->take($length)->get();

        $currentPage = ($start / $length) + 1;
        $totalPages = ceil($totalFiltered / $length);

        $adminsData = $admins->map(function ($admin) {
            return [
                'id' => $admin->adminID,
                'Fname' => $admin->Fname,
                'Lname' => $admin->Lname,
                'email' => $admin->login->email ?? 'N/A',
                'status' => $admin->isActive ? 'Active' : 'Inactive',
                'isSentCredentials' => $admin->isSentCredentials,
                'salutation' => $admin->salutation,
                'schoolIDNo' => $admin->schoolIDNo,
                'Mname' => $admin->Mname,
                'Sname' => $admin->Sname,
                'branch' => $admin->branchDetail ? $admin->branchDetail->branchDescription : 'N/A',
            ];
        });

        return response()->json([
            'data' => $adminsData,
            'recordsTotal' => Admin::count(),
            'recordsFiltered' => $totalFiltered,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }





    public function updateAdmin(Request $request)
    {

        $admin = Admin::find($request->adminID);

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found.',
            ], 404);
        }

        $request->validate([
            'Lname' => 'required|string|max:255',
            'Fname' => 'required|string|max:255',
            'schoolIDNo' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:login_tbl,email,' . $admin->loginID . ',loginID', // Exclude the current user's loginID
            'salutation' => 'required|string|max:255',
        ]);

        $login = Login::find($admin->loginID);

        if (!$login) {
            return response()->json([
                'success' => false,
                'message' => 'Login record not found.',
            ], 404);
        }

        $login->email = $request->email;
        $login->save();

        $admin->Lname = $request->Lname;
        $admin->Fname = $request->Fname;
        $admin->Mname = $request->Mname;
        $admin->Sname = $request->Sname;
        $admin->schoolIDNo = $request->schoolIDNo;
        $admin->salutation = $request->salutation;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Admin information updated successfully!',
        ]);
    }


    public function displaySuperAdminBranchList()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('superadmin.superadmin-branches', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    // public function getBranchesData()
    // {
    //     $branches = Branch::get()->map(function ($branch) {
    //         return [
    //             'id' => $branch->branchID,
    //             'branchDescription' => $branch->branchDescription,
    //         ];
    //     });
    //     return response()->json($branches);
    // }

    public function getBranchesData(Request $request)
    {
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = strtolower($request->input('search.value', ''));
        $orderColumn = $request->input('order.0.column', '0');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = [
            null,
            'branchDescription',
        ];
        $orderColumnName = $columns[$orderColumn] ?? 'branchDescription';

        $branches = Branch::query();

        if (!empty($searchValue)) {
            $branches = $branches->get()->filter(function ($branch) use ($searchValue) {
                return stripos(strtolower($branch->branchDescription), $searchValue) !== false;
            });
        } else {
            $branches = $branches->get();
        }

        $branches = $branches->sortBy(function ($branch) use ($orderColumnName) {
            return strtolower(data_get($branch, $orderColumnName, ''));
        }, SORT_REGULAR, $orderDirection === 'desc');

        $total = $branches->count();

        $branchesData = $branches
            ->slice($start, $length)
            ->map(function ($branch) {
                return [
                    'id' => $branch->branchID,
                    'branchDescription' => $branch->branchDescription,
                ];
            });

        return response()->json([
            'data' => $branchesData->values(),
            'recordsTotal' => Branch::count(),
            'recordsFiltered' => $total,
        ]);
    }






    public function displaySuperAdminActivityLog()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('superadmin.superadmin-act-log', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    // public function getActLogsData(Request $request)
    // {
    //     $loginID = session('loginID');

    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);
    //     $searchValue = $request->input('search.value', '');
    //     $orderColumn = $request->input('order.0.column', '2');
    //     $orderDirection = $request->input('order.0.dir', 'desc');

    //     $columns = ['action', 'description', 'action_time'];
    //     $orderColumnName = $columns[$orderColumn] ?? 'action_time';

    //     $query = AuditTrail::where('record_id', $loginID);

    //     if (!empty($searchValue)) {
    //         $query->where(function ($query) use ($searchValue) {
    //             $query->where('action', 'like', "%{$searchValue}%")
    //                 ->orWhere('description', 'like', "%{$searchValue}%")
    //                 ->orWhere('action_time', 'like', "%{$searchValue}%");
    //         });
    //     }

    //     $totalFiltered = $query->count();

    //     $logs = $query->orderBy($orderColumnName, $orderDirection)
    //         ->orderBy('action_time', 'desc')
    //         ->offset($start)
    //         ->limit($length)
    //         ->get()
    //         ->map(function ($log) {
    //             return [
    //                 'id' => $log->record_id,
    //                 'user' => $log->user,
    //                 'action' => $log->action,
    //                 'table_name' => $log->table_name,
    //                 'old_value' => $log->old_value,
    //                 'new_value' => $log->new_value,
    //                 'description' => $log->description,
    //                 'action_time' => $log->action_time,
    //             ];
    //         });

    //     return response()->json([
    //         'data' => $logs,
    //         'recordsTotal' => AuditTrail::where('record_id', $loginID)->count(),
    //         'recordsFiltered' => $totalFiltered,
    //     ]);
    // }

    public function getActLogsData(Request $request)
    {
        $loginID = session('loginID');

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');
        $orderColumn = $request->input('order.0.column', '2');
        $orderDirection = $request->input('order.0.dir', 'desc');

        $columns = ['action', 'description', 'action_time'];
        $orderColumnName = $columns[$orderColumn] ?? 'action_time';

        $query = AuditTrail::where('record_id', $loginID);

        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('action', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhere('action_time', 'like', "%{$searchValue}%");
            });
        }

        $totalFiltered = $query->count();

        $logs = $query->orderBy($orderColumnName, $orderDirection)
            ->orderBy('action_time', 'desc')
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->record_id,
                    'user' => $log->user,
                    'action' => $log->action,
                    'table_name' => $log->table_name,
                    'old_value' => $log->old_value,
                    'new_value' => $log->new_value,
                    'description' => $log->description,
                    'action_time' => $log->action_time,
                ];
            });

        $currentPage = ($start / $length) + 1;
        $totalPages = ceil($totalFiltered / $length);

        // Return the JSON response
        return response()->json([
            'data' => $logs,
            'recordsTotal' => AuditTrail::where('record_id', $loginID)->count(),
            'recordsFiltered' => $totalFiltered,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }






    public function displaySuperAdminCourseList()
    {
        $loginID = session('loginID');
        $role = session('role');

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $programs = Programs::byBranch()->get();


        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('superadmin.superadmin-courselist', compact('loginID', 'userinfo', 'user', 'role', 'programs', 'notifications', 'unreadCount'));
    }

    public function displaySuperAdminProgramList()
    {
        $loginID = session('loginID');
        $role = session('role');
        $branch = session('branch');

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $programs = Programs::byBranch()->get();


        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('superadmin.superadmin-programlist', compact('loginID', 'userinfo', 'user', 'role', 'programs', 'notifications', 'unreadCount'));
    }


    public function addAdmin(Request $request)
    {
        $request->validate([
            'Lname' => 'required|string|max:255',
            'Fname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'salutation' => 'required|string|max:255',
            'branch' => 'required|integer',
            'schoolIDNo' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['Lname', 'Fname', 'Mname', 'Sname', 'email', 'salutation', 'branch', 'schoolIDNo']);

        // Check if email already exists in login_tbl
        $existingLogin = Login::where('email', $data['email'])->first();

        if ($existingLogin) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already associated with another account!',
            ], 400);
        }

        // Create login record
        $login = new Login();
        $login->email = $data['email'];
        $plainPassword = Str::random(8); // Generate a random password
        $login->password = bcrypt($plainPassword);
        $login->save();

        $loginID = $login->loginID;

        // Create admin record
        $admin = new Admin();
        $admin->Lname = $data['Lname'];
        $admin->Fname = $data['Fname'];
        $admin->Mname = $data['Mname'];
        $admin->Sname = $data['Sname'];
        $admin->branch = $data['branch'];
        $admin->isActive = 0;
        $admin->isSentCredentials = 0;
        $admin->schoolYear = "2024-2025";
        $admin->schoolIDNo = $data['schoolIDNo'];
        $admin->semester = 1;
        $admin->salutation = $data['salutation'];
        $admin->loginID = $loginID;
        $admin->save();

        // Capture new values for audit trail
        $newValues = json_encode([
            'login' => $login->getAttributes(),
            'admin' => $admin->getAttributes(),
        ]);

        $userAdmin = Login::with('superadmin')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->superadmin->Lname . ', ' . $userAdmin->superadmin->Fname;

        // Get admin's name for audit trail user field
        $userAdmin = $admin->Lname . ', ' . $admin->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Create',
            'table_name' => 'admin_tbl, login_tbl',
            'new_value' => $newValues,
            'description' => "Account Admin Created Successfully: $userAdmin",
            'action_time' => Carbon::now(),
        ]);

        $admin = Admin::where('loginID', $loginID)->first();
        if ($admin) {
            $admin->isSentCredentials = 1;
            $admin->save();
        }

        // Notification::route('mail', $login->email)
        //     ->notify(new SuperAdminSendAdminCredentials(
        //         $plainPassword,
        //         $admin->Fname,
        //         $admin->Lname,
        //         $admin->salutation,
        //         $login->email
        //     ));

        Mail::to($login->email)->send(new AdminAccountCredentials(
            $plainPassword,
            $admin->Fname,
            $admin->Lname,
            $admin->salutation,
            $login->email
        ));


        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Admin added successfully!',
        ]);
    }



    public function sendAdminCredentials(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $login = Login::where('email', $request->email)->first();

        if (!$login) {
            return response()->json(['success' => false, 'message' => 'Email not found.']);
        }

        $plainPassword = Str::random(8);

        $hashedPassword = Hash::make($plainPassword);
        $login->password = $hashedPassword;
        $login->save();

        $admin = Admin::where('loginID', $login->loginID)->first();
        if ($admin) {
            $admin->isSentCredentials = 1;
            $admin->save();
        }

        Notification::route('mail', $request->email)
            ->notify(new SuperAdminSendAdminCredentials($plainPassword, $request->fname, $request->lname, $request->salutation, $request->email));

        return response()->json(['success' => true, 'message' => 'Admin Credentials sent successfully.']);
    }

    public function sendBatchAdminCredentials(Request $request)
    {
        $selectedAdminIDs = $request->input('selectedAdminIDs');

        if (is_null($selectedAdminIDs) || !is_array($selectedAdminIDs)) {
            return response()->json(['message' => 'Invalid admin IDs.'], 400);
        }

        $admins = Admin::whereIn('loginID', $selectedAdminIDs)->with('login')->get();

        foreach ($admins as $admin) {
            $plainPassword = Str::random(8);
            $hashedPassword = Hash::make($plainPassword);

            if ($admin->login) {
                $admin->login->password = $hashedPassword;
                $admin->login->save();

                $admin->isSentCredentials = 1;
                $admin->save();

                $admin->login->notify(new BatchSuperAdminSendAdminCredentials(
                    $plainPassword,
                    $admin->Fname,
                    $admin->Lname,
                    $admin->Mname,
                    $admin->Sname,
                    $admin->salutation,
                    $admin->login->email
                ));
            }
        }

        return response()->json(['success' => true, 'message' => 'Batch credentials sent successfully.']);
    }

    public function displayAccountInfo()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();

        return view('settings-acc-info', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    public function displayUpdatePassword()
    {
        $loginID = session('loginID');
        $role = session('role');

        // $user = Login::with('admin')->find($loginID);
        // $userinfo = $user ? $user->admin : null;

        $user = SuperAdmin::with(['login'])
            ->where('loginID', $loginID)
            ->first();

        $userinfo = $user;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $loginID)
            ->latest('created_at')
            ->get()
            ->map(function ($notification) {
                $notification->created_at = Carbon::parse($notification->created_at)->diffForHumans();
                $notificationData = json_decode($notification->data, true);
                $notification->message = $notificationData['data']['message'] ?? 'No message provided.';
                $notification->fileID = $notificationData['data']['fileID'] ?? null;
                return $notification;
            });

        $unreadCount = $notifications->whereNull('read_at')->count();


        return view('settings-pass-info', compact('loginID', 'role', 'user', 'userinfo', 'notifications', 'unreadCount'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|different:currentPassword',
            'confirmPassword' => 'required|same:newPassword'
        ]);

        $user = Login::find($request->loginID);

        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'currentPassword' => ['Your current password is incorrect.']
                ]
            ], 422);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        $userAdmin = Login::with('superadmin')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->superadmin->Lname . ', ' . $userAdmin->superadmin->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Update',
            'table_name' => 'super admin',
            'description' => "User " .$userName. " change password",
            'action_time' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Super Admin password updated successfully!'
        ]);
    }

    public function updatePersonalInfo(Request $request)
    {
        $request->validate([
            'adminID' => 'required|exists:admin_tbl,adminID',
            'loginID' => 'required|exists:login_tbl,loginID',
            'Fname' => 'required|string|max:255',
            'Mname' => 'nullable|string|max:255',
            'Lname' => 'required|string|max:255',
            'Sname' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'salutation' => 'nullable|string|max:255',
        ]);

        $admin = Admin::find($request->adminID);
        $login = Login::find($request->loginID);

        if ($admin && $login) {
            $admin->Fname = $request->Fname;
            $admin->Mname = $request->Mname;
            $admin->Lname = $request->Lname;
            $admin->Sname = $request->Sname;
            $admin->salutation = $request->salutation;
            $admin->save();

            $login->email = $request->email;
            $login->save();

            return response()->json(['success' => true, 'message' => 'Personal information updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Admin or login record not found'], 404);
    }

    public function addBranch(Request $request)
    {
        $request->validate([
            'branchDescription' => 'required|string|max:255',
        ]);

        try {

            $loginID = session('loginID');

            // Create the program
            $branch = Branch::create([
                'branchDescription' => $request->input('branchDescription'),
            ]);

            $userSuperAdmin = Login::with(['superadmin'])
                ->where('loginID', $loginID)
                ->first();

            $userName = $userSuperAdmin->superadmin->Lname . ', ' . $userSuperAdmin->superadmin->Fname;


            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Create',
                'table_name' => 'programs',
                'old_value' => null,
                'new_value' => json_encode([
                    'branchDescription' => $branch->branchDescription
                ]),
                'description' => "Created Branch:{$branch->branchDescription}",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branch added successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add branch: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateBranch(Request $request)
    {
        $request->validate([
            'branchID' => 'required|exists:branch_tbl,branchID',
            'branchDescription' => 'required|string|max:255',
        ]);

        try {
            $branch = Branch::findOrFail($request->input('branchID'));

            $oldValues = [
                'branchDescription' => $branch->branchDescription,
            ];

            // Update the program
            $branch->update([
                'branchDescription' => $request->input('branchDescription'),
            ]);

            $newValues = [
                'branchDescription' => $branch->branchDescription,
            ];

            $userSuperAdmin = Login::with(['superadmin'])
                ->where('loginID', session('loginID'))
                ->first();

            $userName = $userSuperAdmin->superadmin->Lname . ', ' . $userSuperAdmin->superadmin->Fname;

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Update',
                'table_name' => 'programs',
                'old_value' => json_encode($oldValues),
                'new_value' => json_encode($newValues),
                'description' => "Updated Branch: '{$branch->branchDescription}",
                'action_time' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branch updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the branch.',
            ], 500);
        }
    }
}
