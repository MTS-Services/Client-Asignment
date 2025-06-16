<x-admin::layout>


    <form action="{{ route('im.book-issues.update-return' $id) }}" method="POST">
        @csrf
        @method('PATCH')
        <select name="returned_by" id="">
            @foreach (App\Models\User::all() as $user)
                <option value="{{ $user->id }}" @if ($user->id == $issue->user_id) selected @endif>{{ $user->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">
            {{ __('Mark as Returned') }}
        </button>
    </form>

</x-admin::layout>
