<?php

namespace App\Listeners;

use App\Events\SendMail;
use App\Mail\Subscribe;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Website;
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

        dd($event);
        $subscription = Subscription::find($event->subscriptionId)->toArray();
        $user = User::find($subscription->user_id)->toArray();
        $website = Website::find($subscription->website_id)->toArray();

        $posts = $website->posts();

        foreach($posts as $post){
           // Mail::to($user->email)->send(new Subscribe($user->email, $post->title, $post->description));
        }

    }
}
