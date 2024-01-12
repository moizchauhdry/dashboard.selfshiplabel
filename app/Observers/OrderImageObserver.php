<?php

namespace App\Observers;

use App\Models\OrderImage;

class OrderImageObserver {

    /**
     * Handle the OrderImage "deleted" event.
     *
     * @param  \App\Models\OrderImage  $orderImage
     * @return void
     */
    public function deleting(OrderImage $orderImage) {
        if(file_exists(public_path('uploads/'.$orderImage->image))) {
            unlink(public_path('uploads/'.$orderImage->image));
        }
    }
}
