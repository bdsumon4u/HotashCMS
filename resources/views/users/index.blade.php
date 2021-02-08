Users Index<br>
@foreach($users as $user)
    <h4>{{ $user->name }}</h4>
    <form method="POST" action="{{ route('users.destroy', $user) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endforeach
