<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function add(AddToCartRequest $request): RedirectResponse
    {
        $cart = $this->cartService->addPackageToCart($request->validated());

        return redirect()->route('customer.cart.show')
            ->with('success', 'Paket kustom berhasil dimasukkan ke keranjang.')
            ->with('cart', $cart);
    }

    public function show(): View
    {
        $cart = $this->cartService->getCart();

        return view('customer.cart.show', compact('cart'));
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clearCart();

        return redirect()->route('customer.cart.show')
            ->with('success', 'Keranjang pesanan telah dikosongkan.');
    }
}
