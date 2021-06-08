@foreach(\App\Models\Order::where('seller_id', auth()->user()->id)->orderBy('created_at', 'desc')->get() as $order)

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
                    @if($order->rental)
                        Type:<a style="color: #5E60CE"> Rental</a><br />
                        @if($order->tracking_number !== null)
                            Status Buyer:<a style="color: #5E60CE"> {{ $order->delivered ? 'Delivered' : 'Shipped' }}</a><br />
                        @else
                            Status Buyer:<a style="color: #5E60CE"> Not started</a><br />
                        @endif
                        @if($order->return_tracking_number !== null)
                            Status Seller:<a style="color: #5E60CE"> {{ $order->returned ? 'Returned' : 'Shipped' }}</a><br />
                        @else
                            Status Seller:<a style="color: #5E60CE"> Not started</a><br />
                        @endif
                    @else
                        Type:<a style="color: #5E60CE"> Purchase</a><br />
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
                    @endif
                    
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row justify-content-center">

                <div class="col-6" style="margin-bottom: -1em;">
                    <form class="w-100" action="/print-shipping-label?order={{ $order->id }}" method="POST">
                        @csrf
                        @if($order->refunded)
                        <button class="btn-modern btn-disabled w-100" type="button">Print Label</button>
                        @else
                        <button class="btn-modern w-100" type="submit">Print Label</button>
                        @endif
                    </form>
                </div>

                <div class="col-6" style="margin-bottom: -1em;">
                    <form class="w-100" action="/refund-order?order={{ $order->id }}" method="POST" id="refund-order-form">
                        @csrf
                        @if($order->refunded)
                            <button class="btn-modern w-100" style="color: darkgray;background: #e0e1dd;" type="button" disabled>Refund</button>
                        @else
                            <button class="btn-modern w-100" type="submit">Refund</button>
                        @endif
                    </form>
                </div>

                <div class="col-12 col-md-6" style="margin-bottom: -1em;">
                    @if($order->tracking_number !== null)
                        <a href="https://tools.usps.com/go/TrackConfirmAction.action?tLabels={{ $order->tracking_number }}" class="btn-modern" style="width: 100%;">Track Shipment</a>
                    @else
                        <a href="#" class="btn-modern-disabled">Track Shipment</a>
                    @endif
                </div>

                <div class="col-12 col-md-6" style="margin-bottom: -1em;">
                    <a href="javascript:loadConversation('{{ $buyer->id }}');" class="btn-modern">Message Buyer</a>
                </div>

            </div>
        </div>

    </div>

    @endif

@endforeach