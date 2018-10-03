@extends('layouts.app')

@section('content')
    <form id="new-post" method="post" action="{{ route('post.add') }}" enctype="multipart/form-data">
        @csrf
        <div class="card custom-card first">
            <div class="card-header">
                <i class="fas fa-pen-square"></i> Ajouter un post
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control msg" rows="3" name="message" placeholder="Ecrire une publication..."></textarea>
                </div>
            </div>
            <div class="card-footer text-muted">
                <label class="btn btn-default">
                    <i class="fas fa-camera-retro"></i> <input type="file" name="cover_images[]" accept="image/*" multiple hidden>
                </label>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>

    @foreach ($posts as $post)
        <div class="card custom-card">
            <div class="card-header">
                <div class="float-left">
                    <a href="{{ route('post.edit', ['id' => $post->id]) }}" class="btn btn-default" role="button">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form method="post" action="{{ route('post.del', ['id' => $post->id], false) }}">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-default" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                <div class="float-right publied_at">Cette annonce a été publiée {{ $post->created_at->diffForHumans() }}</div>
            </div>
            <div class="card-body">
                <p>{{ $post->description}}</p>
            </div>
            <div class="card-footer text-muted">    
                @foreach ($post->images as $image)
                    <a href="{{ url("storage/{$image->name}") }}" target="_blank">
                        <img src="{{ url("storage/{$image->name}") }}" class="img-responsive">
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach

    {{ $posts->links() }}
@endsection