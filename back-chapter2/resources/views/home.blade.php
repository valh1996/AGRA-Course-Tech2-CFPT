<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>HUTTER | AGRA Tech2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">
    <link rel="stylesheet" href="{{ mix('/css/all.min.css') }}">
  </head>
  <body>
    <section class="container">
        <div class="row">
            <!-- header -->
            <header class="col-md-3">
                <div class="col-md-12">
                    <img src="{{ asset('img/cfpt-logo.png') }}" id="logo" class="img-responsive">
                    <h1 id="title">Centre de Formation Professionnelle et Technique d'Informatique</h1>
                    <span id="account">@cfpt.info</span>
                </div>
                <div class="col-md-12">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.html">Accueil</a>
                            <a class="nav-link" href="#">Un autre lien</a>
                        </li>
                    </ul> 
                </div>
            </header>

            <div class="col-md-9">
                <div class="col-md-12">
                    <img src="{{ asset('img/wallpaper.png') }}" class="img-responsive thumbnail-header">
                </div>
                <!-- content   -->
                <div id="content" class="col-md-12">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="errors">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                                    <form method="post" action="{{ route('post.del', ['id' => $post->id], false) }}">
                                        @csrf
                                        <input type="hidden" name="_method" value="put" />
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>

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
                </div>
            </div>
        </div>
    </section>

    <!-- js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="{{ mix('/js/all.min.js') }}"></script>
  </body>
</html>