<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //
    public function store(StoreReviewRequest $request){
        $data=$request->validated();
        $data['user_id']=Auth::id();
        Review::create($data);
        return back()->with ('success','Thank you for your review');

    }
}
