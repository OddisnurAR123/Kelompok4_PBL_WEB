<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <!-- Breadcrumb -->
                @if(isset($breadcrumb) && isset($breadcrumb->list))
                    <ol class="breadcrumb float-sm-left"> <!-- Ubah float-sm-right menjadi float-sm-left -->
                        @foreach($breadcrumb->list as $key => $item)
                            @if(is_string($item))
                                <!-- Jika item adalah string -->
                                <li class="breadcrumb-item {{ $key == count($breadcrumb->list) - 1 ? 'active' : '' }}">
                                    {{ $item }}
                                </li>
                            @elseif(is_object($item) && isset($item->label, $item->url))
                                <!-- Jika item adalah objek -->
                                <li class="breadcrumb-item {{ $key == count($breadcrumb->list) - 1 ? 'active' : '' }}">
                                    @if($key == count($breadcrumb->list) - 1)
                                        {{ $item->label }}
                                    @else
                                        <a href="{{ $item->url }}">{{ $item->label }}</a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
</section>
