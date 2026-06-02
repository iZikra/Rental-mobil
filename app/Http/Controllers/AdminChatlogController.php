<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatLog;

class AdminChatlogController extends Controller
{
    public function index()
    {
        $logs = ChatLog::with('user')->latest()->paginate(20);
        return view('admin.chat-logs.index', compact('logs'));
    }
}
