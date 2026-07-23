<?php

namespace App\Http\Controllers;

use App\Models\VerificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{

    // Show all verification records
    public function viewIndex()
    {
        $verifications = VerificationModel::with('user')->get();

        return view('admin.verifications.index', compact('verifications'));
    }


    // Create page
    public function create()
    {
        return view('admin.verifications.create');
    }


    // Store verification
    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',

            'citizenship_front' => 'required|image',
            'citizenship_back' => 'required|image',
            'pan_card' => 'nullable|image',
        ]);


        $front = $request->file('citizenship_front')
            ->store('verifications','public');


        $back = $request->file('citizenship_back')
            ->store('verifications','public');


        $pan = null;

        if($request->hasFile('pan_card'))
        {
            $pan = $request->file('pan_card')
                ->store('verifications','public');
        }


        VerificationModel::create([

            'user_id'=>$request->user_id,

            'full_name'=>$request->full_name,

            'citizenship_front'=>$front,

            'citizenship_back'=>$back,

            'pan_card'=>$pan,

            'status'=>'pending'

        ]);


        return redirect()
            ->route('admin.verifications.index')
            ->with('success','Verification created successfully');

    }



    // Show single verification
    public function show($id)
    {

        $verification = VerificationModel::with('user')
            ->findOrFail($id);


        return view(
            'admin.verifications.show',
            compact('verification')
        );

    }




    // Edit page
    public function edit($id)
    {

        $verification = VerificationModel::findOrFail($id);


        return view(
            'admin.verifications.edit',
            compact('verification')
        );

    }





    // Update verification
    public function update(Request $request,$id)
    {

        $verification = VerificationModel::findOrFail($id);



        $request->validate([

            'status'=>'required|in:pending,approved,rejected',

            'remarks'=>'nullable|string'

        ]);



        $verification->update([

            'status'=>$request->status,

            'remarks'=>$request->remarks,

        ]);



        if($request->status == 'approved')
        {
            $verification->verified_at = now();
            $verification->save();
        }



        return redirect()
            ->route('admin.verifications.index')
            ->with('success','Verification updated');

    }





    // Delete verification
    public function destroy($id)
    {

        $verification = VerificationModel::findOrFail($id);



        if($verification->citizenship_front)
        {
            Storage::disk('public')
            ->delete($verification->citizenship_front);
        }


        if($verification->citizenship_back)
        {
            Storage::disk('public')
            ->delete($verification->citizenship_back);
        }


        if($verification->pan_card)
        {
            Storage::disk('public')
            ->delete($verification->pan_card);
        }



        $verification->delete();



        return redirect()
            ->route('admin.verifications.index')
            ->with('success','Verification deleted');

    }

}
