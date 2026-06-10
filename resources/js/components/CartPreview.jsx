import React, { useState, useEffect } from 'react';

export default function CartPreview() {
    const [open, setOpen]       = useState(false);
    const [cart, setCart]       = useState({ items: [], total: 0, count: 0 });
    const [loading, setLoading] = useState(false);

    const fetchCart = async () => {
        setLoading(true);
        try {
            const res  = await fetch('/api/cart');
            const data = await res.json();
            setCart(data);
        } catch (err) {
            console.error('Cart fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchCart();
        window.addEventListener('cart-updated', fetchCart);
        return () => window.removeEventListener('cart-updated', fetchCart);
    }, []);

    return (
        <>
            {/* Cart button */}
            <button
                onClick={() => { setOpen(true); fetchCart(); }}
                className="relative flex items-center justify-center w-9 h-9
                           text-gray-400 hover:text-white transition-colors rounded-lg
                           hover:bg-white/5"
                aria-label="Open cart"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {cart.count > 0 && (
                    <span className="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#DC2626] rounded-full
                                     text-white text-[10px] font-bold flex items-center justify-center
                                     ring-2 ring-[#0a0a0a]">
                        {cart.count > 9 ? '9+' : cart.count}
                    </span>
                )}
            </button>

            {/* Backdrop */}
            {open && (
                <div
                    className="fixed inset-0 bg-black/70 backdrop-blur-sm z-40"
                    onClick={() => setOpen(false)}
                />
            )}

            {/* Drawer */}
            <div className={`fixed top-0 right-0 h-full w-full sm:w-96 z-50
                             bg-[#111111] border-l border-white/10
                             transform transition-transform duration-300 ease-in-out flex flex-col
                             ${open ? 'translate-x-0' : 'translate-x-full'}`}>

                {/* Header */}
                <div className="flex items-center justify-between px-6 py-5 border-b border-white/10 shrink-0">
                    <div className="flex items-center gap-3">
                        <h2 className="font-display text-2xl tracking-widest text-white">CART</h2>
                        {cart.count > 0 && (
                            <span className="bg-[#DC2626] text-white text-xs font-bold
                                             px-2 py-0.5 rounded-full">
                                {cart.count}
                            </span>
                        )}
                    </div>
                    <button
                        onClick={() => setOpen(false)}
                        className="text-gray-500 hover:text-white transition-colors
                                   w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/5"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Items */}
                <div className="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                    {loading ? (
                        <div className="flex items-center justify-center h-32">
                            <div className="w-6 h-6 border-2 border-[#DC2626] border-t-transparent rounded-full animate-spin" />
                        </div>
                    ) : cart.items.length === 0 ? (
                        <div className="text-center py-16">
                            <svg className="w-12 h-12 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5}
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p className="font-display text-xl tracking-widest text-gray-600 mb-2">CART IS EMPTY</p>
                            <p className="text-gray-600 text-sm">No items added yet.</p>
                        </div>
                    ) : (
                        cart.items.map(item => (
                            <div key={item.id}
                                 className="flex gap-4 bg-[#1a1a1a] border border-white/10 rounded-xl p-4">
                                {/* Thumbnail */}
                                <div className="w-16 h-16 bg-[#0a0a0a] rounded-lg shrink-0 overflow-hidden flex items-center justify-center">
                                    {item.image ? (
                                        <img src={`/storage/${item.image}`} alt={item.name}
                                             className="w-full h-full object-cover" />
                                    ) : (
                                        <div className="w-6 h-6 bg-[#DC2626]/10 rounded" />
                                    )}
                                </div>
                                {/* Info */}
                                <div className="flex-1 min-w-0">
                                    <p className="text-white text-sm font-semibold truncate mb-0.5">{item.name}</p>
                                    <p className="text-gray-500 text-xs mb-1">Qty: {item.quantity}</p>
                                    <p className="text-[#DC2626] font-bold text-sm">
                                        ${parseFloat(item.subtotal).toFixed(2)}
                                    </p>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {/* Footer */}
                {cart.items.length > 0 && (
                    <div className="px-6 py-5 border-t border-white/10 space-y-4 shrink-0">
                        <div className="flex justify-between items-center">
                            <span className="text-gray-400 text-sm">Subtotal</span>
                            <span className="text-white font-bold text-lg">
                                ${parseFloat(cart.total).toFixed(2)}
                            </span>
                        </div>
                        <a href="/cart"
                           className="block w-full text-center bg-[#DC2626] hover:bg-[#991B1B]
                                      text-white font-semibold py-4 rounded-lg uppercase tracking-widest
                                      text-sm transition-colors duration-200">
                            View Cart & Checkout
                        </a>
                        <button
                            onClick={() => setOpen(false)}
                            className="block w-full text-center text-gray-500 hover:text-gray-300
                                       text-xs transition-colors"
                        >
                            ← Continue Shopping
                        </button>
                    </div>
                )}
            </div>
        </>
    );
}
