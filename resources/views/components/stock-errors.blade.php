@if(session('stock_errors'))
    @php
        $stockErrors = session('stock_errors');
    @endphp
    
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Stock Issues Detected
        </h5>
        
        @if(!empty($stockErrors['out_of_stock']))
            <div class="mb-3">
                <strong>Out of Stock Items:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($stockErrors['out_of_stock'] as $item)
                        <li>
                            <strong>{{ $item['product']->name }}</strong> - 
                            You have {{ $item['cart_quantity'] }} in cart, but only {{ $item['available_stock'] }} available
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(!empty($stockErrors['low_stock']))
            <div class="mb-3">
                <strong>Insufficient Stock Items:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($stockErrors['low_stock'] as $item)
                        <li>
                            <strong>{{ $item['product']->name }}</strong> - 
                            You have {{ $item['cart_quantity'] }} in cart, but only {{ $item['available_stock'] }} available
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <hr>
        <p class="mb-0">
            <strong>Please update your cart quantities or remove out-of-stock items before proceeding to checkout.</strong>
        </p>
        
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif 