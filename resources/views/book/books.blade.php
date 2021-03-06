<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight pt-2"
                    style="text-shadow: 2px 2px rgb(110, 154, 204);font-weight:bold;font-family: monospace; -webkit-text-stroke: 0.4px #000;  color:white;font-size:20px;">
                    {{ __('Books') }}
                </h1>
            </div>
            <div class="col">
                <a href="books/create" type="button" class="btn btn-outline-light shadow rounded pull-right"
                    id="newBook">New Book</a>
            </div>
        </div>
    </x-slot>
    @if (session('success'))
        <br>
        <div style="padding-right:5%;padding-left:5%;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-lg p-3 mb-5 bg-white "
                style="background-color:#cedcee;border-radius: 15px;">
                <h1
                    style="color: #779ecb; font-family: 'Great Vibes', cursive; font-size: 45px; line-height: 60px; font-weight: normal;  text-align: center; text-shadow: 0 3px 2px #000; ">
                    My Store</h1>

                <div class="row p-2" id="showBook" style="">
                    @foreach ($books as $book)
                        <div class="column p-4" style="min-width: 245px;">
                            <div class="card p-0" style=" min-width: 245px;">
                                <div class="bookWrap">
                                    <div class="book">
                                        <img class="cover" src="images/{{ $book->file_path }}" alt="Avatar"
                                            style="width:100%">
                                        <div class="spine"></div>
                                    </div>
                                </div>

                                <div class=" p-2" style=" min-width: 245px; ">
                                    <h2><b>{{ $book->book_name }}</b></h2>
                                    <p> </p>
                                    <div class="row pb-2 pt-2">
                                        <div class="col-6">
                                            <a href="{{ URL::to('books/' . $book->id . '/edit') }}"
                                                class="btn btn-warning btn-sm shadow rounded pull-left btn-block"><i
                                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="col-6 right">
                                            <button type="button"
                                                class="btn btn-danger btn-sm shadow rounded pull-right btn-block btn-book"
                                                id="{{ $book->id }}"><i class="fa fa-trash"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
