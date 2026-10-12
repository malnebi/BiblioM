@foreach ($libraries as $library)
    <x-userlib-card :user="$library->owner" :library="$library" />
@endforeach
