<div>
    <h3 class="title">
    <i class="far fa-envelope"></i> <?php echo app('translator')->get('messages.messages'); ?>
    </h3>

    <div class="card">
    <div class="row no-gutters">
    
    <div class="col-4 border-right" id="people-container">
        
        <?php $__empty_1 = true; $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="row no-gutters pt-2 pb-2 border-top">
        <div class="col-12 col-sm-12 col-md-2">
        <div class="profilePicXS mt-0 ml-0 mr-2 ml-2 shadow-sm">
		    <a href="javascript:scrollToLast()" class="select-message-user" wire:click="openConversation(<?php echo e($p->id); ?>)">
			    <img src="<?php echo e($p->profile->profilePicture); ?>" alt="" width="40" height="40" class="select-message-user">
		    </a>
        </div>
        </div>

        <div class="col-12 col-sm-12 col-md-10">
            <a href="javascript:scrollToLast()" class="d-none d-sm-none d-md-block text-dark select-message-user" wire:click="openConversation(<?php echo e($p->id); ?>)" >
                <?php echo e($p->profile->name); ?>

            </a>
            <small>
                <a href="javascript:scrollToLast()" class="text-secondary ml-2 ml-sm-2 ml-md-0 select-message-user" wire:click="openConversation(<?php echo e($p->id); ?>)">
                    <?php echo e($p->profile->handle); ?> 
                </a>
                
                <?php if(isset($unreadMsg) AND count($unreadMsg) AND $lastMsg = $unreadMsg->where('from_id', $p->id)->first()): ?> 
                    <?php if($lastMsg->is_read == 'No'): ?>
                        <strong>
                            <?php echo e(substr($lastMsg->message, 0, 55)); ?>

                            <?php if(strlen($lastMsg->message) > 55): ?> ... <?php endif; ?>
                        </strong>
                    <?php else: ?>
                        <em>
                            <?php echo e(substr($lastMsg->message, 0, 55)); ?>

                            <?php if(strlen($lastMsg->message) > 55): ?> ... <?php endif; ?>
                        </em>
                    <?php endif; ?>
                <?php endif; ?>
                
            </small>
            <br>
        </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php echo app('translator')->get('profile.noSubscriptions'); ?>
        <?php endif; ?>


        <br>
    </div>

    <div class="col-8 border-top" id="messages-container">

    <?php if(isset($toName) AND !empty($toName)): ?>

    <div class="p-2 text-secondary">
        <?php echo app('translator')->get('messages.to'); ?>: <?php echo e($toName); ?>

    </div>

    <?php endif; ?>

    <?php if(isset($messages) AND count($messages)): ?>
    <div class="row no-gutters" wire:poll.3000ms="openConversation(<?php echo e($toUserId); ?>)">
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($msg->from_id == auth()->id()): ?>
                <div class="col-9 mt-3">
                    <div class="bg-primary text-white p-2 rounded-right">
                        <?php echo e($msg->message); ?>

                    </div>
                    <small class="text-secondary ml-2">
                        <?php if($msg->is_read == 'No'): ?>
                            <i class="fas fa-check-double"></i> 
                        <?php else: ?>
                            <i class="fas fa-check-circle"></i> 
                        <?php endif; ?>
                        <?php echo e($msg->created_at->diffForHumans()); ?>

                    </small>
                </div>
            <?php else: ?>
                <div class="col-9 mt-3 offset-3">
                    <div class="bg-secondary text-white p-2 rounded-left">
                        <?php echo e($msg->message); ?>

                    </div>
                    <div class="text-right">
                        <small class="text-secondary mr-2">
                            <?php
                                $msg->is_read = 'Yes';
                                $msg->save();
                            ?>
                            
                            <small class="text-secondary ml-2">
                                <?php if($msg->is_read == 'No'): ?>
                                    <i class="fas fa-check-double"></i> 
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i> 
                                <?php endif; ?>
                                <?php echo e($msg->created_at->diffForHumans()); ?>

                            </small>
                            
                        </small>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
        
    </div>
    </div>

    <?php if(isset($toName) AND !empty($toName)): ?>
    <div class="row no-gutters">
    <div class="col-8 offset-4">
        <input name="message" id="message-inp" data-id="" class="form-control bg-light p-2 rounded-0" placeholder="<?php echo app('translator')->get('messages.writeAndPressEnter'); ?>" wire:keydown.enter="sendMessage($event.target.value)" value="<?php echo e($message); ?>" wire:model.lazy="message">
    </div>
    </div>
    <?php endif; ?>

    </div><!-- ./row -->
    </div>
</div><?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/message.blade.php ENDPATH**/ ?>