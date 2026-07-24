<?php

namespace App\Http\Controllers;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;
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
public function update(Request $request, $id)
{
    $verification = VerificationModel::find($id);

    if (!$verification) {
        return response()->json([
            'success' => false,
            'message' => 'Verification not found'
        ], 404);
    }

    // Store old status
    $oldStatus = $verification->status;

    $validator = Validator::make($request->all(), [

        'full_name' => 'nullable|string|max:255',

        'citizenship_front' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'citizenship_back' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'pan_card' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',

        'status' => 'nullable|in:pending,approved,rejected',

        'remarks' => 'nullable|string',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    if ($request->filled('full_name')) {
        $verification->full_name = $request->full_name;
    }

    if ($request->hasFile('citizenship_front')) {

        if ($verification->citizenship_front) {
            Storage::disk('public')->delete($verification->citizenship_front);
        }

        $verification->citizenship_front = $request->file('citizenship_front')
            ->store('verifications', 'public');
    }

    if ($request->hasFile('citizenship_back')) {

        if ($verification->citizenship_back) {
            Storage::disk('public')->delete($verification->citizenship_back);
        }

        $verification->citizenship_back = $request->file('citizenship_back')
            ->store('verifications', 'public');
    }

    if ($request->hasFile('pan_card')) {

        if ($verification->pan_card) {
            Storage::disk('public')->delete($verification->pan_card);
        }

        $verification->pan_card = $request->file('pan_card')
            ->store('verifications', 'public');
    }

    if ($request->filled('status')) {

        $verification->status = $request->status;

        if ($request->status == 'approved') {
            $verification->verified_at = now();
        } else {
            $verification->verified_at = null;
        }
    }

    if ($request->filled('remarks')) {
        $verification->remarks = $request->remarks;
    }

    $verification->save();

    /*
    |--------------------------------------------------------------------------
    | Send Notification
    |--------------------------------------------------------------------------
    */
    if ($oldStatus != $verification->status) {

        $title = '';
        $message = '';

        if ($verification->status == 'approved') {

            $title = 'Verification Approved';
            $message = 'Congratulations! Your identity verification has been approved.';

        } elseif ($verification->status == 'rejected') {

            $title = 'Verification Rejected';
            $message = 'Your identity verification has been rejected. Please check the remarks and submit again.';

        }

        if ($title != '') {

            // Save notification to database
            NotificationModel::create([
                'user_id' => $verification->user_id,
                'title' => $title,
                'message' => $message
            ]);

            // Send Push Notification
            try {

                $user = UserModel::find($verification->user_id);

                if ($user && !empty($user->fcm_token)) {

                    app(FCMService::class)->sendNotification(
                        $user->fcm_token,
                        $title,
                        $message
                    );

                }

            } catch (\Exception $e) {

                Log::error('Verification Notification Error: ' . $e->getMessage());

            }
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Verification updated successfully',
        'data' => $verification
    ]);
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
