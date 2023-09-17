<?php

// use Illuminate\Database\Eloquent\Model;
// use app\Models\Request;



$req = App\Models\Request::find("2023-09-05-20:46:47-000030");
$ans = $req->confirmRequest(20);

echo $ans;
