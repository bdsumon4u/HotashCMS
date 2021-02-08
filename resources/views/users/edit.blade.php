<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PATCH')
    <input name="name" value="Sumon Ahmed" />
    <input name="email" value="bdsumon4u@gmail.com" />
    <input name="password" value="password" />
    <input type="submit" value="Submit">
</form>
