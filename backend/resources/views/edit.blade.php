@extends('layouts.app')

@section('content')
    <form id="edit-post" method="post" action="{{ route('post.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="card custom-card first">
            <div class="card-header">
                <i class="fas fa-pen-square"></i> Modification de l'annonce n°{{ $post->id }}
            </div>
            <div class="card-body">
                <div class="form-group">
                <textarea class="form-control msg" rows="3" name="message" placeholder="Ecrire une publication...">{{ $post->description }}</textarea>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="row">
                    @foreach ($post->images as $image)
                    <div class="col-md-3">
                        <input type="checkbox" name="current_cover_images[]" value="{{ $image->id }}" checked />
                        <br>
                        <a href="{{ url("storage/{$image->name}") }}" target="_blank">
                            <img src="{{ url("storage/{$image->name}") }}" class="img-responsive">
                        </a>
                    </div>
                    @endforeach
                </div>
                <label class="btn btn-default">
                    <i class="fas fa-camera-retro"></i> <input type="file" name="new_cover_images[]" accept="image/*" multiple hidden>
                </label>
                <input type="hidden" name="_method" value="PUT">
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>
        </div>
    </form>
@endsection