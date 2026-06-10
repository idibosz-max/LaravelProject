import React, { useState, useEffect, useCallback } from 'react';

export default function ProductFilter({ initialProducts, categories }) {
    const [products, setProducts]     = useState(initialProducts);
    const [search, setSearch]         = useState('');
    const [categoryId, setCategoryId] = useState('');
    const [loading, setLoading]       = useState(false);

    const fetchProducts = useCallback(async () => {
        setLoading(true);
        const params = new URLSearchParams();
        if (search)     params.set('q', search);
        if (categoryId) params.set('category_id', categoryId);
        try {
            const res  = await fetch(`/search?${params}`);
            const data = await res.json();
            setProducts(data);
        } catch (err) {
            console.error('Search error:', err);
        } finally {
            setLoading(false);
        }
    }, [search, categoryId]);

    useEffect(() => {
        const timer = setTimeout(fetchProducts, 350);
        return () => clearTimeout(timer);
    }, [fetchProducts]);

    const addToCart = async (productId) => {
        try {
            const res = await fetch('/api/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            });
            if (res.ok) window.dispatchEvent(new CustomEvent('cart-updated'));
        } catch (err) {
            console.error('Cart error:', err);
        }
    };

    return (
        <div>
            {/* Filter controls */}
            <div className="flex flex-wrap gap-4 mb-8">
                <div className="relative flex-1 min-w-[260px]">
                    <input
                        type="text"
                        placeholder="Search products…"
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        className="w-full bg-[#1a1a1a] border border-white/15 rounded-lg
                                   px-4 py-3 text-sm text-white placeholder-gray-500
                                   focus:border-[#DC2626] focus:outline-none transition-colors"
                    />
                    {loading && (
                        <div className="absolute right-3 top-1/2 -translate-y-1/2">
                            <div className="w-4 h-4 border-2 border-[#DC2626] border-t-transparent rounded-full animate-spin" />
                        </div>
                    )}
                </div>

                <select
                    value={categoryId}
                    onChange={e => setCategoryId(e.target.value)}
                    className="bg-[#1a1a1a] border border-white/15 rounded-lg
                               px-4 py-3 text-sm text-white
                               focus:border-[#DC2626] focus:outline-none transition-colors"
                >
                    <option value="">All Categories</option>
                    {categories.map(cat => (
                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                    ))}
                </select>

                {(search || categoryId) && (
                    <button
                        onClick={() => { setSearch(''); setCategoryId(''); }}
                        className="px-4 py-3 text-sm text-gray-400 hover:text-white
                                   border border-white/15 rounded-lg transition-colors"
                    >
                        ✕ Clear
                    </button>
                )}
            </div>

            {/* Count */}
            <p className="text-gray-500 text-xs mb-6 uppercase tracking-widest font-medium">
                {loading ? 'Searching…' : `${products.length} product${products.length !== 1 ? 's' : ''} found`}
            </p>

            {/* Grid */}
            {products.length === 0 && !loading ? (
                <div className="text-center py-24 border border-white/5 rounded-xl bg-[#111]">
                    <p className="font-display text-4xl tracking-widest text-gray-700 mb-3">NO RESULTS</p>
                    <p className="text-gray-500 text-sm mb-5">Try different search terms or category.</p>
                    <button
                        onClick={() => { setSearch(''); setCategoryId(''); }}
                        className="text-[#DC2626] hover:text-red-400 text-sm font-medium transition-colors"
                    >
                        Clear filters
                    </button>
                </div>
            ) : (
                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    {products.map(product => (
                        <ProductCard key={product.id} product={product} onAddToCart={addToCart} />
                    ))}
                </div>
            )}
        </div>
    );
}

function ProductCard({ product, onAddToCart }) {
    const [adding, setAdding] = useState(false);
    const price  = product.sale_price ?? product.price;
    const onSale = product.sale_price && parseFloat(product.sale_price) < parseFloat(product.price);
    const discount = onSale ? Math.round((1 - product.sale_price / product.price) * 100) : 0;

    const handleAdd = async () => {
        setAdding(true);
        await onAddToCart(product.id);
        setTimeout(() => setAdding(false), 800);
    };

    return (
        <article className="group bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden
                            hover:border-[#DC2626]/60 transition-all duration-300 flex flex-col">
            {/* Image */}
            <a href={`/products/${product.slug}`}
               className="block relative overflow-hidden bg-[#111] aspect-square">
                {product.image ? (
                    <img
                        src={`/storage/${product.image}`}
                        alt={product.name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center">
                        <div className="w-14 h-14 bg-[#DC2626]/10 rounded-lg flex items-center justify-center">
                            <div className="w-6 h-6 bg-[#DC2626]/20 rounded"></div>
                        </div>
                    </div>
                )}
                {/* Badge */}
                {onSale && (
                    <div className="absolute top-3 left-3 bg-[#DC2626] text-white
                                    text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                        {discount}% OFF
                    </div>
                )}
            </a>

            {/* Info */}
            <div className="p-5 flex flex-col flex-1">
                <p className="text-[#DC2626] text-[10px] font-semibold uppercase tracking-widest mb-1.5">
                    {product.category}
                </p>
                <h3 className="text-white font-semibold text-sm leading-snug mb-3 flex-1 line-clamp-2
                               group-hover:text-[#DC2626] transition-colors duration-200">
                    <a href={`/products/${product.slug}`}>{product.name}</a>
                </h3>

                {/* Price */}
                <div className="flex items-center gap-2.5 mb-4">
                    <span className={`font-bold text-lg ${onSale ? 'text-[#DC2626]' : 'text-white'}`}>
                        ${parseFloat(price).toFixed(2)}
                    </span>
                    {onSale && (
                        <span className="text-gray-500 text-sm line-through">
                            ${parseFloat(product.price).toFixed(2)}
                        </span>
                    )}
                </div>

                {/* CTA */}
                {product.in_stock ? (
                    <button
                        onClick={handleAdd}
                        disabled={adding}
                        className={`w-full text-white text-xs font-semibold py-3 rounded-lg
                                   uppercase tracking-widest transition-all duration-200
                                   ${adding
                                       ? 'bg-green-700 cursor-default'
                                       : 'bg-[#DC2626] hover:bg-[#991B1B] active:bg-[#7f1d1d]'}`}
                    >
                        {adding ? '✓ Added!' : 'Add to Cart'}
                    </button>
                ) : (
                    <button disabled
                            className="w-full bg-[#1f1f1f] border border-gray-700 text-gray-500
                                       text-xs font-semibold py-3 rounded-lg uppercase tracking-widest cursor-not-allowed">
                        Out of Stock
                    </button>
                )}
            </div>
        </article>
    );
}
