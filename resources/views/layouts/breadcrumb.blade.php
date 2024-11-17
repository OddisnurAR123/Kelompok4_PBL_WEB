<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $breadcrumb->title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if(isset($breadcrumb) && isset($breadcrumb->list))
                        @foreach($breadcrumb->list as $key => $item)
                            @if(is_string($item))
                                <!-- Jika item adalah string, langsung tampilkan -->
                                @if($key == count($breadcrumb->list) - 1)
                                    <li class="breadcrumb-item">{{ $item }}</li>
                                @else
                                    <li class="breadcrumb-item active">{{ $item }}</li>
                                @endif
                            @elseif(is_object($item))
                                <!-- Jika item adalah objek, akses label dan url -->
                                @if($key == count($breadcrumb->list) - 1)
                                    <li class="breadcrumb-item"><a href="{{ $item->url }}">{{ $item->label }}</a></li>
                                @else
                                    <li class="breadcrumb-item active">{{ $item->label }}</li>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
</section>
