@foreach(auth()->user()->getOrdersToShip() as $order)

    @if(\App\Models\Product::where('id', $order->product_id)->exists())
    @php
        $product = \App\Models\Product::where('id', $order->product_id)->first();
        $buyer = \App\Models\User::where('id', $order->user_id)->first();
    @endphp

    <div class="row ready-to-ship">
        <div class="col-12">
            <div class="row justify-content-center text-center" style="margin-bottom: -2em;">
                <div class="col-10">
                    <a href="/item?id={{ $product->id }}"><h5 style="margin-top: 1em;margin-bottom: -0.1em;">{{ $product->title }}</h5></a>
                </div>
            </div>
            <hr />
        </div>

        <div class="col-12" onclick="location.href='/item?id={{ $product->id }}';" style="cursor: pointer;">
            <div class="row">
                <div class="col-6">
                @if(! empty($product->images))
                    @if(\App\Models\File::where('id', $product->images[0])->exists())
                        @php
                            $thumbnail_url = \App\Models\File::where('id', $product->images[0])->first()->getURL();
                        @endphp
                        <img style="max-height: 10em" src="{{ $thumbnail_url }}" />
                    @endif
                @endif
                </div>
                <div class="col-6">
                    @if($order->tracking_number !== null)
                        Status:<a style="color: #5E60CE"> {{ $order->delivered ? 'Delivered' : 'Shipped' }}</a><br />
                    @else
                        Status:<a style="color: #5E60CE"> Not started</a><br />
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12" style="margin-top: -2em;">
            <div class="row justify-content-center status-payments">

                <div class="col-6" style="margin-bottom: -1em;">
                    <form class="w-100" action="/print-shipping-label?order={{ $order->id }}" method="POST">
                        @csrf
                        <button class="btn-modern w-100" type="submit">PRINT LABEL</button>
                    </form>
                </div>

                <div class="col-6" style="margin-bottom: -1em;">
                    <form class="w-100" action="/refund-order?order={{ $order->id }}" method="POST" id="refund-order-form">
                        @csrf
                        @if($order->refunded)
                            <button class="btn-modern btn-disabled w-100" type="button" disabled>REFUND</button>
                        @else
                            <button class="btn-modern w-100" type="submit">REFUND</button>
                        @endif
                    </form>
                </div>

                <div class="col-12" style="margin-bottom: -1em;">
                    <a href="javascript:loadConversation('{{ $buyer->id }}');" class="btn-modern" style="width: 100%;" type="submit">MESSAGE BUYER</a>
                </div>

            </div>
        </div>

    </div>

    @endif

@endforeach