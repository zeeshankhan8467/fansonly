<div>
    <h3 class="title">
    <i class="far fa-envelope"></i> @lang('messages.messages')
    </h3>

    <div class="card">
    <div class="row no-gutters">
    
    <div class="col-4 border-right" id="people-container">
        
        @forelse($people as $p)
        <div class="row no-gutters pt-2 pb-2 border-top">
        <div class="col-12 col-sm-12 col-md-2">
        <div class="profilePicXS mt-0 ml-0 mr-2 ml-2 shadow-sm">
		    <a href="javascript:scrollToLast()" class="select-message-user" wire:click="openConversation({{ $p->id }})">
			    <img src="{{  $p->profile->profilePicture }}" alt="" width="40" height="40" class="select-message-user">
		    </a>
        </div>
        </div>

        <div class="col-12 col-sm-12 col-md-10">
            <a href="javascript:scrollToLast()" class="d-none d-sm-none d-md-block text-dark select-message-user" wire:click="openConversation({{ $p->id }})" >
                {{ $p->profile->name }}
            </a>
            <small>
                <a href="javascript:scrollToLast()" class="text-secondary ml-2 ml-sm-2 ml-md-0 select-message-user" wire:click="openConversation({{ $p->id }})">
                    {{  $p->profile->handle }} 
                </a>
                {{-- <p class="ml-2 ml-sm-2 ml-md-0"> --}}
                @if(isset($unreadMsg) AND count($unreadMsg) AND $lastMsg = $unreadMsg->where('from_id', $p->id)->first()) 
                    @if($lastMsg->is_read == 'No')
                        <strong>
                            {{ substr($lastMsg->message, 0, 55) }}
                            @if(strlen($lastMsg->message) > 55) ... @endif
                        </strong>
                    @else
                        <em>
                            {{ substr($lastMsg->message, 0, 55) }}
                            @if(strlen($lastMsg->message) > 55) ... @endif
                        </em>
                    @endif
                @endif
                {{-- </p> --}}
            </small>
            <br>
        </div>
        </div>
        @empty
            @lang('profile.noSubscriptions')
        @endforelse


        <br>
    </div>

    <div class="col-8 border-top" id="messages-container">

    @if(isset($toName) AND !empty($toName))

    <div class="p-2 text-secondary">
        @lang('messages.to'): {{  $toName }}
    </div>

    @endif

    @if(isset($messages) AND count($messages))
    <div class="row no-gutters" wire:poll.3000ms="openConversation({{ $toUserId  }})">
        @foreach($messages as $msg)
            @if($msg->from_id == auth()->id())
                <div class="col-9 mt-3">
                    <div class="bg-primary text-white p-2 rounded-right">
                        {{  $msg->message }}
                    </div>
                    <small class="text-secondary ml-2">
                        @if($msg->is_read == 'No')
                            <i class="fas fa-check-double"></i> 
                        @else
                            <i class="fas fa-check-circle"></i> 
                        @endif
                        {{ $msg->created_at->diffForHumans() }}
                    </small>
                </div>
            @else
                <div class="col-9 mt-3 offset-3">
                    <div class="bg-secondary text-white p-2 rounded-left">
                        {{ $msg->message }}
                    </div>
                    <div class="text-right">
                        <small class="text-secondary mr-2">
                            @php
                                $msg->is_read = 'Yes';
                                $msg->save();
                            @endphp
                            
                            <small class="text-secondary ml-2">
                                @if($msg->is_read == 'No')
                                    <i class="fas fa-check-double"></i> 
                                @else
                                    <i class="fas fa-check-circle"></i> 
                                @endif
                                {{ $msg->created_at->diffForHumans() }}
                            </small>
                            
                        </small>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif
        
    </div>
    </div>

    @if(isset($toName) AND !empty($toName))
    <div class="row no-gutters">
    <div class="col-8 offset-4">
        <input name="message" id="message-inp" data-id="" class="form-control bg-light p-2 rounded-0" placeholder="@lang('messages.writeAndPressEnter')" wire:keydown.enter="sendMessage($event.target.value)" value="{{ $message }}" wire:model.lazy="message">
    </div>
    </div>
    @endif

    </div><!-- ./row -->
    </div>
</div>