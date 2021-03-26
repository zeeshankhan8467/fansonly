<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Message as Msg;
use App\User;

class Message extends Component
{

    public $messages;
    public $message;
    public $toName;
    public $toUserId;

    protected $listeners = ['scrollTolast'];

    public function scrollToLast()
    {
        $this->emit('scroll-to-last');
    }

    public function mount()
    {
        $message = '';
    }

    public function sendMessage($message)
    {
        if (empty(trim($message))) {
            $this->emit('growl', [
                'title' => __('messages.enterSomething'),
            ]);
        }

        // add the new msg to db
        $msg = new Msg;
        $msg->from_id = auth()->id();
        $msg->to_id = $this->toUserId;
        $msg->message = $message;
        $msg->save();

        // reset this message
        $this->message = '';
        $this->messages = $this->getMessages($this->toUserId);

        $this->emit('scroll-to-last');
        $this->emit('reset-message');
    }

    public function openConversation($user)
    {
        // get recipient
        $toName = User::select('name')->where('id', $user)->first();

        // set messages
        $messages = $this->getMessages($user);

        $this->messages = $messages;
        $this->toName = $toName->name;
        $this->toUserId = $user;

        // $this->emit('scroll-to-last');
    }

    public function render()
    {
        // get followers and follows
        $people = $this->getPeople();
        $messages = $this->messages;

        // get last messages toward this user
        $unreadMsg = Msg::select('message', 'from_id', 'is_read')
            ->where('to_id', auth()->id())
            ->orderByDesc('id')
            ->get()
            ->unique('from_id');

        return view('livewire.message', compact('people', 'messages', 'unreadMsg'));
    }

    private function getPeople()
    {
        // get followers and follows with total messages
        $people = auth()->user()
            ->whereHas('followings', function ($q) {
                $q->where('following_id', auth()->id());
            })
            ->orWhereHas('followers', function ($q) {
                $q->where('follower_id', auth()->id());
            })
            ->get();

        return $people;
    }

    public function getMessages($user)
    {

        // get prior conversation with this user
        return Msg::where(function ($q) use ($user) {
            $q->where('to_id', auth()->id());
            $q->where('from_id', $user);
        })->orWhere(function ($q) use ($user) {
            $q->where('from_id', auth()->id());
            $q->where('to_id', $user);
        })
            ->with('receiver:id,name', 'sender:id,name')
            ->orderBy('created_at')
            ->get();
    }
}
