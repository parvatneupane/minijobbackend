<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;
class UserSettingController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | User Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $query = UserModel::query();



        // Search

        if($request->filled('search')){


            $search = $request->search;


            $query->where(function($q) use($search){


                $q->where(
                    'name',
                    'LIKE',
                    "%$search%"
                )

                ->orWhere(
                    'email',
                    'LIKE',
                    "%$search%"
                );


            });


        }




        // Role filter

        if($request->filled('role')){


            $query->where(
                'role',
                $request->role
            );


        }






        // Active users

        $users = $query

            ->where(
                'status',
                'active'
            )

            ->latest()

            ->get();







        // Pending users


        $pendingUsers = UserModel::where(
            'status',
            'pending'
        )

        ->latest()

        ->get();







        $roles=[

            'admin',

            'client',

            'freelancer'

        ];







        $editUser=null;





        if($request->filled('edit_id')){


            $editUser =
                UserModel::findOrFail(
                    $request->edit_id
                );


        }







        return view(
            'admin.usersettings.index',
            compact(
                'users',
                'pendingUsers',
                'roles',
                'editUser'
            )
        );


    }









    /*
    |--------------------------------------------------------------------------
    | Create User
    |--------------------------------------------------------------------------
    */


    public function store(Request $request)
    {



        $request->validate([


            'name'=>
            'required|string|max:255',


            'email'=>
            'required|email|unique:users,email',


            'password'=>
            'required|min:6',


            'role'=>
            'required|in:admin,client,freelancer',



        ]);







        UserModel::create([


            'name'=>
            $request->name,


            'email'=>
            $request->email,


            'password'=>
            Hash::make(
                $request->password
            ),


            'role'=>
            $request->role,


            'status'=>
            $request->status ?? 'active'


        ]);







        return redirect(
            '/admin/usersettings'
        )
        ->with(
            'success',
            'User created successfully'
        );


    }









   //update user


    public function update(
        Request $request,
        UserModel $user
    )
    {



        $request->validate([


            'name'=>
            'required|string|max:255',


            'email'=>
            'required|email',


            'role'=>
            'required',



        ]);







        $data=[


            'name'=>
            $request->name,


            'email'=>
            $request->email,


            'role'=>
            $request->role,


            'status'=>
            $request->status ?? 'active'


        ];






        if($request->filled('password')){


            $data['password']
            =
            Hash::make(
                $request->password
            );


        }







        $user->update($data);








        return redirect(
            '/admin/usersettings'
        )

        ->with(
            'success',
            'User updated successfully'
        );



    }









    /*
    |--------------------------------------------------------------------------
    | Delete User
    |--------------------------------------------------------------------------
    */


    public function destroy(
        UserModel $user
    )
    {



        // Prevent deleting own account

        if(
            Auth::id()==$user->id
        ){


            return back()
            ->with(
                'error',
                'You cannot delete your own account'
            );


        }






        $user->delete();






        return back()

        ->with(
            'success',
            'User deleted successfully'
        );



    }









    /*
    |--------------------------------------------------------------------------
    | Approve Pending User
    |--------------------------------------------------------------------------
    */


    public function approve(
       int $id
    )
    {



        $user =
        UserModel::findOrFail($id);






        $user->update([


            'status'=>'active'


        ]);







        return response()->json([


            'success'=>true,


            'message'=>
            'User approved successfully'


        ]);



    }









    /*
    |--------------------------------------------------------------------------
    | Login As User
    |--------------------------------------------------------------------------
    */

  
/*
|--------------------------------------------------------------------------
| Impersonate User
|--------------------------------------------------------------------------
*/

public function loginAs(int $id)
{


    $targetUser = UserModel::findOrFail($id);



    // Only admin can impersonate

    if(Auth::user()->role !== 'admin'){

        return back()->with(
            'error',
            'Unauthorized action'
        );

    }




    // Prevent impersonating admin

    if($targetUser->role === 'admin'){


        return back()->with(
            'error',
            'Cannot impersonate another admin'
        );

    }





    // Save current admin session

    Session::put(
        'impersonator_id',
        Auth::id()
    );



    Session::put(
        'is_impersonating',
        true
    );





    // Login as target user

    Auth::login($targetUser);





    return redirect('/dashboard')
        ->with(
            'success',
            'You are now logged in as '.$targetUser->name
        );

}








/*
|--------------------------------------------------------------------------
| Stop Impersonation
|--------------------------------------------------------------------------
*/

public function backToAdmin()
{


    if(!Session::has('impersonator_id')){


        return redirect('/');

    }





    $adminId =
        Session::get(
            'impersonator_id'
        );





    Auth::loginUsingId(
        $adminId
    );





    Session::forget(
        [
            'impersonator_id',
            'is_impersonating'
        ]
    );






    return redirect('/admin/usersettings')
        ->with(
            'success',
            'Returned to admin account'
        );

}




}
