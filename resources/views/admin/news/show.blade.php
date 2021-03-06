@extends('admin.layout')

@section('content')



    {!! Form::model($news, ['url' => ['admin/association/news', $news->link()], 'method' => 'put']) !!}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-block">
                    <h3 class="card-title">
                        Title
                    </h3>

                    <div class="form-group mt-3">
                        {!! Form::text('title', $news->title(), ['class' => 'form-control']) !!}
                    </div>


                    <h3 class="card-title">
                        Content
                    </h3>

                    <div class="row d-flex align-items-stretch">
                        <div class="col">
                            {!! Form::textarea('content', $news->content()->originalMarkdown(), ['class' => 'form-control', 'id' => 'news-item-content']) !!}
                        </div>
                        <div class="col-md-6 d-none">
                            <div style="overflow-y: scroll" class="card">
                                <div class="card-block" id="news-item-preview">

                                    {!! $news->content() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-block bg-faded">
                    <h4 class="card-title">
                        Publishing
                    </h4>
                    <p>
                        This news article hasn't been published yet. You can either pick a publication date (so that for instance the article will be published on next monday) or save and publish the article directly.
                    </p>


                    <div class="d-flex justify-content-between align-items-end">
                        <div class="form-group mb-0">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            {!! Form::label('publish-at', 'Publish at:', ['class' => 'control-label-col']) !!}
                            {!! Form::date('publicationDate', $news->publicationDate()->format('Y-m-d'), ['class' => 'form-control']) !!}
                        </div>

                        <button class="btn btn-outline-primary pull-right">
                            <i class="fa fa-upload" aria-hidden="true"></i>

                            Publish
                        </button>
                    </div>
                </div>
                <div class="card-block">
                    @if ($news->publicationDate() < (new DateTimeImmutable('2017-08-24')))
                        <div class="alert alert-warning">

                            <strong>
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                Warning!
                            </strong>
                            This news item was imported from our old wordpress website.
                            By changing its content you might break stuff..
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        @if ($news->publicationDate() > (new DateTimeImmutable('2017-08-24')))
                            <button class="btn btn-outline-success">
                                <i class="fa fa-check" aria-hidden="true"></i>

                                Update
                            </button>
                        @else

                            <button class="btn btn-outline-warning" onclick="return confirm('Saving this news item might override import setting. Are you sure you want to save?')">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>

                                Update
                            </button>
                        @endif

                        {!! Form::submit('Archive', ['class' => 'btn-link text-danger pull-right']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col">

            <div class="card">
                <div class="card-block">
                    <h3>Miscelanious</h3>


                    <div class="form-group row">
                        <div class="col-sm-4">
                            <i class="fa fa-link" aria-hidden="true"></i>
                            {!! Form::label('link', 'Link slug:', ['class' => 'form-control-label']) !!}

                        </div>
                        <div class="col-sm-8">
                            {!! Form::text('link', $news->link(), ['class' => 'form-control', 'disabled' => true]) !!}
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-4">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            {!! Form::label('latest-edit', 'Latest edit at:', ['class' => 'control-label-col']) !!}
                        </div>
                        <div class="col-sm-8">
                            {!! Form::date('publicationDate', $news->publicationDate()->format('Y-m-d'), ['class' => 'form-control', 'disabled' => true]) !!}
                        </div>
                    </div>

                    <a class="card-link" href="/association/news/{{ $news->link() }}">
                        View {{ $news->title() }}
                    </a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-block">

                    <h3>
                        Author
                    </h3>

                    <div class="row">
                        <div class="col-md-3">
                            <img id="profilePicture" alt="" src="{{ $news->authorPhoto() }}" style="cursor: pointer;" class="img-fluid rounded w-100"/>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                {!! Form::text('author', $news->authorName(), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {!! Form::close() !!}


        @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

        {{-- Syntax highlighting --}}
        <script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">


        <script>
         var simplemde = new SimpleMDE({
             element: document.getElementById("news-item-content"),
             spellChecker: false,
             autoSave: {
                 enabled: true,
                 uniqueId: "news-{{ $news->link()  }}"
             },
             promptURLs: true,
             /* previewRender: ,*/
             /* previewRender: function(plainText) {*/
             /* console.log(plainText);*/
		         /* return customMarkdownParser(plainText); // Returns HTML from a custom parser*/
	           /* },*/
	           previewRender: function(plainText) { // Async method
                 var that = this;
                 var parent = that.parent;

                 var preview = document.getElementById("news-item-preview");
                 var compiled = this.parent.markdown(plainText);
                 preview.innerHTML = compiled;
                 return compiled;
	           },

             toolbar: [
                 "bold", "italic", "strikethrough",
                 "|",
                 "heading-1", "heading-2", "heading-3",
                 "|",
                 "code", "quote", "link", "image", "table",
                 "|",
                 "unordered-list", "ordered-list",
                 "|",
                 "preview", "side-by-side", "fullscreen",
                 "guide"
             ]
         });
        </script>
        <style type="text/css">
        .CodeMirror, .CodeMirror-scroll {
            max-height: 300px;
        }
        </style>

        <script type="text/javascript">
         function confirmSavingOfImportedScript(e) {
             if (confirm('Saving this news item might override import setting')) {
                 return true;
             }
             e.preventDefault();
             e.stopPropogation();
             return false;
         }
        </script>
        @endpush
@endsection
