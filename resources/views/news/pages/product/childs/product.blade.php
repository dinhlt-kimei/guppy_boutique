<div class="row row-sm">
    @foreach ($items as $item)
        <div class="col-6 col-md-4 col-lg-3 col-xl-5col">
            <div class="product">
                @include('news.patirials.product.image',    ['item' => $item])
                @include('news.patirials.product.content',  ['item' => $item])
            </div>
        </div>
    @endforeach
</div>
    