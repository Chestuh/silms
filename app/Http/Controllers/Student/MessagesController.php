<?php

namespace App\Http\Controllers\Student;

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
                $byOther[$otherId] = ['user' => $other, 'last_message' => $m, 'unread' => 0];
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
        $conversations = $this->getConversations($user);
        return view('student.messages.index', compact('conversations'));
    }

    public function thread(Request $request, User $user)
    {
        $me = $request->user();
        if ($user->id === $me->id) {
            return redirect()->route('student.messages');
        }
        if ($user->role !== 'instructor') {
            return redirect()->route('student.messages')->with('error', 'You can only message instructors.');
        }
        Message::where('receiver_id', $me->id)->where('sender_id', $user->id)->whereNull('read_at')->update(['read_at' => now()]);
        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->with('sender', 'receiver')->orderBy('created_at')->get();
        $conversations = $this->getConversations($me);
        return view('student.messages.index', compact('conversations', 'messages', 'user'));
    }

    /**
     * Compose: send message to an instructor.
     */
    public function create(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) {
            abort(403);
        }
        $courseIds = $student->enrollments()->where('status', 'enrolled')->pluck('course_id');
        $instructors = User::where('role', 'instructor')
            ->whereHas('instructor', function ($q) use ($courseIds) {
                $q->whereHas('courses', fn ($q2) => $q2->whereIn('id', $courseIds));
            })
            ->with('instructor:id,user_id,department')
            ->orderBy('name')
            ->get();
        if ($instructors->isEmpty()) {
            $instructors = User::where('role', 'instructor')->with('instructor')->orderBy('name')->get();
        }
        return view('student.messages.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:5000'],
        ]);
        $me = $request->user();
        $receiver = User::findOrFail($request->receiver_id);
        if ($receiver->role !== 'instructor') {
            return back()->withErrors(['receiver_id' => 'You can only send messages to instructors.'])->withInput();
        }
        Message::create([
            'sender_id' => $me->id,
            'receiver_id' => $receiver->id,
            'subject' => null,
            'body' => $request->body,
        ]);
        return redirect()->route('student.messages.thread', $receiver)->with('success', 'Message sent.');
    }
}
