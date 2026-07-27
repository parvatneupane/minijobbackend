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

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $freelancers = User::where('role', 'freelancer')->get();
     $category = TaskCategoriesModel::inRandomOrder()->first();

if (!$category) {
    $this->command->error('No task categories found!');
    return;
}

        if ($clients->isEmpty() || $freelancers->isEmpty()) {
            $this->command->error('Please create clients and freelancers first.');
            return;
        }

        foreach (range(1, 20) as $i) {

            $client = $clients->random();
            $freelancer = $freelancers->random();
   
            // Task
            $task = Task::create([
                'user_id' => $client->id,
                'category_id' =>$category->id, // change if needed
                'title' => "Task $i",
                'description' => "Dummy task description",
                'deadline' => now()->addDays(rand(5,30)),
                'required_skills' => 'Laravel, PHP',
                'min_experience' => '2 Years',
                'budget' => rand(5000,50000),
                'status' => 'open',
            ]);

            // Proposal
            $proposal = Proposal::create([
                'task_id' => $task->id,
                'user_id' => $freelancer->id,
                'description' => 'I can complete this project.',
                'takes_time' => rand(5,20),
                'achievement' => '100+ completed projects',
                'status' => 'accepted',
            ]);

            // Contract
            $contract = Contract::create([
                'task_id' => $task->id,
                'proposal_id' => $proposal->id,
                'client_id' => $client->id,
                'freelancer_id' => $freelancer->id,
                'start_date' => now(),
                'deadline' => now()->addDays(15),
                'status' => 'active',
            ]);

            // Submission
            $submission = Submission::create([
                'contract_id' => $contract->id,
                'freelancer_id' => $freelancer->id,
                'message' => 'Project completed successfully.',
                'attachment' => 'submission.zip',
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Payment
            Payment::create([
                'contract_id' => $contract->id,
                'client_id' => $client->id,
                'freelancer_id' => $freelancer->id,
                'amount' => $task->budget,
                'platform_fee' => $task->budget * 0.10,
                'freelancer_amount' => $task->budget * 0.90,
                'payment_method' => 'esewa',
                'transaction_id' => 'TXN'.rand(100000,999999),
                'status' => 'released',
            ]);

            // Review
            Review::create([
                'task_id' => $task->id,
                'contract_id' => $contract->id,
                'client_id' => $client->id,
                'freelancer_id' => $freelancer->id,
                'rating' => rand(4,5),
                'review' => 'Excellent work!',
                'recommended' => true,
            ]);

            // Chat
            $chat = Chat::create([
                'contract_id' => $contract->id,
                'last_message' => 'Looking forward to working with you.',
                'last_message_time' => now(),
            ]);

            // Messages
            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $client->id,
                'message' => 'Please start the project.',
                'message_type' => 'text',
                'is_seen' => 1,
            ]);

            Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $freelancer->id,
                'message' => 'Sure, I will begin today.',
                'message_type' => 'text',
                'is_seen' => 1,
            ]);
        }
    }
}