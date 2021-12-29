<?php

namespace App\Listeners;

use App\Events\SendMail;
use App\Mail\Subscribe;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Website;
use Illuminate\Contracts\Mail\MailQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Mail;

class SendMailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  \App\Events\SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        $subscriptions = $event->subscriptions;

        if($subscriptions){
            foreach($subscriptions as $subscription){

                $user = User::find($subscription->user_id)->toArray();
                $website = Website::find($subscription->website_id)->toArray();

                // send email for the posts belongs to this website
                $posts = $website->posts();

                foreach($posts as $post){
                    //get send emails to user
                    $mails = $user->mails();
                    foreach($mails as $mail){
                        //send email if already not sent
                        if($post->id != $mail->post_id){
                            // To avoid error Mail server need to be configured
                            print($post);
                            //tested mail sending implementation
                            // Mail::to($user->email)->send(new Subscribe($user->email, $post->title, $post->description));

                            // or  user MailQueue for queueing mail sending
                            // Mail::queue('Html.view', $data, function ($message) {
                            //     $message->from('john@johndoe.com', 'John Doe');
                            //     $message->sender('john@johndoe.com', 'John Doe');
                            //     $message->to('john@johndoe.com', 'John Doe');
                            //     $message->cc('john@johndoe.com', 'John Doe');
                            //     $message->bcc('john@johndoe.com', 'John Doe');
                            //     $message->replyTo('john@johndoe.com', 'John Doe');
                            //     $message->subject('Subject');
                            //     $message->priority(3);
                            //     $message->attach('pathToFile');
                            // });
                        }
                    }
                }

            }
        }
    }
}
