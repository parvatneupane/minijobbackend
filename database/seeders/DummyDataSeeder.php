<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel as User;
use App\Models\TaskModel as Task;
use App\Models\ProposalModel as Proposal;
use App\Models\ContractModel as Contract;
use App\Models\SubmissionModel as Submission;
use App\Models\PaymentModel as Payment;
use App\Models\ReviewModel as Review;
use App\Models\ChatModel as Chat;
use App\Models\MessageModel as Message;
use App\Models\TaskCategoriesModel;
use App\Models\ConflictModel as Conflict;
use App\Models\ConflictReplyModel as ConflictReply;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Check Users
        |--------------------------------------------------------------------------
        */

        $clients = User::where('role', 'client')->get();
        $freelancers = User::where('role', 'freelancer')->get();


        if ($clients->isEmpty() || $freelancers->isEmpty()) {

            $this->command->error(
                'Please create clients and freelancers first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Task Categories If Empty
        |--------------------------------------------------------------------------
        */

        if (TaskCategoriesModel::count() == 0) {

            $categories = [
                'Web Development',
                'Mobile App Development',
                'UI/UX Design',
                'Graphic Design',
                'Digital Marketing',
                'Content Writing',
                'SEO Services',
                'Data Entry',
                'Video Editing',
                'Software Testing',
            ];


            foreach ($categories as $category) {

                TaskCategoriesModel::create([
                    'name' => $category,
                    'status' => 1,
                ]);
            }


            $this->command->info(
                'Task categories created successfully.'
            );
        }



        /*
        |--------------------------------------------------------------------------
        | Generate Dummy Data
        |--------------------------------------------------------------------------
        */

        foreach (range(1, 20) as $i) {


            $client = $clients->random();
            $freelancer = $freelancers->random();


            $category = TaskCategoriesModel::inRandomOrder()->first();


            if (!$category) {

                $this->command->error(
                    'No task categories found!'
                );

                return;
            }



            /*
            |--------------------------------------------------------------------------
            | Task
            |--------------------------------------------------------------------------
            */

            $task = Task::create([

                'user_id' => $client->id,

                'category_id' => $category->id,

                'title' => "Dummy Task $i",

                'description' =>
                    "Dummy task description for task number $i",

                'deadline' =>
                    now()->addDays(rand(5,30)),

                'required_skills' =>
                    'Laravel, PHP, MySQL',

                'min_experience' =>
                    '2 Years',

                'budget' =>
                    rand(5000,50000),

                'status' =>
                    'open',
            ]);




            /*
            |--------------------------------------------------------------------------
            | Proposal
            |--------------------------------------------------------------------------
            */

            $proposal = Proposal::create([

                'task_id' =>
                    $task->id,

                'user_id' =>
                    $freelancer->id,

                'description' =>
                    'I can complete this project professionally.',

                'takes_time' =>
                    rand(5,20),

                'achievement' =>
                    '100+ completed projects',

                'status' =>
                    'accepted',
            ]);





            /*
            |--------------------------------------------------------------------------
            | Contract
            |--------------------------------------------------------------------------
            */

            $contract = Contract::create([

                'task_id' =>
                    $task->id,

                'proposal_id' =>
                    $proposal->id,

                'client_id' =>
                    $client->id,

                'freelancer_id' =>
                    $freelancer->id,

                'start_date' =>
                    now(),

                'deadline' =>
                    now()->addDays(15),

                'status' =>
                    'active',
            ]);






            /*
            |--------------------------------------------------------------------------
            | Submission
            |--------------------------------------------------------------------------
            */

            Submission::create([

                'contract_id' =>
                    $contract->id,

                'freelancer_id' =>
                    $freelancer->id,

                'message' =>
                    'Project completed successfully.',

                'attachment' =>
                    'submission.zip',

                'status' =>
                    'submitted',

                'submitted_at' =>
                    now(),
            ]);






            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            Payment::create([

                'contract_id' =>
                    $contract->id,

                'client_id' =>
                    $client->id,

                'freelancer_id' =>
                    $freelancer->id,

                'amount' =>
                    $task->budget,

                'platform_fee' =>
                    $task->budget * 0.10,

                'freelancer_amount' =>
                    $task->budget * 0.90,

                'payment_method' =>
                    'esewa',

                'transaction_id' =>
                    'TXN' . rand(100000,999999),

                'status' =>
                    'released',
            ]);







            /*
            |--------------------------------------------------------------------------
            | Review
            |--------------------------------------------------------------------------
            */

            Review::create([

                'task_id' =>
                    $task->id,

                'contract_id' =>
                    $contract->id,

                'client_id' =>
                    $client->id,

                'freelancer_id' =>
                    $freelancer->id,

                'rating' =>
                    rand(4,5),

                'review' =>
                    'Excellent work!',

                'recommended' =>
                    true,
            ]);








            /*
            |--------------------------------------------------------------------------
            | Conflict + Replies
            |--------------------------------------------------------------------------
            */

      if (rand(1,3) == 1) {

    $conflict = Conflict::create([
        'contract_id' => $contract->id,
        'raised_by' => $client->id,
        'against_user' => $freelancer->id,
        'raised_by_role' => 'client',
        'title' => 'Project quality issue',
        'reason' => 'The submitted work does not match requirements.',
        'attachment' => 'conflict_attachment.pdf',
        'status' => 'open',
        'admin_response' => null,
    ]);


    ConflictReply::create([
        'conflict_id' => $conflict->id,
        'user_id' => $freelancer->id,
        'message' => 'I will review and fix the issue.',
        'attachment' => null,
    ]);


    ConflictReply::create([
        'conflict_id' => $conflict->id,
        'user_id' => $client->id,
        'message' => 'Please resolve this issue quickly.',
        'attachment' => null,
    ]);
}








            /*
            |--------------------------------------------------------------------------
            | Chat
            |--------------------------------------------------------------------------
            */

            $chat = Chat::create([

                'contract_id' =>
                    $contract->id,

                'last_message' =>
                    'Looking forward to working with you.',

                'last_message_time' =>
                    now(),
            ]);





            Message::create([

                'chat_id' =>
                    $chat->id,

                'sender_id' =>
                    $client->id,

                'message' =>
                    'Please start the project.',

                'message_type' =>
                    'text',

                'is_seen' =>
                    1,
            ]);




            Message::create([

                'chat_id' =>
                    $chat->id,

                'sender_id' =>
                    $freelancer->id,

                'message' =>
                    'Sure, I will begin today.',

                'message_type' =>
                    'text',

                'is_seen' =>
                    1,
            ]);

        }



        $this->command->info(
            'Dummy data created successfully!'
        );
    }
}
