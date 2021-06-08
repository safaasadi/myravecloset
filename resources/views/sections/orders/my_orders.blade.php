@foreach(\App\Models\Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get() as $order)

    @if(\App\Models\Product::where('id', $order->product_id)->exists())
    @php
        $product = \App\Models\Product::where('id', $order->product_id)->first();
        $seller = \App\Models\User::where('id', $order->seller_id)->first();
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
                        @if($order->refunded)
                        Status:<a style="color: #e71d36"> Refunded</a><br />
                        @else
                        Status:<a style="color: #5E60CE"> {{ $order->delivered ? 'Delivered' : 'Shipped' }}</a><br />
                        @endif
                    @else
                        @if($order->refunded)
                        Status:<a style="color: #e71d36"> Refunded</a><br />
                        @else
                        Status:<a style="color: #5E60CE"> Not started</a><br />
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">

            <div class="row justify-content-center">
                <div class="col-12 col-md-6" style="margin-bottom: -1em;">
                    @if($order->tracking_number !== null)
                        <a href="https://tools.usps.com/go/TrackConfirmAction.action?tLabels={{ $order->tracking_number }}" class="btn-modern" style="width: 100%;">Track Shipment</a>
                    @else
                        <a href="#" class="btn-modern-disabled" style="width: 100%;">Track Shipment</a>
                    @endif
                </div>

                <div class="col-12 col-md-6" style="margin-bottom: -1em;">
                    <a href="javascript:loadConversation('{{ $seller->id }}');" class="btn-modern" style="width: 100%;" type="submit">Message Seller</a>
                </div>

                <div class="col-12" style="margin-bottom: -1em;">
                    <form class="w-100" action="/mark-order-delivered?order={{ $order->id }}" method="POST">
                        @csrf
                        @if(! $order->delivered)
                            <button class="btn-modern w-100" type="submit">Mark Delivered</a>
                        @else
                            <button class="btn-modern-disabled w-100" type="button" disabled>Mark Delivered</button>
                        @endif
                    </form>
                </div>

            </div>
        </div>

    </div>

    @endif

@endforeach