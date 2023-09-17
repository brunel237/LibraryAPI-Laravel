<?php

namespace App\Models;

use App\Notifications\BorrowRequestNotification;
use App\Notifications\FailedRequestNotification;
use App\Notifications\PurchaseRequestNotification;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;
//---------------------------------------------------------------------------------------------------------------
protected $primaryKey = 'id';
public $incrementing = false;

// protected $dispatchesEvents = [
//     'deleting' => RequestDeleting::class,
// ];

protected $fillable = [
    'user_id',
    'book_id',
    'payment_id',
    'action',
    'quantity',
    'status',
    'justif',
];

//---------------------------------------------------------------------------------------------------------------

    // public function __construct($attr, $user_id, $book_id, $pay_id, $qty, $action){

    // }
//---------------------------------------------------------------------------------------------------------------

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function book(){
        return $this->belongsTo(Book::class);
    }
    public function payment(){
        return $this->belongsTo(Payment::class);
    }



    public static function booted(){
        static::creating(function($request){
            $request->user_id = auth()->user()->id;
            $sender_id = str_pad($request->user_id, 6, 0, STR_PAD_LEFT);
            $request->id = now()->toDateString()."-".now()->toTimeString()."-{$sender_id}";
        });
    }

//---------------------------------------------------------------------------------------------------------------

    public function confirmRequest($duration=null){
        $resp = json_encode([
            "status" => true,
            "message" => "success"
        ]);


        if ($this->status != "on treatment") return json_encode([
            "status" => false,
            "message" => "This request has already been treated."
        ]);

        $exception = false;

        if ($this->action == "purchase"){

            $total_price = $this->quantity * $this->book->selling_price;
            $difference =  $this->payment->amount -$total_price;

            switch ($difference){
                case "$difference < 0" : {
                    $resp = json_encode([
                        "status" => false,
                        "message" => "Insufficient Amount"
                    ]);
                    break;
                }
                case "$difference == 0" : {
                    $message = "You just sold an article. Thank you";
                    // $this->user->notification()->create([
                    //     'message' => "You just bought {$this->quantity} {$this->book->type}s
                    //     entitled '{$this->book->title}'."
                    // ]);
                    $this->user->notify(new PurchaseRequestNotification($this, $message));
                    $purchase = new Purchase([], $this->id, $total_price);
                    $purchase->save();
                    break;
                }
                default : {
                    $message = "You just bought an article. {$this->difference} difference was
                                credited back to your account. Thank you";
                    $resp =  $this->payment
                            ->account()
                            ->find($this->payment->account_id)
                            ->creditAccount($difference);
                    $this->user->notify(new PurchaseRequestNotification($this, $message));
                    $purchase = new Purchase([], $this->id, $total_price);
                    $purchase->save();
                    // $this->user->notification()->create([
                    //     'message' => "You just bought {$this->quantity} {$this->book->type}s
                    //     entitled '{$this->book->title}'. Your difference {$difference} was
                    //     credited back to your account."
                    // ]);
                    break;
                }

            }

        }
        else {

            $total_price = $this->quantity * $this->book->lending_price ;
            $difference =  $this->payment->amount - $total_price ;

            if (! $this->user->isEligible){
                $resp =  json_encode([
                    "status" => false,
                    "message" => "Not Eligible for this request. "
                ]);
            }else

            switch ($difference){
                case "$difference < 0": {

                    $dept = new DeptStatus([], $this->user_id, abs($difference));
                    $dept->save();
                    $message = "You borrowed an article and but the amount you paid was insufficient.
                                You now have a dept of {$this->difference}. This amount will be deducted
                                from any new payment you make. Thank you..";
                    // $this->user->notification()->create([
                    //     'message' => "You just borrow {$this->quantity} {$this->book->type}s
                    //     entitled '{$this->book->title}'. You got a new dept of {abs($difference)}"
                    // ]);
                    break;
                }
                case "$difference == 0": {
                    // return $difference;
                    $message = "You just borrowed an article. Thank you";
                    // $this->user->notification()->create([
                    //     'message' => "You just borrow {$this->quantity} {$this->book->type}s
                    //     entitled '{$this->book->title}'."
                    // ]);
                    break;
                }
                default: {

                    $resp =  $this->payment()
                            ->find($this->payment_id)
                            ->account()
                            ->find($this->payment->account_id)
                            ->creditAccount($difference);
                    $message = "You borrowed an article and {$this->difference} difference was
                                credited back to your account. Thank you";
                    // $this->user->notification()->create([
                    //     'message' => "You just borrow {$this->quantity} {$this->book->type}s
                    //     entitled '{$this->book->title}'. Your difference {$difference} was
                    //     credited back to your account."
                    // ]);
                    break;
                }
            }
            $this->user()->find($this->user_id)->notify(new BorrowRequestNotification($this, $message));
            $this->user()->find($this->user_id)->update(['isEligible' => false]);
            $borrow = new Borrow([],$this->id, $duration);
            $borrow->save();

        }

        if ($exception) return $resp;

        if (json_decode($resp)->status){

            $this->update([
                'status' => "accepted",
                'justif' => "User Eligible for this action"
            ]);
            // $bc = $this->book->bookCopy()->where('book_id', $this->book_id)->first();
            // $bc->update(['stock_quantity' => $this->book->bookCopy->stock_quantity - $this->quantity]);
            // return ($this->book->bookCopy->first()->book_id;
            // BookCopy::where('book_id', $this->book_id)->update(['stock_quantity' => $this->book->bookCopy->stock_quantity - $this->quantity]);
            $this->book->bookCopy->first()->resetStockQuantity($this->quantity);
            $this->payment->delete();
        }
        else{
            $this->update([
                'status' => "rejected",
                'justif' => json_decode($resp)->message
            ]);
            $message = "Your request  for {$this->quantity} {$this->book->type}s
                        entitled '{$this->book->title}' was rejected. ".json_decode($resp)->message;
            $this->user()->find($this->user_id)->notify(new FailedRequestNotification($message));

        }

        // $this->delete();

        return $resp;

    }
    //---------------------------------------------------------------------------------

    // public function deleteRequest($justif){
    //     $this->update(['justif' => $justif]);
    //     $this->delete();

    //     return json_encode([
    //         "status" => true,
    //         "message" => "success"
    //     ]);
    // }

}

// class RequestDeleting{

//     public function handle(Request $model, $justif){
//         $model->justif = $justif;
//         $model->save();
//         $model->destroy()
//     }

// }
