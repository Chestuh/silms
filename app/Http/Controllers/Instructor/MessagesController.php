<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    private function getConversations($user)
    {
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('sender', 'receiver')
            ->orderByDesc('created_at')
            ->get();
        $byOther = [];
        foreach ($messages as $m) {
            $otherId = $m->sender_id === $user->id ? $m->receiver_id : $m->sender_id;
            if (!isset($byOther[$otherId])) {
                $other = $m->sender_id === $user->id ? $m->receiver : $m->sender;
                if (!$other) {
                    continue;
                }
                $byOther[$otherId] = [
                    'user' => $other,
                    'last_message' => $m,
                    'unread' => 0,
                ];
            }
            if ($m->receiver_id === $user->id && $m->read_at === null) {
                $byOther[$otherId]['unread']++;
            }
        }
        $conversations = array_values($byOther);
        usort($conversations, fn ($a, $b) => $b['last_message']->created_at->timestamp <=> $a['last_message']->created_at->timestamp);
        return $conversations;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $instructor = $user->instructor;
        if (!$instructor) {
            abort(403);
        }
        $conversations = $this->getConversations($user);
        return view('instructor.messages.index', compact('conversations'));
    }

    public function thread(Request $request, User $user)
    {
        $me = $request->user();
        $instructor = $me->instructor;
        if (!$instructor) {
            abort(403);
        }
        if ($user->id === $me->id) {
            return redirect()->route('instructor.messages.index');
        }
        if ($user->role !== 'student') {
            return redirect()->route('instructor.messages.index')->with('error', 'You can only message students.');
        }
        Message::where('receiver_id', $me->id)->where('sender_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->with('sender', 'receiver')->orderBy('created_at')->get();
        $conversations = $this->getConversations($me);
        return view('instructor.messages.index', compact('conversations', 'messages', 'user'));
    }

    public function create(Request $request)
    {
        $me = $request->user();
        $instructor = $me->instructor;
        if (!$instructor) {
            abort(403);
        }
        $studentUserIds = \App\Models\Enrollment::whereIn('course_id', $instructor->courses()->pluck('id'))
            ->where('status', 'enrolled')
            ->distinct()
            ->pluck('student_id');
        $students = User::where('role', 'student')
            ->whereHas('student', fn ($q) => $q->whereIn('id', $studentUserIds))
            ->with('student:id,user_id,student_number')
            ->orderBy('name')
            ->get();
        return view('instructor.messages.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:5000'],
        ]);
        $me = $request->user();
        $receiver = User::findOrFail($request->receiver_id);
        if ($receiver->role !== 'student') {
            return back()->withErrors(['receiver_id' => 'You can only send messages to students.'])->withInput();
        }
        Message::create([
            'sender_id' => $me->id,
            'receiver_id' => $receiver->id,
            'subject' => null,
            'body' => $request->body,
        ]);
        return redirect()->route('instructor.messages.thread', $receiver)->with('success', 'Message sent.');
    }
}
